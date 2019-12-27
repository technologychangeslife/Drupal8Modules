<?php

namespace Drupal\bhge_n02_global_nav\Controller;

use Drupal\bhge_n02_global_nav\MenuProcessor;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Global nav data controller.
 */
class GlobalNavData extends ControllerBase {

  public $configFactory;

  public $menuProcessor;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('bhge_n02_global_nav.processor')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $configFactory, MenuProcessor $menuProcessor) {
    $this->configFactory = $configFactory;
    $this->menuProcessor = $menuProcessor;
  }

  /**
   * {@inheritdoc}
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Load HSE and Nav data.
   */
  public function load() {
    $hseInfoConfig = $this->configFactory->get('bhge.hse_info_settings');
    $days = !empty($hseInfoConfig->get('hse_days')) ? $hseInfoConfig->get('hse_days') : 0;

    $data['data']['hse'] = [
      'days' => $days,
      'suffix' => $hseInfoConfig->get('hse_days_suffix'),
    ];

    $data['data']['subsites_navigation'] = $this->menuProcessor->processor('subsites-navigation');
    $data['data']['microsites_navigation'] = $this->menuProcessor->processor('microsites-navigation');
    $data['data']['cache_timestamp'] = time();

    $response = new CacheableJsonResponse($data);
    $response->addCacheableDependency(CacheableMetadata::createFromRenderArray($data)->setCacheMaxAge(86400));
    return $response;
  }

}
