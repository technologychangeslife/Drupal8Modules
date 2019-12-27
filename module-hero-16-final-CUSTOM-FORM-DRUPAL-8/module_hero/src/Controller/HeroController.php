<?php

namespace Drupal\module_hero\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\module_hero\HeroArticleService;

/**
 * This is our hero controller.
 */
class HeroController extends ControllerBase {

  private $articleHeroService;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_hero.hero_articles')
    );
  }

  public function __construct(HeroArticleService $articleHeroService) {
    $this->articleHeroService = $articleHeroService;
  }

  public function heroList() {

    kint($this->articleHeroService->getHeroArticles()); die();

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

    return [
      '#theme' => 'hero_list',
      '#items' => $heroes,
      '#title' => $this->t('Our wonderful heroes list'),
    ];

  }
}
