<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c106_block_text",
 *   label = @Translation("C106 Block Text"),
 *   entity_type = "paragraph",
 *   bundle = "c106_block_text",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC106BlockText extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 1,
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
    ];
  }

}
