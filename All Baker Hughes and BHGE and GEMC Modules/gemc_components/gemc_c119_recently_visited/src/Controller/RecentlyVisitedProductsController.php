<?php

namespace Drupal\gemc_c119_recently_visited\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller to get recently visited products.
 */
class RecentlyVisitedProductsController extends ControllerBase {

  /**
   * Entity query factory.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  private $entityQuery;

  /**
   * Field data service.
   *
   * @var \Drupal\gemc_components\FieldData\FieldDataService
   */
  private $fieldDataService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      $container->get('gemc_components.field_data_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($entityQuery, $entityTypeManager, $fieldDataService) {
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->fieldDataService = $fieldDataService;
  }

  /**
   * Get Recently visited products.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Current request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Recently visited products.
   */
  public function getResponse(Request $request) {
    $productIds = $request->get('ids');
    $currentId = $request->get('currentId');
    if (!is_null($productIds)) {
      $productIds = explode(',', $productIds);
    }
    else {
      $productIds = [];
    }
    $productIds = array_diff($productIds, [$currentId]);

    $limit = $request->get('limit');
    $productIds = array_slice($productIds, 0, $limit);

    $products = [];
    if (!empty($productIds)) {
      $products = $this->getProducts($productIds);
      $products = $this->prepareProducts($products);
    }

    $result = [
      'title' => $this->t('Recently visited products'),
      'data' => $products,
      'pagination' => [
        'total' => count($products),
        'offset' => 0,
        'limit' => count($products),
      ],
      'statusCode' => 200,
    ];

    return new JsonResponse($result);
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
        $productTag = '';
        if ($product->hasField('field_prod_tags') && $product->get('field_prod_tags')->entity) {
          $productTag = $product->get('field_prod_tags')->entity->getName();
        };

        $result = [
          'contentType' => 'image',
          'type' => !empty($productTag) ? $productTag : '',
          'title' => $product->getTitle(),
          'image' => $this->fieldDataService->getResponsiveImageData(['#items' => $product->get('field_image')], 'cards_carousel_image', 'cards_carousel_image'),
          'link' => [
            'url' => $product->url(),
            'label' => $product->getTitle(),
          ],
        ];
        $results[] = $result;
      }
    }

    return $results;
  }

}
