<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c126_product_specification_group",
 *   label = @Translation("C126 Product specification group"),
 *   entity_type = "paragraph",
 *   bundle = "c126_product_specification_group",
 *   view_mode = "default"
 * )
 */
class ParagraphsC126ProductSpecificationGroup extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'subItems' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_c126_product_spec'], ['multiple' => TRUE]),
    ];
  }

}
