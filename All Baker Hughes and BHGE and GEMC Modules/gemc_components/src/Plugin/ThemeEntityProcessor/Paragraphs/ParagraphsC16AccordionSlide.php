<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c16_accordion_slide",
 *   label = @Translation("C16 Accordion"),
 *   entity_type = "paragraph",
 *   bundle = "c16_accordion_slide",
 *   view_mode = "default"
 * )
 */
class ParagraphsC16AccordionSlide extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $link_data = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);
    $variables['data'] = [
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => nl2br($variables['elements']['field_description']['#items']->value),
      'themeId' => !empty($variables['elements']['field_industry_color']['#items']) ? $variables['elements']['field_industry_color']['#items']->value : 1,
      'link' => [
        'label' => $link_data['text'],
        'url' => $link_data['url'],
      ],
      'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'accordion_image_normal', 'accordion_image_small'),
    ];
  }

}
