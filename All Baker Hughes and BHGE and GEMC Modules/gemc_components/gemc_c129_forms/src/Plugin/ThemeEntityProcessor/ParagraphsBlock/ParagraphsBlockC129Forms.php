<?php

namespace Drupal\gemc_c129_forms\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c129_forms",
 *   label = @Translation("C129 Forms"),
 *   entity_type = "paragraph",
 *   bundle = "c129_forms",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC129Forms extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {

    /** @var \Drupal\gemc_components\MarketoHelpers $marketoHelpers */
    $marketoHelpers = \Drupal::service('gemc_components.helpers');

    // Populate meta.
    $marketoMeta = $marketoHelpers->populateNdata($variables['paragraph']->getParentEntity(), []);

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 2,
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'clientId' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_munchkin_id']),
      'formId' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_marketo_id']),
      'metaData' => $marketoMeta,
    ];
  }

}
