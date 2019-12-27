<?php

namespace Drupal\ge_marketo_form\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ge_marketo_form\Plugin\MarketoFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'service_now_default' widget.
 *
 * @FieldWidget(
 *   id = "marketo_form_default",
 *   label = @Translation("Marketo Form"),
 *   description = @Translation("Marketo Form"),
 *   field_types = {
 *     "marketo_form"
 *   }
 * )
 */
class MarketoFormWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The Marketo Form Manager.
   *
   * @var Drupal\ge_marketo_form\Plugin\MarketoFormManager
   */
  protected $marketoFormManager;

  /**
   * MarketoFormWidget constructor.
   *
   * @param int $plugin_id
   *   Plugin ID.
   * @param string $plugin_definition
   *   Plugin Definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   Field Definition.
   * @param array $settings
   *   The settings array.
   * @param array $third_party_settings
   *   The Third Party Settings.
   * @param \Drupal\ge_marketo_form\Plugin\MarketoFormManager $marketoFormManager
   *   The Marketo Form Manager.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, MarketoFormManager $marketoFormManager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $this->marketoFormManager = $marketoFormManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('plugin.manager.marketo_form_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $marketoFormPlugins = $this->getMarketoFormsPlugins();
    $element['value'] = $element + [
      '#type' => 'select',
      '#options' => $marketoFormPlugins,
      '#empty_value' => '',
      '#default_value' => (isset($items[$delta]->value) && isset($marketoFormPlugins[$items[$delta]->value])) ? $items[$delta]->value : NULL,
      '#description' => t('Select available Marketo Form'),
    ];
    return $element;
  }

  /**
   * Marketo Form Plugins.
   */
  protected function getMarketoFormsPlugins() {
    $plugins = $this->marketoFormManager->getDefinitions();
    $options = [];
    foreach ($plugins as $pluginId => $plugin) {
      if (!$plugin['gated']) {
        $options[$pluginId] = $plugin['label'];
      }
    }

    return $options;
  }

}
