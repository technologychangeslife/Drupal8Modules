<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c111_two_column_text",
 *   label = @Translation("C111 Two Column Text"),
 *   entity_type = "paragraph",
 *   bundle = "c111_two_column_text",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC111TwoColumnText extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 4,
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'content' =>
        [
          [
            'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
            'contentType' => 'textBlock',
          ],
          [
            'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description']),
            'contentType' => 'textBlock',
          ],
        ],
    ];
  }

}
