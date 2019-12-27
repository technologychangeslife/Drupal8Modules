<?php

namespace Drupal\bhge_core\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class BhgeMarketInfoSettingsForm extends ConfigFormBase {

  /**
   * State service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  const FORM_FIELDS = [
    'market_info_usa_rig_count',
    'market_info_usa_change_from_last_week',
    'market_info_usa_change_from_last_week_delta',
    'market_info_canada_rig_count',
    'market_info_canada_change_from_last_week',
    'market_info_canada_change_from_last_week_delta',
    'market_info_international_rig_count',
    'market_info_international_change_from_last_week',
    'market_info_international_change_from_last_week_delta',
    'market_info_usa_change_from_last_year',
    'market_info_usa_change_from_last_year_delta',
    'market_info_canada_change_from_last_year',
    'market_info_canada_change_from_last_year_delta',
    'market_info_international_change_from_last_year',
    'market_info_international_change_from_last_year_delta',
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\Core\State\StateInterface $state
   *   State interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, StateInterface $state) {
    parent::__construct($config_factory);
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bhge_market_info_settings_form';
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

    $form['group_market_info'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Market info'),
      '#attributes' => ['style' => 'overflow: auto;'],
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        'group_rig_count_usa' => [
          '#attributes' => ['style' => 'width: 29%; float: left; margin-right:1%;'],
          '#type' => 'fieldset',
          '#title' => $this->t('Rig count USA'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          "options" => [
            "market_info_usa_rig_count" => [
              '#type' => 'textfield',
              '#title' => $this->t('Count'),
              '#default_value' => $this->state->get('market_info_usa_rig_count'),
              '#size' => 50,
            ],
            "market_info_usa_change_from_last_week" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last month'),
              '#default_value' => $this->state->get('market_info_usa_change_from_last_week'),
              '#size' => 50,
            ],
            "market_info_usa_change_from_last_week_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last month delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_usa_change_from_last_week_delta'),
            ],
            "market_info_usa_change_from_last_year" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last year'),
              '#default_value' => $this->state->get('market_info_usa_change_from_last_year'),
              '#size' => 50,
            ],
            "market_info_usa_change_from_last_year_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last year delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_usa_change_from_last_year_delta'),
            ],
          ],
        ],

        'group_rig_count_canada' => [
          '#attributes' => ['style' => 'width: 29%; float: left; margin-right:1%;'],
          '#type' => 'fieldset',
          '#title' => $this->t('Rig count Canada'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          "options" => [
            "market_info_canada_rig_count" => [
              '#type' => 'textfield',
              '#title' => $this->t('Count'),
              '#default_value' => $this->state->get('market_info_canada_rig_count'),
              '#size' => 50,
            ],
            "market_info_canada_change_from_last_week" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last month'),
              '#default_value' => $this->state->get('market_info_canada_change_from_last_week'),
              '#size' => 50,
            ],
            "market_info_canada_change_from_last_week_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last month delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_canada_change_from_last_week_delta'),
            ],
            "market_info_canada_change_from_last_year" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last year'),
              '#default_value' => $this->state->get('market_info_canada_change_from_last_year'),
              '#size' => 50,
            ],
            "market_info_canada_change_from_last_year_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last year delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_canada_change_from_last_year_delta'),
            ],
          ],
        ],

        'group_rig_count_international' => [
          '#attributes' => ['style' => 'width: 28%; float: left; margin-right: 1%;'],
          '#type' => 'fieldset',
          '#title' => $this->t('Rig count International'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          "options" => [
            "market_info_international_rig_count" => [
              '#type' => 'textfield',
              '#title' => $this->t('Count'),
              '#default_value' => $this->state->get('market_info_international_rig_count'),
              '#size' => 50,
            ],
            "market_info_international_change_from_last_week" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last month'),
              '#default_value' => $this->state->get('market_info_international_change_from_last_week'),
              '#size' => 50,
            ],
            "market_info_international_change_from_last_week_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last month delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_international_change_from_last_week_delta'),
            ],
            "market_info_international_change_from_last_year" => [
              '#type' => 'textfield',
              '#title' => $this->t('Change from last year'),
              '#default_value' => $this->state->get('market_info_international_change_from_last_year'),
              '#size' => 50,
            ],
            "market_info_international_change_from_last_year_delta" => [
              '#type' => 'select',
              '#title' => t('Change from last year delta'),
              '#options' => [t('Neutral'), t('Up'), t('Down')],
              '#default_value' => $this->state->get('market_info_international_change_from_last_year_delta'),
            ],
          ],
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
      $this->state->set($formField, $form_state->getValue($formField));
    }
  }

}
