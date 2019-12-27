<?php

namespace Drupal\bhge_core\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class BhgeExternalAPISettingsForm extends ConfigFormBase {

  protected $careerConfig;

  protected $hseInfoConfig;

  protected $stockInfoConfig;

  const FORM_FIELDS = [
    'career' => [
      'career_search_api_oauth_url',
      'career_search_api_url',
      'career_search_api_client_id',
      'career_search_api_client_secret',
    ],
    'stock_info' => [
      'stock_info_url',
    ],
    'hse' => [
      'hse_days',
      'hse_last_updated',
      'hse_fetch_url',
      'hse_days_suffix',
    ],
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);

    $this->careerConfig = $this->configFactory->getEditable('bhge.career_settings');
    $this->hseInfoConfig = $this->configFactory->getEditable('bhge.hse_info_settings');
    $this->stockInfoConfig = $this->configFactory->getEditable('bhge.stock_info_settings');
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
    return 'bhge_external_api_settings_form';
  }

  /**
   * Hse Days formatter.
   *
   * @return string
   *   Returns wether HSE updates are there or not.
   */
  protected function hseDays() {

    if (!empty($this->hseInfoConfig->get('hse_last_updated')) && is_numeric($this->hseInfoConfig->get('hse_last_updated'))) {
      return \Drupal::service('date.formatter')->format($this->hseInfoConfig->get('hse_last_updated'));
    }

    return 'never';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // return;.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $is_my_bhge = Settings::get('is_my_bhge');
    $current_env = Settings::get('current_env');

    $form['group_career_search_api'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Career search API'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      "options" => [
        "career_search_api_oauth_url" => [
          '#type' => 'textfield',
          '#title' => $this->t('OAuth url'),
          '#default_value' => $this->careerConfig->get('career_search_api_oauth_url'),
          '#size' => 255,
        ],
        "career_search_api_url" => [
          '#type' => 'textfield',
          '#title' => $this->t('Search endpoint'),
          '#default_value' => $this->careerConfig->get('career_search_api_url'),
          '#size' => 255,
        ],
        "career_search_api_client_id" => [
          '#type' => 'textfield',
          '#title' => $this->t('Client Id'),
          '#default_value' => $this->careerConfig->get('career_search_api_client_id'),
          '#size' => 255,
        ],
        "career_search_api_client_secret" => [
          '#type' => 'textfield',
          '#title' => $this->t('Client secret'),
          '#default_value' => $this->careerConfig->get('career_search_api_client_secret'),
          '#size' => 255,
        ],
      ],
    ];

    $form['group_stock_info'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Stock info'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      "options" => [
        "stock_info_url" => [
          '#type' => 'textfield',
          '#title' => $this->t('Stock Info URL'),
          '#default_value' => $this->stockInfoConfig->get('stock_info_url'),
          '#size' => 50,
        ],
      ],
    ];

    $form['group_hse_days'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Global nav data'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      "options" => [
        "hse_fetch_url" => [
          '#type' => 'textfield',
          '#title' => $this->t('Fetch URL'),
          '#description' => $this->t('URL from where the global navigation and HSE data is fetched.'),
          '#default_value' => $this->hseInfoConfig->get('hse_fetch_url'),
          '#size' => 50,
        ],
        "hse_last_updated" => [
          '#type' => 'hidden',
          '#default_value' => time(),
          '#size' => 50,
        ],
      ],
    ];

    if ($current_env !== 'acsf' && !$is_my_bhge) {
      $form['group_hse_days']['options']['hse_days'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Enter HSE Days Value'),
        '#description' => $this->t('This value will be incremented daily, last time updated:') . $this->hseDays(),
        '#default_value' => $this->hseInfoConfig->get('hse_days'),
        '#size' => 50,
      ];
      $form['group_hse_days']['options']['hse_days_suffix'] = [
        '#type' => 'textfield',
        '#title' => $this->t('HSE Suffix'),
        '#description' => $this->t('The text that will appear after counter.'),
        '#default_value' => $this->hseInfoConfig->get('hse_days_suffix'),
        '#size' => 50,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    foreach (self::FORM_FIELDS['career'] as $formField) {
      $this->careerConfig->set($formField, $form_state->getValue($formField))->save();
    }
    foreach (self::FORM_FIELDS['stock_info'] as $formField) {
      $this->stockInfoConfig->set($formField, $form_state->getValue($formField))->save();
    }
    foreach (self::FORM_FIELDS['hse'] as $formField) {
      $this->hseInfoConfig->set($formField, $form_state->getValue($formField))->save();
    }
  }

}
