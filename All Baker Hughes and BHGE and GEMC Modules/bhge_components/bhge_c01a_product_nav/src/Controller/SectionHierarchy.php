<?php

namespace Drupal\bhge_c01a_product_nav\Controller;

use Drupal\bhge_c01a_product_nav\ProductNavigation;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for section hierarchy admin page.
 */
class SectionHierarchy extends ControllerBase {

  public $request;

  public $productNavigation;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('bhge_c01a_product_nav.product_navigation_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, ProductNavigation $productNavigation) {
    $this->request = $request;
    $this->productNavigation = $productNavigation;
  }

  /**
   * Load gallery data.
   */
  public function content() {
    $onlyPublished = FALSE;
    if ($this->request->get('ony-published')) {
      $onlyPublished = TRUE;
    }
    $sectionTree = $this->productNavigation->buildProductsMenuTree($onlyPublished);
    $tree = $this->prepareTree($sectionTree);
    $build = [
      '#theme' => 'menu',
      '#items' => $tree,
      '#title' => 'Hierarchy of product sectionpages',
      '#description' => 'desc',
    ];
    return $build;
  }

  /**
   * Prepare tree of sections.
   *
   * @param array $sectionTree
   *   Tree of data.
   * @param bool $recursive
   *   Recursive flag.
   *
   * @return array
   *   Nested, formatted array.
   */
  private function prepareTree(array $sectionTree, $recursive = FALSE) {
    $newTree = [];
    foreach ($sectionTree as $section) {
      $title = $section->title . ' - (' . $section->id . ')';
      if (isset($section->status) && !$section->status) {
        $title .= ' [unpublished]';
      }
      if (isset($section->field_has_page_value) && !$section->field_has_page_value) {
        $title .= ' [no page]';
      }
      if ($recursive) {
        $title = '└  ' . $title;
      }
      $newTree[$section->id] = [
        'title' => $title,
        'url' => 'internal:/node/' . $section->id,
      ];
      if (isset($section->below) && count($section->below)) {
        $newTree[$section->id]['below'] = $this->prepareTree($section->below, TRUE);
      }
    }
    return $newTree;
  }

}
