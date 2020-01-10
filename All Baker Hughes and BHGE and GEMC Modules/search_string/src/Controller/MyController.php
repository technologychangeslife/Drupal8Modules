<?php

namespace Drupal\search_string\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * The class to be called from ajax to update the template.
 */
class MyController extends ControllerBase {
  /**
   * This function to get current time.
   */
  public function getContent() {

    $config = \Drupal::config('search_string.settings');
    print "search_string =  ".$search_string = $config->get('title');
    
    $search_query = \Drupal::entityQuery('node');
    // Published or not.
    $search_query->condition('status', 1);
    // Only English docs required.
    $search_query->condition('title', $search_string, 'CONTAINS');
    $search_nids = $search_query->execute();
    
    $contents = [];

    foreach ($search_nids as $search_nid) {
     
     array_push($contents, "Node ID : ".$search_nid);
     
     //$contents['id'] = $search_nid;
     
    }
    print_r($contents);
    return [
      '#theme' => 'content_list',
      '#items' => $contents,
      '#title' => $this->t('List Of Contents Matching the search criteria'),
    ];

  }

}
