<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c107_image_and_text",
 *   label = @Translation("C107 Image And Text"),
 *   entity_type = "paragraph",
 *   bundle = "c107_image_and_text",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC107ImageAndText extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'c107_image', 'c107_image'),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
    ];
  }

}
