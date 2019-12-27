<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;
use Drupal\Core\Url;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c201_logo",
 *   label = @Translation("C201 Logo"),
 *   entity_type = "paragraph",
 *   bundle = "c201_logo",
 *   view_mode = "default"
 * )
 */
class ParagraphsC201Logo extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $image = $this->fieldDataService->getResponsiveImageData($variables['elements']['field_logo'], 'logo', 'logo');
    $paragraph = $variables['elements']['#paragraph'];
    $category_url = NULL;
    if ($paragraph->hasField('field_product_category') && !$paragraph->get('field_product_category')->isEmpty()) {
      $category_url = Url::fromRoute('entity.node.canonical', ['node' => $paragraph->get('field_product_category')->getvalue()[0]['target_id']], ['absolute' => TRUE])->toString();
    }
    $variables['data'] = [
      'normal' => $image['normal'],
      'alt' => $variables['elements']['field_title']['#items']->value,
      'product_category_url' => $category_url,
    ];
  }

}
