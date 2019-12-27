<?php

/**
 * @file
 * Builds placeholder replacement tokens for commercial content.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function gemc_n01_product_nav_token_info() {
  $info = [];

  $info['tokens']['node']['commercial-path'] = [
    'name' => t('GEMC Commercial path'),
    'description' => t('Returns the current section parent hierarchy for commercial content GEMC.'),
  ];

  return $info;
}

/**
 * Implements hook_tokens().
 */
function gemc_n01_product_nav_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];

  $language_manager = \Drupal::languageManager();
  if (isset($options['langcode'])) {
    $langcode = $options['langcode'];
  }
  else {
    $langcode = $language_manager->getCurrentLanguage()->getId();
  }
  if (isset($tokens['commercial-path'])) {
    if (isset($data['entity_type']) && $data['entity_type'] == 'node' && !empty($data['entity']) && in_array($data['entity']->bundle(), ['section', 'product'])) {

      $arr_path = [];
      $original = $tokens['commercial-path'];

      // Initialise static cache.
      if (!isset($nids_parsed)) {
        static $nids_parsed = [];
      }

      // Get parent trail.
      $trail = \Drupal::service('gemc_n01_product_nav.section_trail')->currentTrail($data['entity'], FALSE);

      // Dont parse twice, use cached alias.
      if (isset($nids_parsed[$data['entity']->id()])) {
        $replacements = $nids_parsed[$data['entity']->id()];
      }
      else {
        foreach ($trail['parents'] as $parent) {
          $arr_path[] = $parent->title;
        }
        $arr_path[] = $data['entity']->getTitle();
        foreach ($arr_path as &$path_part) {
          $path_part = \Drupal::service('pathauto.alias_cleaner')->cleanString($path_part, $options);
        }
        $replacements[$original] = implode('/', $arr_path);

        // Fill static cache.
        if ($replacements) {
          $nids_parsed[$data['entity']->id()] = $replacements;
        }
      }
    }
  }
  return $replacements;
}
