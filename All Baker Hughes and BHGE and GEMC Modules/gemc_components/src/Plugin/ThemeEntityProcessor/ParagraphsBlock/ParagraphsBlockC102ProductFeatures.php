<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c102_key_product_features",
 *   label = @Translation("C102 Product Features"),
 *   entity_type = "paragraph",
 *   bundle = "c102_product_features",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC102ProductFeatures extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'specifications' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_product_features'], ['multiple' => TRUE]),
    ];
    /* passing current language for nested paragraph translation */
    $variables['langcode'] = \Drupal::languageManager()->getCurrentLanguage()->getId();

    /** @var \Drupal\node\NodeInterface $node */
    $node = $variables["paragraph"]->getParentEntity();
    if ($node->hasField('field_c126_product_specification') && !$node->get('field_c126_product_specification')
      ->isEmpty()) {
      $specificationsUrl = $node->toUrl('canonical', ['query' => ['display' => 'specifications']])
        ->toString();
      $variables['data']['cta'] = [
        'description' => $this->t('See all Specifications'),
        'href' => $specificationsUrl,
      ];
    }

  }

}
