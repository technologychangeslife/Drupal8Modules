<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

/**
 * Variant interface for sticky header.
 */
interface VariantInterface {

  /**
   * Get title for sticky header.
   *
   * @return array
   *   Title for sticky header.
   */
  public function getTitle();

  /**
   * Get navigation items for sticky header.
   *
   * @return array
   *   Navigation items for sticky header.
   */
  public function getNavigationItems();

  /**
   * Get CTA items for sticky header.
   *
   * @return array
   *   CTA items for sticky header.
   */
  public function getctaItems();

}
