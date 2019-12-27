<?php

namespace Drupal\gemc_download_center\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Download Center Settings Form Class.
 */
class DownloadCenterSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gemc_download_center_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['gemc_download_center.settings'];
  }

  /**
   * DownloadCenterSettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config Factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    // parent::__construct($config_factory);.
  }

  /**
   * DownloadCenterSettingsForm create to fetch config.factor.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container interface.
   *
   * @return \Drupal\Core\Form\ConfigFormBase|\Drupal\gemc_download_center\Form\DownloadCenterSettingsForm
   *   Returns config factory.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('gemc_download_center.settings');

    $form['download_center'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Download Center page settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      'options' => [
        'instructions_label' => [
          '#type' => 'textfield',
          '#title' => $this->t('Instructions label'),
          '#description' => t('Example: Instructions'),
          '#required' => TRUE,
          '#default_value' => $config->get('page.instructions.label') ? $config->get('page.instructions.label') : 'Instructions',
          '#size' => 50,
        ],
        'instructions_body' => [
          '#type' => 'textarea',
          '#title' => $this->t('Instructions body'),
          '#description' => t('Contains HTML, convert special characters such as &'),
          '#required' => TRUE,
          '#default_value' => $config->get('page.instructions.body') ? $config->get('page.instructions.body') : '',
          '#rows' => 15,
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('gemc_download_center.settings')
      ->set('page.instructions.label', $form_state->getValue('instructions_label'))
      ->set('page.instructions.body', $form_state->getValue('instructions_body'))
      ->save();

    parent::submitForm($form, $form_state);
    Cache::invalidateTags(['gemc_download_center.settings']);
    \Drupal::service('plugin.cache_clearer')->clearCachedDefinitions();
  }

}
