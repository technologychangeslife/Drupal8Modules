<?php

namespace Drupal\custom_timezone\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_timezone\GetTimezoneService;


/**
 * The class to assign template to binder form.
 */
class MyController extends ControllerBase {
 
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('custom_timezone.get_timezone')
    );
  }

  public function __construct(GetTimezoneService $timeZoneService) {
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * This function renders the reorder form.
   */
  public function getTime() {
    $rand = rand();
    
    $config = \Drupal::config('custom_timezone.settings');
    // Will print 'Hello'.
    $config->get('country');
    // Will print 'en'.
    $config->get('city');
    
    $config->get('timezone');
    
    $curent_time = $this->timeZoneService->getCurrentTime($config->get('timezone'));
    
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Current Time Comes here without clear cache!'.$curent_time),
    );
    // This is the important part, because will render only the TWIG template.
    return new Response(render($build));
  }

}
