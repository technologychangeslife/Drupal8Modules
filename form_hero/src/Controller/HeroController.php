<?php

namespace Drupal\form_hero\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
//use Drupal\form_hero\HeroArticleService;

/**
 * This is our hero controller.
 */
class HeroController extends ControllerBase {

  private $articleHeroService;

  /*public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_hero.hero_articles')
    );
  }

  public function __construct(HeroArticleService $articleHeroService) {
    $this->articleHeroService = $articleHeroService;
  }*/

  public function heroList() {
   
    $this->my_page_cache();
    
    print "cookies == ".$_COOKIE['Drupal_visitor_key']; print '<br>';
    //exit();
    
    var_dump($_COOKIE['Drupal_visitor_key']);
    
    $cookies = $_COOKIE['Drupal_visitor_key'];
    
    print "key_name = ".$value;
    
    print "var == ".$_SESSION['session_var']; 
    
    //kint($details);

    //kint($this->articleHeroService->getHeroArticles()); die();

    $heroes = [
      ['name' => 'Hulk'],
      ['name' => 'Thor'],
      ['name' => 'Iron Man'],
      ['name' => 'Luke Cage'],
      ['name' => 'Black Widow'],
      ['name' => 'Daredevil'],
      ['name' => 'Captain America'],
      ['name' => 'Wolverine']
    ];
    
    
    
    $build = [
      '#type' => 'markup',
      '#markup' => t('Cookies!!') . ' ' . $cookies,
    ];

    /*return [
      '#theme' => 'hero_list',
      '#items' => $heroes,
      '#title' => $this->t('Our wonderful heroes list'),
    ];*/
    
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
