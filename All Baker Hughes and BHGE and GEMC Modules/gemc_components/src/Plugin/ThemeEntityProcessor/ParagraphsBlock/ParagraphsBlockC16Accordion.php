<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c16_accordion",
 *   label = @Translation("C16 Accordion"),
 *   entity_type = "paragraph",
 *   bundle = "c16_accordion",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC16Accordion extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 2,
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => nl2br($variables['elements']['field_description']['#items']->value),
      'categories' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_slides'], ['multiple' => TRUE]),
    ];
  }

}
