<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c01_hero",
 *   label = @Translation("C01 Hero"),
 *   entity_type = "paragraph",
 *   bundle = "c01_hero",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC01Hero extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $items = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_c01_hero_slides'], ['multiple' => TRUE]);

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 0,
      'playLabel' => t('Play Movie'),
      'items' => $items,
    ];
  }

}
