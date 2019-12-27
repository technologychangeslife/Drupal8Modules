<?php

namespace Drupal\bhge_c55_gallery;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

/**
 * Gallery extension.
 */
class GalleryData {

  protected $connection;

  protected $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   * @param \Drupal\bhge_c55_gallery\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(Connection $connection, DataHelpers $dataHelpers) {
    $this->connection = $connection;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Made query with sorting option.
   *
   * @return mixed
   *   Return query.
   */
  public function galleryQuery($ctypes, $parentId, $topicId = NULL, $countOnly = FALSE, $sort = 'weight', $offset = 0, $limit = 10, $filterCategory = NULL) {

    // Get nodes by ctype and status published.
    $query = $this->connection->select('node_field_data', 'n');
    $query->condition('n.type', $ctypes, 'IN');
    $query->condition('n.status', 1);
    $query->addField('n', 'nid', 'nid');

    $referencedParents = NULL;

    // If only event item content type.
    if ($ctypes == ['event_item'] && !empty($parentId)) {

      // Parent - child relationship.
      $query->leftJoin('node__field_parent', 'parent', 'parent.entity_id = n.nid');
      $query->condition('parent.field_parent_target_id', $parentId);
      $query->addField('parent', 'entity_id', 'pid');

      $referencedParents = $query->execute()->fetchCol();

      // Force return to stop execution, since no items.
      if (empty($referencedParents)) {
        return NULL;
      }
    }
    elseif ($ctypes == ['blog_post'] && !empty($parentId)) {
      $authorId = $parentId;
      $query->leftJoin('node__field_author', 'a', 'a.entity_id = n.nid');
      $query->condition('a.field_author_target_id', $authorId);
      $query->addField('a', 'entity_id', 'eid');
    }
    elseif ($ctypes == ['question'] && !empty($parentId)) {
      $query->leftJoin('node__field_blog_page', 'b', 'b.entity_id = n.nid');
      $query->condition('b.field_blog_page_target_id', $parentId);
    }

    // Filter on topic or team.
    if ($ctypes == ['person']) {
      if (!empty($topicId) && is_string($topicId)) {
        $topicId = explode(',', $topicId);
      }
      if (!empty($topicId) && is_array($topicId)) {
        // Don't show person from the page we are on.
        $query->condition('n.nid', $parentId, '!=');
        $query->leftJoin('node__field_team', 'team', 'team.entity_id = n.nid');
        $query->condition('team.field_team_target_id', $topicId, 'IN');
        $query->addField('team', 'entity_id', 'eid');
        $referencedParents = $query->execute()->fetchCol();
        if (!empty($referencedParents)) {
          $query->condition('team.entity_id', $referencedParents, 'IN');
        }
      }
    }
    elseif (!empty($topicId) && is_numeric($topicId)) {
      $query->leftJoin('node__field_topic', 'topic', 'topic.entity_id = n.nid');
      $query->condition('topic.field_topic_target_id', $topicId);
      $query->addField('topic', 'entity_id', 'eid');
      if (!empty($referencedParents)) {
        $query->condition('topic.entity_id', $referencedParents, 'IN');
      }
    }

    // Filter on category.
    if (!empty($filterCategory) && is_numeric($filterCategory)) {
      $query->leftJoin('node__field_categories', 'c', 'c.entity_id = n.nid');
      $query->condition('c.field_categories_target_id', $filterCategory);
    }

    if ($sort == 'weight') {
      $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
      $query->orderBy('weight.field_weight_value', 'DESC');
    }
    elseif ($sort == 'votes') {
      // $query->leftJoin('node__field_votes',
      // 'votes',
      // 'votes.entity_id = n.nid');
      // $query->orderBy('votes.field_votes_value', 'DESC');.
    }
    // Always have creation date at least as secondary sorter.
    $query->orderBy('n.created', 'DESC');
    // $query->orderBy('n.nid', 'DESC');.
    if ($limit) {
      $query->range($offset, $limit);
    }

    // In case we only want to count resuls.
    if ($countOnly) {
      return $query->countQuery()->execute()->fetchField();
    }

    $nids = $query->execute()->fetchCol();

    if (!empty($nids)) {
      return Node::loadMultiple($nids);
    }
  }

  /**
   * Get data for topics sidebar exposed filter.
   *
   * @param string $ctype
   *   The variable to store content type.
   * @param [type]|null $parentId
   *   The parent ID.
   * @param [type]|null $filterCategory
   *   The Category filter.
   *
   * @return mixed
   *   Return taxonomy data.
   */
  public function topics($ctype, $parentId = NULL, $filterCategory = NULL) {

    $query = $this->connection->select('node_field_data', 'n');
    $query->condition('n.type', $ctype);
    $query->condition('n.status', 1);

    if ($ctype == 'event_item' && !empty($parentId)) {
      // Parent - child relationship.
      $query->leftJoin('node__field_parent', 'parent', 'parent.entity_id = n.nid');
      $query->condition('parent.field_parent_target_id', $parentId);
      $query->addField('parent', 'entity_id', 'pid');
    }
    else {
      $query->addField('n', 'nid', 'nid');
    }

    if (!empty($filterCategory) && is_numeric($filterCategory)) {
      $query->leftJoin('node__field_categories', 'c', 'c.entity_id = n.nid');
      $query->condition('c.field_categories_target_id', $filterCategory);
    }

    $nids = $query->execute()->fetchCol();
    $results = $this->topicsTaxonomyData($nids);

    return $results;
  }

  /**
   * Get title and tid for forming the filters sidebar.
   *
   * @param array $nids
   *   The Node ids.
   *
   * @return mixed
   *   Returns the query result from node field topic.
   */
  public function topicsTaxonomyData(array $nids) {

    if (empty($nids)) {
      return NULL;
    }

    $query = $this->connection->select('node__field_topic', 'ft');
    $query->condition('ft.entity_id', $nids, 'IN');
    $query->leftJoin('taxonomy_term_field_data', 'td', 'ft.field_topic_target_id = td.tid');
    $query->addField('td', 'tid', 'filter');
    $query->addField('td', 'name', 'title');
    $query->orderBy('td.weight');
    $query->distinct();
    return $query->execute()->fetchAll();
  }

  /**
   * Prepare data.
   *
   * @param array $ctypes
   *   The content types array.
   * @param int $parentId
   *   The Parent ID.
   * @param string $topic
   *   The topic.
   * @param string $sort
   *   The sort by weight query.
   * @param int $offset
   *   The query offset.
   * @param int $limit
   *   The query limit.
   * @param [type]|null $filterCategory
   *   The category filter.
   *
   * @return array
   *   Returns the data of array.
   */
  public function prepareData(array $ctypes, $parentId, $topic = NULL, $sort = 'weight', $offset = 0, $limit = 10, $filterCategory = NULL) {

    $data = [];
    $data['results'] = [];

    $nodes = $this->galleryQuery($ctypes, $parentId, $topic, FALSE, $sort, $offset, $limit, $filterCategory);
    $data['total'] = $this->galleryQuery($ctypes, $parentId, $topic, TRUE, $sort, 0, 0, $filterCategory);
    $data['count'] = count($nodes);
    $data['show_more'] = FALSE;
    if ($data['total'] > $data['count']) {
      $data['show_more'] = TRUE;
    }

    if (!empty($nodes)) {
      foreach ($nodes as $node) {
        $data['results'][] = $this->dataHelpers->fillData($node);
      }
    }
    return $data;
  }

}
