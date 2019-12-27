<?php

/**
 * @file
 * Drush commands.
 */

/**
 * Implements hook_drush_command().
 */
function bhge_n02_global_nav_drush_command() {
  $items = [];
  $items['hse-increment'] = [
    'description' => 'Allows to increment HSE days daily.',
    'aliases' => ['hse:increment'],
  ];
  return $items;
}

/**
 * Increment method.
 */
function drush_bhge_n02_global_nav_hse_increment() {

  $config = \Drupal::configFactory()->getEditable('bhge.hse_info_settings');
  $currentValue = $config->get('hse_days');
  $lastUpdated = $config->get('hse_last_updated');

  if (empty($currentValue)) {
    $currentValue = 0;
  }

  // Force cast the value.
  $currentValue = (int) $currentValue;

  // Getting times.
  $current = date('Ymd');
  $lastUpdated = date('Ymd', $lastUpdated);

  // Run increment.
  if ($current != $lastUpdated) {

    $currentValue++;
    $config->set('hse_days', $currentValue)->save();
    $config->set('hse_last_updated', time())->save();

    // Flushing the caches to rebuild the menu.
    drupal_flush_all_caches();
  }

  drush_print('HSE updated value is:' . ' at ' . $currentValue . ' ' . date('Y-M-dd', $current));

}
