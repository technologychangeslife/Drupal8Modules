<?php

namespace Drupal\ge_marketo_form\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Class MarketoFormManager.
 *
 * @package Drupal\ge_marketo_form\Plugin
 */
class MarketoFormManager extends DefaultPluginManager {

  /**
   * Constructor for TaskPluginManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces,
                              CacheBackendInterface $cache_backend,
                              ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Marketo', $namespaces, $module_handler,
      'Drupal\ge_marketo_form\Plugin\MarketoFormInterface',
      'Drupal\ge_marketo_form\Annotation\MarketoForm');

    $this->alterInfo('ge_marketo_form_info');
    $this->setCacheBackend($cache_backend, 'ge_marketo_form_plugins');
  }

}
