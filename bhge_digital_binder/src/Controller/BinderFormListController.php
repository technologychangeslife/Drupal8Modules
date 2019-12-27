<?php

namespace Drupal\bhge_digital_binder\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * The class to assign template to reorder form.
 */
class BinderFormListController extends ControllerBase {

  /**
   * This function renders the reorder form.
   */
  public function displayForm() {

    $binderForm = \Drupal::formBuilder()->getForm('Drupal\bhge_digital_binder\Form\ReorderForm');

    return [
      '#theme' => 'digital_binder_list',
      '#search_results_list' => $binderForm,
    ];
  }

}
