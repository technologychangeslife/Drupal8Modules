<?php

namespace Drupal\bhge_user_registration\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;

/**
 * UserApprovalController.
 */
class BhgeUserApprovalController extends ControllerBase {

  /**
   * Approve user function.
   */
  public function approveUser($uid) {
    if (is_int($uid)) {
      $user = User::load($uid);

    }
    return [
      '#theme' => 'Requestpendingmessage',
      '#pendingmessage' => 'User marked approved: ' . $uid,
    ];
  }

}
