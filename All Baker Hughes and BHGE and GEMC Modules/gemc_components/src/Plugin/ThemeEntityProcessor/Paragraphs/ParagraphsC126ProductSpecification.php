<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c126_product_specification",
 *   label = @Translation("C126 Product specification"),
 *   entity_type = "paragraph",
 *   bundle = "c126_product_specification",
 *   view_mode = "default"
 * )
 */
class ParagraphsC126ProductSpecification extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'leftBody' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'rightBody' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description']),
    ];
  }

}
