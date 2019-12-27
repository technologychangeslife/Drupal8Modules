<?php

namespace Drupal\geform\Plugin\Block;

use Drupal\user\Entity\User;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'headerlogin' block.
 *
 * @Block(
 *   id = "headerlogin_block",
 *   admin_label = @Translation("Custom block"),
 *   category = @Translation("Custom headerlogin block")
 * )
 */
class HeaderloginBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;
    $uid = \Drupal::currentUser()->id();

    $user = User::load($uid);
    $approvalstatus = $user->field_useraccountstatus->value;
    $firstname = $user->field_first_name->value;
    $email = $user->mail->value;
    if ($approvalstatus == '1') {
      $userstatus = 'Approved';
    }
    elseif ($approvalstatus == '0') {
      $userstatus = 'Rejected';
    }
    else {
      $userstatus = 'Pending';
    }
    if (!empty($uid)) {
      return [
        '#type' => 'markup',
        '#markup' => '<div>Hello ' . $email . ' <br />Status:' . $userstatus . '<br /><a href=' . $base_url . '/user/logout>Logout</a></div>',
      ];
    }
    else {
      return [
        '#type' => 'markup',
        '#markup' => '<div><a href=' . $base_url . '/user/login>Login</a><a href=' . $base_url . '/user/login>Register</a></div>',
      ];
    }

  }

}
