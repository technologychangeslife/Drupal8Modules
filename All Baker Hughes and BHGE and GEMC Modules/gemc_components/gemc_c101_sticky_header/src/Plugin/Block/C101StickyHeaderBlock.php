<?php

namespace Drupal\gemc_c101_sticky_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\gemc_c101_sticky_header\StickyHeader\DefaultVariant;
use Drupal\gemc_c101_sticky_header\StickyHeader\IndustrySegmentVariant;
use Drupal\gemc_c101_sticky_header\StickyHeader\IndustryVariant;
use Drupal\gemc_c101_sticky_header\StickyHeader\ProductCategoryVariant;
use Drupal\gemc_c101_sticky_header\StickyHeader\ProductVariant;
use Drupal\gemc_c101_sticky_header\StickyHeader\BrandVariant;

/**
 * Provides a Sticky Header block.
 *
 * @Block(
 *  id = "c101_sticky_header_block",
 *  admin_label = @Translation("C101 Sticky header"),
 *   context = {
 *     "node" = @ContextDefinition("entity:node")
 *   }
 * )
 */
class C101StickyHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    // parent::__construct($configuration, $plugin_id, $plugin_definition);.
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $variant = $this->getStickyHeaderVariant();
    $navigationItems = $variant->getNavigationItems();
    $activeNavItem = $this->t('Overview');
    foreach ($navigationItems as $navigationItem) {
      if ($navigationItem['active'] === TRUE) {
        $activeNavItem = $navigationItem['description'];
      }
    }
    $data = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 0,
      'selectedItem' => $activeNavItem,
      'title' => $variant->getTitle(),
      'mobileCallout' => $variant->getContactLink('field_c04_contact'),
      'navItems' => $navigationItems,
      'ctaItems' => $variant->getctaItems(),
      'transparent' => empty($navigationItems),
    ];

    return [
      '#theme' => 'c101_sticky_header',
      '#data' => $data,
    ];
  }

  /**
   * Gte cache context.
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['url']);
  }

  /**
   * Get header variant based on node type.
   *
   * @return \Drupal\gemc_c101_sticky_header\StickyHeader\VariantInterface
   *   Returns variant interface.
   */
  private function getStickyHeaderVariant() {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->getContextValue('node');
    switch ($node->bundle()) {
      case 'product':
        return new ProductVariant($node);

      case 'section':
        return new ProductCategoryVariant($node);

      case 'product_brand':
        return new BrandVariant($node);

      case 'industry':
        return new IndustryVariant($node);

      case 'industry_segment':
        return new IndustrySegmentVariant($node);

      default:
        return new DefaultVariant($node);
    }
  }

}
