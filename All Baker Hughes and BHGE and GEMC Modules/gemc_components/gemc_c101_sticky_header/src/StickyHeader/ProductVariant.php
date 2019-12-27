<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\node\NodeInterface;

/**
 * Sticky header variant for Product nodes.
 */
class ProductVariant extends DefaultVariant {

  /**
   * {@inheritdoc}
   */
  public function getNavigationItems() {
    $navigationItems = [];
    $hasDownloadsTab = !$this->node->get('field_c130_downloads')->isEmpty();
    $hasSpecificationsTab = !$this->node->get('field_c126_product_specification')->isEmpty();

    if ($hasDownloadsTab || $hasSpecificationsTab) {
      if ($hasSpecificationsTab) {
        $navigationItems[] = [
          'description' => $this->t('Specs'),
          'href' => $this->node->toUrl('canonical', ['fragment' => 'specifications'])->toString(),
          'active' => FALSE,
        ];
      }

      if ($hasDownloadsTab) {
        $navigationItems[] = [
          'description' => $this->t('Downloads'),
          'href' => $this->node->toUrl('canonical', ['fragment' => 'downloads'])->toString(),
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
      $this->getAllSolutionsLink('section', $this->getFirstLevelSectionId($this->node)),
    ]);
  }

  /**
   * Get first level section for product.
   *
   * @param \Drupal\node\NodeInterface $product
   *   Product node.
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   *   First level section for product.
   */
  private function getFirstLevelSectionId(NodeInterface $product) {
    $categoryField = $product->get('field_filter_main_section');
    if (!$categoryField->isEmpty()) {
      $sectionId = $categoryField->get(0)->getValue()['target_id'];
      return $sectionId;
    }
    return NULL;
  }

}
