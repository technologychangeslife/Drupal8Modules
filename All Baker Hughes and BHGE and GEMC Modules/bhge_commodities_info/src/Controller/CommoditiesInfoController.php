<?php

namespace Drupal\bhge_commodities_info\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableMetadata;
use Scheb\YahooFinanceApi\ApiClient;

/**
 * Controller routines for Commodities information.
 */
class CommoditiesInfoController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function getData() {
    try {
      $yahooClient = new ApiClient();
      $data = [];
      $symbolConversion = [
        "NGQ17.NYM" => "naturalGas",
        "BZQ17.NYM" => "brent",
        "CLQ17.NYM" => "WTI",
      ];
      $stockInfo = $yahooClient->getQuotesList([
        "NGQ17.NYM",
        "BZQ17.NYM",
        "CLQ17.NYM",
      ]);

      if ($stockInfo['query'] && $stockInfo['query']['results'] && $stockInfo['query']['results']['quote']) {
        foreach ($stockInfo['query']['results']['quote'] as $info) {
          $change = (float) $info['Change'];

          $symbolConversion[$info['Symbol']] = strcasecmp('wti', $symbolConversion[$info['Symbol']]) ? $symbolConversion[$info['Symbol']] : strtolower($symbolConversion[$info['Symbol']]);

          $data[$symbolConversion[$info['Symbol']]] = [
            'count' => (float) $info['LastTradePriceOnly'],
            'changeDelta' => $this->directionOfChange($change),
            'change' => abs($change),
          ];
        }
      }
    }
    catch (\Exception $e) {
    }
    if (!count($data)) {
      $cache = \Drupal::cache()->get('bhge_commodities_info:commodities');
      if ($cache) {
        $data = $cache->data;
      }
      else {
        // Create a dummy of the datastructure to not break javascript.
        $data = [
          'naturalGas' => [
            'count' => '',
            'change' => '',
            'changeDelta' => '',
          ],
          'brent' => [
            'count' => '',
            'change' => '',
            'changeDelta' => '',
          ],
          'wti' => [
            'count' => '',
            'change' => '',
            'changeDelta' => '',
          ],
        ];
      }
    }
    else {
      \Drupal::cache()->set('bhge_commodities_info:commodities', $data);
    }
    return $data;
  }

  /**
   * Pos/neg change since last value.
   *
   * @param int $delta
   *   Difference since last.
   *
   * @return int
   *   Change direction.
   */
  public function directionOfChange($delta) {
    if ($delta > 0) {
      return 1;
    }
    elseif ($delta < 0) {
      return 2;
    }

    return 0;
  }

  /**
   * Pos/neg change since last value.
   *
   * @param int $delta
   *   Difference since last.
   *
   * @return string
   *   class name.
   */
  public function signForChange($delta) {
    if ($delta > 0) {
      return 'plus';
    }
    elseif ($delta < 0) {
      return 'min';
    }

    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getCachableDependency() {
    return CacheableMetadata::createFromRenderArray([
      '#cache' => [
        'contexts' => ['theme'],
        'tags' => ['commodities-info'],
        // TODO enable cache when dev is finished.
        'max-age' => 0,
      ],
    ]);
  }

}
