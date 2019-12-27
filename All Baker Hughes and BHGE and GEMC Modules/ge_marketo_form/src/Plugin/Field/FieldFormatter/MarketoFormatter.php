<?php

namespace Drupal\ge_marketo_form\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ge_marketo_form\Plugin\MarketoFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the breakfast formatter.
 *
 * @FieldFormatter(
 *   id = "marketo_form_formatter",
 *   module = "ge_marketo_form",
 *   label = @Translation("Marketo Form formatter"),
 *   field_types = {
 *     "marketo_form"
 *   }
 * )
 */
class MarketoFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The Marketo Form Manager.
   *
   * @var \Drupal\ge_marketo_form\Plugin\MarketoFormManager
   */
  protected $marketoFormManager;

  /**
   * MarketoFormatter constructor.
   *
   * @param int $plugin_id
   *   The Plugin ID.
   * @param string $plugin_definition
   *   The Plugin Definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition.
   * @param array $settings
   *   The settings array.
   * @param string $label
   *   The label.
   * @param string $view_mode
   *   The View Mode.
   * @param array $third_party_settings
   *   Third Party Settings array.
   * @param \Drupal\ge_marketo_form\Plugin\MarketoFormManager $marketoFormManager
   *   Marketo Form Manager.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, MarketoFormManager $marketoFormManager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->marketoFormManager = $marketoFormManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // @see \Drupal\Core\Field\FormatterPluginManager::createInstance().
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('plugin.manager.marketo_form_manager')
    );
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $marketoForm = $this->marketoFormManager->createInstance($item->value);
      $elements[$delta] = $marketoForm->getForm();
    }

    return $elements;
  }

}
