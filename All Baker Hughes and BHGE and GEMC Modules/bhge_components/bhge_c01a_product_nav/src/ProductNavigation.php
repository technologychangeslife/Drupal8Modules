<?php

namespace Drupal\bhge_c01a_product_nav;

use Drupal\bhge_c55_product_gallery\ProductData;
use Drupal\Core\Database\Connection;

/**
 * Class ProductNavigation.
 *
 * @package Drupal\bhge_c01a_product_nav */
class ProductNavigation {

  /**
   * The current database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Get max depth.
   *
   * @var int
   */
  public static $maxDepth = 5;

  /**
   * The Product Data container.
   *
   * @var \Drupal\bhge_c55_product_gallery\ProductData
   *   This will contain product data.
   */
  protected $productData;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   * @param \Drupal\bhge_c55_product_gallery\ProductData $productData
   *   This variable will contain product data.
   */
  public function __construct(Connection $connection, ProductData $productData) {
    $this->connection = $connection;
    $this->productData = $productData;
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
  public function parseInDepth(array $sections, $maxDepth = 3, $recursive = FALSE, $onlyPublished = TRUE) {

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

        if (!empty($subSections['rows'])) {
          $section->below = new \stdClass();
          // Make recursive call.
          $section->below = $this->parseInDepth($subSections['rows'], $maxDepth, TRUE, $onlyPublished);
        }
        else {
          if ($section->field_has_page_value == 0 && $onlyPublished) {
            // Deepest level section in its branch without page, dont show.
            unset($sections[$key]);

          }
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

    // Define maxDepth.
    $maxDepth = 10;

    // Get parent items.
    $mainSections = $this->productData->getMainsections(TRUE, TRUE, $onlyPublished);
    $tree = $this->parseInDepth($mainSections, $maxDepth, TRUE, $onlyPublished);
    $tree = $this->addProductsToTree($tree);
    return $tree;
  }

  /**
   * Retrieve menu tree.
   *
   * @param bool $regenerate
   *   Purge cache and renew.
   *
   * @return mixed
   *   List of nested sections.
   */
  public function retrieveMenuTree($regenerate = FALSE) {
    if (!$regenerate) {
      // Menu rendering from cache or regeneration of it.
      if ($cache = \Drupal::cache()->get('products_menu_cache')) {
        return $cache->data;
      }
    }

    // Retrieve product navigation.
    $menuTree = $this->buildProductsMenuTree();

    // Parse menu tree to find childless items on detail level.
    foreach ($menuTree as &$subItem) {
      if (isset($subItem->below)) {
        $childlessBelow = [];
        foreach ($subItem->below as $key => &$detailItem) {

          // Find all detail level sections without childs.
          if (!isset($detailItem->below) || count($detailItem->below) == 0) {
            if (empty($detailItem->field_separate_menu_column_value)) {
              $childlessBelow[] = clone($detailItem);
              unset($subItem->below[$key]);
            }
          }
          else {
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
    \Drupal::cache()->set('products_menu_cache', $menuTree);
    return $menuTree;
  }

  /**
   * Add products to menu tree.
   *
   * @param array $tree
   *   Section tree.
   *
   * @return array
   *   tree with added products.
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
   * @param object $item
   *   Section tree.
   *
   * @return object
   *   Altered menu item.
   */
  private function addProductsToParent($item) {
    $subproducts = $this->productData->getSubproducts([$item->id], 0, 10, FALSE, FALSE, TRUE);
    if (count($subproducts['rows'])) {
      if (!isset($item->below)) {
        $item->below = $subproducts['rows'];
      }
      else {
        $item->below = array_merge($item->below, $subproducts['rows']);
      }
    }
    return $item;
  }

}
