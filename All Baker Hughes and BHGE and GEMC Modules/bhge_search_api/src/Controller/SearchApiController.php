<?php

namespace Drupal\bhge_search_api\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SearchApiController.
 *
 * @package Drupal\bhge_search_api\Controller
 */
class SearchApiController extends ControllerBase {

  const PRODUCT = 'Product & Services';

  const DOCUMENT = 'Documents';

  const SECTION = "Product Categories";

  const PAGE = "Pages";

  const NEWSITEM = "News";

  const EVENT = "Events";

  const ARTICLE = "Articles";

  const EVENTSESSION = "Event Sessions";

  const BLOG = "Blog Posts";

  const WEBCASTS = "Webcasts";

  const PERSON = "Persons";

  const CASESTUDY = "Case Study";

  /**
   * Query limit.
   *
   * @var PRODUCT_LIMIT
   */
  const PRODUCT_LIMIT = 6;

  const SEARCH_LIMIT = 9;

  protected $request;

  protected $facets;

  protected $searchHelpers;

  protected $searchTerm;

  protected $requestFacets;

  protected $productPage;

  protected $searchPage;

  protected $cache;

  protected $internalSiteConfig;

  protected $sortFieldProducts;

  protected $sortFieldOther;

  protected $sortOrderProducts;

  protected $sortOrderOther;

