<?php

namespace Drupal\ge_marketo_form\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Retrieves marketo form plugin definitions for all custom blocks.
 */
class GatedMarketoForm extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The node type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeTypeStorage;

  /**
   * The config factory.
   *
   * Subclasses should use the self::config() method, which may be overridden to
   * address specific needs when loading config, rather than this property
   * directly. See \Drupal\Core\Form\ConfigFormBase::config() for an example of
   * this.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a BlockContent object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $nodeTypeStorage
   *   The marketo form storage.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(EntityStorageInterface $nodeTypeStorage, ConfigFactoryInterface $configFactory) {
    $this->nodeTypeStorage = $nodeTypeStorage;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('node_type'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $config = $this->configFactory->get('ge_marketo_form.settings');
    $nodeTypes = $this->nodeTypeStorage->loadMultiple();
    // Reset the discovered definitions.
    $this->derivatives = [];
    foreach ($nodeTypes as $nodeType) {
      /** @var \Drupal\node\NodeTypeInterface $nodeType */
      if ($nodeType->getThirdPartySetting('ge_marketo_form', 'enabled')) {
        $this->derivatives[$nodeType->id()] = $base_plugin_definition;
        $this->derivatives[$nodeType->id()]['label'] = $nodeType->label();
        $this->derivatives[$nodeType->id()]['formId'] = $config->get($nodeType->id() . '.form_id') ? $config->get($nodeType->id() . '.form_id') : $config->get('marketo_default_form_id');
        $this->derivatives[$nodeType->id()]['buttonText'] = $config->get($nodeType->id() . '.button_text') ? $config->get($nodeType->id() . '.button_text') : t('Submit');
        $this->derivatives[$nodeType->id()]['gated'] = TRUE;
        $this->derivatives[$nodeType->id()]['isFile'] = $config->get($nodeType->id() . '.is_file');
        $this->derivatives[$nodeType->id()]['context'] = [
          'node' => new ContextDefinition('entity:node'),
        ];
      }
    }

    return $this->derivatives;
  }

}
