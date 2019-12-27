<?php

namespace Drupal\bhge_c34_product_comparison;

use Drupal\Core\Database\Connection;

/**
 * Load product attributes for comparison data.
 *
 * It's a base class that is used both for BHGE and GEMC.
 */
abstract class ComparisonDataBase {

  public static $limitSection = 6;

  public static $limitSubsection = 8;

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
   * Get all related products with that have the same parent section.
   *
   * @param int $parentSectionId
   *   The parent section id of the product.
   * @param bool $onlyPublished
   *   This variable is used to check the status of entity.
   *
   * @return mixed
   *   Found sections.
   */
  public function getRelatedProducts($parentSectionId, $onlyPublished = TRUE) {
    $query = $this->getRelatedProductsQuery($parentSectionId, $onlyPublished);

    $result = $query->execute()->fetchAll();
    return $result;
  }

  /**
   * Get query for all related products which have the same parent section.
   *
   * @param int $parentSectionId
   *   This is the parent section ID of product.
   * @param bool $onlyPublished
   *   This variable is used to check the status of entity.
   *
   * @return \Drupal\Core\Database\Query\SelectInterface
   *   Query object.
   */
  protected function getRelatedProductsQuery($parentSectionId, $onlyPublished = TRUE) {
    $query = $this->connection->select('node_field_data', 'n');
    $query->condition('n.type', 'product');

    $query->leftJoin('node__field_prod_section', 'sect', 'n.nid = sect.entity_id');
    $query->addField('sect', 'field_prod_section_target_id', 'section_id');

    $query->leftJoin('node__field_product_attributes', 'attributes', 'n.nid = attributes.entity_id');
    // Must have attributes attached.
    $query->condition('attributes.entity_id', NULL, 'IS NOT NULL');

    $query->leftJoin('paragraph__field_attribute_value', 'value', 'attributes.field_product_attributes_target_id = value.entity_id');
    $query->addField('value', 'field_attribute_value_value', 'attribute_value');

    $query->leftJoin('paragraph__field_product_attribute', 'label', 'attributes.field_product_attributes_target_id = label.entity_id');
    $query->addField('label', 'field_product_attribute_target_id', 'term_id');

    $query->leftJoin('taxonomy_term_field_data', 'term', 'label.field_product_attribute_target_id = term.tid');
    $query->addField('term', 'tid', 'attribute_id');

    // Add base fields.
    $query->fields('n', ['nid', 'title', 'status']);

    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('sect.field_prod_section_target_id', $parentSectionId);

    $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = sect.entity_id');
    $query->orderBy('weight.field_weight_value', 'DESC');
    $query->orderBy('n.title');
    $query->orderBy('term.weight', 'DESC');
    $query->orderBy('term.name');

    return $query;
  }

  /**
   * Get all available product attributes.
   *
   * @param array $tids
   *   An array of ids to compare with taxonomy term ids.
   * @param string $vid
   *   A string to compare product attributes id from taxonomy term field data.
   *
   * @return mixed
   *   Return an mixed set of taxonomy term data.
   */
  public function getProductAttributes(array $tids = [], string $vid = 'product_attributes') {
    $query = $this->connection->select('taxonomy_term_field_data', 't');
    $query->fields('t', ['tid', 'name']);

    $query->condition('t.vid', $vid);
    if (count($tids)) {
      $query->condition('t.tid', $tids, 'IN');
    }

    $query->orderBy('t.weight', 'ASC');
    $query->orderBy('t.name');

    $result = $query->execute()->fetchAllKeyed();
    return $result;
  }

}
