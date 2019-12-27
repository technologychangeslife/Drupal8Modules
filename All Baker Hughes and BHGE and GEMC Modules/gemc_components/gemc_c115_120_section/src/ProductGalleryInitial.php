<?php

namespace Drupal\gemc_c115_120_section;

use Drupal\Core\Url;

/**
 * Load initial product gallery data.
 */
class ProductGalleryInitial {

  private $limitSection;

  private $limitSubsection;

  /**
   * Gallery data provider.
   *
   * @var \Drupal\gemc_c115_120_section\ProductData
   */
  protected $productData;

  private $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\gemc_c115_120_section\ProductData $productData
   *   Gallery data provider.
   * @param \Drupal\gemc_c115_120_section\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(ProductData $productData, DataHelpers $dataHelpers) {
    $this->productData = $productData;
    $this->dataHelpers = $dataHelpers;
    $this->limitSection = $productData::$limitSection;
    $this->limitSubsection = $productData::$limitSubsection;
  }

  /**
   * Initial dataset for full pageload.
   *
   * @param int $nid
   *   Node id of visited section.
   * @param int $currentOffset
   *   Range offset.
   * @param string $thisTitle
   *   Title of current section.
   *
   * @return array
   *   Gallery data with filters and results.
   */
  public function getInitialData($nid, $currentOffset, $thisTitle) {

    $data = $results = $filters = [];
    // Find out if this is a "parent section" that contains other sections.
    $subCategories = $this->productData->getFilters($nid);

    if ($subCategories['count']) {
      // Get first page of subproducts the current section.
      $childProducts = $this->productData->getSubproducts([$nid], $currentOffset, $this->limitSection, FALSE);
      $filters = $subCategories['items'];

      // This is a section that contains subsections.
      $limit = $this->limitSection;

      if (!empty($childProducts['items']) && count($childProducts['items'])) {
        // Add childproducts to filter list.
        $childProductsFilter = new \stdClass();
        $childProductsFilter->id = $nid;
        $childProductsFilter->type = 'product';
        $childProductsFilter->title = $thisTitle;
        $filters = ['childproducts' => $childProductsFilter] + $filters;
      }

      if (!empty($childProducts['items']) && count($childProducts['items'])) {
        // With filters, own child products.
        $results = $childProducts;
      }
      else {
        // With filters, subsections.
        $results = $this->productData->getSubsections($filters[0]->id, $currentOffset, $this->limitSection);
        if (!$results['count']) {
          // With filters, subproducts.
          $results = $this->productData->getSubproducts([$filters[0]->id], $currentOffset, $this->limitSection);
        }
      }
    }
    else {
      // Get first page of subproducts the current section.
      $childProducts = $this->productData->getSubproducts([$nid], $currentOffset, $this->limitSubsection, FALSE);
      if ($childProducts['count']) {
        // Without filters, child products.
        $results = $childProducts;
        $limit = $this->limitSubsection;
        if ($results['count']) {
          // This is an "end" section that contains products.
          $data['type'] = 'subsection';
          // $data['filter_nid'] = $nid;.
        }
      }
    }

    if (!empty($results['total'])) {
      $url_params = [];
      $categories = $this->dataHelpers->prepareFilters($filters);
      if (is_array($categories)) {
        $first_category = reset($categories);
        if (!empty($first_category['contentType'])) {
          $url_params['contenttype'] = $first_category['contentType'];
        }
        if (!empty($first_category['topic'])) {
          $url_params['topic'] = $first_category['topic'];
        }
      }

      $url = Url::fromRoute('bhge_gallery.product_gallery', ['nid' => $nid], ['query' => $url_params]);
      $data['api'] = [
        'pagination' => [
          'total' => $results['total'],
          'offset' => 0,
          'limit' => $limit,
        ],
        'action' => $url->toString(),
      ];
      $data['items'] = $this->dataHelpers->prepareData($results, 0, $limit);
      $data['categories'] = $categories;
    }

    return $data;
  }

}
