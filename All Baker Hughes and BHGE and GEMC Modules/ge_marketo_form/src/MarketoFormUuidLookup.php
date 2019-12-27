<?php

namespace Drupal\ge_marketo_form;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Cache\CacheCollector;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Lock\LockBackendInterface;

/**
 * A cache collector that caches IDs for marketo_form UUIDs.
 *
 * As marketo_form entities are used as block plugin derivatives, it is a
 * fairly safe limitation that there are not hundreds of them, a site will
 * likely run into problems with too many marketo_form entities in other places
 * than a cache that only stores UUID's and IDs. The same assumption is not true
 * for other content entities.
 *
 * @internal
 */
class MarketoFormUuidLookup extends CacheCollector {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a BlockContentUuidLookup instance.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend.
   * @param \Drupal\Core\Lock\LockBackendInterface $lock
   *   The lock backend.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(CacheBackendInterface $cache, LockBackendInterface $lock, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct('marketo_form_uuid', $cache, $lock);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function resolveCacheMiss($key) {
    $ids = $this->entityTypeManager->getStorage('marketo_form')->getQuery()
      ->condition('uuid', $key)
      ->execute();

    // Only cache if there is a match, otherwise creating new entities would
    // require to invalidate the cache.
    $id = reset($ids);
    if ($id) {
      $this->storage[$key] = $id;
      $this->persist($key);
    }
    return $id;
  }

}
