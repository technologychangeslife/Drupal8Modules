<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c109_image_carousel",
 *   label = @Translation("C109 Image Carousel"),
 *   entity_type = "paragraph",
 *   bundle = "c109_image_carousel",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC109ImageCarousel extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $c109Images = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_c109_images']);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'items' => count($variables['elements']['field_c109_images']['#items']) == 1 ? [$c109Images] : $c109Images,
    ];
  }

}
