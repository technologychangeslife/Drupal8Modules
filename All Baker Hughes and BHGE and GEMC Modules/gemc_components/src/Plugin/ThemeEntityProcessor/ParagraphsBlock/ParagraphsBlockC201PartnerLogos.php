<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c201_partner_logos",
 *   label = @Translation("C201 Partner Logos"),
 *   entity_type = "paragraph",
 *   bundle = "c201_logos",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC201PartnerLogos extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'logos' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_logos']),
      'copy_text' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_copy']),
    ];
  }

}
