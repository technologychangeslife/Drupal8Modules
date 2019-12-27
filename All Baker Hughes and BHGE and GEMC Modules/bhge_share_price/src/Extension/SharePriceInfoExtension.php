<?php

namespace Drupal\bhge_share_price\Extension;

/**
 * Class SharePriceInfoExtension.
 *
 * @package Drupal\bhge_share_price\Extension
 */
class SharePriceInfoExtension extends \Twig_Extension {

  /**
   * Get Name.
   *
   * @inheritdoc
   */
  public function getName() {
    return 'bhge_share_price_extension';
  }

  /**
   * Get Functions.
   *
   * @inheritdoc
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('getJsonElement', [
        $this,
        'getJsonElement',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getFeed', [
        $this,
        'getFeed',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getSharePriceChange', [
        $this,
        'getSharePriceChange',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('addJsonElements', [
        $this,
        'addJsonElements',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('trimZero', [
        $this,
        'trimZero',
      ], ['is_safe' => ['html']]),

    ];
  }

  /**
   * Add elements for json.
   *
   * @param double $element1
   *   Element one which needs to be added from JSON.
   * @param double $element2
   *   Element two which needs to be added from JSON.
   *
   * @return float
   *   Sum value.
   */
  public function addJsonElements(double $element1, double $element2) {
    return (double) $element1 + (double) $element2;
  }

  /**
   * Get the JSON Element function.
   *
   * @param Object json
   *   The JSON object.
   * @param string $element
   *   The JSON element.
   *
   * @return string
   *   Returns the JSON element.
   */
  public function getJsonElement(Object $json, $element) {
    if ($json->$element) {
      return (string) $json->$element;
    }
  }

  /**
   * Determine direction of change.
   *
   * @param double $jsonElement
   *   The JSON Element.
   * @param string $currency
   *   The currency.
   *
   * @return string
   *   Class.
   */
  public function getSharePriceChange(double $jsonElement, $currency) {
    $jsonElement = (double) $jsonElement;
    if ($currency == 'direction') {
      if ($jsonElement == 0) {
        return 'even';
      }
      if ($jsonElement < 0) {
        return 'down';
      }
      if ($jsonElement > 0) {
        return 'up';
      }
    }
    elseif ($currency == 'sign') {
      if ($jsonElement == 0) {
        return '';
      }
      if ($jsonElement > 0) {
        return 'plus';
      }
      if ($jsonElement < 0) {
        return 'min';
      }
    }
  }

  /**
   * Load data feed.
   *
   * @return mixed
   *   Returns the feed for share price info.
   */
  public function getFeed() {
    $share_price_info = \Drupal::service('bhge_share_price.share_price_info.class');
    return $share_price_info->getFeed();
  }

  /**
   * Trim zero.
   *
   * @param double $value
   *   The value.
   *
   * @return string
   *   Returns the value.
   */
  public function trimZero(double $value) {
    return ((double) $value < 0 && (double) $value > -1) ? '-' . substr($value, 2) : $value;
  }

}
