<?php

namespace Drupal\bhge_market_info;

use Drupal\bhge_commodities_info\Controller\CommoditiesInfoController;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\State\StateInterface;
use Drupal\bhge_share_price\Controller\SharePriceInfoController;

/**
 * Market info like stock levels.
 */
class MarketInfoService {

  /**
   * The Commodities Info controller.
   *
   * @var \Drupal\bhge_commodities_info\Controller\CommoditiesInfoController
   */
  protected $commoditiesController;

  /**
   * The StateInterface.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The CacheBackendInterface.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * MarketInfoService constructor.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The StateInterface.
   * @param \Drupal\bhge_commodities_info\Controller\CommoditiesInfoController $commoditiesController
   *   The CommoditiesInfoController.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cacheBackend
   *   The CacheBackendInterface.
   * @param \Drupal\bhge_share_price\Controller\SharePriceInfoController $sharePriceInfoController
   *   The SharePriceInfoController.
   */
  public function __construct(StateInterface $state, CommoditiesInfoController $commoditiesController, CacheBackendInterface $cacheBackend, SharePriceInfoController $sharePriceInfoController) {
    $this->state = $state;
    $this->commoditiesController = $commoditiesController;
    $this->cache = $cacheBackend;
    $this->sharePriceInfo = $sharePriceInfoController;
  }

  /**
   * Get element from json data.
   *
   * @param object $json
   *   JSON data.
   * @param string $element
   *   Key of element.
   *
   * @return string
   *   Element value.
   */
  public function getJsonElement($json, $element) {
    if ($json !== NULL && $json->$element) {
      return (string) $json->$element;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $cid = 'market_info:info';
    $data = NULL;
    if ($cache = $this->cache->get($cid)) {
      $data = $cache->data;
    }
    else {

      $stockInfo = $this->sharePriceInfo->getFeed();
      $stockInfoChange = $this->getJsonElement($stockInfo, 'changeNumber');
      $stockInfoCount = $this->getJsonElement($stockInfo, 'lastTrade');

      $regions = ['usa', 'canada', 'international'];

      $data = [
        'rigCount' => [],
        'stockInfo' => [
          'count' => number_format(($stockInfoCount ? $stockInfoCount : 0), 2),
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

      $this->cache->set($cid, $data, 60);
    }

    return $data;
  }

}
