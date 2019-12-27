<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c112_kpi_carousel",
 *   label = @Translation("C112 Kpi Carousel"),
 *   entity_type = "paragraph",
 *   bundle = "c112_kpis",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC112KpiCarousel extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $kpis = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_kpis'], ['multiple' => TRUE]);
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 0,
      'items' => $kpis,
    ];
  }

}
