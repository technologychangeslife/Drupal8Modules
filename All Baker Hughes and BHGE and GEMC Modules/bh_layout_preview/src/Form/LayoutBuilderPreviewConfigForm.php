<?php

namespace Drupal\bh_layout_preview\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * Description of LayoutBuilderPreviewConfigForm.
 */
class LayoutBuilderPreviewConfigForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const BH_LAYOUT_BUILDER_PREVIEW_SETTINGS = 'bh_layout_preview.layout_builder_settings';

  /**
   * The configuration fatcory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The layout builder plugins.
   *
   * @var \Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins
   */
  protected $layoutBuilderPlugins;

  /**
   * Constructs a LayoutBuilderPreviewConfigForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.   *.
   * @param \Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins $layout_builder_plugins
   *   The layout builder plugins.
   */
  public function __construct(ConfigFactoryInterface $config_factory, GetLayoutBuilderPlugins $layout_builder_plugins) {
    parent::__construct($config_factory);
    $this->configFactory = $config_factory;
    $this->layoutBuilderPlugins = $layout_builder_plugins;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('bh_layout_preview.layout_builder_plugins')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'layout_builder_preview_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::BH_LAYOUT_BUILDER_PREVIEW_SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::BH_LAYOUT_BUILDER_PREVIEW_SETTINGS);
    $getPluginLists = $this->layoutBuilderPlugins->getPluginList();
    foreach ($getPluginLists as $key => $lists) {
      $form[$key] = [
        '#type' => 'fieldset',
        '#title' => $this->t($key),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE
      ];
      foreach ($lists as $k => $list) {
        $title = $list['title'];
        $form[$key][$k] = [
          '#type' => 'textfield',
          '#title' => $this->t($title),
          '#default_value' => $config->get($k),
          '#placeholder' => 'Please Enter component Url for ' . $list['title'],
          '#description' => 'Please Enter component Url. <br>Eg: /component-library/' . str_replace(' ', '-', $title),
        ];
      }
    }
    $form['#attached']['library'][] = 'bh_layout_preview/bh-preview-modal';
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $getFormElements = array_keys($form_state->getValues());

    // Unset form component field.
    $submit = array_search('submit', $getFormElements);
    $form_build_id = array_search('form_build_id', $getFormElements);
    $form_token = array_search('form_token', $getFormElements);
    $form_id = array_search('form_id', $getFormElements);
    $op = array_search('op', $getFormElements);
    unset($getFormElements[$submit], $getFormElements[$form_build_id], $getFormElements[$form_token], $getFormElements[$form_id], $getFormElements[$op]);

    foreach ($getFormElements as $val) {
      $uri = $form_state->getValue($val);
      if (!empty($uri)) {
        // Prase the uri.
        $prase_uri = UrlHelper::parse($uri);
        $path = $prase_uri['path'];

        if (!empty($path)) {
          $position = strrpos($path, ".");
          // Path validation if it is not file.
          if ($position === FALSE) {
            $external = UrlHelper::isExternal($path);
            $url_object = \Drupal::service('path.validator')->getUrlIfValid($path);
            if ($external) {
              $form_state->setErrorByName($val, 'Please enter valid Url');
            }
            elseif ($url_object === FALSE) {
              $form_state->setErrorByName($val, 'Please enter valid Url');
            }
          }
        }

      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $getFormElements = array_keys($form_state->getValues());

    // Unset form component field.
    $submit = array_search('submit', $getFormElements);
    $form_build_id = array_search('form_build_id', $getFormElements);
    $form_token = array_search('form_token', $getFormElements);
    $form_id = array_search('form_id', $getFormElements);
    $op = array_search('op', $getFormElements);
    unset($getFormElements[$submit], $getFormElements[$form_build_id], $getFormElements[$form_token], $getFormElements[$form_id], $getFormElements[$op]);

    $config = $this->configFactory->getEditable(static::BH_LAYOUT_BUILDER_PREVIEW_SETTINGS);
    foreach ($getFormElements as $val) {
      $config->set($val, $form_state->getValue($val));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
