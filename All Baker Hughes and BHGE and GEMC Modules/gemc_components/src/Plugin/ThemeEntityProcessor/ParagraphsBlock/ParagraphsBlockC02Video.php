<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c02_video",
 *   label = @Translation("C02 Video"),
 *   entity_type = "paragraph",
 *   bundle = "c02_video",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC02Video extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
    $paragraph = $variables['paragraph'];
    $hostEntity = $paragraph->getParentEntity();
    switch ($hostEntity->bundle()) {
      case 'section':
        $blockTopOffset = 2;
        break;

      default:
        $blockTopOffset = 4;
    }

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => $blockTopOffset,
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'video' => $this->fieldDataService->getVideoInformation($variables['elements']['field_video']),
      'videoBackgroundImage' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'normal', 'small'),
    ];
  }

}
