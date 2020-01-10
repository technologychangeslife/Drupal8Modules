<?php

namespace Drupal\bh_share_price\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use Drupal\bh_share_price\Controller\SharePriceInfoController;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "get_stock_price",
 *   label = @Translation("Get Stock Price Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/get-stock-price"
 *   }
 * )
 */
class GetStockPriceRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\bh_share_price\Controller\SharePriceInfoController $stockPrice
   *   Provide stock price details.
   */
  public function __construct(
  array $configuration,
  $plugin_id,
  $plugin_definition,
  array $serializer_formats,
  LoggerInterface $logger,
  AccountProxyInterface $current_user,
  ConfigFactoryInterface $config_factory,
  SharePriceInfoController $stockPrice) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->config = $config_factory->get('bh.stock_info_settings');
    $this->stockPrice = $stockPrice;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition, $container->getParameter('serializer.formats'), $container->get('logger.factory')->get('example_rest'), $container->get('current_user'), $container->get('config.factory'), $container->get('bh_share_price.share_price_info.class')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }
    $stockPrice = $this->stockPrice->getFeed();
    $stockPriceData = json_decode(json_encode($stockPrice), TRUE);
    $build = [
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    $response = new ResourceResponse($stockPriceData);
    $response->addCacheableDependency($build, $stockPriceData);
    return $response;
  }

}
