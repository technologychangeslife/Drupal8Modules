<?php

namespace Drupal\ge_marketo_form\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Retrieves marketo form plugin definitions for all custom blocks.
 */
class MarketoForm extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The marketo form storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $marketoFormStorage;

  /**
   * Constructs a BlockContent object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $marketoFormStorage
   *   The marketo form storage.
   */
  public function __construct(EntityStorageInterface $marketoFormStorage) {
    $this->marketoFormStorage = $marketoFormStorage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('marketo_form')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $marketoFornmEntities = $this->marketoFormStorage->loadMultiple();
    // Reset the discovered definitions.
    $this->derivatives = [];
    foreach ($marketoFornmEntities as $marketoFornmEntity) {
      /** @var \Drupal\ge_marketo_form\Entity\MarketoFormInterface $marketoFormEntity */
      $this->derivatives[$marketoFornmEntity->uuid()] = $base_plugin_definition;
      $this->derivatives[$marketoFornmEntity->uuid()]['label'] = $marketoFornmEntity->label();
      $this->derivatives[$marketoFornmEntity->uuid()]['formId'] = $marketoFornmEntity->getFormId();
      $this->derivatives[$marketoFornmEntity->uuid()]['gated'] = FALSE;
      $this->derivatives[$marketoFornmEntity->uuid()]['isFile'] = FALSE;
    }

    return $this->derivatives;
  }

}
