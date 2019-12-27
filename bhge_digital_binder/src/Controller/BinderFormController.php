<?php

namespace Drupal\bhge_digital_binder\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * The class to assign template to binder form.
 */
class BinderFormController extends ControllerBase {

  /**
   * This function renders the reorder form.
   */
  public function displayForm() {

    $binderForm = \Drupal::formBuilder()->getForm('Drupal\bhge_digital_binder\Form\BinderForm');

    return [
      '#theme' => 'digital_binder_form',
      '#binderForm' => $binderForm,
    ];
  }

}
