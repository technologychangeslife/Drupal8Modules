<?php

namespace Drupal\bhge_c55_content_repo\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;

/**
 * Controller routines for content repository.
 */
class ContentRepository extends ControllerBase {

  const PAGE_SIZE = 100000;

  public $request;

  public $route;

  public $entityQuery;

  public $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      \Drupal::request(),
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      \Drupal::service('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityQuery, $entityTypeManager, $route) {
    $this->request = $request;
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->route = $route;
  }

  /**
   * Search content repository.
   *
   * @return array
   *   Search response.
   */
  public function search() {
    $offset = Xss::filter($this->request->get('offset'));
    $limit = Xss::filter($this->request->get('limit'));
    $documents = $this->getFiles(
      $offset,
      $limit,
      Xss::filter($this->request->get('function')),
      Xss::filter($this->request->get('level')),
      Xss::filter($this->request->get('region'))
    );

    $result = [];
    foreach ($documents as $document) {
      $result[] = $this->transformDocToJson($document);
    }

    $response = new Response();
    $count = count($this->getFiles(
      0,
      self::PAGE_SIZE,
      Xss::filter($this->request->get('function')),
      Xss::filter($this->request->get('level')),
      Xss::filter($this->request->get('region'))
    ));
    $response->setContent(json_encode(
      [
        'data' => $result,
        'statusCode' => 200,
        'pagination' => [
          'total' => $count,
          'offset' => Xss::filter($this->request->get('offset')),
          'limit' => Xss::filter($this->request->get('limit')),
        ],
      ]
    ));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * Search content repository DB search.
   *
   * @return array
   *   List of files.
   */
  public function getFiles($offset = 0, $limit = self::PAGE_SIZE, $function = NULL, $level = NULL, $region = NULL) {
    $mediaQ = $this->entityQuery->get('node')
      ->condition('type', 'document')
      ->range($offset, $limit)
      ->condition('status', 1);

    if ($function) {
      $conditionGroup = $mediaQ->orConditionGroup()
        ->condition('field_function', NULL, 'IS NULL', NULL)
        ->condition('field_function', $function);
      $mediaQ->condition($conditionGroup);
      $mediaQ->sort('field_function', 'DESC');
    }

    if ($level) {
      $conditionGroup = $mediaQ->orConditionGroup()
        ->condition('field_level', NULL, 'IS NULL', NULL)
        ->condition('field_level', $level);
      $mediaQ->condition($conditionGroup);
      $mediaQ->sort('field_level', 'DESC');
    }

    if ($region) {
      $conditionGroup = $mediaQ->orConditionGroup()
        ->condition('field_region', NULL, 'IS NULL', NULL)
        ->condition('field_region', $region);
      $mediaQ->condition($conditionGroup);
      $mediaQ->sort('field_region', 'DESC');
    }
    $mediaQ->sort('changed', 'DESC');

    return $this->entityTypeManager->getStorage('node')
      ->loadMultiple($mediaQ->execute());
  }

  /**
   * Serve as json.
   */
  public function transformDocToJson(Node $document) {

    $gated = 0;
    if (!empty($document->get('field_gated_content')[0])) {
      $gated = $document->get('field_gated_content')[0]->value;
    }
    $file = !empty($document->field_file->entity) ? $document->field_file->entity : NULL;
    $filesize = !empty($file) ? format_size($file->filesize->value) : '';
    $file_type = !empty($file) ? pathinfo($file->filename->value) : '';
    $file_ext = !empty($file_type) ? $file_type['extension'] : '';
    if ($gated) {
      $url = $document->toUrl()->toString();
    }
    else {
      $url = !empty($file) ? file_create_url($file->getFileUri()) : '';
    }
    $file_language = !empty($file) ? $file->language()->getName() : '';

    return [
      "contentType" => "download",
      'title' => $document->getTitle(),
      'description' => $document->get('field_description')->value ? $document->get('field_description')->value : '',
      'filesize' => $filesize,
      'type' => $file_ext,
      'url' => $url,
      "isDownloadType" => "true",
      "fileExtension" => $file_ext,
      "fileType" => '',
      "fileLanguage" => $file_language,
      "fileSize" => $filesize,
      'gated' => $gated,
    ];
  }

}
