<?php

namespace Drupal\bhge_market_info\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for market information.
 */
class MarketInfoPreview extends ControllerBase {

  public $state;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('state'));
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public function init() {
    return [
      '#info' => $this->getMarketInfo(),
      '#cache' => ['max-age' => 360],
    ];
  }

  /**
   * Get marketinfo.
   *
   * @return array
   *   Market info.
   */
  public function getMarketInfo() {
    return array_merge([
      'market_info_usa_rig_count' => $this->state->get('market_info_usa_rig_count'),
      'market_info_usa_change_from_last_week' => $this->state->get('market_info_usa_change_from_last_week'),
      'market_info_usa_change_from_last_week_delta' => $this->state->get('market_info_usa_change_from_last_week_delta'),
      'market_info_canada_rig_count' => $this->state->get('market_info_canada_rig_count'),
      'market_info_canada_change_from_last_week' => $this->state->get('market_info_canada_change_from_last_week'),
      'market_info_canada_change_from_last_week_delta' => $this->state->get('market_info_canada_change_from_last_week_delta'),
      'market_info_international_rig_count' => $this->state->get('market_info_international_rig_count'),
      'market_info_international_change_from_last_week' => $this->state->get('market_info_international_change_from_last_week'),
      'market_info_international_change_from_last_week_delta' => $this->state->get('market_info_international_change_from_last_week_delta'),
      'market_info_usa_change_from_last_year' => $this->state->get('market_info_usa_change_from_last_year'),
      'market_info_usa_change_from_last_year_delta' => $this->state->get('market_info_usa_change_from_last_year_delta'),
      'market_info_canada_change_from_last_year' => $this->state->get('market_info_canada_change_from_last_year'),
      'market_info_canada_change_from_last_year_delta' => $this->state->get('market_info_canada_change_from_last_year_delta'),
      'market_info_international_change_from_last_year_delta' => $this->state->get('market_info_international_change_from_last_year_delta'),
      'market_info_international_change_from_last_year' => $this->state->get('market_info_international_change_from_last_year'),
      'stock_info_bhi_value' => $this->state->get('stock_info_bhi_value'),
      'stock_info_bhi_value_change' => $this->state->get('stock_info_bhi_value_change'),
      'stock_info_bhi_value_change_delta' => $this->state->get('stock_info_bhi_value_change_delta'),
    ], \Drupal::service('bhge_commodities_info.class')->getData());
  }

}
