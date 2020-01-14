<?php
/**
 * @file
 * Contains \Drupal\search_string\Plugin\Block\SearchBlock.
 */
namespace Drupal\search_string\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
/**
 * Provides a 'search_string' block.
 *
 * @Block(
 *   id = "search_string_block",
 *   admin_label = @Translation("Article block"),
 *   category = @Translation("Custom article block example")
 * )
 */
class SearchBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\search_string\Form\CustomForm');
    return $form;
   }
}