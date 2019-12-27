<?php

namespace Drupal\bh_layout_preview\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Description of ExposeContentTypeConfigForm.
 */
class ExposeContentTypeConfigForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const BH_LAYOUT_CONTENT_TYPE = 'bh_layout_preview.expose_content_type';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'expose_content_type_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::BH_LAYOUT_CONTENT_TYPE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::BH_LAYOUT_CONTENT_TYPE);
    $types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();
    foreach ($types as $key => $val) {
      $lists[$key] = $val->label();
    }
    $form['bh_content_types'] = [
      '#type' => 'checkboxes',
      '#options' => $lists,
      '#title' => $this->t('Select the list of Content type need to display in content dropdown'),
      '#default_value' => $config->get('selected_content_type')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->configFactory->getEditable(static::BH_LAYOUT_CONTENT_TYPE);
    $config->set('selected_content_type', $form_state->getValue('bh_content_types'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
