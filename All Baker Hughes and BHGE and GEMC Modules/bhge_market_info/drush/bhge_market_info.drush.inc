<?php

/**
 * @file
 * Drush commands for market info.
 */

define('MARKET_INFO_FILE', 'public://market-info.json');

/**
 * Implements hook_drush_command().
 */
function bhge_market_info_drush_command() {
  $items['market_info_get'] = [
    'description' => 'Market Info Update',
    'arguments' => [],
    'aliases' => ['mi-update'],
  ];

  return $items;
}

/**
 * Market Info Update callback.
 */
function drush_bhge_market_info_market_info_get() {
  $marketInfoService = \Drupal::service('bhge_market_info.class');

  $data = $marketInfoService->getData();

  if (empty($data)) {
    drush_log('File is not updated because the marketInfoServices is not returning any data', 'error');
  }

  $filePath = \Drupal::service('file_system')->realpath(MARKET_INFO_FILE);
  $file = new SplFileObject($filePath, 'w');
  $file->fwrite(json_encode($data));
  drush_log('File has been updated', 'success');
}
