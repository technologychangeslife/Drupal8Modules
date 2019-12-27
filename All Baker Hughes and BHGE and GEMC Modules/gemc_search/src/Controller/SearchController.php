<?php

namespace Drupal\gemc_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\Query;
use Drupal\search_api\SearchApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SearchController.
 *
 * @package Drupal\gemc_search\Controller
 */
class SearchController extends ControllerBase {

  const SEARCH_LIMIT = 10;

  /**
   * Current request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * Search helpers.
   *
   * @var \Drupal\gemc_search\SearchHelpers
   */
  private $searchHelpers;

  /**
   * Search term from current request.
   *
   * @var string
   */
  private $searchTerm;

  /**
   * Search page number from current request.
   *
   * @var int
   */
  private $searchPage;

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $searchHelpers, $searchTerm, $searchPage) {
    $this->request = $request;
    $this->searchHelpers = $searchHelpers;
    $this->searchTerm = $searchTerm;
    $this->searchPage = !empty($searchPage) && is_numeric($searchPage) ? $searchPage : 0;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $request = $container->get('request_stack')->getCurrentRequest();
    return new static(
      $request,
      $container->get('gemc_search.search_helpers'),
      $request->get('search'),
      $request->get('page')
    );
  }

  /**
   * Initial function for search getting all results.
   *
   * @return array
   *   Returns the theme information with data and cache.
   */
  public function search() {
    try {
      $data = $this->getSearchResults();
      $resultsFoundLabel = $data['count'] . sprintf(' result%s found', $data['count'] != 1 ? 's' : '');
      $data['header'] = [
        'title' => $this->t('Search results for'),
        'keyword' => $this->searchTerm,
        'resultIndicator' => t('@resultsfoundlable', ['@resultsfoundlable' => $resultsFoundLabel]),
      ];
    }
    catch (SearchApiException $e) {
      $data['header'] = [
        'title' => $this->t('Search is temporary unavailable.'),
        'resultIndicator' => $e->getMessage(),
      ];
    }

    return [
      '#theme' => 'gemc_search',
      '#data' => $data,
      '#c04_contact' => [
        'heading' => t('Would you like to learn more?'),
        'description' => t('Contact Us'),
        'href' => '/contact',
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * Retrieving search results.
   *
   * @return array
   *   Returns the search result and pagination information.
   *
   * @throws \Drupal\search_api\SearchApiException
   */
  private function getSearchResults() {
    $searchIndex = $this->searchHelpers->getSearchIndex();
    $searchResults = [];
    $resultCount = 0;
    $totalPages = 0;

    if ($searchIndex && !empty($this->searchTerm)) {
      /** @var \Drupal\search_api\Query\Query $query */
      $query = Index::load($searchIndex->id())->query();

      $s_term = preg_replace('/\s+/', '', $this->searchTerm);
      if (stripos($s_term, 'system1') !== FALSE) {
        // Change the parse mode for the search.
        $parse_mode = \Drupal::service('plugin.manager.search_api.parse_mode')
          ->createInstance('phrase');
        $query->setParseMode($parse_mode);
      }
      $query->addCondition('status', 'true');
      $this->applySearchKey($query);
      $query->range(!empty($this->searchPage) ? $this->searchPage * self::SEARCH_LIMIT : 0, self::SEARCH_LIMIT);

      $data = $query->execute();
      $results = $data->getResultItems();

      $resultCount = $data->getResultCount();
      $totalPages = ceil($resultCount / self::SEARCH_LIMIT);
      $searchResults = $this->searchHelpers->parseResults($results);
    }

    return [
      'items' => $searchResults,
      'count' => $resultCount,
      'pager' => $this->getPager($totalPages, $this->searchPage),
    ];
  }

  /**
   * Get pager for search results.
   *
   * @return array
   *   Returns the pagination varibles and information.
   */
  private function getPager($totalPages, $pager) {
    if ($totalPages > 1) {
      $currentPage = $pager ? $pager : 0;
      return [
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
        'previousPage' => $currentPage - 1,
        'nextPage' => $currentPage + 1,
      ];
    }
  }

  /**
   * Apply search key to query.
   *
   * @param \Drupal\search_api\Query\Query $query
   *   Search api query.
   */
  private function applySearchKey(Query &$query) {
    if (!empty($this->searchTerm)) {
      $query->keys($this->searchTerm);
    }
  }

}
