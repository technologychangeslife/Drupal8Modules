<?php

namespace Drupal\bhge_c55_downloads\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller routines downloads filter.
 */
class DownloadFilter extends ControllerBase {

  public $request;

  public $route;

  public $entityTypeManager;

  private $total;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $route, $entityTypeManager) {
    $this->request = $request;
    $this->route = $route;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Get id of paragraph.
   *
   * @return mixed
   *   Return paragraph id.
   */
  private function getParagraphId() {
    return Xss::filter($this->request->get('pid'));
  }

  /**
   * Get id of selected download type.
   *
   * @return mixed
   *   Return selected download type.
   */
  private function getSelectedDownloadType() {
    return Xss::filter($this->request->get('topic'));
  }

  /**
   * Get offset from url.
   *
   * @return mixed
   *   Return offset
   */
  private function getOffset() {
    return Xss::filter($this->request->get('offset'));
  }

  /**
   * Get limit from url.
   *
   * @return mixed
   *   Return limit
   */
  private function getLimit() {
    return Xss::filter($this->request->get('limit'));
  }

  /**
   * Set total count of all files or of files with specific type.
   *
   * @param int $total
   *   Param int total.
   */
  private function setTotal($total) {
    $this->total = $total;
  }

  /**
   * Get total count of all files or of files with specific type.
   *
   * @return int
   *   Return int total.
   */
  private function getTotal() {
    return $this->total;
  }

  /**
   * Get all download files attached on this paragraph.
   *
   * @return array
   *   Return files for download from paragraph.
   */
  private function getFilesFromParagraph() {
    $offset = $this->getOffset();
    $limit = $this->getLimit();
    $paragraph = $this->entityTypeManager->getStorage('paragraph')->load($this->getParagraphId());

    $media_items = $paragraph->get('field_dam_dlds');
    $is_dam = FALSE;
    if (!$media_items->isEmpty()) {
      $files = $media_items;
      $is_dam = TRUE;
    }
    else {
      $files = $paragraph->get('field_downloads');
    }

    $download_type_id = $this->getSelectedDownloadType();

    foreach ($files as $file) {
      $field_download_type = $file->entity->field_download_type->entity;
      if ($is_dam) {
        $custom_file = $this->processFileField($file->entity, 'field_asset');
        $custom_file['title'] = $file->entity->getName();
      }
      else {
        $custom_file = $this->processFileField($file->entity, 'field_file');
      }

      $download_type_name = !empty($field_download_type) ? $field_download_type->getName() : '';
      $download_type_cat_id = !empty($field_download_type) ? $field_download_type->id() : '';

      if ($download_type_id == 0) {
        $filtered[] = $this->prepareFilteredFiles($custom_file, $download_type_name);
      }
      else {
        if ($download_type_cat_id == $download_type_id) {
          $filtered[] = $this->prepareFilteredFiles($custom_file, $download_type_name);
        }
      }
    }

    $downloads = [];
    $step = $offset + $limit;
    $count = count($filtered);
    $this->setTotal($count);

    if ($step >= $count) {
      $step = $count;
    }
    for ($i = $offset; $i < $step; $i++) {
      $downloads[] = $filtered[$i];
    }
    return $downloads;
  }

  /**
   * Put filtered files in array and prepare for json output.
   *
   * @param array $file
   *   Array with download file information.
   * @param string $type
   *   Selected type of download files.
   *
   * @return array
   *   Return array of filtered files.
   */
  private function prepareFilteredFiles(array $file, $type) {
    $type_arr = ['fileType' => $type];

    return $type_arr + $file;
  }

  /**
   * Extract file data from file field.
   *
   * @param object $media
   *   This is the media object with needed information.
   * @param string $fileField
   *   This is the file field.
   *
   * @return array|null
   *   Return array of download file attributes.
   */
  private function processFileField($media, $fileField) {
    // Download File.
    $file = $media->{$fileField}->entity;
    if (!is_null($file)) {
      $filesize = format_size($file->filesize->value);
      $file_path_info = pathinfo($file->filename->value);
      $url = file_create_url($file->get('uri')->value);
      $language = $file->language();
      return [
        'isDownloadType' => 'true',
        'contentType' => 'download',
        'fileExtension' => $file_path_info['extension'],
        'title' => $media->getName(),
        'fileLanguage' => $language->getName(),
        'fileSize' => $filesize,
        'url' => $url,
      ];
    }
    return NULL;
  }

  /**
   * Return pagination information.
   *
   * @return array
   *   Array with information for pagination.
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
   * Prepare json response.
   *
   * @param array $files
   *   Filtered download files.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Return json response with download files and types.
   */
  private function prepareJsonResponse(array $files) {
    $jsonResponse = new Response();
    $jsonResponse->setContent(json_encode(
        [
          'pagination' => $this->getPagination(),
          'statusCode' => 200,
          'data' => $files,
        ]
      )
    );
    $jsonResponse->headers->set('Content-Type', 'application/json');
    return $jsonResponse;
  }

  /**
   * Serve filter results depend of download type.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Return prepared json response.
   */
  public function getFilteredFiles() {
    $files = $this->getFilesFromParagraph();

    return $this->prepareJsonResponse($files);
  }

}
