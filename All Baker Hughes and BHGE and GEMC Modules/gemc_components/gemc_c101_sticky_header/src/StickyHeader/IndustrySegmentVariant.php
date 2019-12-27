<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\node\NodeInterface;

/**
 * Sticky header variant for Product nodes.
 */
class IndustrySegmentVariant extends DefaultVariant {

  /**
   * {@inheritdoc}
   */
  public function getNavigationItems() {
    $navigationItems = [];
    $displayName = $this->getActiveDisplayName();
    $hasDownloadsTab = !$this->node->get('field_c130_downloads')->isEmpty();

    if ($hasDownloadsTab) {
      $navigationItems[] = [
        'description' => $this->t('Overview'),
        'href' => $this->getDisplayUrl(),
        'active' => $displayName == 'full',
      ];

      if ($hasDownloadsTab) {
        $navigationItems[] = [
          'description' => $this->t('Downloads'),
          'href' => $this->getDisplayUrl('downloads'),
          'active' => $displayName == 'downloads',
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
      $this->getAllSolutionsLink('industry', $this->getParentIndustryId($this->node)),
    ]);
  }

  /**
   * Get parent industry id.
   *
   * @param \Drupal\node\NodeInterface $industrySegment
   *   Industry segment node.
   *
   * @return \Drupal\node\NodeInterface
   *   Parent industry id.
   */
  private function getParentIndustryId(NodeInterface $industrySegment) {
    $parentField = $industrySegment->get('field_parent_industry');
    $industryId = $parentField->get(0)->getValue()['target_id'];
    return $industryId;
  }

}
