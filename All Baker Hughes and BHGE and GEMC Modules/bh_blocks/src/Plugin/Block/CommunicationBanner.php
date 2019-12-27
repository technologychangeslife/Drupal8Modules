<?php

namespace Drupal\bh_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Communication Banner' block.
 *
 * @Block(
 *  id = "communication_banner",
 *  admin_label = @Translation("Communication Banner"),
 * )
 */
class CommunicationBanner extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $categories = [
      'curated',
    ];
    $output = $this->getView($categories);
    $build['container']['articles'] = $output;
    $build['#cache'] = [
      'tags' => ['node_list', 'views_list'],
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function getView($categories = []) {

    $output = [];
    foreach ($categories as $cat) {
      $output[$cat] = views_embed_view('news', $cat);
    }
    return $output;
  }

}
