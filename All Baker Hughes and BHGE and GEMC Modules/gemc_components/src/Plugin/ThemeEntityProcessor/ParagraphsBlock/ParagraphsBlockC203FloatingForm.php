<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;
use Drupal\node\Entity\Node;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c203_floating_form",
 *   label = @Translation("C203 Floating Form"),
 *   entity_type = "paragraph",
 *   bundle = "c203_floating_form",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC203FloatingForm extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {

    // Populate meta.
    $marketoMeta = $this->populateNdata([], $variables['paragraph']->getParentEntity());

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'popupVisible' => 'false',
      'formOptions' => [
        'blockTopOffset' => 0,
        'title' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
        'largeHeader' => 1,
        'clientId' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_munchkin_id']),
        'formId' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_marketo_id']),
        'metaData' => $marketoMeta,
        'thankYou' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_thank_you_text']),
      ],
    ];
    $variables['cta'] = [
      'icon' => 0,
      'link' => 0,
      'action' => [
        'type' => 'openForm',
        'formComponentId' => 'c203-floating-form',
        'copy' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_label']),
      ],
    ];
  }

  /**
   * Get meta data for hidden fields.
   *
   * @param array $meta
   *   Meta.
   * @param \Drupal\node\Entity\Node $entity
   *   Entity.
   *
   * @return mixed
   *   Returns the meta.
   */
  private function populateNdata(array $meta, Node $entity) {

    $hierarchy = $this->getNhierarchy($entity, TRUE);

    foreach ($hierarchy as $key => $item) {
      switch ($key) {

        // N2 - level 2 data of hierarchy.
        case 2:
          $meta['mCProductApplicationGEMkto'] = urldecode($item);
          break;

        // N3 - level 3 data of hierarchy.
        case 3:
          $meta['mCProductCategoryGEMkto'] = urldecode($item);
          break;

        // N4 - level 4 data of hierarchy.
        case 4:
          $meta['mCProductSubCategoryGEMkto'] = urldecode($item);
          break;
      }
    }
    return $meta;
  }

  /**
   * Get N Levels.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node object.
   * @param bool $reverse
   *   The var to reverse the array.
   *
   * @return array
   *   Returns the hierarchy in array format.
   */
  private function getNhierarchy(Node $node, $reverse = FALSE) {

    /** @var \Drupal\bhge_c01a_product_nav\SectionTrail $trail */
    $trail = \Drupal::service('gemc_n01_product_nav.section_trail');
    $trail = $trail->currentTrail($node);
    $hierarchy = [];

    if (!empty($trail['parents'])) {
      $hierarchy[] = $trail['current']->title;

      foreach (array_reverse($trail['parents']) as $item) {
        $hierarchy[] = $item->title;
      }

      if ($reverse) {
        $hierarchy = array_reverse($hierarchy);
      }

      $hierarchy = array_combine(range(1, count($hierarchy)), array_values($hierarchy));
    }

    return $hierarchy;
  }

}
