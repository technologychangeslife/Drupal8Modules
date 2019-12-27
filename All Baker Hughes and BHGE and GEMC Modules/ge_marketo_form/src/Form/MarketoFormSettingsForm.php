<?php

namespace Drupal\ge_marketo_form\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MarketoFormSettingsForm Class.
 */
class MarketoFormSettingsForm extends ConfigFormBase {


  /**
   * Node type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeTypeStorage;

  /**
   * Gated node types array.
   *
   * @var array
   */
  protected $nodeTypesGated = [];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ge_marketo_form_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ge_marketo_form.settings'];
  }

  /**
   * Node type staorage.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityStorageInterface $nodeTypeStorage) {
    parent::__construct($config_factory);

    $this->nodeTypeStorage = $nodeTypeStorage;
  }

  /**
   * Creating node type storage.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity.manager')->getStorage('node_type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ge_marketo_form.settings');

    $form['marketo'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Marketo settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      'options' => [
        'marketo_url' => [
          '#type' => 'textfield',
          '#title' => $this->t('Marketo Form URL'),
          '#description' => t('Example: //app-sjp.marketo.com'),
          '#required' => TRUE,
          '#default_value' => $config->get('marketo_url') ? $config->get('marketo_url') : NULL,
          '#size' => 50,
        ],
        'marketo_id' => [
          '#type' => 'textfield',
          '#title' => $this->t('Marketo Account ID'),
          '#description' => t('Example: 330-FCH-291'),
          '#required' => TRUE,
          '#default_value' => $config->get('marketo_id') ? $config->get('marketo_id') : NULL,
          '#size' => 50,
        ],
        'marketo_default_form_id' => [
          '#type' => 'number',
          '#required' => TRUE,
          '#description' => t('Example: 4622'),
          '#title' => $this->t('Marketo Default Form ID'),
          '#default_value' => $config->get('marketo_default_form_id') ? $config->get('marketo_default_form_id') : NULL,
          '#size' => 50,
        ],
        'node_types' => [
          '#type' => 'fieldset',
          '#title' => t('Gated content types'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          '#options' => [],
        ],
        'channel_partner_marketo' => [
          '#type' => 'fieldset',
          '#title' => t('Channel partner finder'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          'options' => [
            '#type' => 'fieldset',
            '#title' => $this->t('Marketo form details'),
            '#collapsible' => TRUE,
            '#collapsed' => FALSE,
            'options' => [
              'cp_marketo_form_id' => [
                '#type' => 'number',
                '#required' => TRUE,
                '#description' => t('Example: 4622'),
                '#title' => t('Marketo Form ID'),
                '#default_value' => $config->get('channel_partner_marketo_form_id') ? $config->get('channel_partner_marketo_form_id') : $config->get('marketo_default_form_id'),
                '#size' => 50,
              ],
              'cp_marketo_title' => [
                '#type' => 'textfield',
                '#title' => t('Marketo Title'),
                '#default_value' => $config->get('channel_partner_marketo_title') ? $config->get('channel_partner_marketo_title') : t('Channel Partner Contact Form'),
                '#description' => t('Example: Channel Partner Contact Form'),
              ],
              'cp_thank_you_message' => [
                '#type' => 'textfield',
                '#title' => t('Marketo Thank You Text'),
                '#default_value' => $config->get('channel_partner_marketo_thank_you_text') ? $config->get('channel_partner_marketo_thank_you_text') : t('Your message has been submitted. A representative will be in touch with you shortly.'),
                '#description' => t('Example: Thank you'),
              ],
            ],
          ],
        ],
      ],
    ];

    $nodeTypesGated = $this->nodeTypesGated;

    if (empty($nodeTypesGated)) {
      $nodeTypes = $this->nodeTypeStorage->loadMultiple();
      foreach ($nodeTypes as $nodeType) {
        /** @var \Drupal\node\NodeTypeInterface $nodeType */
        if ($nodeType->getThirdPartySetting('ge_marketo_form', 'enabled')) {
          $this->nodeTypesGated[] = $nodeType;
          $form['marketo']['options']['node_types']['options']['gated_' . $nodeType->id()] = [
            '#type' => 'fieldset',
            '#title' => $nodeType->label(),
            '#collapsible' => TRUE,
            '#collapsed' => FALSE,
            'options' => [
              $nodeType->id() . '_form_id' => [
                '#type' => 'number',
                '#required' => TRUE,
                '#description' => t('Example: 4622'),
                '#title' => $this->t('Form ID'),
                '#default_value' => $config->get($nodeType->id() . '.form_id'),
                '#size' => 50,
              ],
              $nodeType->id() . '_is_file' => [
                '#type' => 'checkbox',
                '#title' => t('Check this box if this gated content has file or external link'),
                '#default_value' => $config->get($nodeType->id() . '.is_file'),
              ],
              $nodeType->id() . '_button_text' => [
                '#type' => 'textfield',
                '#title' => t('Marketo Button Text'),
                '#default_value' => $config->get($nodeType->id() . '.button_text'),
                '#description' => t('Example: Download'),
              ],
            ],
          ];
        }

      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ge_marketo_form.settings')
      ->set('marketo_url', $form_state->getValue('marketo_url'))
      ->set('marketo_id', $form_state->getValue('marketo_id'))
      ->set('marketo_default_form_id', $form_state->getValue('marketo_default_form_id'))
      ->set('channel_partner_marketo_form_id', $form_state->getValue('cp_marketo_form_id'))
      ->set('channel_partner_marketo_title', $form_state->getValue('cp_marketo_title'))
      ->set('channel_partner_marketo_thank_you_text', $form_state->getValue('cp_thank_you_message'))
      ->save();

    // Add gated settings.
    $allValues = $form_state->getValues();
    foreach ($this->nodeTypesGated as $item) {
      $type = $item->id();
      foreach ($allValues as $key => $value) {
        if (strpos($key, $type) !== FALSE) {
          $valueType = str_replace($type . '_', '', $key);
          $this->config('ge_marketo_form.settings')->set($type . '.' . $valueType, $value)->save();
        }
      }

    }

    parent::submitForm($form, $form_state);
    Cache::invalidateTags(['ge_marketo_form.settings']);
    \Drupal::service('plugin.cache_clearer')->clearCachedDefinitions();
  }

}
