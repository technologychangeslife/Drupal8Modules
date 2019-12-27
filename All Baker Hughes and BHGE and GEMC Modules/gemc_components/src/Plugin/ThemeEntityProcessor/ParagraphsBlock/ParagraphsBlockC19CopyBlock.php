<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c19_copy_block",
 *   label = @Translation("C19 Copy Block"),
 *   entity_type = "paragraph",
 *   bundle = "c19_block_copy",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC19CopyBlock extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $cta = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);
    if (!empty($cta)) {
      $cta = [
        'label' => $cta['text'],
        'href' => $cta['url'],
      ];
    }
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 2,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
      'cta' => $cta,
    ];
  }

}
