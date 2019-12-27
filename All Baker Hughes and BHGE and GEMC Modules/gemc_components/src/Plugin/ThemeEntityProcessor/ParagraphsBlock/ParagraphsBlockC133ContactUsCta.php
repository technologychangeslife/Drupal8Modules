<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c133_contact_us_cta",
 *   label = @Translation("C133 Contact Us Cta"),
 *   entity_type = "paragraph",
 *   bundle = "c133_contact_us_cta",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC133ContactUsCta extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $countries = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_c133_country']);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'callCTA' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_label']),
      'contactSection' => [
        'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      ],
      'countries' => count($variables['elements']['field_c133_country']['#items']) == 1 ? [$countries] : $countries,
      'defaultSelect' => $this->t('Other'),
      'defaultSelectCopy' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_other_text']),
    ];
  }

}
