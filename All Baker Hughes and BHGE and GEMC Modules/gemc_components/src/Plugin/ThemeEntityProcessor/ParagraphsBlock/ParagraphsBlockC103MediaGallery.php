<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c103_media_gallery",
 *   label = @Translation("C103 Media Gallery"),
 *   entity_type = "paragraph",
 *   bundle = "c103_media_gallery",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC103MediaGallery extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $count = 0;
    if (!empty($variables['elements']['field_media_items']['#items'])) {
      $count = count($variables['elements']['field_media_items']['#items']);
    }

    $pagination_text = $this->t('Showing {index} out of',
      ['@count' => $count]);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'pagination' => $pagination_text,
      'items' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_media_items'], ['view_mode' => 'media_gallery', 'multiple' => TRUE]),
    ];
  }

}
