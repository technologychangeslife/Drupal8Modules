<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c131_subsection_hero",
 *   label = @Translation("C131 Subsection Hero"),
 *   entity_type = "paragraph",
 *   bundle = "c131_section_hero",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC131SubsectionHero extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'themeId' => !empty($variables['elements']['field_industry_color']['#items']) ? $variables['elements']['field_industry_color']['#items']->value : 1,
      'blockTopOffset' => 0,
      'className' => !empty($variables['elements']['field_hero_display']['#items']) && $variables['elements']['field_hero_display']['#items']->value == 'minimal' ? 'is-full-exposed' : NULL,
      'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_background_image'], 'c01_image', 'c01_image_small'),
      'subTitle' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
    ];
  }

}
