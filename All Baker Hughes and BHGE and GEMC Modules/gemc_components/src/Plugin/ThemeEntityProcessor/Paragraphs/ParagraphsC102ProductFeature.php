<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c102_product_feature",
 *   label = @Translation("C102 Product Feature"),
 *   entity_type = "paragraph",
 *   bundle = "c102_product_feature",
 *   view_mode = "default"
 * )
 */
class ParagraphsC102ProductFeature extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description']),
    ];
  }

}
