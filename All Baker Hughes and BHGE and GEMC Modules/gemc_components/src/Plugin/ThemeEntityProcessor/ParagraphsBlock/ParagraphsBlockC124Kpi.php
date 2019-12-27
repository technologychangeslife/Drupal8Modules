<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c124_kpi",
 *   label = @Translation("C124 Kpi"),
 *   entity_type = "paragraph",
 *   bundle = "c124_kpi",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC124Kpi extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 4,
      'kpi' =>
        [
          'icon' => $variables['elements']['field_kpi_icon']['#items']->value,
          'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_kpi_value']),
          'subTitle' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
        ],
      'copy' =>
        [
          'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
        ],
    ];
  }

}
