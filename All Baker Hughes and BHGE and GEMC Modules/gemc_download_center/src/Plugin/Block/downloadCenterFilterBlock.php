<?php

namespace Drupal\gemc_download_center\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Utility\Xss;

/**
 * Provides a Download Center block.
 *
 * @Block(
 *  id = "download_center_filter_block",
 *  admin_label = @Translation("Download Center Filters")
 * )
 */
class DownloadCenterFilterBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * A configuration object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * {@inheritdoc}
   *
   * @param array $configuration
   *   The configuration array.
   * @param int $plugin_id
   *   The plugin id.
   * @param string $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config_factory->get('gemc_download_center.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block_ids = [
      'industry',
      'category',
      'product',
      'exposedformdownload_centerdownload_center',
    ];
    $blocks = [];

    foreach ($block_ids as $block_id) {
      $block = \Drupal::entityTypeManager()
        ->getStorage('block')
        ->load($block_id);
      if (!empty($block)) {
        $blocks[$block_id] = \Drupal::entityTypeManager()
          ->getViewBuilder('block')
          ->view($block);
      }
    }

    return [
      '#theme' => 'download_center_filter_block',
      '#blocks' => $blocks,
      '#instructions' => [
        'label' => $this->config->get('page.instructions.label'),
        'body' => xss::filter($this->config->get('page.instructions.body')),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
