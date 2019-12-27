<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;
use Drupal\Core\Url;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c04_contact",
 *   label = @Translation("C04 Contact"),
 *   entity_type = "paragraph",
 *   bundle = "c04_contact",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC04Contact extends GemcThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {

    // Get content type of node.
    $ctype = $variables['paragraph']->getParentEntity()->getType();

    $options = [];

    $marketoOptions = [];

    /** @var \Drupal\gemc_components\MarketoHelpers $marketoHelpers */
    $marketoHelpers = \Drupal::service('gemc_components.helpers');

    // Populate meta only on following CTs.
    if (in_array($ctype, ['section', 'product'])) {

      // Populate ProductofInterest with node title.
      $marketoOptions['mCProductofInterestGEMKTO'] = urldecode($variables['paragraph']->getParentEntity()->title->value);

      // Populate N Levels.
      $marketoOptions = $marketoHelpers->populateNData($marketoOptions, $variables['paragraph']->getParentEntity());

    }

    if (!empty($marketoOptions)) {
      $options = ['query' => $marketoOptions];
    }
    $contactLink = '';
    if (!$variables['paragraph']->get('field_contact_links')->isEmpty()) {
      $contactLink = $variables['paragraph']->get('field_contact_links')->get(0)->getUrl()->toString();
    }

    $links = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_contact_links']);

    $variables['data'] = [
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_contact_heading']),
      'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_contact_description']),
      'link_text' => $links['text'],
      'href' => $contactLink,
    ];
  }

}
