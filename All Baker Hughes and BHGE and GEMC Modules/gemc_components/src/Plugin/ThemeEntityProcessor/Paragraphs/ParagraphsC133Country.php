<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c133_country",
 *   label = @Translation("C133 Country"),
 *   entity_type = "paragraph",
 *   bundle = "c133_country",
 *   view_mode = "default"
 * )
 */
class ParagraphsC133Country extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $image = $this->fieldDataService->getResponsiveImageData($variables['elements']['field_flag'], 'flag', 'flag');
    $variables['data'] = [
      'country' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_country_name']),
      'phone' => $variables['elements']['field_phone_number']['#items']->value,
      'flag' => !empty($image) ? $image['normal'] : '',
    ];
  }

}
