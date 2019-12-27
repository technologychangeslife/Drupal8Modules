<?php

namespace Drupal\gemc_components;

use Drupal\taxonomy\Entity\Term;

/**
 * Marekto Helpers.
 */
class MarketoHelpers {

  /**
   * Get meta data for hidden fields.
   *
   * @param array $meta
   *   The meta array.
   * @param \Drupal\node\Entity\Node $entity
   *   The entity object.
   *
   * @return mixed
   *   Returns the meta.
   */
  public function populateNData($entity, $meta = []) { // phpcs:ignore

    $hierarchy = $this->getNHierarchy($entity, TRUE);

    foreach ($hierarchy as $key => $item) {
      switch ($key) {

        // N2 - level 2 data of hierarchy.
        case 2:
          $meta['mCProductApplicationGEMkto'] = urldecode($item);
          break;

        // N3 - level 3 data of hierarchy.
        case 3:
          $meta['mCProductCategoryGEMkto'] = urldecode($item);
          break;

        // N4 - level 4 data of hierarchy.
        case 4:
          $meta['mCProductSubCategoryGEMkto'] = urldecode($item);
          break;
      }
    }
    return $meta;
  }

  /**
   * Get N Levels.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node object.
   * @param bool $reverse
   *   The var to reverse array.
   *
   * @return array
   *   Returns the hierarchy in array format.
   */
  private function getNHierarchy($node, $reverse = FALSE) { // phpcs:ignore
    /** @var \Drupal\gemc_n01_product_nav\SectionTrail $trail */
    $trail = \Drupal::service('gemc_n01_product_nav.section_trail');
    $trail = $trail->currentTrail($node);
    $hierarchy = [];

    if (!empty($trail['parents'])) {
      $hierarchy[] = $trail['current']->title;

      foreach (array_reverse($trail['parents']) as $item) {
        $hierarchy[] = $item->title;
      }

      if ($reverse) {
        $hierarchy = array_reverse($hierarchy);
      }

      $hierarchy = array_combine(range(1, count($hierarchy)), array_values($hierarchy));
    }
    return $hierarchy;
  }

}
