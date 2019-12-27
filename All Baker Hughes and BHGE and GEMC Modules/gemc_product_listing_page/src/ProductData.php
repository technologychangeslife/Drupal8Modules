<?php

namespace Drupal\gemc_product_listing_page;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Database\Connection;
use Drupal\gemc_c115_120_gallery\GalleryData;
use Drupal\gemc_c115_120_gallery\DataHelpers;
use Drupal\node\Entity\Node;

/**
 * Load product related data.
 */
class ProductData {

  public static $limitSection = 6;

  public static $limitSubsection = 8;

  protected $request;
  protected $connection;
  protected $galleryData;
  protected $dataHelpers;

  /**
   * Constructor.
   *
   * @param string $request
   *   Get the current request.
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   * @param Drupal\gemc_c115_120_gallery\GalleryData $galleryData
   *   The gallery data.
   * @param Drupal\gemc_c115_120_gallery\DataHelpers $dataHelpers
   *   The data helpers.
   */
  public function __construct($request, Connection $connection, GalleryData $galleryData, DataHelpers $dataHelpers) {
    $this->request = $request->getCurrentRequest();
    $this->connection = $connection;
    $this->galleryData = $galleryData;
    $this->dataHelpers = $dataHelpers;
    $this->brand = Xss::filter($this->request->query->get('product_brand'));
    $this->industry = Xss::filter($this->request->query->get('industry'));
    $this->category = Xss::filter($this->request->query->get('section'));
    $this->limit = Xss::filter($this->request->query->get('limit'));
    $this->offset = Xss::filter($this->request->query->get('offset'));
  }

  /**
   * Get all filters for solutions.
   *
   * @return array
   *   Filters.
   */
  public function getFilters() {
    $filters = [];

    $solutions = $this->getFilter('section');
    if (!empty($solutions)) {
      $filters[] = [
        'categoryName' => t('Solution'),
        'items' => $solutions,
      ];
    }

    $industries = $this->getFilter('industry');
    if (!empty($industries)) {
      $filters[] = [
        'categoryName' => t('Industry'),
        'items' => $industries,
      ];
    }

    $brands = $this->getFilter('product_brand');
    if (!empty($brands)) {
      $filters[] = [
        'categoryName' => t('Brand'),
        'items' => $brands,
      ];
    }

    return $filters;
  }

  /**
   * Get api query for load more.
   *
   * @return string
   *   Api query.
   */
  public function getApiQuery() {
    if (!empty($this->brand)) {
      return '&reference=parent_brand&pid=' . $this->brand;
    }
    elseif (!empty($this->industry)) {
      return '&reference=filter_industry&pid=' . $this->industry;
    }
    elseif (!empty($this->category)) {
      return '&reference=filter_main_section&pid=' . $this->category;
    }

    return '';
  }

  /**
   * Get selected item in filters.
   *
   * @return string
   *   Selected item.
   */
  public function getSelectedItem() {

    foreach (['brand', 'industry', 'category'] as $property) {
      if (isset($this->{$property}) && is_numeric($this->{$property})) {
        $node = Node::load($this->{$property});
        if ($node) {
          return $node->getTitle();
        }
      }
    }

    return '';
  }

  /**
   * Get products.
   *
   * @param int $limit
   *   Limit the number of results.
   * @param int $offset
   *   Offset.
   *
   * @return array
   *   Found products.
   */
  public function getProducts($limit, $offset) {
    $results = [];

    if (!empty($this->brand)) {
      $nodes = $this->galleryData->galleryQuery(['product'], 'field_parent_brand', $this->brand, NULL, FALSE, 'weight', $offset, $limit);
      $results['count'] = $this->galleryData->galleryQuery(['product'], 'field_parent_brand', $this->brand, NULL, TRUE, 'weight', 0, 0);
    }
    elseif (!empty($this->industry)) {
      $nodes = $this->galleryData->galleryQuery(['product'], 'field_filter_industry', $this->industry, NULL, FALSE, 'weight', $offset, $limit);
      $results['count'] = $this->galleryData->galleryQuery(['product'], 'field_filter_industry', $this->industry, NULL, TRUE, 'weight', 0, 0);
    }
    elseif (!empty($this->category)) {
      $nodes = $this->galleryData->galleryQuery(['product'], 'field_filter_main_section', $this->category, NULL, FALSE, 'weight', $offset, $limit);
      $results['count'] = $this->galleryData->galleryQuery(['product'], 'field_filter_main_section', $this->category, NULL, TRUE, 'weight', 0, 0);
    }
    else {
      $nodes = $this->galleryData->galleryQuery(['product'], NULL, NULL, NULL, FALSE, 'weight', $offset, $limit);
      $results['count'] = $this->galleryData->galleryQuery(['product'], NULL, NULL, NULL, TRUE, 'weight', 0, NULL);
    }

    if (!empty($nodes)) {
      foreach ($nodes as $node) {
        $results['items'][] = $this->dataHelpers->fillData($node);
      }
    }

    return $results;
  }

  /**
   * Get all published and active filter.
   *
   * @param string $contentType
   *   The content type.
   *
   * @return mixed
   *   Found sections.
   */
  private function getFilter($contentType) {

    // Get published, active nodes, of type $contentType.
    $query = $this->connection->select('node_field_data', 'n');

    $query->condition('n.status', 1);

    $query->condition('n.type', $contentType);

    if ($contentType == 'section') {
      $query->innerJoin('node__field_filter_main_section', 'sect', 'sect.field_filter_main_section_target_id = n.nid');
    }

    if ($contentType == 'industry') {
      $query->innerJoin('node__field_filter_industry', 'industry', 'industry.field_filter_industry_target_id = n.nid');
    }

    // Get basic values.
    $query->addField('n', 'title', 'label');
    $query->addField('n', 'nid', 'id');

    $data = $query->execute()->fetchAll();

    if (!empty($data)) {
      foreach ($data as $key => $item) {
        // Retrieve main links.
        $data[$key]->href = '';
        $data[$key]->href = new \stdClass();
        $data[$key]->href = '?' . $contentType . '=' . $data[$key]->id;
      }
    }

    return array_unique($data, SORT_REGULAR);
  }

}
