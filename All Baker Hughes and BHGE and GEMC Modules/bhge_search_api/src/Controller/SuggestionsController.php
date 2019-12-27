<?php

namespace Drupal\bhge_search_api\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\search_api\Entity\Index;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SearchSuggestions.
 *
 * @package Drupal\bhge_search_api\Controller
 */
class SuggestionsController extends ControllerBase {

  protected $request;
  protected $searchHelpers;
  protected $searchTerm;

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $searchHelpers) {
    $this->request = $request;
    $this->searchHelpers = $searchHelpers;
    $this->searchTerm = Xss::filter($this->request->get('q'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('bhge_search_api.search_helpers')
    );
  }

  /**
   * The search term function.
   *
   * @return string
   *   Returns the search term.
   */
  public function getSearchTerm() {
    return $this->searchTerm;
  }

  /**
   * Get suggestion results.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Returning the symfony response.
   */
  public function getSuggestions() {
    $suggestions = $this->getSuggestionResults();

    $jsonResponse = new Response();
    $jsonResponse->setContent(json_encode($suggestions));
    $jsonResponse->headers->set('Content-Type', 'application/json');
    $jsonResponse->headers->set('X-Drupal-Cache-Tags', $this->getSearchTerm());
    return $jsonResponse;
  }

  /**
   * Getting suggestions based on search term.
   *
   * @return array
   *   Returning the formatted array.
   */
  private function getSuggestionResults() {
    $searchIndex = $this->searchHelpers->getSearchIndex();
    $searchResults = [];
    if ($searchIndex && !empty($this->getSearchTerm())) {
      $query = Index::load($searchIndex->id())->query();
      $query->addCondition('status', 'true');
      $query->keys($this->getSearchTerm());
      $query->setFulltextFields(['title']);

      $data = $query->execute();
      $results = $data->getResultItems();
      $searchResults = $this->parseResults($results);
    }
    return $searchResults;
  }

  /**
   * Parsing results to array.
   *
   * @param array $results
   *   Passing the results from query.
   *
   * @return array
   *   Returning the formatted array.
   */
  private function parseResults(array $results) {
    $list = [];
    $searchKey = strtolower($this->getSearchTerm());
    $pattern = '*' . $searchKey . '*';

    foreach ($results as $item) {
      $result = $item->getFields();
      $title = strtolower($this->searchHelpers->getTitle($result));
      if (strpos($title, $searchKey) !== FALSE) {
        $titleExploded = explode(' ', trim($title));
        $array = array_filter($titleExploded, function ($entry) use ($pattern) {
          return fnmatch($pattern, $entry);
        });

        foreach ($array as $item) {
          $list[] = [
            'label' => $item,
            'value' => $item,
          ];
        }
      }
    }
    return array_values(array_unique($list, SORT_REGULAR));
  }

}
