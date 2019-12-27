<?php

namespace Drupal\ge_marketo_form\Plugin;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginBase;
use Drupal\ge_marketo_form\MarketoFormUuidLookup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MarketoFormBase.
 *
 * @package Drupal\ge_marketo_form\Plugin
 */
abstract class MarketoFormBase extends ContextAwarePluginBase implements MarketoFormInterface, ContainerFactoryPluginInterface {

  /**
   * Marketo Form Entity.
   *
   * @var \Drupal\ge_marketo_form\Entity\MarketoFormInterface
   */
  protected $marketoFormEntity;

  /**
   * Module Handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The block content UUID lookup service.
   *
   * @var \Drupal\ge_marketo_form\MarketoFormUuidLookup
   */
  protected $uuidLookup;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $moduleHandler, ConfigFactoryInterface $configFactory, MarketoFormUuidLookup $uuidLookup, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $moduleHandler;
    $this->configFactory = $configFactory;
    $this->uuidLookup = $uuidLookup;
    $this->entityTypeManager = $entity_type_manager;
    $this->setContextMapping(['node' => '@node.node_route_context:node']);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler'),
      $container->get('config.factory'),
      $container->get('marketo_form.uuid_lookup'),
      $container->get('entity_type.manager')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getClientId() {
    if ($marketoId = $this->configFactory->get('ge_marketo_form.settings')
      ->get('marketo_id')) {
      return $marketoId;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getFormId(array $variables = []) {
    if (isset($this->getPluginDefinition()['formId']) && $this->getPluginDefinition()['formId']) {
      return $this->getPluginDefinition()['formId'];
    }

    if ($this->getEntity()) {
      return $this->getEntity()->getFormId();
    }

    if ($marketoFormId = $this->configFactory->get('ge_marketo_form.settings')
      ->get('marketo_default_form_id')) {
      return $marketoFormId;
    }

    return NULL;
  }

  /**
   * Get Form.
   *
   * @param array $variables
   *   The form variables.
   *
   * @return array
   *   Returns array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getForm(array $variables = []) {
    return [
      '#theme' => 'marketo_form',
      '#data' => [
        'id' => $this->getClientId(),
        'formId' => $this->getFormId($variables),
        'gated' => $this->isGated(),
        'buttonText' => t('Submit'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isGated() {
    if ($this->getPluginDefinition()['gated']) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Get Button Text.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|mixed
   *   Returns submit button text.
   */
  protected function getButtonText() {
    if (!isset($this->marketoFormEntity)) {
      return t('Submit');
    }

    return $this->marketoFormEntity->getButtonText();
  }

  /**
   * Get entity function.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\ge_marketo_form\Entity\MarketoFormInterface|null
   *   Returns marketo form entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getEntity() {
    if (!isset($this->marketoFormEntity)) {
      $uuid = $this->getDerivativeId();
      if ($id = $this->uuidLookup->get($uuid)) {
        $this->marketoFormEntity = $this->entityTypeManager->getStorage('marketo_form')
          ->load($id);
      }
    }
    return $this->marketoFormEntity;
  }

}
