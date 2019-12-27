<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c20_quick_links",
 *   label = @Translation("C20 Quick Links"),
 *   entity_type = "paragraph",
 *   bundle = "c20_quick_links",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC20QuickLinks extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $quickLinks = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_quick_link']);

    $result = [];
    if (!empty($quickLinks) && count($variables['elements']['field_quick_link']['#items']) > 1) {
      foreach ($quickLinks as $quickLink) {
        $result[] = [
          'description' => $quickLink['text'],
          'url' => $quickLink['url'],
        ];
      }
    }
    elseif (!empty($quickLinks)) {
      $result[] = [
        'description' => $quickLinks['text'],
        'url' => $quickLinks['url'],
      ];
    }

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'items' => $result,
    ];
  }

}
