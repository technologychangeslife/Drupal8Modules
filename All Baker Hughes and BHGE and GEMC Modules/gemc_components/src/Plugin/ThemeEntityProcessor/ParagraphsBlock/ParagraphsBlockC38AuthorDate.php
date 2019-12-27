<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c38_author_date",
 *   label = @Translation("C38 Author Date"),
 *   entity_type = "paragraph",
 *   bundle = "c38_author_date",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC38AuthorDate extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $date = strtotime($variables['elements']['field_date']['#items']->value);
    $date = date('M, d Y', $date);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 1,
      'author' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_author']),
      'subTitle' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
      'date' => $date,
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description']),
    ];
  }

}
