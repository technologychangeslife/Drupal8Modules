<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c113_promotional_banner",
 *   label = @Translation("C113 Promotional Banner"),
 *   entity_type = "paragraph",
 *   bundle = "c113_promotional_banner",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC113PromotionalBanner extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $cta = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 2,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => nl2br($variables['elements']['field_description_plain']['#items']->value),
      'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'c113_promotional_banner_image', 'c113_promotional_banner_image'),
    ];

    if (!empty($cta)) {
      $variables['data']['cta'] = [
        'description' => $cta['text'],
        'href' => $cta['url'],
      ];
    }
  }

}
