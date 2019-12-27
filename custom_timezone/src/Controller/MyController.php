<?php

namespace Drupal\custom_timezone\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_timezone\GetTimezoneService;

/**
 * The class to be called from ajax to update the template.
 */
class MyController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('custom_timezone.get_timezone')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(GetTimezoneService $timeZoneService) {
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * This function to get current time.
   */
  public function getTime() {

    $config = \Drupal::config('custom_timezone.settings');
    $curent_time = $this->timeZoneService->getCurrentTime($config->get('timezone'));

    $build = [
      '#type' => 'markup',
      '#markup' => t('Current Time Comes here without clear cache!') . ' ' . $curent_time,
    ];
    // This is the important part, because will render only the TWIG template.
    return new Response(render($build));
  }

}
