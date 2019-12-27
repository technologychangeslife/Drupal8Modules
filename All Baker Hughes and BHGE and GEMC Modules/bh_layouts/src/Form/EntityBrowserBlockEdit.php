<?php

namespace Drupal\bh_layouts\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Definition of EntityBrowserBlockEdit class.
 */
class EntityBrowserBlockEdit {

  /**
   * Render API callback: Processes the table element.
   */
  public static function processTable(&$element, FormStateInterface $form_state, &$complete_form) {
    $operations = [];
    foreach ($element as $id => $item) {
      if (is_array($item) && isset($item['operations'])) {
        $split_id = explode(':', $id);
        $nid = $split_id[1];
        // Adding Edit link.
        $operations['edit'] = [
          '#type' => 'link',
          '#title' => t('Edit'),
          '#url' => Url::fromRoute('entity.node.edit_form', [
            'node' => $nid,
            'destination' => $complete_form['destination']['#value'],
          ]),
          '#attributes' => ['class' => ['button']],
        ];
        // Adding Remove button.
        $operations += $item['operations'];
        $element[$id]['operations'] = $operations;
      }
    }
    return $element;
  }

}
