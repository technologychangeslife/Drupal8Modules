<?php

namespace Drupal\bh_settings\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\file\Entity\File;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class BhGeneralSettingsForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'marketo_munchkin_id',
    'bh_news_no_results_messaging',
    'team_list_default_tab',
    'show_stock_change_icon_in_menu'
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bh.general_settings');
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
    return 'bh_general_settings_form';
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
  public function getTeamListOptions() {
    $vid = 'job_position';
    $options = [0 => 'Select'];
    // Retrieve all terms and make them the option select.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);

    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['group_basic_site_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic site settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'marketo_munchkin_id' => [
          '#type' => 'textfield',
          '#title' => $this->t('Marketo Munchkin ID'),
          '#default_value' => $this->config->get('marketo_munchkin_id'),
          '#maxlength' => 120,
          '#size' => 60,
          '#description' => $this->t('Global Marketo Munchkin Id.')
        ],
        'bh_news_no_results_messaging' => [
          '#type' => 'text_format',
          '#title' => $this->t('News Landing component "No Results" messaging'),
          '#default_value' => $this->config->get('bh_news_no_results_messaging.value'),
          '#format' => $this->config->get('bh_news_no_results_messaging.format'),
          '#description' => $this->t('Provide the messaging that should be displayed when no results are found in the news filtering component.'),
          '#allowed_formats' => [
            'full_html',
          ],
        ],
        'team_list_default_tab' => [
          '#type' => 'select',
          '#chosen' => TRUE,
          '#multiple' => FALSE,
          '#title' => $this->t('Default tab for Contact\'s team list'),
          '#default_value' => $this->config->get('team_list_default_tab'),
          '#options' => $this->getTeamListOptions(),
          '#description' => $this->t('This setting controls the default tab for the Contact\'s team list.')
        ],
        'show_stock_change_icon_in_menu' => [
          '#type' => 'checkbox',
          '#title' => t('Stock Change Icon in Menu'),
          '#default_value' => $this->config->get('show_stock_change_icon_in_menu'),
          '#description' => $this->t('Use this to show the icons for the stock value in the main and external menus.')
        ]
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
