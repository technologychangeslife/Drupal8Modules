<?php

namespace Drupal\bhge_market_info\Controller;

use Drupal\Component\Utility\Xss;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Return market info.
 */
class MarketInfoController extends ControllerBase {

  protected $request;

  public $state;

  public $commoditiesController;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('state'), $container->get('bhge_commodities_info.class'), \Drupal::request());
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($state, $commoditiesController, $request) {
    $this->state = $state;
    $this->commoditiesController = $commoditiesController;
    $this->request = $request;
  }

  /**
   * Load market info data.
   */
  public function getJsonElement($json, $element) {
    if ($json !== NULL && $json->$element) {
      return (string) $json->$element;
    }
  }

  /**
   * Load market info data.
   */
  public function getData() {
    $cid = 'market_info:info';
    $data = NULL;
    if ($cache = \Drupal::cache()->get($cid)) {
      $data = $cache->data;
    }
    else {

      $share_price_info = \Drupal::service('bhge_share_price.share_price_info.class');
      $stockInfo = $share_price_info->getFeed();
      $stockInfoChange = $this->getJsonElement($stockInfo, 'changeNumber');
      $stockInfoCount = $this->getJsonElement($stockInfo, 'lastTrade');

      $regions = ['usa', 'canada', 'international'];

      $data = [
        'rigCount' => [],
        'stockInfo' => [
          'count' => $stockInfoCount ? $stockInfoCount : 0,
          'change' => $stockInfoChange ? $stockInfoChange : 0,
          'changeDelta' => $this->commoditiesController->directionOfChange($stockInfoCount - $stockInfoChange),
        ],
        'commodities' => $this->commoditiesController->getData(),
      ];

      foreach ($regions as $region) {
        $data['rigCount'][$region] = [
          'count' => intval($this->state->get(sprintf('market_info_%s_rig_count', $region))) ? intval($this->state->get(sprintf('market_info_%s_rig_count', $region))) : 0,
          'changeLastWeek' => intval($this->state->get(sprintf('market_info_%s_change_from_last_week', $region))) ? intval($this->state->get(sprintf('market_info_%s_change_from_last_week', $region))) : 0,
          'changeLastWeekDelta' => intval($this->state->get(sprintf('market_info_%s_change_from_last_week_delta', $region))) ? intval($this->state->get(sprintf('market_info_%s_change_from_last_week_delta', $region))) : 0,
          'changeLastYear' => intval($this->state->get(sprintf('market_info_%s_change_from_last_year', $region))) ? intval($this->state->get(sprintf('market_info_%s_change_from_last_year', $region))) : 0,
          'changeLastYearDelta' => intval($this->state->get(sprintf('market_info_%s_change_from_last_year_delta', $region))) ? intval($this->state->get(sprintf('market_info_%s_change_from_last_year_delta', $region))) : 0,
        ];
      }

      \Drupal::cache()->set($cid, $data, 60);
    }

    return $this->responseWithData($data);
  }

  /**
   * Return data as json.
   */
  public function responseWithData($data) {
    $response = JsonResponse::create($data);

    // Set ttl to 30s.
    $response->setSharedMaxAge(30);
    $date = new \DateTime();
    $date->modify('+30 seconds');
    $response->setExpires($date);
    return $response;
  }

  /**
   * Return data as themed preview.
   */
  public function preview() {
    return [
      '#theme' => 'market_info_preview',
      '#info' => $this->getPreviewMarketInfo(),
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * Get preview data.
   */
  public function getPreviewMarketInfo() {
    $info = [];
    $regions = ['usa', 'canada', 'international'];

    foreach ($regions as $region) {
      $keys = [
        sprintf('market_info_%s_rig_count', $region),
        sprintf('market_info_%s_change_from_last_week', $region),
        sprintf('market_info_%s_change_from_last_week_delta', $region),
        sprintf('market_info_%s_change_from_last_year', $region),
        sprintf('market_info_%s_change_from_last_year_delta', $region),
      ];
      $regionInfo = [];

      foreach ($keys as $key) {
        $regionInfo[$key] = Xss::filter($this->request->get($key));
      }

      $info = array_merge($info, $regionInfo);
    }

    return array_merge($info, \Drupal::service('bhge_commodities_info.class')
      ->getData());
  }

}
