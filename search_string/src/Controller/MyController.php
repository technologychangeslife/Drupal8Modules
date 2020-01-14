<?php

namespace Drupal\search_string\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Form\FormInterface;

/**
 * The class to be called from ajax to update the template.
 */
class MyController extends ControllerBase {
  /**
   * This function to get current time.
   */
  public function getContent() {

    $config = \Drupal::config('search_string.settings');
    $search_string = $config->get('title');
    
    $search_query = \Drupal::entityQuery('node');
    // Published or not.
    $search_query->condition('status', 1);
    // Only English docs required.
    $search_query->condition('title', $search_string, 'CONTAINS');
    $search_nids = $search_query->execute();
    
    $contents = [];

    foreach ($search_nids as $search_nid) {
     
     array_push($contents, "Node ID (Found in Titles) : ".$search_nid);
     
    }
    
    $search_query->condition('body', $search_string, 'CONTAINS');
    $search_nids = $search_query->execute();

    foreach ($search_nids as $search_nid) {
     
     array_push($contents, "Node ID (Found in Block text Only) : ".$search_nid);
     
    }
    
    //block_text_only for news
    
    //print_r($contents);
    return [
      '#theme' => 'content_list',
      '#items' => $contents,
      '#title' => $this->t('List Of Contents Matching the search criteria'),
    ];

  }
  
  /**
   * This function to get the form. And load it without Header and Footer.
   */
  public function getForm() {

    $form = \Drupal::formBuilder()->getForm('Drupal\search_string\Form\CustomForm');
    //return $form;
    // This is the important part, because will render only the TWIG template.
    return new Response(render($form));
  }

}
