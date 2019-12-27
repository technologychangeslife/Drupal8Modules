<?php

namespace Drupal\gemc_n01_product_nav;

use Drupal\gemc_c115_120_section\DataHelpers;
use Drupal\gemc_c115_120_section\ProductData;

/**
 * Allows to build product navigation tree.
 */
class ProductNavigation {

  /**
   * Max depth of tree.
   *
   * @var int
   */
  const MAX_DEPTH = 10;

  /**
   * Product data service.
   *
   * @var \Drupal\gemc_c115_120_section\ProductData
   */
  protected $productData;

  /**
   * Data helpers.
   *
   * @var \Drupal\gemc_c115_120_section\DataHelpers
   */
  private $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\gemc_c115_120_section\ProductData $productData
   *   Product data.
   * @param \Drupal\gemc_c115_120_section\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(ProductData $productData, DataHelpers $dataHelpers) {
    $this->productData = $productData;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Get nested tree of subsections recursively.
   *
   * @param array $sections
   *   List of sections to parse.
   * @param int $maxDepth
   *   Maxumim recursive depth.
   * @param bool $recursive
   *   Recursing.
   * @param bool $onlyPublished
   *   Get only published content.
   *
   * @return array
   *   List of sections.
   */
  public function parseInDepth(array $sections, $maxDepth, $recursive = FALSE, $onlyPublished = TRUE) {
    static $storedDepth;

    if ($recursive) {
      $storedDepth++;
      if ($storedDepth >= $maxDepth) {
        $storedDepth--;
        return [];
      }
    }
    if (is_array($sections)) {
      foreach ($sections as $key => $section) {
        $parentNid = $section->id;

        $subSections = $this->productData->getSubsections($parentNid, 0, 100, TRUE, FALSE, $onlyPublished);

        if (!empty($subSections['items'])) {
          // Make recursive call.
          $section->below = $this->parseInDepth($subSections['items'], $maxDepth, TRUE, $onlyPublished);
        }
      }
    }
    $storedDepth--;
    return $sections;
  }

  /**
   * Build productsection menu tree.
   *
   * @return array
   *   Tree with nested productsections.
   */
  public function buildProductsMenuTree($onlyPublished = TRUE) {
    $mainSections = $this->productData->getMainsections(TRUE, $onlyPublished);
    $tree = $this->parseInDepth($mainSections, static::MAX_DEPTH, TRUE, $onlyPublished);
    $tree = $this->addProductsToTree($tree);
    return $tree;
  }

  /**
   * Retrieve menu tree.
   *
   * @return mixed
   *   List of nested sections.
   */
  public function retrieveMenuTree() {
    // Retrieve product navigation.
    $menuTree = $this->buildProductsMenuTree();

    // Parse menu tree to find childless items on detail level.
    foreach ($menuTree as &$subItem) {

      // Add link.
      $subItem->url = $this->dataHelpers->getPathFromNid($subItem->id);
      if (isset($subItem->below)) {
        $childlessBelow = [];
        foreach ($subItem->below as $key => &$detailItem) {

          // Add link.
          $detailItem->url = $this->dataHelpers->getPathFromNid($detailItem->id);

          // Find all detail level sections without childs.
          if (!isset($detailItem->below) || count($detailItem->below) == 0) {
            $childlessBelow[] = clone($detailItem);
            unset($subItem->below[$key]);
          }
          else {
            foreach ($detailItem->below as &$dropdownItem) {
              // Add link.
              $dropdownItem->url = $this->dataHelpers->getPathFromNid($dropdownItem->id);
            }
            $dropdownCount = count($detailItem->below);

            // Truncate all download level section lists to max 3.
            if ($dropdownCount > 3) {
              $detailItem->below = array_slice($detailItem->below, 0, 3);
              $detailItem->below['view_all_count'] = $dropdownCount;
            }
          }
        }

        // Wrapup all detail level sections without childs in 1 list.
        if (count($childlessBelow)) {
          $subItem->below = ['childless' => $childlessBelow] + $subItem->below;
        }
      }
    }

    return $menuTree;
  }

  /**
   * Add products to menu tree.
   *
   * Only product with "Show in product menu" option selected are added to menu.
   *
   * @param array $tree
   *   Section tree.
   *
   * @return array
   *   Tree with added products.
   */
  private function addProductsToTree(array $tree) {
    foreach ($tree as &$parent) {
      if (isset($parent->below)) {
        foreach ($parent->below as &$child) {
          if (!isset($child->below) || (isset($child->below) && count($child->below) < 4)) {
            $child = $this->addProductsToParent($child);
          }
        }
      }
      if (!isset($parent->below) || (isset($parent->below) && count($parent->below) < 4)) {
        $parent = $this->addProductsToParent($parent);
      }
    }
    return $tree;
  }

  /**
   * Add products to parent item.
   *
   * Only product with "Show in product menu" option selected are added to menu.
   *
   * @param object $item
   *   Section tree.
   *
   * @return object
   *   Altered menu item.
   */
  private function addProductsToParent($item) {
    $subproducts = $this->productData->getSubproducts([$item->id], 0, 10, FALSE, TRUE);
    if (count($subproducts['items'])) {
      if (!isset($item->below)) {
        $item->below = $subproducts['items'];
      }
      else {
        $item->below = array_merge($item->below, $subproducts['items']);
      }
    }
    return $item;
  }

}
