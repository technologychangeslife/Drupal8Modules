<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c126_product_specifications",
 *   label = @Translation("C126 Product specifications"),
 *   entity_type = "paragraph",
 *   bundle = "c126_product_specifications",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC126ProductSpecifications extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'header' => [
        'subHeading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
        'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
        'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'product_image_normal', 'product_image_small'),
      ],
      'specsList' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_c126_specification_group'], ['multiple' => TRUE]),
    ];
  }

}
