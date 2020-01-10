<?php

namespace Drupal\search_string\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Our config form.
 */
class TextConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "search_string_confighero";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('search_string.settings');

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Title'),
      '#default_value' => $config->get('title'),
    ];
    
    $form['desc'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Desc'),
      '#default_value' => $config->get('desc'),
    ];
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'search_string.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('search_string.settings');

    $config
      ->set('title', $form_state->getValue('title'))
      ->save();
      
    $config
      ->set('desc', $form_state->getValue('desc'))
      ->save();
      
    parent::submitForm($form, $form_state);
  }
}
