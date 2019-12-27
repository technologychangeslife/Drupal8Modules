<?php

namespace Drupal\gemc_product_listing_page\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\gemc_product_listing_page\ProductData;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for product gallery endpoint.
 */
class Products extends ControllerBase {

  const BASE_LIMIT = 12;

  private $request;
  private $productData;
  private $limit;
  private $offset;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('database'),
      $container->get('gemc_product_listing_page.product_data')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(Request $request, $connection, ProductData $productData) {
    $this->request = $request;
    $this->productData = $productData;
    $this->limit = Xss::filter($this->request->query->get('limit'));
    $this->offset = Xss::filter($this->request->query->get('offset'));
  }

  /**
   * Load Product Listing Page data.
   */
  public function init() {
    $limit = !empty($this->limit) ? $this->limit : self::BASE_LIMIT;
    $offset = !empty($this->offset) ? $this->offset : 0;
    $nextPage = $limit + $offset;

    $results = $this->productData->getProducts($limit, $offset);

    $selectedItem = $this->productData->getSelectedItem();

    // Get Filters.
    $filters = $this->productData->getFilters();

    $productListing = [
      '#theme' => 'gemc_product_listing_page',
      '#c114_dropdown' => [
        'title' => t('Solutions & Services'),
        'selectorTitle' => t('Show me solutions for'),
        'selectedItem' => $selectedItem,
        'dropdownCta' => [
          'label' => 'Show All Solutions',
          'href' => Url::fromRoute('gemc_product_listing_page.products')->toString(),
        ],
        'items' => $filters,
      ],
      '#c115_120_gallery' => [
        'scrollComponent' => TRUE,
        'items' => array_key_exists('items', $results) ? $results['items'] : [],
      ],
      '#c04_contact' => [
        'heading' => t('Would you like to learn more?'),
        'description' => t('Contact Us'),
        'href' => '/contact',
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    if ($results['count'] > $nextPage) {
      $apiQueryParameter = $this->productData->getApiQuery();
      $productListing['#c115_120_gallery']['loadMore'] = t('Load more');
      $productListing['#c115_120_gallery']['recentType'] = t('Load more');
      $productListing['#c115_120_gallery']['api'] = [
        'action' => '/api/v1/content-gallery?contenttype=product' . $apiQueryParameter,
        'pagination' => [
          'total' => (int) $results['count'],
          'offset' => (int) $offset,
          'limit' => (int) $limit,
        ],
      ];
    }

    return $productListing;

  }

}
