<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c103_gallery_image_view_mode_media_gallery",
 *   label = @Translation("C103 Gallery image view mode media gallery"),
 *   entity_type = "paragraph",
 *   bundle = "c103_gallery_image",
 *   view_mode = "media_gallery"
 * )
 */
class ParagraphsC103GalleryImageViewModeMediaGallery extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'thumbnail' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'gallery_item_normal', 'gallery_item_small'),
      'lightboxImage' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_image'], ['style' => 'normal'])['url'],
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_description_plain']),
      'type' => 'image',
    ];
  }

}
