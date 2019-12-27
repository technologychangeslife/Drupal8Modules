<?php

namespace Drupal\bhge_rigcount\Extra;

use GuzzleHttp\Client;

/**
 * The RigCounter class.
 */
class RigCounter {
  /**
   * The entry url for the API Call.
   */
  const API_ENTRY_POINT = 'https://us.gis.connect.bakerhughes.com/RigServiceV4/PostRigWebSvc.asmx/GetSummaryRigCount';

  /**
   * The parameter for the security token.
   */
  const API_TOKEN_PARAM = 'T';

  /**
   * First key.
   *
   * @var null|string
   */
  protected $keyFirst = NULL;

  /**
   * Second key.
   *
   * @var null|string
   */
  protected $keySecond = NULL;

  /**
   * Data from rig counter webservice.
   *
   * @var null|RigCount[]
   */
  protected $data = NULL;

  /**
   * RigCounter constructor.
   */
  public function __construct() {
  }

  /**
   * Set the first key for the security token.
   *
   * @param string $key
   *   The first key.
   *
   * @return $this
   */
  public function setFirstKey($key) {
    $this->keyFirst = $key;
    return $this;
  }

  /**
   * Set the second key for the security token.
   *
   * @param string $key
   *   The second key.
   *
   * @return $this
   */
  public function setSecondKey($key) {
    $this->keySecond = $key;
    return $this;
  }

  /**
   * Get the PST date for security token.
   *
   * @return string
   *   Returns the date in d-M-Y format.
   */
  protected function getPstDate() {
    $date = new \DateTime("now", new \DateTimeZone('America/Los_Angeles'));
    return $date->format('d-M-Y');
  }

  /**
   * Generate the Security hash.
   *
   * @return string
   *   Returns the security token.
   */
  protected function generateSecurityToken() {
    return md5($this->keyFirst . $this->getPstDate() . $this->keySecond);
  }

  /**
   * Perform the http request.
   *
   * @return mixed|\Psr\Http\Message\ResponseInterface
   *   Returns the API response.
   */
  protected function perFormHttpRequest() {
    $client = new Client();
    $response = $client->request('GET', self::API_ENTRY_POINT, [
      'query' => [self::API_TOKEN_PARAM => $this->generateSecurityToken()],
    ]);
    return $response;
  }

  /**
   * Get the data from the webservice and transform it into XML.
   *
   * @param bool $forceRefresh
   *   Force refresh of the data from the server.
   *
   * @return \Drupal\bhge_rigcount\Extra\RigCount[]
   *   Returns exception or data.
   *
   * @throws \Exception
   */
  protected function getData($forceRefresh = FALSE) {
    if (NULL === $this->data || $forceRefresh) {
      $response = $this->perFormHttpRequest();
      $data = simplexml_load_string($response->getBody()->getContents());

      if (FALSE === $data) {
        throw new \Exception('Xml is not valid on the Rig counter webservice.');
      }
      else {
        $this->data = [];
        foreach ($data->regions->region as $region) {
          $this->data[] = new RigCount($region);
        }
        return $this->data;
      }
    }
    else {
      return $this->data;
    }
  }

  /**
   * Get the rig count for one or all zones.
   *
   * @param string $zone
   *   The zone variable.
   * @param bool $forceRefresh
   *   The force Refresh variable.
   *
   * @return \Drupal\bhge_rigcount\Extra\RigCount|RigCount[]
   *   Returns exception or region.
   *
   * @throws \Exception
   */
  public function getDataFor($zone = '', $forceRefresh = FALSE) {
    if ('' == $zone) {
      return $this->getData($forceRefresh);
    }
    else {
      /** @var \Drupal\bhge_rigcount\Extra\RigCount $region */
      foreach ($this->getData($forceRefresh) as $region) {
        if ($region->getArea() == $zone) {
          return $region;
        }
      }

      throw new \Exception('Zone could not be found.');
    }

  }

}
