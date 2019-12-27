<?php

namespace Drupal\bhge_c55_product_gallery;

/**
 * Load initial product gallery data.
 */
class ProductGalleryInitial {

  private $limitSection;

  private $limitSubsection;

  /**
   * Gallery data provider.
   *
   * @var \Drupal\bhge_c55_product_gallery\ProductData
   */
  protected $productData;

  private $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\bhge_c55_product_gallery\ProductData $productData
   *   Gallery data provider.
   * @param \Drupal\bhge_c55_product_gallery\DataHelpers $dataHelpers
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
   * @param int $filterNid
   *   Node id of selected filter section.
   * @param int $currentOffset
   *   Range offset.
   * @param string $thisTitle
   *   Title of current section.
   *
   * @return array
   *   Gallery data with filters and results.
   */
  public function getInitialData($nid, $filterNid, $currentOffset, $thisTitle) {

    // Set initial datastructure.
    $data = [
      'results' => [
        'rows' => [],
        'total' => 0,
        'count' => 0,
      ],
      'show_more' => FALSE,
      'initial_type' => '',
      'filter_nid' => 0,
      'offset' => 0,
      'has_highlights' => FALSE,
    ];

    // Get first page of subproducts the current section.
    $childProducts = $this->productData->getSubproducts([$nid], $currentOffset, $this->limitSubsection, FALSE, FALSE);

    // Find out if this is a "parent section" that contains other sections.
    $data['filters'] = $this->productData->getFilters($nid);
    if ($data['filters']['count']) {
      // This is a section that contains subsections.
      $data['has_filters'] = TRUE;
      $data['limit'] = $this->limitSection;
      $data['type'] = 'section';

      if (!empty($childProducts['rows']) && count($childProducts['rows'])) {
        // Add childproducts to filter list.
        $childProductsFilter = new \stdClass();
        $childProductsFilter->id = 'childproducts_' . $nid;
        $childProductsFilter->title = $thisTitle;
        $data['filters']['rows'] = ['childproducts' => $childProductsFilter] + $data['filters']['rows'];
      }

      if ($filterNid) {
        // With filters, prefiltered.
        $data['results'] = $this->productData->getSubsections($filterNid, $currentOffset, $this->limitSection);
        if (!$data['results']['count']) {
          // Get first page of subproducts of first filter section.
          $data['results'] = $this->productData->getSubproducts([$filterNid], $currentOffset, $this->limitSection);
          if (!$data['results']['count']) {
            // Apparently no subproducts found. Show grid of subsections
            // without filters.
            $data['results'] = $this->productData->getSubsections($nid, $currentOffset, $this->limitSubsection, FALSE, FALSE);
            $data['filters'] = [];
            $data['has_filters'] = FALSE;
            $data['type'] = 'mainsection';
            $data['limit'] = $this->limitSubsection;
          }
        }
      }
      else {
        // See if we have highlighted products.
        $data['results'] = $this->productData->getSubproducts($data['filters']['nids'], $currentOffset, $this->limitSection, TRUE, FALSE);

        if ($data['results']['total']) {
          // With filters, highlights.
          // Add highlights to filter list.
          $highlightsFilter = new \stdClass();
          $highlightsFilter->id = 'highlights_0';
          $highlightsFilter->title = 'Highlights';
          $data['filters']['rows'] = ['highlights' => $highlightsFilter] + $data['filters']['rows'];
          $data['has_highlights'] = TRUE;
        }
        else {
          if (!empty($childProducts['rows']) && count($childProducts['rows'])) {
            // With filters, own child products.
            $data['results'] = $childProducts;
          }
          else {
            // With filters, subsections.
            $data['results'] = $this->productData->getSubsections([$data['filters']['nids'][0]], $currentOffset, $this->limitSection);
            if (!$data['results']['count']) {
              // With filters, subproducts.
              $data['results'] = $this->productData->getSubproducts([$data['filters']['nids'][0]], $currentOffset, $this->limitSection, FALSE, TRUE);
            }
          }
          $data['filter_nid'] = $filterNid = $data['filters']['rows'][0]->id;
        }
      }

    }
    else {
      // Without filters, child products.
      $data['results'] = $childProducts;
      if ($data['results']['count']) {
        // This is an "end" section that contains products.
        $data['type'] = 'subsection';
        $data['limit'] = $this->limitSubsection;
        $data['filter_nid'] = $nid;
      }
    }

    if ($data['has_highlights']) {
      $data['initial_type'] = 'highlights';
    }
    else {
      $data['initial_type'] = !empty($data['type']) ? $data['type'] : '';
    }

    // Show "load more" button?
    if ($data['results']['count'] && $data['results']['count'] < $data['results']['total'] - $currentOffset) {
      $data['show_more'] = TRUE;
      $data['new_offset'] = $data['limit'] + $currentOffset;
    }

    // Link to this node.
    $data['this_url'] = $this->dataHelpers->getPathFromNid($nid);

    return $data;
  }

}
