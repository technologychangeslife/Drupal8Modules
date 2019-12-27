<?php

/**
 * @file
 * Drush commands for stock info.
 */

/**
 * Implements hook_drush_command().
 */
function bh_share_price_drush_command() {
  $items['stock-info-get'] = [
    'description' => 'Stock Info Update',
    'arguments' => [],
    'aliases' => ['stock-update'],
    'bootstrap' => DRUSH_BOOTSTRAP_NONE,
  ];

  return $items;
}

/**
 * Stock Info Update callback.
 */
function drush_bh_share_price_stock_info_get() {
  $marketInfoService = \Drupal::service('bh_share_price.share_price_info.class');
  $stockData = $marketInfoService->getFeed();

  if (empty($stockData)) {
    drush_log('File is not updated because of no returning any data', 'error');
  }else {
    drush_log('File has been updated', 'success');
  }

}
