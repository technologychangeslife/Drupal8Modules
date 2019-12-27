<?php

namespace Drupal\bhge_digital_binder\Form;

use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\taxonomy\Entity\Term;

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
    $this->bhgeDigitalBinderPageCache();
    $current_uri = \Drupal::request()->getRequestUri();
    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    $temp_store = 1;
    if ($current_uri == '/digital-binder-list?same-order') {
      $form['same_order'] = [
        '#type' => 'item',
        '#markup' => $this->t("<b>2 or more documents have the same sequence number.</b>"),
      ];
    }
    $blank = $search_results = '';
    $tempstore->set('same_order', $blank);
    $x = 0;
    foreach ($search_result_data as $key => $val) {
      $file_size_format = '';
      $node = Node::load($key);
      $search_results = '<div>' . $node->title->value . '</div>' . $search_results;

      $dam_field_file = $node->get('field_dam_file')->getValue();
      if (!empty($dam_field_file[0]['target_id'])) {
        $dam_url = $this->getfileurl($dam_field_file[0]['target_id']);
        $dam_url = str_replace("%20", " ", $dam_url);
        $dam_url = str_replace("%28", "(", $dam_url);
        $dam_url = str_replace("%29", ")", $dam_url);
        $files_path = explode("sites", $dam_url);
        $dam_file_path = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'sites' . $files_path[1];
        if (file_exists($dam_file_path)) {
          $file_size = filesize($dam_file_path);
          $file_size_format = $this->formatSizeUnits($file_size);
        }
        else {
          $file_size_format = "File not found.";
        }
      }
      $file_type = $node->field_topic->getValue();
      $tid = $file_type[0]['target_id'];
      if (!empty($tid)) {
        $term = Term::load($tid);
        $name = $term->getName();
      }
      if ($file_size_format != "File not found.") {
        $form['reorder_' . $key] = [
          '#type' => 'fieldset',
          '#prefix' => '<div class="reorder-binder reorder-binder-drag">',
        ];
        $form['reorder_' . $key][$key] = [
          '#type' => 'number',
          '#title' => "<div class='reorder-title'>" . $node->title->value . "</div>",
          '#attributes' => ['title' => t('Assign a priorty number to sequence the document.')],
          '#required' => FALSE,
          '#min' => 1,
          '#max' => 100,
          '#size' => 3,
          '#default_value' => $temp_store,
          '#prefix' => "<div class='drag-drop-icon'></div><div class='download-type'></div><div class='file-details'>",
          '#suffix' => "<div class='file-info'><span class='file-name'>" . $name . "</span><span>" . $file_size_format . "</span></div></div>",
        ];

        $temp_store++;

        $form['reorder_' . $key]['delete_' . $key] = [
          '#type' => 'button',
          '#name' => 'delete_' . $key,
          '#value' => $this->t('Delete'),
          '#attributes' => ['class' => ['delete-button']],
          '#prefix' => "<div class='delete-details'>",
          '#suffix' => "</div></div>",
        ];
        $x++;
      }
      if ($file_size_format == "File not found. Please check.") {
        $form['missing_' . $key] = [
          '#type' => 'item',
          '#markup' => $this->t("<b>@node-title</b> This File is missing. Please contact site admin.", ['@node-title' => $node->title->value]),
        ];
      }
    }
    if ($x == 0) {
      $form['no_files'] = [
        '#type' => 'item',
        '#markup' => t('<b>2 or more documents are needed for binder.</b>'),
      ];
    }

    if ($x == 1) {
      $form['one_file'] = [
        '#type' => 'item',
        '#markup' => t('<b>2 or more documents are needed for binder.</b>'),
      ];
    }
    $form['reorder_buttons'] = [
      '#type' => 'fieldset',
      '#prefix' => '<div class="reorder-btn">',
    ];
    if ($x <= 1) {
      $reorder_go_back_suffix = '</div></div>';
    }
    else {
      $reorder_go_back_suffix = '</div>';
    }
    $form['reorder_buttons']['go_back'] = [
      '#type' => 'button',
      '#value' => $this->t('Go Back'),
      '#prefix' => "<div class='reorder-back'>",
      '#suffix' => $reorder_go_back_suffix . '<div class="ajax-progress ajax-progress-throbber ajax-through-js"><div class="throbber"> </div><div class="message">Please wait...</div></div>',
    ];
    if ($x > 1) {
      $form['reorder_buttons']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
        '#name' => 'submit_form',
        '#prefix' => "<div class='reorder-submit'>",
        '#suffix' => "</div></div>",

      ];
      $form['reorder_buttons_top'] = [
        '#type' => 'fieldset',
        '#prefix' => '<div class="reorder-btn reorder-btn-top">',
      ];
      $form['reorder_buttons_top']['go_back_top'] = [
        '#type' => 'button',
        '#value' => $this->t('Go Back'),
        '#prefix' => "<div class='reorder-back'>",
        '#suffix' => '</div>',
      ];
      $form['reorder_buttons_top']['submit_top'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
        '#prefix' => "<div class='reorder-submit'>",
        '#suffix' => "</div></div>",
      ];
    }
    return $form;
  }

  /**
   * Function to kill the cache for the page.
   */
  public function bhgeDigitalBinderPageCache() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    return [
      '#markup' => time(),
    ];
  }

  /**
   * This function will create PHP file size in MB, GB, etc.
   */
  public function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1) {
      $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1) {
      $bytes = $bytes . ' byte';
    }
    else {
      $bytes = '0 bytes';
    }

    return $bytes;
  }

  /**
   * This function will give the DAM file Url.
   */
  public function getfileurl($dam_field_file_target_id) {
    $media = Media::load($dam_field_file_target_id);
    if (!empty($media)) {
      $check = $media->get('field_asset')->getValue();
      $media_field_asset = $media->get('field_asset')->getValue();
      $file = File::load($media_field_asset[0]['target_id']);
      $dam_file_uri = $file->getFileUri();
      $dam_url = file_create_url($dam_file_uri);
      return $dam_url;
    }
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

    if ($htmlIdofTriggeredElement == 'edit-go-back' || $htmlIdofTriggeredElement == 'edit-go-back-top') {
      $response = new RedirectResponse('/binder-form');
      $response->send();
    }

    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    // $search_result_reorder = array();
    $check = [];
    foreach ($search_result_data as $key => $val) {
      $order = $form_state->getValue($key);
      if (in_array($order, $check) && !empty($order)) {
        $form_state->setErrorByName($key, t('Dont give same order to 2 files.'));
        $response = new RedirectResponse('/digital-binder-list?same-order');
        $response->send();
      }
      $check[$form_state->getValue($key)] = $form_state->getValue($key);
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
