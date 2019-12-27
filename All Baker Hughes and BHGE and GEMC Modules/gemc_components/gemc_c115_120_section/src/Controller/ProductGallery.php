<?php

namespace Drupal\gemc_c115_120_section\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\gemc_c115_120_section\DataHelpers;
use Drupal\gemc_c115_120_section\ProductData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for product gallery endpoint.
 */
class ProductGallery extends ControllerBase {

  private $limitSection;

  private $limitSubsection;

  protected $request;

  public $route;

  public $productData;

  public $dataHelpers;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('current_route_match'),
      $container->get('gemc_c115_120_section.product_data'),
      $container->get('gemc_c115_120_section.gallery_data_helpers')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(Request $request, CurrentRouteMatch $route, ProductData $productData, DataHelpers $dataHelpers) {
    $this->request = $request;
    $this->route = $route;
    $this->productData = $productData;
    $this->dataHelpers = $dataHelpers;
    $this->limitSection = $productData::$limitSection;
    $this->limitSubsection = $productData::$limitSubsection;
  }

  /**
   * Load gallery data.
   */
  public function load() {

    $nid = $this->route->getParameter('nid');
    $offset = Xss::filter($this->request->query->get('offset'));
    $type = $this->dataHelpers->validateFilterType($this->request->query->get('contenttype'));
    $filterNid = $this->request->query->get('topic');
    switch ($type) {
      case 'section':
        $limit = $this->limitSection;
        $results = $this->productData->getSubsections($nid, $offset, $limit, FALSE, FALSE);
        break;

      case 'subsection':
        $limit = $this->limitSection;
        $results = $this->productData->getSubsections($filterNid, $offset, $limit, FALSE, TRUE, TRUE, $nid);
        if (!$results['items']) {
          // If no subsections, see if there are subproducts.
          $results = $this->productData->getSubproducts([$filterNid], $offset, $limit);
        }
        break;

      default:
        // Can only be subsection with products.
        $limit = $this->limitSubsection;
        $results = $this->productData->getSubproducts([$nid], $offset, $limit, FALSE);
        break;
    }

    $data['pagination'] = [
      'total' => (int) $results['total'],
      'offset' => (int) $offset,
      'limit' => (int) $limit,
    ];
    $data['data'] = $this->dataHelpers->prepareData($results, $offset, $limit);
    $data['statuscode'] = 200;

    return new JsonResponse($data);
  }

}
