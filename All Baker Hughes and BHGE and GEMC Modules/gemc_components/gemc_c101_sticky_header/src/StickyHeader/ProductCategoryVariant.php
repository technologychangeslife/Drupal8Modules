<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Sticky header variant for Product nodes.
 */
class ProductCategoryVariant extends DefaultVariant {

  /**
   * {@inheritdoc}
   */
  public function getNavigationItems() {
    $navigationItems = [];
    $hasDownloadsTab = !$this->node->get('field_c130_downloads')->isEmpty();

    if ($hasDownloadsTab) {
      $navigationItems[] = [
        'description' => $this->t('Downloads'),
        'href' => $this->node->toUrl('canonical', ['fragment' => 'downloads'])->toString(),
        'active' => FALSE,
      ];
    }

    return $navigationItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getctaItems() {
    $filterEntity = $this->getFirstLevelProductSection($this->node);
    if (!isset($filterEntity) || $filterEntity === NULL) {
      return [];
    }
    return array_filter([
      $this->getAllSolutionsLink('section', $filterEntity->id()),
    ]);
  }

  /**
   * Get first level product section.
   *
   * @param \Drupal\node\NodeInterface $productSection
   *   Product section node.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\node\NodeInterface
   *   First level product section.
   */
  private function getFirstLevelProductSection(NodeInterface $productSection) {
    if (!isset($productSection) || $productSection === NULL || $productSection->hasField('field_section_parents')) {
      return NULL;
    }
    while (TRUE) {
      $parentField = $productSection->get('field_section_parents');
      if ($parentField->isEmpty()) {
        break;
      }
      $productSectionId = $parentField->get(0)->getValue()['target_id'];
      $productSection = Node::load($productSectionId);
    };
    return $productSection;
  }

}
