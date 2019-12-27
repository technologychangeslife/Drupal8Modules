<?php

namespace Drupal\bhge_c55_gallery\Controller;

use Drupal\bhge_c55_gallery\DataHelpers;
use Drupal\bhge_c55_gallery\GalleryData;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Gallery controller.
 */
class GalleryController extends ControllerBase {

  public $request;

  public $route;

  public $entityQuery;

  public $entityTypeManager;

  private $total;

  protected $galleryData;

  protected $dataHelpers;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('entity.query'),
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
      $container->get('bhge_c55_gallery.gallery_data'),
      $container->get('bhge_c55_gallery.gallery_data_helpers')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityQuery, $route, $entityTypeManager, GalleryData $galleryData, DataHelpers $dataHelpers) {
    $this->request = $request;
    $this->route = $route;
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->galleryData = $galleryData;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Get content types from contenttype string.
   *
   * @return array
   *   Return content types.
   */
  private function getContentTypes() {
    $arrCtypes = explode('|', Xss::filter($this->request->get('contenttype')));
    return $arrCtypes;
  }

  /**
   * Get topic from topic string.
   *
   * @return int
   *   Return topic id.
   */
  private function getTopic() {
    return Xss::filter($this->request->get('topic'));
  }

  /**
   * Get sort by option from sort strng.
   *
   * @return int
   *   Return sorting option.
   */
  private function getSortBy() {
    return Xss::filter($this->request->get('sort'));
  }

  /**
   * Get parent id from url.
   *
   * @return mixed
   *   Return id of parent element.
   */
  private function getParentId() {
    $content_type = $this->getContentTypes();
    if (in_array($content_type,
         [
           ['event_item'],
           ['person'],
           ['blog_post'],
           ['question'],
         ])) {
      return Xss::filter($this->request->get('pid'));
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get offset from url.
   *
   * @return mixed
   *   Return offset.
   */
  private function getOffset() {
    return Xss::filter($this->request->get('offset'));
  }

  /**
   * Get limit from url.
   *
   * @return mixed
   *   Return limit.
   */
  private function getLimit() {
    return Xss::filter($this->request->get('limit'));
  }

  /**
   * Get category from url.
   *
   * @return mixed
   *   Return category.
   */
  private function getCategory() {
    return Xss::filter($this->request->get('category'));
  }

  /**
   * Set total count of finded nodes.
   *
   * @param int $total
   *   Set total count of elements.
   */
  private function setTotal($total) {
    $this->total = $total;
  }

  /**
   * Get total count of finded nodes.
   *
   * @return mixed
   *   Return total count od elements.
   */
  private function getTotal() {
    return $this->total;
  }

  /**
   * Get pagination information.
   *
   * @return array
   *   Return array with pagination data.
   */
  private function getPagination() {
    $pagination = [
      'total' => $this->getTotal(),
      'offset' => $this->getOffset(),
      'limit' => $this->getLimit(),
    ];
    return $pagination;
  }

  /**
   * Get data. Load from query, check and set data in array.
   *
   * @return array
   *   Return data.
   */
  private function getData() {
    $data = $this->galleryData->prepareData($this->getContentTypes(), $this->getParentId(), $this->getTopic(), $this->getSortBy(), $this->getOffset(), $this->getLimit(), $this->getCategory());
    $this->setTotal($data['total']);
    return $data['results'];
  }

  /**
   * Main function. Api route is set to this function.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Return json response with all requested data.
   */
  public function getFilteredResult() {
    return $this->dataHelpers->prepareJsonResponse($this->getData(), $this->getPagination());
  }

}
