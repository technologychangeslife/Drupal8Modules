<?php

namespace Drupal\bhge_market_info\Extension;

/**
 * Create custom Twig marketinfo extentions.
 */
class MarketInfoExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'market_info_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('getInitMarketInfo', [
        $this,
        'getInitMarketInfo',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('addOrSub', [
        $this,
        'addOrSub',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('directionOfChange', [
        $this,
        'directionOfChange',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('signOfChange', [
        $this,
        'signOfChange',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Initial marketinfo.
   */
  public function getInitMarketInfo() {
    $marketInfo = \Drupal::service('bhge_market_info.market.info.class');
    return $marketInfo->init();
  }

  /**
   * More or less.
   *
   * @param int $subject1
   *   Last value.
   * @param int $subject2
   *   New value.
   * @param string $operand
   *   Switch.
   *
   * @return int
   *   difference.
   */
  public function addOrSub($subject1, $subject2, $operand) {
    switch ($operand) {
      case '1':
        return (int) $subject1 + (int) $subject2;

      case '2':
        return (int) $subject1 - (int) $subject2;

      default:
        return (int) $subject1;
    }
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
  public function signOfChange($delta) {
    return \Drupal::service('bhge_commodities_info.class')->signForChange($delta);
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
  public function directionOfChange($delta) {
    switch ($delta) {
      case '1':
        return 'up';

      case '2':
        return 'down';

      case '0':
        return 'even';

      default:
        return '';
    }
  }

}
