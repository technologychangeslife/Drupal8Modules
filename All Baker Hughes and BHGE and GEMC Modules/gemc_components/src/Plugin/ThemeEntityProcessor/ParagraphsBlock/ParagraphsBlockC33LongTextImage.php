<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c33_long_text_image",
 *   label = @Translation("C33 Long Text Image"),
 *   entity_type = "paragraph",
 *   bundle = "c33_long_text_image",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC33LongTextImage extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $cta = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 0,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'subtitle' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_longtextimage_subtitle']),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
      'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'c33_image', 'c33_image_small'),
    ];

    if (!empty($cta)) {
      $variables['data']['cta'] = [
        'href' => $cta['url'],
        'label' => $cta['text'],
      ];
    }
  }

}
