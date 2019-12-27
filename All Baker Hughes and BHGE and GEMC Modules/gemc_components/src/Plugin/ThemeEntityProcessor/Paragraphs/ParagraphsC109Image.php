<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c109_image",
 *   label = @Translation("C109 Image"),
 *   entity_type = "paragraph",
 *   bundle = "c109_image",
 *   view_mode = "default"
 * )
 */
class ParagraphsC109Image extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $image = $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'c109_image', 'c109_image');

    $variables['data'] = [
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'image' => !empty($image) ? $image : '',
    ];
  }

}
