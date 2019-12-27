<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c108_quote",
 *   label = @Translation("C108 Quote"),
 *   entity_type = "paragraph",
 *   bundle = "c108_quote",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC108Quote extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'className' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_small_font']) ? 'small' : '',
      'quote' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_quote']),
      'author' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_author']),
    ];
  }

}
