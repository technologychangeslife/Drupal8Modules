<?php

namespace Drupal\ge_marketo_webhooks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class WebhooksController.
 *
 * @package Drupal\ge_marketo_webhooks\Controller
 */
class WebhooksController extends ControllerBase {

  /**
   * Calculte Paoi Handler.
   *
   * @param string $payload
   *   Payload.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Returns Json response.
   */
  public function calculatePaoiHandler($payload = NULL) {
    $scores = [
      'apm' => isset($_GET['apm']) ? $_GET['apm'] : NULL,
      'automation' => isset($_GET['automation']) ? $_GET['automation'] : NULL,
      'brilliantManufacturing' => isset($_GET['brilliantManufacturing']) ? $_GET['brilliantManufacturing'] : NULL,
      'cyber' => isset($_GET['cyber']) ? $_GET['cyber'] : NULL,
      'fieldServiceManagement' => isset($_GET['fieldServiceManagement']) ? $_GET['fieldServiceManagement'] : NULL,
      'managedServices' => isset($_GET['managedServices']) ? $_GET['managedServices'] : NULL,
      'predix' => isset($_GET['predix']) ? $_GET['predix'] : NULL,
      'professionalServices' => isset($_GET['professionalServices']) ? $_GET['professionalServices'] : NULL,
      'services' => isset($_GET['services']) ? $_GET['services'] : NULL,
      'supportServices' => isset($_GET['supportServices']) ? $_GET['supportServices'] : NULL,
    ];
    $paoi = t('None');
    $topScore = 0;
    foreach ($scores as $label => $score) {
      if ($score > $topScore) {
        $topScore = $score;
        $paoi = $label;
      }
    }

    $output = ['scores' => $scores, 'paoi' => $paoi];

    return new JsonResponse($output);

  }

}
