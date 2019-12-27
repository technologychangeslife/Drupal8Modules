<?php

namespace Drupal\bhge_rigcount\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\bhge_rigcount\Extra\RigCounter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller routines for Rig counter.
 */
class RigCountController extends ControllerBase {

  /**
   * Get rig count.
   */
  public function getCount() {
    $rigcount = new RigCounter();
    $rigcount->setFirstKey('RigCount')->setSecondKey('SummaryInformation');

    $response = new Response();
    $response->setContent('success');
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

}
