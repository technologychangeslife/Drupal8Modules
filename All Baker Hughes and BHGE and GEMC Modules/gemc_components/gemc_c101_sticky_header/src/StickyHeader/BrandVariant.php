<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Sticky header variant for Product nodes.
 */
class BrandVariant extends DefaultVariant {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->node->getTitle();
  }

  /**
   * {@inheritdoc}
   */
  public function getNavigationItems() {
    $navigationItems = [];
    $hasStickyLinks = !$this->node->get('field_sticky_links')->isEmpty();

    if ($hasStickyLinks) {
      $links = $this->node->get('field_sticky_links')->getValue();
      foreach ($links as $link) {
        $navigationItems[] = [
          'description' => $link['title'],
          'href' => Url::fromUri($link['uri'])->toString(),
          'active' => FALSE,
        ];
      }
    }

    return $navigationItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getctaItems() {
    return array_filter([
      $this->getAllSolutionsLink('product_brand', $this->node->id()),
    ]);
  }

}
