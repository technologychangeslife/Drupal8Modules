<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

/**
 * Default sticky header variant.
 */
class DefaultVariant extends VariantBase {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getNavigationItems() {
    $navigationItems = [];
    if ($this->node->hasField('field_c130_downloads') && !$this->node->get('field_c130_downloads')
      ->isEmpty()) {
      $displayName = $this->getActiveDisplayName();
      $navigationItems[] = [
        'description' => $this->t('Overview'),
        'href' => $this->getDisplayUrl(),
        'active' => $displayName == 'full',
      ];

      $navigationItems[] = [
        'description' => $this->t('Downloads'),
        'href' => $this->getDisplayUrl('downloads'),
        'active' => $displayName == 'downloads',
      ];
    }

    return $navigationItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getctaItems() {
    return array_filter([
      $this->getAllSolutionsLink(),
    ]);
  }

}
