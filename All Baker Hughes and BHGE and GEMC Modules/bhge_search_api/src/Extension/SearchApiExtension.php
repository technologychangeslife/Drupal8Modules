<?php

namespace Drupal\bhge_search_api\Extension;

use Drupal\bhge_search_api\Controller\SearchApiController;
use Drupal\Component\Utility\Xss;

/**
 * Create custom Twig UI extentions.
 */
class SearchApiExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'search_api_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction(
        'getSearchTerm', [
          $this,
          'getSearchTerm',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'getAllSearchTerms', [
          $this,
          'getAllSearchTerms',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'allProductsAreBeingSearched', [
          $this,
          'allProductsAreBeingSearched',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'highlightSearchedWord', [
          $this,
          'highlightSearchedWord',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'addFacetToUrl', [
          $this,
          'addFacetToUrl',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'removeFacetInUrl', [
          $this,
          'removeFacetInUrl',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'addSortToUrl', [
          $this,
          'addSortToUrl',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'addPageToUrl', [
          $this,
          'addPageToUrl',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'checkFacetStatus', [
          $this,
          'checkFacetStatus',
        ], ['is_safe' => ['html']]
      ),
      new \Twig_SimpleFunction(
        'sanitizeSearchString', [
          $this,
          'sanitizeSearchString',
        ], ['is_safe' => ['html']]
      ),
    ];
  }

  /**
   * Replaces all spaces with hyphens and removes special chars.
   *
   * Return string.
   */
  public function sanitizeSearchString($string) {
    // $string = str_replace(' ', '', $string);.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
  }

  /**
   * Get search key from request.
   *
   * Return string.
   */
  public function getSearchTerm() {
    $srhstring = Xss::filter(\Drupal::request()->get('search'));
    $srhstring = $this->sanitizeSearchString($srhstring);
    return $srhstring;
  }

  /**
   * Get all query parameters from request.
   *
   * Return array.
   */
  public function getAllSearchTerms() {
    $queryString = \Drupal::request()->getQueryString();
    $queryString = $this->sanitizeSearchString($queryString);
    $queryString = urldecode($queryString);
    parse_str($queryString, $output);
    return $this->processSearchTerms($output);
  }

  /**
   * Check if all products are being searched.
   *
   * Return boolean.
   */
  public function allProductsAreBeingSearched() {
    $facets = \Drupal::request()->get('f');

    return !empty($facets) ? in_array('content_type:product', $facets) : '';
  }

  /**
   * Highlight searched word.
   *
   * Return string.
   */
  public function highlightSearchedWord($string) {
    $searchTerm = $this->getSearchTerm();
    return preg_replace("/\b" . $searchTerm . "\b/i", "<strong>\$0</strong>", $string);
  }

  /**
   * Add products and services facet to query parameters.
   *
   * Return string.
   */
  public function addFacetToUrl($facet = '') {
    $request = \Drupal::request();
    $allFacets = $request->get('f');
    if (!empty($facet)) {
      if (!is_null($allFacets)) {
        array_push($allFacets, $facet);
      }
      else {
        $allFacets[] = $facet;
      }
    }

    $searchQuery = [
      'f' => !empty($allFacets) ? array_unique($allFacets, SORT_REGULAR) : '',
      'search' => $request->get('search'),
      'sort_by-products' => $request->get('sort_by-products'),
      'sort_by-other-results' => $request->get('sort_by-other-results'),
      'sort_order-products' => $request->get('sort_order-products'),
      'sort_order-other-results' => $request->get('sort_order-other-results'),
      'p' => $request->get('p'),
    ];
    return '?' . http_build_query($searchQuery);
  }

  /**
   * Add Page to URL.
   */
  public function addPageToUrl($page, $pageQueryParameter) {
    $request = \Drupal::request();
    $allFacets = $request->get('f');

    $searchQuery = [
      'f' => !empty($allFacets) ? array_unique($allFacets, SORT_REGULAR) : '',
      'search' => $request->get('search'),
      'sort_by-products' => $request->get('sort_by-products'),
      'sort_by-other-results' => $request->get('sort_by-other-results'),
      'sort_order-products' => $request->get('sort_order-products'),
      'sort_order-other-results' => $request->get('sort_order-other-results'),
    ];
    if ($pageQueryParameter == 'page') {
      $searchQuery['page'] = $page;
      $searchQuery['p'] = $request->get('p');
    }
    else {
      $searchQuery['page'] = $request->get('page');
      $searchQuery['p'] = $page;
    }
    return '?' . http_build_query($searchQuery);
  }

  /**
   * Remove  facet to query parameters.
   *
   * Return string.
   */
  public function removeFacetInUrl($parameter) {
    $request = \Drupal::request();
    $allFacets = $request->get('f');

    foreach ($allFacets as $key => $facet) {
      if (strpos($facet, $parameter) !== FALSE) {
        unset($allFacets[$key]);
      }
    }

    $searchQuery = [
      'f' => $allFacets,
      'search' => $request->get('search'),
      'page' => $request->get('page'),
      'p' => $request->get('p'),
      'sort_by-products' => $request->get('sort_by-products'),
      'sort_by-other-results' => $request->get('sort_by-other-results'),
      'sort_order-products' => $request->get('sort_order-products'),
      'sort_order-other-results' => $request->get('sort_order-other-results'),
    ];
    return '?' . http_build_query($searchQuery);
  }

  /**
   * Add sort to query parameters.
   *
   * @param string $option
   *   Returns option.
   * @param string $result_type
   *   Returns result type.
   *
   * @return array
   *   Returns array or URLS.
   */
  public function addSortToUrl($option, $result_type = '') {
    $request = \Drupal::request();
    $order = $request->get('sort_order-' . $result_type);
    $sortby = $request->get('sort_by-' . $result_type);
    if (!empty($order)) {
      if ((strcmp($order, 'ASC') == 0) && (strcmp($sortby, $option) == 0)) {
        $order = 'DESC';
      }
      else {
        $order = 'ASC';
      }
    }
    else {
      $order = 'ASC';
    }

    $searchQuery = [
      'f' => $request->get('f'),
      'search' => $request->get('search'),
      'page' => $request->get('page'),
      'p' => $request->get('p'),
      'sort_by-' . $result_type => $option,
      'sort_order-' . $result_type => $order,
    ];

    if ($result_type == 'other-results') {
      $searchQuery['sort_by-products'] = $request->get('sort_by-products');
      $searchQuery['sort_order-products'] = $request->get('sort_order-products');
    }
    elseif ($result_type == 'products') {
      $searchQuery['sort_by-other-results'] = $request->get('sort_by-other-results');
      $searchQuery['sort_order-other-results'] = $request->get('sort_order-other-results');
    }
    elseif ($result_type == '') {
      // Mobile.
      $searchQuery['sort_by-products'] = $option;
      $searchQuery['sort_order-products'] = $order;
      $searchQuery['sort_by-other-results'] = $option;
      $searchQuery['sort_order-other-results'] = $order;
    }

    return [
      'url' => '?' . http_build_query($searchQuery),
      'order' => $order,
    ];
  }

  /**
   * Check if facet is active.
   *
   * Return boolean.
   */
  public function checkFacetStatus($facet) {
    $allFacets = \Drupal::request()->get('f');
    return !empty($allFacets) ? in_array($facet, $allFacets) : '';
  }

  /**
   * Preprocess search terms.
   */
  private function processSearchTerms($output) {
    $searchTerms = [];
    if (!empty($output['f'])) {
      foreach ($output['f'] as $parameter) {

        $queryTermExploded = explode(':', $parameter);
        if (array_key_exists(1, $queryTermExploded)) {
          if ($queryTermExploded[0] != 'content_type') {
            $searchTerms[] = [
              'name' => $queryTermExploded[1],
              'removeUrl' => $this->removeFacetInUrl($parameter),
            ];
          }
          else {
            $queryTerm = $queryTermExploded[1];
            $searchTerms[] = [
              'name' => SearchApiController::getContentTypeLabel($queryTerm),
              'removeUrl' => $this->removeFacetInUrl($parameter),
            ];
          }
        }
      }
    }
    return $searchTerms;
  }

}
