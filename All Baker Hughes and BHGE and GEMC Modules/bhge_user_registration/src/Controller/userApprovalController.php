<?php

namespace Drupal\bhge_user_registration\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * UserApprovalController.
 */
class BhgeUserApprovalController extends ControllerBase {

  /**
   * The Approve User Message.
   */
  public function approveUser($sid) {

    return [
      '#theme' => 'Requestpendingmessage',
      '#pendingmessage' => 'User marked approved',
    ];
  }

}
