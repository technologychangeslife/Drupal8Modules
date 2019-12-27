<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c01_hero_slide",
 *   label = @Translation("C01 Hero Slide"),
 *   entity_type = "paragraph",
 *   bundle = "c01_hero_slide",
 *   view_mode = "default"
 * )
 */
class ParagraphsC01HeroSlide extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $cta = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);
    $image = $this->fieldDataService->getResponsiveImageData($variables['elements']['field_background_image'], 'c01_image', 'c01_image_small');
    $video = $this->fieldDataService->getVideoInformation($variables['elements']['field_video']);

    $variables['data'] = [
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'image' => !empty($image) ? $image : '',
      'type' => !empty($video) ? 'video' : 'image',
      'video' => !empty($video) ? $video : [],
      'themeId' => !empty($variables['elements']['field_industry_color']['#items']) ? $variables['elements']['field_industry_color']['#items']->value : 1,
    ];

    if (!empty($cta)) {
      if (!empty($cta['text'])) {
        $variables['data']['link'] = [
          'url' => $cta['url'],
          'label' => $cta['text'],
        ];
      }
      $variables['data']['href'] = $cta['url'];
    }
  }

}
