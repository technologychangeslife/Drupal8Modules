<?php

namespace Drupal\bh_settings\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BhExternalAPISettingsForm.
 *
 * @package Drupal\bh_settings\Form
 */
class BhExternalAPISettingsForm extends ConfigFormBase {

  protected $stockInfoConfig;

  const FORM_FIELDS = [
    'stock_info' => [
      'stock_info_url',
    ],
  ];

  /**
   * BhExternalAPISettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->stockInfoConfig = $this->configFactory->getEditable('bh.stock_info_settings');
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
    return 'bh_external_api_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::FORM_FIELDS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['group_stock_info'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Stock info'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      "options" => [
        "stock_info_url" => [
          '#type' => 'textfield',
          '#title' => $this->t('Stock API URL'),
          '#default_value' => $this->stockInfoConfig->get('stock_info_url'),
          '#size' => 80,
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

    foreach (self::FORM_FIELDS['stock_info'] as $formField) {
      $this->stockInfoConfig->set($formField, $form_state->getValue($formField))->save();
    }
  }

}
