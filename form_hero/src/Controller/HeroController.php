<?php

namespace Drupal\form_hero\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This is our hero controller.
 */
class HeroController extends ControllerBase {

  public function heroList() {
   
    $this->my_page_cache();
    
    $cookies = $_COOKIE['Drupal_visitor_key'];    
    
    $build = [
      '#type' => 'markup',
      '#markup' => t('Cookies!!') . ' ' . $cookies,
    ];    
    return $build;

  }
  
  /**
  * Function to kill the cache for the page.
  */
  function my_page_cache() {
   \Drupal::service('page_cache_kill_switch')->trigger();
   return [
     '#markup' => time(),
   ];
  }
}
