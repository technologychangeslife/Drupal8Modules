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
class BhgeSocialSettingsForm extends ConfigFormBase {

  protected $config;

  protected $emailShareSender;

  const FORM_FIELDS = [
    'email_share_sender',
    'facebook_page_url',
    'twitter_page_url',
    'linkedin_page_url',
    'social_share_title',
    'instagram_page_url',
    'youtube_page_url',
  ];

  /**
   * SpinAwardsSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bhge.social_settings');
    $this->emailShareSender = 'noreply@bhge';
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
    return 'bhge_social_settings_form';
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
    $form['group_basic_social_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic social settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      'options' => [
        'social_share_title' => [
          '#type' => 'textfield',
          '#title' => $this->t('Title on social share component'),
          '#default_value' => $this->config->get('social_share_title'),
          '#size' => 255,
        ],
      ],
    ];

    $form['group_social_links'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Social links'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      'options' => [
        'email_share_sender' => [
          '#type' => 'textfield',
          '#title' => $this->t('Share via email sender'),
          '#default_value' => $this->config->get('email_share_sender'),
          '#size' => 255,
        ],
        'facebook_page_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('Facebook page url'),
          '#default_value' => $this->config->get('facebook_page_url'),
          '#size' => 255,
        ],
        'twitter_page_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('Twitter page url'),
          '#default_value' => $this->config->get('twitter_page_url'),
          '#size' => 255,
        ],
        'linkedin_page_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('LinkedIn page url'),
          '#default_value' => $this->config->get('linkedin_page_url'),
          '#size' => 255,
        ],
        'instagram_page_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('Instagram page url'),
          '#default_value' => $this->config->get('instagram_page_url'),
          '#size' => 255,
        ],
        'youtube_page_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('YouTube page url'),
          '#default_value' => $this->config->get('youtube_page_url'),
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