  protected $baseUrl;

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $searchHelpers, $cache, $configFactory, $searchTerm, $requestFacets, $productPage, $searchPage, $sortFieldProducts, $sortOrderProducts, $sortFieldOther, $sortOrderOther) {

    global $base_url;

    $this->baseUrl = preg_replace('#^https?://#', '', rtrim($base_url, '/'));
    $this->request = $request;
    $this->searchHelpers = $searchHelpers;
    $this->cache = $cache;
    $this->internalSiteConfig = $configFactory->get('config_split.config_split.internal_site');
    $this->searchTerm = !empty($searchTerm) ? Xss::filter($searchTerm) : '';
    $this->requestFacets = !empty($requestFacets) ? Xss::filter($requestFacets) : [];
    $this->productPage = !empty($productPage) ? Xss::filter($productPage) : 0;
    $this->searchPage = !empty($searchPage) ? Xss::filter($searchPage) : 0;
    $this->sortFieldProducts = !empty($sortFieldProducts) ? Xss::filter($sortFieldProducts) : '';
    $this->sortFieldOther = !empty($sortFieldOther) ? Xss::filter($sortFieldOther) : '';
    $this->sortOrderProducts = !empty($sortOrderProducts) ? Xss::filter($sortOrderProducts) : '';
    $this->sortOrderOther = !empty($sortOrderOther) ? Xss::filter($sortOrderOther) : '';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $request = $container->get('request_stack')->getCurrentRequest();
    return new static(
      $request,
      $container->get('bhge_search_api.search_helpers'),
      $container->get('cache.entity'),
      $container->get('config.factory'),
      $request->get('search'),
      $request->get('f'),
      $request->get('p'),
      $request->get('page'),
      $request->get('sort_by-products'),
      $request->get('sort_order-products'),
      $request->get('sort_by-other-results'),
      $request->get('sort_order-other-results')
    );
  }

  /**
   * Function to get the search term.
   *
   * @return string
   *   Returns the search term.
   */
  public function getSearchTerm() {
    return $this->searchTerm;
  }

  /**
   * Function to request facets.
   *
   * @return string
   *   Returns request facets.
   */
  public function getRequestFacets() {
    return $this->requestFacets;
  }

  /**
   * Function to return Product Page.
   *
   * @return string
   *   Returns product page.
   */
  public function getProductPage() {
    return $this->productPage;
  }

  /**
   * Function to return search page.
   *
   * @return string
   *   Returns search page.
   */
  public function getSearchPage() {
    return $this->searchPage;
  }

  /**
   * Function Returns Cache.
   *
   * @return mixed
   *   Returns Cache.
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * Function returns Internal site config.
   *
   * @return mixed
   *   Returns Internal site config.
   */
  public function getInternalSiteConfig() {
    return $this->internalSiteConfig;
  }

  /**
   * Function to sort field products.
   *
   * @return string
   *   Returns sort field products.
   */
  public function getSortFieldProducts() {
    return $this->sortFieldProducts;
  }

  /**
   * Function sort field other.
   *
   * @return string
   *   Returns sort field other.
   */
  public function getSortFieldOther() {
    return $this->sortFieldOther;
  }

  /**
   * Function to get sort order products.
   *
   * @return string
   *   Returns sort order products.
   */
  public function getSortOrderProducts() {
    return $this->sortOrderProducts;
  }

  /**
   * Function to sort order other.
   *
   * @return string
   *   Returns sort order other.
   */
  public function getSortOrderOther() {
    return $this->sortOrderOther;
  }

  /**
   * Initial function for search getting all results.
   *
   * @return array
   *   Returns theme information and variables.
   */
  public function search() {

    $theme = [
      '#theme' => 'bhge_search',
      '#productsAndServices' => $this->getProductsAndServices(),
      '#searchResults' => $this->getSearchResults(),
      '#facets' => $this->getFacets(),
      '#totalResultCount' => $this->getTotalResultCount(),
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    return $theme;
  }

  /**
   * Retrieving search results.
   *
   * @return array
   *   Returns search result and parameter.
   */
  private function getSearchResults() {
    $searchIndex = $this->searchHelpers->getSearchIndex();
    $searchResults = [];
    $resultCount = 0;
    $totalPages = 0;

    if ($searchIndex) {
      /** @var \Drupal\search_api\Query\Query $query */
      $query = Index::load($searchIndex->id())->query();
      $this->applySort($query, 'other-results');
      $query->addCondition('type', 'product', '<>');
      $this->applyFacets($query);
      $this->filterByPrivacy($query);
      $query->addCondition('status', 'true');
      $this->applySearchKey($query);
      $query->setOption('bq', 'base_site_url:"' . $this->baseUrl . '"^4');
      $query->range(!empty($this->getSearchPage()) && is_numeric($this->getSearchPage()) ? $this->getSearchPage() * self::SEARCH_LIMIT : 0, self::SEARCH_LIMIT);

      $data = $query->execute();
      $results = $data->getResultItems();
      $resultCount = $data->getResultCount();
      $totalPages = ceil($resultCount / self::SEARCH_LIMIT);
      $searchResults = $this->parseResults($results);
    }
    return [
      'results' => $searchResults,
      'count' => $resultCount,
      'pager' => $this->getPager($totalPages, $this->getSearchPage(), self::SEARCH_LIMIT),
    ];
  }

  /**
   * Retrieving products and services.
   *
   * @return array
   *   Returns product and services.
   */
  private function getProductsAndServices() {
    $searchIndex = $this->searchHelpers->getSearchIndex();
    $productsAndServices = [];
    $resultCount = 0;
    $totalPages = 0;

    if ($searchIndex) {

      /** @var \Drupal\search_api\Query\Query $query */
      $query = Index::load($searchIndex->id())->query();
      $this->applySort($query, 'products');
      $query->addCondition('type', 'product');
      $this->applyFacets($query, FALSE);
      $this->filterByPrivacy($query);
      $query->addCondition('field_active', 'true');
      $query->addCondition('status', 'true');
      $query->setOption('bq', 'base_site_url:"' . $this->baseUrl . '"^4');
      $this->applySearchKey($query);
      $query->range(!empty($this->getProductPage()) && is_numeric($this->getProductPage()) ? $this->getProductPage() * self::PRODUCT_LIMIT : 0, self::PRODUCT_LIMIT);
      $data = $query->execute();
      $results = $data->getResultItems();
      $resultCount = $data->getResultCount();

      if (!empty($resultCount)) {
        $this->facets['content_type']['product'] = [
          'label' => t('Products & Services'),
          'count' => $resultCount,
        ];
      }

      $totalPages = ceil($resultCount / self::PRODUCT_LIMIT);
      $productsAndServices = $this->parseResults($results);
    }
    return [
      'results' => $productsAndServices,
      'count' => $resultCount,
      'pager' => $this->getPager($totalPages, $this->getProductPage(), self::PRODUCT_LIMIT),
    ];
  }

  /**
   * Get pager for search results.
   *
   * @return array
   *   Returns pagination information.
   */
  private function getPager($totalPages, $pager, $limit) {
    $currentPage = $pager ? $pager : 0;
    return [
      'totalPages' => $totalPages,
      'itemsPerPage' => $limit,
      'currentPage' => $currentPage,
      'previousPage' => $currentPage - 1,
      'nextPage' => $currentPage + 1,
    ];
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

    foreach ($results as $item) {
      $result = $item->getFields();
      $url = $this->searchHelpers->getUrl($result);
      $list[] = [
        'url' => $url,
        'title' => $this->searchHelpers->getTitle($result),
        'image' => $this->searchHelpers->getImage($result),
        'copy' => $this->searchHelpers->getCopy($result),
        'content_type' => !empty($result['type']->getValues()) ? $result['type']->getValues()[0] : '',
        'category' => $this->searchHelpers->getCategory($result),
        'download_link' => $this->searchHelpers->getDownloadLink($result),
        'cta_link' => $this->searchHelpers->getCtaLink($result),
        'file' => $this->searchHelpers->getFileData($result),
        'created' => format_date($result['created']->getValues()[0], '', $format = 'F j, Y', $timezone = NULL, $langcode = NULL),
        'event_details' => $this->searchHelpers->getEventDetails($result),
      ];
    }

    return $list;
  }

  /**
   * Apply search key to query.
   *
   * @param \Drupal\search_api\Query\Query $query
   *   Search api query.
   */
  private function applySearchKey(Query &$query) {
    if (!empty($this->getSearchTerm())) {
      $query->keys($this->getSearchTerm());
    }
  }

  /**
   * Check site privacy.
   *
   * @param \Drupal\search_api\Query\Query $query
   *   Search api query.
   */
  private function filterByPrivacy(Query &$query) {
    if (empty($this->getInternalSiteConfig()->get('status'))) {
      $query->addCondition('privacy', 'public');
    }
  }

  /**
   * Apply facets in request to search query.
   *
   * @param \Drupal\search_api\Query\Query $query
   *   Search api query.
   * @param bool $filterContentTypes
   *   Parameter deciding if content types should be applied to query.
   */
  private function applyFacets(Query &$query, $filterContentTypes = TRUE) {
    if (is_array($this->getRequestFacets())) {
      $content_types = [];
      $topics = [];
      $search_tags = [];
      $authors = [];
      $product_tags = [];
      $categories = [];

      foreach ($this->getRequestFacets() as $facet) {
        list($type, $value) = explode(':', $facet);
        switch ($type) {
          case 'content_type':
            $content_types[] = $value;
            break;

          case 'topic':
            $topics[] = $value;
            break;

          case 'search_tag':
            $search_tags[] = $value;
            break;

          case 'author_title':
            $authors[] = $value;
            break;

          case 'product_tag':
            $product_tags[] = $value;
            break;

          case 'category':
            $categories[] = $value;
            break;
        }
      }
      if ($filterContentTypes && !empty($content_types)) {
        $query->addCondition('type', $content_types);
      }
      if (!empty($topics)) {
        $query->addCondition('topic_name', $topics, 'IN');
      }
      if (!empty($search_tags)) {
        $query->addCondition('search_tag_name', $search_tags, 'IN');
      }
      if (!empty($authors)) {
        $query->addCondition('author_title', $authors, 'IN');
      }
      if (!empty($product_tags)) {
        $query->addCondition('product_tag', $product_tags, 'IN');
      }
      if (!empty($categories)) {
        $query->addCondition('field_category_name', $categories, 'IN');
      }
    }
  }

  /**
   * Apply sort to query.
   */
  private function applySort(&$query, $result_type) {
    $sortField = Xss::filter($this->request->get('sort_by-' . $result_type));
    $sortOrder = Xss::filter($this->request->get('sort_order-' . $result_type));
    if (!empty($sortField)) {
      if (!empty($sortOrder)) {
        $query->sort($sortField, $sortOrder);
      }
      else {
        $query->sort($sortField);
      }
    }
  }

  /**
   * Get all result count.
   *
   * @return int
   *   Returns the total result count.
   */
  private function getTotalResultCount() {
    $contentTypeFacets = $this->facets['content_type'];
    $count = 0;
    if (!empty($contentTypeFacets)) {
      foreach ($contentTypeFacets as $facet) {
        $count += $facet['count'];
      }
    }
    return $count;
  }

  /**
   * Retrieve content type labels.
   *
   * @param string $ctype
   *   The content type string.
   *
   * @return string
   *   Returns content type.
   */
  public static function getContentTypeLabel($ctype) {
    switch ($ctype) {
      case 'product':
        return self::PRODUCT;

      break;
      case 'document':
        return self::DOCUMENT;

      break;
      case 'section':
        return self::SECTION;

      break;
      case 'page':
        return self::PAGE;

      break;
      case 'news_item':
        return self::NEWSITEM;

      break;
      case 'event':
        return self::EVENT;

      break;
      case 'event_item':
        return self::EVENTSESSION;

      break;
      case 'article':
        return self::ARTICLE;

      break;
      case 'blog_post':
        return self::BLOG;

      break;
      case 'webcast_item':
        return self::WEBCASTS;

      break;
      case 'person':
        return self::PERSON;

      case 'case_study_summary':
        return self::CASESTUDY;

      break;
    }
  }

  /**
   * Storing facets based on results in protected variable.
   */
  private function getFacets() {

    $facets = [];
    $pattern = 'content_type:*';
    // Array containing list of active content types.
    $active_content_types = [];

    // Generate unique cache hash based on facets and search term.
    // $cacheIdentifier =
    // implode(" ", $this->getRequestFacets()) . $this->getSearchTerm();.
    // $cacheHash = md5($cacheIdentifier);.
    if (is_array($this->getRequestFacets())) {
      $active_content_types = array_filter($this->getRequestFacets(), function ($entry) use ($pattern) {
        return fnmatch($pattern, $entry);
      });
    }

    $searchIndex = $this->searchHelpers->getSearchIndex();
    $searchIndexId = $searchIndex->id();
    $server = $searchIndex->getServerInstance();

    if ($searchIndex) {

      /** @var \Drupal\search_api\Query\Query $query */

      // Get the index.
      $query = Index::load($searchIndexId)->query();

      // Apply search key.
      $this->applySearchKey($query);

      // Apply privacy.
      $this->filterByPrivacy($query);

      // Filter by status.
      $query->addCondition('status', 'true');

      // If content type facet is active.
      // apply it and load rest of the facets just for that content type.
      if ($server->supportsFeature('search_api_facets')) {

        if (!empty($active_content_types)) {

          $this->applyFacets($query);
          $query->setOption('search_api_facets', [
            'type' => [
              'field' => 'type',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
            'author_title' => [
              'field' => 'author_title',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
            'category' => [
              'field' => 'field_category_name',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
            'search_tag' => [
              'field' => 'search_tag_name',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
            'topic' => [
              'field' => 'topic_name',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
            'product_tag' => [
              'field' => 'product_tag',
              'limit' => 30,
              'operator' => 'OR',
              'min_count' => 1,
              'missing' => FALSE,
            ],
          ]);
        }
        else {
          $query->setOption('search_api_facets', [
            'type' => [
              'field' => 'type',
              'limit' => 30,
              'operator' => 'AND',
              'min_count' => 1,
              'missing' => FALSE,
            ],
          ]
          );
        }
      }

      $data = $query->execute();
      $results = $data->getExtraData('search_api_facets', []);

      if (!empty($results)) {
        foreach ($results as $key => $result) {
          switch ($key) {
            case 'type':
              foreach ($result as $ctype) {
                $ctypeId = str_replace('"', '', $ctype['filter']);
                $facets['content_type'][$ctypeId] = [
                  'label' => $this->getContentTypeLabel($ctypeId),
                  'count' => $ctype['count'],
                ];
              }
              break;

            case 'category':
            case 'search_tag':
            case 'topic':
            case 'product_tag':
            case 'author_title':
              if (!empty($active_content_types)) {
                foreach ($result as $element) {
                  $elementLabel = str_replace('"', '', $element['filter']);
                  $facets[$key][$elementLabel] = [
                    'label' => $elementLabel,
                    'count' => $element['count'],
                  ];
                }
              }
              break;
          }
        }
      }

    }

    $this->facets = $facets;
    return $facets;
  }

}
