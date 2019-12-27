<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c112_kpi",
 *   label = @Translation("C112 Kpi"),
 *   entity_type = "paragraph",
 *   bundle = "c112_kpi",
 *   view_mode = "default"
 * )
 */
class ParagraphsC112Kpi extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_kpi_value']),
      'kpi' => !empty($variables['elements']['field_kpi_icon']['#items']) ? $variables['elements']['field_kpi_icon']['#items']->value : '',
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
    ];
  }

}
