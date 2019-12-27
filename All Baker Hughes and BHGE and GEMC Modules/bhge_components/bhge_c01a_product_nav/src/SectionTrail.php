<?php

namespace Drupal\bhge_c01a_product_nav;

use Drupal\bhge_c55_product_gallery\ProductData;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Class SectionTrail.
 *
 * @package Drupal\bhge_c01a_product_nav
 */
class SectionTrail {

  /**
   * Gallery data provider.
   *
   * @var \Drupal\bhge_c55_product_gallery\ProductData
   */
  protected $productData;

  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ProductData $productData, EntityTypeManager $entityTypeManager) {
    $this->productData = $productData;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Get parent trail upwards for current node.
   *
   * @param object $node
   *   Node, optional.
   * @param bool $publishedOnly
   *   Load only published.
   *
   * @return array
   *   List of trail sections.
   */
  public function currentTrail($node = NULL, $publishedOnly = TRUE) {
    $return = [
      'current' => NULL,
      'parents' => [],
    ];
    if (!$node || $node == NULL) {
      $node = \Drupal::routeMatch()->getParameter('node');
      // Could be nid on revision view.
      if (is_numeric($node)) {
        $node = Node::load($node);
      }
    }
    if ($node && in_array($node->bundle(), ['section', 'product'])) {
      $return['current'] = $this->productData->getParentSection($node->id(), $node->bundle(), $publishedOnly);
      if (isset($return['current']->section_id)) {
        $parents = $this->trailHierarchy($return['current']->section_id, FALSE, $publishedOnly);
        $return['parents'] = array_reverse($parents, TRUE);
      }
    }

    return $return;
  }

  /**
   * Trail of parent sections recursively.
   *
   * @param object $nid
   *   Child node ID;.
   * @param bool $recursive
   *   Recursive check.
   * @param bool $publishedOnly
   *   Load only published.
   * @param int $nesting
   *   Variabe used to display only 5 items.
   *
   * @return array
   *   List of parent sections.
   */
  private function trailHierarchy($nid, $recursive = FALSE, $publishedOnly = TRUE, $nesting = 0) {
    static $parents = [];
    if (!$recursive) {
      $parents = [];
    }

    if ($nesting <= 5) {
      $parent = $this->productData->getParentSection($nid, 'section', $publishedOnly);
      if ($parent) {
        $parents[$nid] = $parent;
        if ($parent->section_id) {
          $this->trailHierarchy($parent->section_id, TRUE, $publishedOnly, ++$nesting);
        }
      }
      return $parents;
    }
  }

}
