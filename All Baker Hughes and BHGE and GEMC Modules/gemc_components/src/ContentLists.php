<?php

namespace Drupal\gemc_components;

use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 * Load content lists.
 */
class ContentLists {

  protected $connection;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get published content data filtered by given type/industries/categories.
   *
   * @param string $contentType
   *   Return only content of this content type.
   * @param int $limit
   *   Max number of nodes to return.
   * @param array|null $parentIndustries
   *   Filter content by these parent industries.
   * @param array|null $parentCategories
   *   Filter content by these parent categories.
   * @param string $sort
   *   A field to sort results by.
   *
   * @return mixed
   *   Published content data filtered by given type/industries/categories.
   */
  public function getContentList($contentType, $limit = 1000, $parentIndustries = NULL, $parentCategories = NULL, $sort = 'created') {

    // Get published, active nodes, of type $contentType.
    $query = $this->connection->select('node_field_data', 'n');

    $query->condition('n.status', 1);

    $query->condition('n.type', $contentType);

    // Get basic values.
    $query->addField('n', 'title');
    $query->addField('n', 'title', 'subHeading');
    $query->addField('n', 'nid', 'id');

    // Get description of node.
    $query->leftJoin('node__body', 'body', 'body.entity_id = n.nid');
    $query->addField('body', 'body_value', 'description');

    if ($contentType == 'section') {
      // Orphaned that contain no references to other sections.
      $query->leftJoin('node__field_section_parents', 'sect', 'sect.entity_id = n.nid');
      $query->isNull('sect.field_section_parents_target_id');
    }

    if (!empty($parentIndustries)) {
      $query->leftJoin('node__field_parent_industries', 'parent_industries', 'parent_industries.entity_id = n.nid');
      $query->condition('parent_industries.field_parent_industries_target_id', $parentIndustries);
    }

    if (!empty($parentCategories)) {
      $query->leftJoin('node__field_prod_section', 'parent_category', 'parent_category.entity_id = n.nid');
      $query->condition('parent_category.field_prod_section_target_id', $parentCategories);
    }

    // Order by weight.
    $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
    $query->orderBy('weight.field_weight_value', 'DESC');
    $query->orderBy('n.title');

    $query->orderBy($sort);

    $query->range(0, $limit);

    $data = $query->execute()->fetchAll();

    // Retrieve the path aliases.
    if (!empty($data)) {
      foreach ($data as $key => $item) {
        // Retrieve main links.
        $data[$key]->href = '';
        $data[$key]->href = new \stdClass();
        $data[$key]->href = Url::fromUri('entity:node/' . $item->id)->toString();

        if (!empty($data[$key]->description)) {
          $data[$key]->description = strip_tags($data[$key]->description);
        }
      }
    }

    return $data;
  }

}
