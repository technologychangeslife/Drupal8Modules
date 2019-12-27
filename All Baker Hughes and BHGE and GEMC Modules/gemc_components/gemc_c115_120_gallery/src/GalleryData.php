<?php

namespace Drupal\gemc_c115_120_gallery;

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
   * @param \Drupal\gemc_c115_120_gallery\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(Connection $connection, DataHelpers $dataHelpers) {
    $this->connection = $connection;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Made query with sorting option.
   *
   * @param array $ctypes
   *   Content types.
   * @param string $referenceField
   *   Parent reference field.
   * @param string $parentId
   *   Parent id.
   * @param string $topicId
   *   Selected topic filter.
   * @param bool $countOnly
   *   Return only count.
   * @param string $sort
   *   Sorter.
   * @param int $offset
   *   Query offset.
   * @param int $limit
   *   Query limit.
   * @param int $filterCategory
   *   Category to prefilter on.
   *
   * @return mixed
   *   Return query.
   */
  public function galleryQuery(array $ctypes, $referenceField = '', $parentId = NULL, $topicId = NULL, $countOnly = FALSE, $sort = 'weight', $offset = 0, $limit = 10, $filterCategory = NULL) {

    // Get nodes by ctype and status published.
    $query = $this->connection->select('node_field_data', 'n');
    $query->distinct();
    $query->condition('n.type', $ctypes, 'IN');
    $query->condition('n.status', 1);
    $query->addField('n', 'nid', 'nid');

    // Filter on weight for incoming requests from gemc.c115_120_industry and gemc.c115_120_brand.
    if( in_array('product', $ctypes) ) {
      $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
      $query->orderBy('weight.field_weight_value', 'DESC');
    }

    // Filter on parent reference field.
    if (!empty($referenceField) && !empty($parentId) && is_numeric($parentId)) {
      $query->leftJoin('node__' . $referenceField, 'reference', 'reference.entity_id = n.nid');
      $query->condition('reference.' . $referenceField . '_target_id', $parentId);
    }

    // Filter on topic (user selected filter).
    if (!empty($topicId) && is_numeric($topicId)) {
      $query->leftJoin('node__field_filter_facets', 'topic', 'topic.entity_id = n.nid');
      $query->condition('topic.field_filter_facets_target_id', $topicId);
      $query->addField('topic', 'entity_id', 'eid');
    }

    // Filter on category.
    if (!empty($filterCategory) && is_numeric($filterCategory)) {
      $query->leftJoin('node__field_filter_categories', 'c', 'c.entity_id = n.nid');
      $query->condition('c.field_filter_categories_target_id', $filterCategory);
    }

    if (!$countOnly) {
      if ($sort == 'weight' && (!in_array('product', $ctypes))) {
        $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
        $query->orderBy('weight.field_weight_value', 'DESC');
      }

      // Always have creation date at least as secondary sorter.
      $query->orderBy('n.created', 'DESC');
    }

    if ($limit) {
      $query->range($offset, $limit);
    }

    // In case we only want to count results.
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
   *   Content type.
   * @param null|array $filterCategory
   *   Parent ID.
   *
   * @return mixed
   *   List of available topics.
   */
  public function topics($ctype, $filterCategory = NULL) {

    $query = $this->connection->select('node_field_data', 'n');
    $query->condition('n.type', $ctype);
    $query->condition('n.status', 1);

    $query->addField('n', 'nid', 'nid');

    if (!empty($filterCategory) && is_numeric($filterCategory)) {
      $query->leftJoin('node__field_filter_categories', 'c', 'c.entity_id = n.nid');
      $query->condition('c.field_filter_categories_target_id', $filterCategory);
    }

    $nids = $query->execute()->fetchCol();
    $results = $this->topicsTaxonomyData($nids);

    return $results;
  }

  /**
   * Get title and tid for forming the filters sidebar.
   *
   * @param array $nids
   *   Node ids.
   *
   * @return mixed
   *   List of taxonomh terms.
   */
  public function topicsTaxonomyData(array $nids) {

    if (empty($nids)) {
      return NULL;
    }

    $query = $this->connection->select('node__field_filter_facets', 'ft');
    $query->condition('ft.entity_id', $nids, 'IN');
    $query->leftJoin('taxonomy_term_field_data', 'td', 'ft.field_filter_facets_target_id = td.tid');
    $query->addField('td', 'tid', 'topic');
    $query->addField('td', 'name', 'label');
    $query->orderBy('td.weight');
    $query->distinct();
    return $query->execute()->fetchAll();
  }

  /**
   * Prepare data.
   *
   * @param array $ctypes
   *   Content types.
   * @param string $referenceField
   *   Parent reference field.
   * @param string $parentId
   *   Parent id.
   * @param string $topic
   *   Selected topic filter.
   * @param string $sort
   *   Sort by weight.
   * @param int $offset
   *   Query offset.
   * @param int $limit
   *   Query limit.
   * @param int $filterCategory
   *   Category to prefilter on.
   *
   * @return array
   *   Results.
   */
  public function prepareData(array $ctypes, $referenceField = '', $parentId = NULL, $topic = NULL, $sort = 'weight', $offset = 0, $limit = 10, $filterCategory = NULL) {

    $data = [];
    $data['results'] = [];

    $nodes = $this->galleryQuery($ctypes, $referenceField, $parentId, $topic, FALSE, $sort, $offset, $limit, $filterCategory);
    $data['total'] = $this->galleryQuery($ctypes, $referenceField, $parentId, $topic, TRUE, $sort, 0, 0, $filterCategory);
    $data['count'] = 0;

    if (!empty($nodes)) {
      $data['count'] = count($nodes);
      foreach ($nodes as $node) {
        $data['results'][] = $this->dataHelpers->fillData($node);
      }
    }
    return $data;
  }

}
