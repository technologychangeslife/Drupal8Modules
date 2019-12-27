<?php

namespace Drupal\bhge_analytics\Plugin\Block;

use Drupal\user\Entity\User;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Creates a block to add dynamic javascript via a render array Block.
 *
 * @Block(
 * id = "block_bhge_analytics",
 * admin_label = @Translation("BHGE Google Analytics"),
 * )
 */
class BhgeAnalyticsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user_type = "employee";
    $account = \Drupal::currentUser();
    $account = \Drupal::currentUser()->getAccount();
    $user = User::load(\Drupal::currentUser()->id());
    $company = $user->get('field_company_non_ge')->value;
    $uid = \Drupal::currentUser()->id();
    $sso = $user->get('field_sso_id')->value;
    if (preg_match('/[a-z]|[A-Z]/', $sso, $matches)) {
      $user_type = "customer";
    }
    $computed_settings = [
      'dimension1' => $uid,
      'dimension2' => $company,
      'dimension3' => $user_type,
    ];

    $build['#attached']['library'][] = 'bhge_analytics/bhge_analytics';
    $build['#attached']['drupalSettings']['localanalytics']['custid'] = $computed_settings;
    return $build;
  }

}
