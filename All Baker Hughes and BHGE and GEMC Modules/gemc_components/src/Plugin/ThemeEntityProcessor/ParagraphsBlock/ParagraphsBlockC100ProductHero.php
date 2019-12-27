<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c100_product_hero",
 *   label = @Translation("C100 Product Hero"),
 *   entity_type = "paragraph",
 *   bundle = "c100_product_hero",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC100ProductHero extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $paragraph = $variables['paragraph'];

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 0,
      'productImage' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'product_image_normal', 'product_image_small'),
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'subHeading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description']),
    ];
  }

}
