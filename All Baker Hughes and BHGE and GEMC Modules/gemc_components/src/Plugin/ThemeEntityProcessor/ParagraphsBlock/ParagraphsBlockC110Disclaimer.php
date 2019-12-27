<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c110_disclaimer",
 *   label = @Translation("C110 Disclaimer"),
 *   entity_type = "paragraph",
 *   bundle = "c110_disclaimer",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC110Disclaimer extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'description' => '© ' . strip_tags($variables['elements']['field_copy']['#items']->value),
      'hasLine' => TRUE,
    ];
  }

}
