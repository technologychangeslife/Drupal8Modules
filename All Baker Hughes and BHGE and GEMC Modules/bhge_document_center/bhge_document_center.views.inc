<?php

/**
 * @file
 * This file is used for altering the view data.
 */

  /**
   * Implements hook_views_data_alter().
   */
  function bhge_document_center_views_data_alter(array &$data) {
  $data['node']['bhge_dam_file_size'] = [
    'title' => t('BHGE DAM file size'),
    'field' => [
      'title' => t('BHGE DAM file size'),
      'help' => t('BHGE DAM file size.'),
      'id' => 'bhge_dam_file_size',
    ],
  ];
  }
