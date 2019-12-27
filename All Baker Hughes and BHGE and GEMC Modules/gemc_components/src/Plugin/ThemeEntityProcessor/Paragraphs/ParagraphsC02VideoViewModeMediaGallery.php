<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\Paragraphs;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c02_video_view_mode_media_gallery",
 *   label = @Translation("C02 Video"),
 *   entity_type = "paragraph",
 *   bundle = "c02_video",
 *   view_mode = "media_gallery"
 * )
 */
class ParagraphsC02VideoViewModeMediaGallery extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $variables['data'] = [
      'thumbnail' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'gallery_item_normal', 'gallery_item_small'),
      'lightboxImage' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_image'], ['style' => 'normal'])['url'],
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'type' => 'video',
      'video' => $this->fieldDataService->getVideoInformation($variables['elements']['field_video']),
    ];
  }

}
