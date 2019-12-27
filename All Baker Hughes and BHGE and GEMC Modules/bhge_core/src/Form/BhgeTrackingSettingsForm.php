<?php

namespace Drupal\bhge_core\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class BhgeTrackingSettingsForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'gtm_id',
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bhge.tracking_settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bhge_tracking_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // Empty return statement not required here phpcs return;.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['group_gtm'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Google tag manager'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        "gtm_id" => [
          '#type' => 'textfield',
          '#title' => $this->t('Id'),
          '#default_value' => $this->config->get('gtm_id'),
          '#size' => 255,
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    foreach (self::FORM_FIELDS as $formField) {
      $this->config->set($formField, $form_state->getValue($formField))->save();
    }
  }

}
