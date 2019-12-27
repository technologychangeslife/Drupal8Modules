<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\node\Entity\Node;

/**
 * Sticky header variant for Product nodes.
 */
class IndustryVariant extends DefaultVariant {

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
      $this->getAllSolutionsLink('industry', $this->node->id()),
    ]);
  }

}
