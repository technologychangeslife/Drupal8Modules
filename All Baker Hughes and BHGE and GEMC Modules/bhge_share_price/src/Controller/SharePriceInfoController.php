<?php

namespace Drupal\bhge_share_price\Controller;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class SharePriceInfoController.
 *
 * @package Drupal\bhge_share_price\Controller
 */
class SharePriceInfoController extends ControllerBase {

  public $config;

  /**
   * The Create Function.
   *
   * @inheritdoc
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'));
  }

  /**
   * Config Factory.
   *
   * @inheritdoc
   */
  public function __construct($config_factory) {
    $this->config = $config_factory->get('bhge.stock_info_settings');
  }

  /**
   * Get info url.
   *
   * @return mixed
   *   url.
   */
  public function getSharePriceInfoUrl() {
    return $this->config->get('stock_info_url');
  }

  /**
   * Get json data from url.
   *
   * @return null|string
   *   json data.
   */
  protected function fetchJsonFromUrl() {
    if ($this->config->get('stock_info_url')) {
      $url = $this->config->get('stock_info_url');
      $client = new Client();
      try {
        $response = $client->request('GET', $url, ['timeout' => 15]);
        return $response->getBody()->getContents();
      }
      catch (RequestException $e) {
        return NULL;
      }
    }
  }

  /**
   * Get json file from disk.
   *
   * @return null|string
   *   Data from JSON file.
   */
  protected function fetchJsonFromDisk() {
    $feedFile = file_directory_temp() . '/market-info.json';
    if (is_file($feedFile)) {
      if ((filesize($feedFile) > 0) && (time() - filemtime($feedFile) <= 30)) {
        return file_get_contents($feedFile);
      }
    }

    $data = $this->fetchJsonFromUrl();
    if (JSON::decode($data)) {
      file_put_contents($feedFile, $data);
    }
    else {
      $data = NULL;
    }
    return $data;
  }

  /**
   * Get json feed from disk.
   *
   * @return mixed
   *   Returns the stock quote.
   */
  public function getFeed() {
    $data = $this->fetchJsonFromDisk();
    if ($data) {
      $feed = JSON::decode($data);
      foreach ($feed['data'] as $quote) {
        if ($quote['isDefault'] == 'true') {
          return (object) $quote;
        }
      }
    }
  }

}
