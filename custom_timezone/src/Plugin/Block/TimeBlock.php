<?php

namespace Drupal\custom_timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_timezone\GetTimezoneService;

/**
 * Provides a block called "Custom TimeZone Block".
 *
 * @Block(
 *  id = "custom_timezone_block",
 *  admin_label = @Translation("Custom TimeZone Block")
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $timeZoneService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('custom_timezone.get_timezone')
    );

  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GetTimezoneService $timeZoneService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = \Drupal::config('custom_timezone.settings');
    $curent_time = $this->timeZoneService->getCurrentTime($config->get('timezone'));

    // Return $table;.
    return [
      '#theme' => 'custom_timezone_block',
      '#title' => "Showing time for " . $config->get('country') . "," . $config->get('city'),
      '#description' => "This time coming from block so it will not change until you clear cache " . $curent_time,
      '#cache' => [
        'tags' => ['config:custom_timezone.settings'],
      ],
    ];

  }

}
