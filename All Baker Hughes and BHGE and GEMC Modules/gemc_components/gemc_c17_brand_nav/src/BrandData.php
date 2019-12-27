<?php

namespace Drupal\gemc_c17_brand_nav;

use Drupal\Core\Database\Connection;

/**
 * Product Brand Data.
 */
class BrandData {

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
   * Get navigation links for c17 of given nid.
   *
   * @param int $nid
   *   Node id.
   *
   * @return array
   *   Navigation links.
   */
  public function getBrandNavLinks($nid) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = $this->connection->select('node_field_data', 'n');
    $query->distinct();
    $query->condition('n.type', 'product_brand');
    $query->addField('n', 'title');
    $query->addField('n', 'nid', 'id');
    $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
    $query->condition('n.status', 1);
    $query->condition('n.langcode', $language);
    $query->orderBy('weight.field_weight_value', 'DESC');
    $query->orderBy('n.title', 'ASC');

    $results = $query->execute()->fetchAll();
    foreach ($results as $key => $result) {
      if ($result->id == $nid) {
        $navLinks = [];
        if (isset($results[$key - 1])) {
          $navLinks['previous'] = $results[$key - 1];
        }
        if (isset($results[$key + 1])) {
          $navLinks['next'] = $results[$key + 1];
        }

        return $navLinks;
      }
    }
  }

}
