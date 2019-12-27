<?php

namespace Drupal\memcache_factory_bootstrap;

use Drupal\memcache\DrupalMemcacheConfig;
use Drupal\memcache\DrupalMemcacheFactory as CoreDrupalMemcacheFactory;

/**
 * Class DrupalMemcacheFactory.
 *
 * @package Drupal\memcache_factory_bootstrap
 */
class DrupalMemcacheFactory {

  /**
   * Get the right memcache drupal class.
   *
   * @param \Drupal\memcache\DrupalMemcacheConfig $settings
   *   Settings.
   * @param string $bin
   *   The bin which is to be used.
   *
   * @return \Drupal\memcache\DrupalMemcache|\Drupal\memcache\DrupalMemcached
   *   Memcache object.
   */
  public static function getInstance(DrupalMemcacheConfig $settings, $bin = NULL) {
    $memcacheFactory = new CoreDrupalMemcacheFactory($settings);
    return $memcacheFactory->get($bin);
  }

}
