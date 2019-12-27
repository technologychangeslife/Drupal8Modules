<?php

namespace Drupal\bhge_career_search\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\ClientException;

/**
 * Controller routines for career search.
 */
class CareerSearch extends ControllerBase {

  const CACHE_BEARER_KEY = 'SEARCH_CAREER_API_BEARER_KEY';

  const DEFAULT_PAGE_SIZE = 25;

  protected $request;

  protected $httpClient;

  protected $cache;

  protected $cacheInvalidator;

  private $careerSearchApiOauthUrl;

  private $careerSearchApiUrl;

  private $careerSearchApiClientId;

  private $careerSearchApiClientSecret;

  private $retried = FALSE;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      \Drupal::request(),
      \Drupal::httpClient(),
      $container->get('config.factory'),
      \Drupal::cache(),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $httpClient, $config_factory, $cache, $cacheInvalidator) {
    $this->request = $request;
    $this->cache = $cache;
    $this->httpClient = $httpClient;
    $this->cacheInvalidator = $cacheInvalidator;

    $this->careerSearchApiOauthUrl = $config_factory->get('bhge.career_settings')->get('career_search_api_oauth_url');
    $this->careerSearchApiUrl = $config_factory->get('bhge.career_settings')->get('career_search_api_url');
    $this->careerSearchApiClientId = $config_factory->get('bhge.career_settings')->get('career_search_api_client_id');
    $this->careerSearchApiClientSecret = $config_factory->get('bhge.career_settings')->get('career_search_api_client_secret');
  }

  /**
   * Check for empty credentials.
   */
  protected function areCredentialsEmpty() {
    return empty($this->careerSearchApiOauthUrl) || empty($this->careerSearchApiUrl) ||
      empty($this->careerSearchApiClientId) || empty($this->careerSearchApiClientSecret);
  }

  /**
   * Get bearer token.
   */
  private function getBearerToken() {
    $cache = $this->cache->get(self::CACHE_BEARER_KEY);

    if ($cache) {
      return $cache->data;
    }
    $request = $this->httpClient->post($this->careerSearchApiOauthUrl, [
      'verify' => FALSE,
      'query' => [
        'grant_type' => 'client_credentials',
        'scope' => 'api',
        'client_id' => $this->careerSearchApiClientId,
        'client_secret' => $this->careerSearchApiClientSecret,
      ],
    ]);

    $response = json_decode($request->getBody()->getContents(), TRUE);
    $this->cache->set(self::CACHE_BEARER_KEY, $response['access_token'], time() + (int) $response['expires_in'], [self::CACHE_BEARER_KEY]);
    return $response['access_token'];
  }

  /**
   * Assemble json query.
   */
  private function getQuery() {
    $idOrKeyword = Xss::filter($this->request->get('keyword'));
    $country = Xss::filter($this->request->get('select-country'));
    $state = Xss::filter($this->request->get('select-state'));
    $experience = Xss::filter($this->request->get('select-experience'));
    $function = Xss::filter($this->request->get('select-function'));

    $q = ['(jobLink:(bakerhughes.taleo.net*) OR jobLink:(sjobs.brassring.com*) OR jobLink:(stgbakerhughes.taleo.net*))'];
    $q[] = '(business:"GE Oil & Gas" OR business:"Baker Hughes GE")';

    if ($idOrKeyword) {
      $q[] = sprintf('(jobNumber:"%s*" OR jobDescription:"%s*")', $idOrKeyword, $idOrKeyword);
    }
    if ($country) {
      $q[] = sprintf('openingLocation:"%s*"', $country);
    }
    if ($state) {
      $q[] = sprintf('state:"%s*"', $state);
    }
    if ($experience) {
      $q[] = sprintf('careerLevel:"%s*"', $experience);
    }
    if ($function) {
      $q[] = sprintf('function:("%s")', $function);
    }
    return implode(' AND ', $q);
  }

  /**
   * Search careers.
   *
   * @return array
   *   json response.
   */
  public function search() {

    if ($this->areCredentialsEmpty()) {
      throw new \Exception("You must configure the search api details (admin bhge settings page).");
    }

    $response = NULL;
    $q = $this->getQuery();

    if (!$this->retried) {
      try {
        $request = $this->httpClient->get($this->careerSearchApiUrl, [
          'verify' => FALSE,
          'headers' => [
            'Authorization' => 'Bearer ' . $this->getBearerToken(),
            'Content-Type' => 'application/x-www-form-urlencoded',
          ],
          'query' => [
            'q' => $q,
            'page' => $this->request->get('page') ? Xss::filter($this->request->get('page')) : 1,
            'pagesize' => $this->getPageSize(),
            'sortField' => 'lastUpdatedSort',
          ],
        ]);

        $response = json_decode($request->getBody()->getContents(), TRUE);
      }
      catch (ClientException $e) {
        if ($e->getCode() == 401) {
          $this->cacheInvalidator->invalidateTags([self::CACHE_BEARER_KEY]);
          $this->search();
          $this->retried = TRUE;
        }
      }
    }

    $page = $this->request->get('page') ? Xss::filter($this->request->get('page')) + 1 : 2;

    $nextPage = $this->formNextPageUrl($page);
    // Check if next page contains any data.
    // If not set nextPage to '' so button in FrontEnd will be disabled.
    if (($response['totalResultCount'] < self::DEFAULT_PAGE_SIZE * $page) && (self::DEFAULT_PAGE_SIZE * $page - $response['totalResultCount']) >= self::DEFAULT_PAGE_SIZE) {
      $nextPage = '';
    }

    $jsonResponse = new Response();
    $searchResults = $this->separateRequiredResponseData($response['data']);
    $jsonResponse->setContent(json_encode(
      [
        'title' => 'Search results',
        'counter' => sprintf('%d job%s Found', $response['totalResultCount'], $response['totalResultCount'] !== 1 ? 's' : ''),
        'loadMoreUrl' => $nextPage,
        'results' => array_reverse($searchResults),
      ])
    );
    $jsonResponse->headers->set('Content-Type', 'application/json');
    return $jsonResponse;
  }

  /**
   * Separate data from response to fit what is required in front end.
   *
   * @return array
   *   Job data.
   */
  public function separateRequiredResponseData($searchResults) {
    $returnData = [];
    foreach ($searchResults as $result) {
      $location = sprintf(
        '%s%s%s',
        $result['openingLocation'] ? $result['openingLocation'] . '; ' : '',
        $result['state'] ? $result['state'] . '; ' : '',
        $result['city'] ? $result['city'] : ''
      );
      $returnData[] = [
        'title' => $result['jobDescription'],
        'code' => $result['jobNumber'],
        'location' => $location,
        'type' => empty($result['careerLevel']) ? '' : $result['careerLevel'],
        'link' => $result['jobLink'],
      ];
    }
    return $returnData;
  }

  /**
   * Get size of page.
   */
  public function getPageSize() {
    return $this->request->get('pagesize') ? Xss::filter($this->request->get('pagesize')) : self::DEFAULT_PAGE_SIZE;
  }

  /**
   * Create pager link for next page.
   */
  public function formNextPageUrl($page) {

    $idOrKeyword = $this->request->get('keyword') ? '&keyword=' . Xss::filter($this->request->get('keyword')) : '';
    $country = $this->request->get('select-country') ? '&select-country=' . Xss::filter($this->request->get('select-country')) : '';
    $state = $this->request->get('select-state-province') ? '&select-state-province=' . Xss::filter($this->request->get('select-state-province')) : '';
    $experience = $this->request->get('select-experience') ? '&select-experience=' . Xss::filter($this->request->get('select-experience')) : '';
    $function = $this->request->get('select-function') ? '&select-function=' . Xss::filter($this->request->get('select-function')) : '';

    return '/career-search?page=' . $page . $idOrKeyword . $country . $state . $experience . $function;
  }

}
