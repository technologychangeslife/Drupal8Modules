<?php

namespace Drupal\bhge_c55_product_gallery\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\bhge_c55_product_gallery\DataHelpers;
use Drupal\bhge_c55_product_gallery\ProductData;
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
      $container->get('bhge_c55_product_gallery.product_data'),
      $container->get('bhge_c55_product_gallery.gallery_data_helpers')
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
   * {@inheritdoc}
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Load gallery data.
   */
  public function load() {

    $nid = $this->route->getParameter('nid');
    $offset = Xss::filter($this->request->query->get('offset'));
    list($type, $filterNid) = array_pad(explode('_', $this->request->query->get('contenttype')), 2, NULL);
    if (empty($filterNid)) {
      $topic = explode('_', $this->request->query->get('topic'));
      if (count($topic) == 1 && $topic[0] != '') {
        $filterNid = $topic[0];
      }
      elseif (count($topic) > 1) {
        list($type, $filterNid) = $topic;
      }
    }
    $type = $this->dataHelpers->validateFilterType($type);

    switch ($type) {
      case 'highlights':
        $limit = $this->limitSection;
        $allFilters = $this->productData->getFilters($nid);
        $data = $this->productData->getSubproducts($allFilters['nids'], $offset, $limit, TRUE, FALSE);
        break;

      case 'mainsection':
        $limit = $this->limitSection;
        $data = $this->productData->getSubsections($nid, $offset, $limit, FALSE, FALSE);
        break;

      case 'section':
        $limit = $this->limitSection;
        $data = $this->productData->getSubsections($filterNid, $offset, $limit);
        if (!$data['rows']) {
          // If no subsections, see if there are subproducts.
          $data = $this->productData->getSubproducts([$filterNid], $offset, $limit);
        }
        break;

      default:
        // Can only be subsection with products.
        $limit = $this->limitSubsection;
        $data = $this->productData->getSubproducts([$filterNid], $offset, $limit, FALSE, FALSE);
        break;
    }

    $data = $this->dataHelpers->prepareData($data, $offset, $limit);

    return new JsonResponse($data);
  }

}
