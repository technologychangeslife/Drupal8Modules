<?php

namespace Drupal\bhge_user_registration\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;

/**
 * RegistrationformController.
 */
class RegistrationformController extends ControllerBase {

  /**
   * Request Pending Message.
   */
  public function requestpendingmessage() {

    return [
      '#theme' => 'Requestpendingmessage',
      '#pendingmessage' => 'Thank you for the information. We see that you have already submitted an access request for engagerecip application and your request is under process. For further questions, please reach out to us at engagerecip@ge.com',
    ];
  }

  /**
   * Request Not Approved Message.
   */
  public function requestnotapprovedmessage() {

    return [
      '#theme' => 'Requestnotapprovedmessage',
      '#notapprovedmessage' => 'Thank you for the information We regret to inform you that your access request for the enagageRecip application has been rejected. For further questions, please reach out to us at engagerecip@ge.com',
    ];
  }

  /**
   * Function to show  document link on the main navigation links.
   */
  public function documentcheck() {
    $user = User::load(\Drupal::currentUser()->id());
    bhge_user_registration_user_login($user);
  }

  /**
   * Function to check document button on page content.
   *
   * Before redirecting to predix site.
   */
  public function documentCheckContentPage() {
    $user = User::load(\Drupal::currentUser()->id());
    bhge_user_registration_user_login($user);

  }

}
