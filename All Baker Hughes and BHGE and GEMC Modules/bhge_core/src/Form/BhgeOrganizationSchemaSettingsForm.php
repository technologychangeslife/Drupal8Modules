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
class BhgeOrganizationSchemaSettingsForm extends ConfigFormBase {

  protected $config;

  const FORM_FIELDS = [
    'organization_name',
    'organization_description',
    'organization_url',
    'organization_logo',
    'organization_facebook_logo',
    'organization_twitter_logo',
    'organization_linkedin_logo',
    'organization_instagram_logo',
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bhge.organization_schema_settings');
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
    return 'bhge_organization_schema_settings_form';
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

    $form['group_organization_schema_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Organization schema markup settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'organization_name' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization name'),
          '#default_value' => $this->config->get('organization_name'),
          '#size' => 255,
        ],
        'organization_description' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization description'),
          '#default_value' => $this->config->get('organization_description'),
          '#size' => 255,
          '#maxlength' => 500,
        ],
        'organization_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('Url of organization web site'),
          '#default_value' => $this->config->get('organization_url'),
          '#size' => 255,
        ],
        'organization_logo' => [
          '#type' => 'managed_file',
          '#title' => $this->t('Organizations logo'),
          '#default_value' => $this->config->get('organization_logo'),
          '#upload_location' => 'public://organization_logo',
        ],
        'organization_facebook_logo' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization facebook logo'),
          '#default_value' => $this->config->get('organization_facebook_logo'),
          '#size' => 255,
          '#tree' => TRUE,
        ],
        'organization_twitter_logo' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization twitter logo'),
          '#default_value' => $this->config->get('organization_twitter_logo'),
          '#size' => 255,
          '#tree' => TRUE,
        ],
        'organization_linkedin_logo' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization linkedin logo'),
          '#default_value' => $this->config->get('organization_linkedin_logo'),
          '#size' => 255,
          '#tree' => TRUE,
        ],
        'organization_instagram_logo' => [
          '#type' => 'textfield',
          '#title' => $this->t('Organization instagram logo'),
          '#default_value' => $this->config->get('organization_instagram_logo'),
          '#size' => 255,
          '#tree' => TRUE,
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
