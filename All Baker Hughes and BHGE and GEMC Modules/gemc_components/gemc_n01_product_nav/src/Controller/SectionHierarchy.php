<?php

namespace Drupal\gemc_n01_product_nav\Controller;

use Drupal\gemc_n01_product_nav\ProductNavigation;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for section hierarchy admin page.
 */
class SectionHierarchy extends ControllerBase {

  /**
   * Product navigation service.
   *
   * @var \Drupal\gemc_n01_product_nav\ProductNavigation
   */
  private $productNavigation;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('gemc_n01_product_nav.product_navigation_data'));
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ProductNavigation $productNavigation) {
    $this->productNavigation = $productNavigation;
  }

  /**
   * Build section hierarchy admin page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   HTTP request object.
   *
   * @return array
   *   Build array for hierarchy admin page.
   */
  public function content(Request $request) {
    $onlyPublished = $request->get('ony-published', FALSE);
    $sectionTree = $this->productNavigation->buildProductsMenuTree($onlyPublished);
    $tree = $this->prepareTree($sectionTree);
    $build = [
      '#theme' => 'menu',
      '#items' => $tree,
      '#title' => $this->t('Hierarchy of product sectionpages'),
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
