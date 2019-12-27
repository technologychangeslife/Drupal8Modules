<?php

namespace Drupal\gemc_search\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Search form' block.
 *
 * @Block(
 *   id = "gemc_search_form_block",
 *   admin_label = @Translation("GEMC Search form"),
 *   category = @Translation("Forms")
 * )
 */
class GemcSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\gemc_search\Form\GemcSearchBlockForm');
  }

}
