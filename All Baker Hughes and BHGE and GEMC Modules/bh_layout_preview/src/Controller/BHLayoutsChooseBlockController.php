<?php

namespace Drupal\bh_layout_preview\Controller;

use Drupal\layout_builder\Controller\ChooseBlockController as ChooseBlockControllerCore;
use Drupal\layout_builder\SectionStorageInterface;
use Drupal\Core\Url;

/**
 * Defines a controller to choose a block.
 *
 * @internal
 *   Controller classes are internal.
 */
class BHLayoutsChooseBlockController extends ChooseBlockControllerCore {

  /**
   * {@inheritdoc}
   */
  public function build(SectionStorageInterface $section_storage, $delta, $region) {
    $build = parent::build($section_storage, $delta, $region);
    $black_categories = $build['block_categories'];

    foreach ($black_categories as $category => $blocks) {
      if (strpos($category, '#') === 0) {
        continue;
      }

      // Update new #theme for append the preview icon.
      $build['block_categories'][$category]['links']['#theme'] = 'bh_layout_preview_block_links';

      // Change the Link heading to "Components".
      if ($category == 'Entity Browser') {
        $build['block_categories'][$category]['#title'] = $this->t('Components');
      }
    }
    return $build;
  }

}
