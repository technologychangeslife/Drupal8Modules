<?php

namespace Drupal\bhge_digital_binder\Form;

use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Implements a simple form.
 */
class ReorderForm extends FormBase {

  /**
   * Build the simple form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    foreach ($search_result_data as $key => $val) {
      $node = Node::load($key);
      $search_results = '<div>' . $node->title->value . '</div>' . $search_results;

      $form[$key] = [
        '#type' => 'textfield',
        '#title' => $node->title->value,
        '#required' => FALSE,
      ];

      $form['delete_' . $key] = [
        '#type' => 'button',
        '#name' => 'delete_' . $key,
        '#value' => $this->t('Delete'),
      ];

    }

    $form['go_back'] = [
      '#type' => 'button',
      '#value' => $this->t('Back'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller.  it must
   * be unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'bhge_digital_binder_reorder_form';
  }

  /**
   * This is Validate Form Function.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $triggerdElement = $form_state->getTriggeringElement();
    $name_of_button_clicked = $triggerdElement['#name'];
    $htmlIdofTriggeredElement = $triggerdElement['#id'];
    $delete_element = explode("delete_", $name_of_button_clicked);
    $delete_id = $delete_element[1];

    if ($htmlIdofTriggeredElement == 'edit-go-back') {
      $response = new RedirectResponse('/binder-form');
      $response->send();
    }

    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    // $search_result_reorder = array();
    foreach ($search_result_data as $key => $val) {
      $order = $form_state->getValue($key);
      if ($key == $delete_id) {
        unset($search_result_data[$key]);
      }
    }
    $tempstore->delete('search_result_data');
    $tempstore->set('search_result_data', $search_result_data);
    // die();
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    $search_result_reorder = [];
    foreach ($search_result_data as $key => $val) {
      $order = $form_state->getValue($key);
      $search_result_reorder[$val] = $order;
    }

    asort($search_result_reorder);

    $search_result_neworder = [];
    foreach ($search_result_reorder as $x => $x_value) {
      $search_result_neworder[$x] = $x;
    }

    $tempstore->delete('search_result_data');
    $tempstore->set('search_result_data_new', $search_result_neworder);
    $search_result_data_new = $tempstore->get('search_result_data_new');

    $response = new RedirectResponse('/binder');
    $response->send();
    // die();
  }

}
