<?php

namespace Drupal\bhge_c55_recently_visited\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller routines for BHGE Recently Visited Products.
 */
class ProductRecentlyVisited extends ControllerBase {

  public $request;

  public $route;

  public $entityQuery;

  public $entityTypeManager;

  protected $dataHelpers;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('bhge_core.data_helpers')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityQuery, $entityTypeManager, $route, $dataHelpers) {
    $this->request = $request;
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->route = $route;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Get Recently visited products.
   *
   * @return Symfony\Component\HttpFoundation\Response
   *   Json response.
   */
  public function getRecentlyVisitedProducts() {
    $productIds = $this->request->get('ids');
    if (!is_null($productIds)) {
      $productIds = explode(',', $productIds);
    }
    else {
      $productIds = [];
    }

    $products = $this->getProducts($productIds);
    if (!empty($products)) {
      $products = $this->prepareProducts($products);
    }

    return $this->prepareJsonResponse($products);
  }

  /**
   * Get products from database based on ids.
   *
   * @param array $ids
   *   Array of product ids.
   *
   * @return array
   *   Array of product nodes.
   */
  private function getProducts(array $ids) {
    $productIds = $this->entityQuery->get('node')
      ->condition('type', 'product')
      ->condition('status', 1)
      ->condition('nid', $ids, 'in')
      ->execute();
    return $this->entityTypeManager->getStorage('node')
      ->loadMultiple($productIds);
  }

  /**
   * Prepare products for response.
   *
   * @param array $products
   *   Array of product nodes.
   *
   * @return array
   *   Structured product data.
   */
  private function prepareProducts(array $products) {
    $results = [];
    foreach ($products as $product) {
      if (!empty($product)) {

        if ($product->hasField('field_prod_tags') && $product->get('field_prod_tags')->entity) {
          $productTag = $product->get('field_prod_tags')->entity->getName();
        }

        $result = [
          'contentType' => 'text',
          'type' => !empty($productTag) ? $productTag : '',
          'title' => $product->getTitle(),
          'url' => $product->url(),
          'image' => '',
          'description' => '',
          'links' => [],
          'buttons' => [],
          'created' => '',
          'target' => '',
        ];

        if ($product->hasField('field_product_information') && !empty($product->get('field_product_information')[0])) {
          $links = [];
          $productInformation = $product->get('field_product_information')->entity;
          if (!empty($productInformation)) {
            $image = $this->dataHelpers->getImage($productInformation, 'field_image', 'field_dam_image', 'cards_carousel_image');
            $this->getCtaLink($productInformation, $links);
            $this->getDownloadCta($productInformation, $links);
          }

          $result['contentType'] = !empty($image) ? 'image' : 'text';
          $result['image'] = !empty($image) ? $image : '';
          $result['gradient'] = '';
          $result['description'] = !empty($productInformation) ? $this->dataHelpers->getDescription($productInformation) : '';
          $result['links'] = $links;
        }

        $results[] = $result;
      }
    }

    return $results;
  }

  /**
   * Prepare json output.
   *
   * @param array $products
   *   Array of structured product data.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Json response.
   */
  private function prepareJsonResponse(array $products) {
    $jsonResponse = new Response();
    $jsonResponse->setContent(json_encode(
        [
          'title' => 'Recently visited products',
          'data' => $products,
          'pagination' => [
            'total' => count($products),
            'offset' => 0,
            'limit' => count($products),
          ],
          'statusCode' => 200,
        ])
    );
    $jsonResponse->headers->set('Content-Type', 'application/json');
    return $jsonResponse;
  }

  /**
   * Get cta link from product information.
   *
   * @param object $productInformation
   *   Product information of product node.
   * @param array $links
   *   Links available on product node.
   */
  private function getCtaLink($productInformation, array &$links) {
    $cta = $productInformation->get('field_cta_link')[0];
    if ($cta && $cta->title && $cta->getUrl()) {
      $links[] = [
        'url' => $cta && $cta->getUrl() ? $cta->getUrl()->toString() : '',
        'title' => $cta && $cta->title ? $cta->title : '',
      ];
    }
  }

  /**
   * Get file url from product information.
   *
   * @param object $productInformation
   *   Product information of product node.
   * @param array $links
   *   Links available on product node.
   */
  private function getDownloadCta($productInformation, array &$links) {
    $cta = $productInformation->get('field_cta_download')->entity;
    if ($cta) {
      $links[] = [
        'url' => $cta ? $cta->url() : '',
        'title' => "Download",
      ];
    }
  }

}
