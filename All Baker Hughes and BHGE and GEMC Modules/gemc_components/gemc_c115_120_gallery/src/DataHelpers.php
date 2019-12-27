<?php

namespace Drupal\gemc_c115_120_gallery;

use Drupal\Component\Utility\Unicode;
use Symfony\Component\HttpFoundation\Response;
use Drupal\gemc_components\FieldData\FieldDataService;

/**
 * Data related helper methods for gallery component.
 */
class DataHelpers {

  protected $fieldDataService;

  /**
   * Constructor.
   *
   * @param \Drupal\gemc_components\FieldData\FieldDataService $fieldDataService
   *   Class with helper methods.
   */
  public function __construct(FieldDataService $fieldDataService) {
    $this->fieldDataService = $fieldDataService;
  }

  /**
   * Fill data array row with properly prepared data.
   *
   * @param object $node
   *   Object with needed information.
   *
   * @return array
   *   Return filled row for data array.
   */
  public function fillData($node) {
    $title = (isset($node->title->value)) ? Unicode::truncate($node->title->value, 80, TRUE, TRUE) : '';

    $row = [
      'contentType' => 'text',
      'title' => $title,
      'description' => $node->body->value,
      'link' => [
        'url' => $node->url(),
        'label' => $title,
      ],
      'id' => $node->id(),
      'type' => $this->getLabel($node),
      'links' => $this->getLinks($node),
      'buttons' => [],
    ];
    $image_small = $this->fieldDataService->getStyleUrl($node->field_image->entity->uri->value, 'medium');
    $image_normal = $this->fieldDataService->getStyleUrl($node->field_image->entity->uri->value, 'thumbnail_normal');
    if (!empty($image_normal)) {
      $row['image'] = [
        'normal' => $image_normal,
        'small' => $image_small,
        'alt' => $title,
      ];
      $row['contentType'] = 'image';
    }

    return $row;
  }

  /**
   * Format the array of filters for use in C115/C120.
   *
   * @param array $topics
   *   List of filter topics.
   * @param string $cType
   *   Content type.
   * @param string $sort
   *   Sorting.
   * @param string $pid
   *   Parent ID.
   * @param bool $addAll
   *   Add "All" filter.
   *
   * @return array
   *   List of formatted filters.
   */
  public function formatTopicFilters(array $topics, $cType, $sort, $pid, $addAll = FALSE) {
    $filters = [];
    if (count($topics) && $addAll) {
      $filters[] = [
        'contentType' => $cType,
        'sort' => $sort,
        'topic' => '',
        'pid' => $pid,
        'label' => 'All',
        'class' => 'selected is-selected',
      ];
    }
    foreach ($topics as $topic) {
      $filters[] = [
        'contentType' => $cType,
        'sort' => $sort,
        'topic' => $topic->topic,
        'pid' => $pid,
        'label' => $topic->label,
        'class' => '',
      ];
    }
    return $filters;
  }

  /**
   * Auxiliary functions. Prepare values for video card in c55.
   *
   * @param array $row
   *   Array to store information for card.
   */
  private function processVideoUrl(array &$row) {
    $row['videoYoutubeId'] = '';
    $row['videoBrightCoveAccount'] = '';
    $row['videoBrightCovePlayer'] = '';
    $row['videoBrightCoveVideo'] = '';
    $row['videoOffice365Chid'] = '';
    $row['videoOffice365Vid'] = '';
    $row['videoIFrame'] = '';
    $row['videoFacebookVideo'] = '';

    $uiUtils = \Drupal::service('bhge_core.twig.uiutilsextension');
    if (strpos($row['video'], 'youtu.be') !== FALSE) {
      $videoParts = explode('/', $row['video']);
      $row['videoYoutubeId'] = end($videoParts);
    }
    elseif (strpos($row['video'], 'youtube') !== FALSE) {
      $videoParts = explode('=', $row['video']);
      $row['videoYoutubeId'] = end($videoParts);
    }
    elseif (strpos($row['video'], 'facebook') !== FALSE) {
      $row['videoFacebookVideo'] = $row['video'];
    }
    elseif (strpos($row['video'], 'brightcove') !== FALSE || strpos($row['video'], 'bcove') !== FALSE) {
      $video_id = end(explode('=', $row['video']));
      $row['videoBrightCoveAccount'] = $uiUtils->getBrightcoveAccount($row['video']);
      $row['videoBrightCovePlayer'] = $uiUtils->getBrightcovePlayer($row['video']);
      $row['videoBrightCoveVideo'] = $video_id;
    }
    else {
      $office365Data = $uiUtils->getOffice365Data($row['video']);
      $row['videoOffice365Chid'] = !empty($office365Data['chId']) ? $office365Data['chId'] : '';
      $row['videoOffice365Vid'] = !empty($office365Data['vId']) ? $office365Data['vId'] : '';
    }
  }

  /**
   * Extract label from node.
   *
   * @param object $node
   *   Object with needed information.
   * @param string $optionFieldName
   *   The option field name.
   *
   * @return string
   *   Label.
   */
  public function getLabel($node, $optionFieldName = 'field_filter_facets') {
    if (!empty($node->{$optionFieldName}->entity)) {
      $label = Unicode::truncate($node->{$optionFieldName}->entity->getName(), 30, TRUE, TRUE);
    }
    else {
      $label = Unicode::truncate($node->type->entity->label(), 30, TRUE, TRUE);
    }
    return $label;
  }

  /**
   * Auxiliary functions. Get and prepare cta links from node.
   *
   * @param object $node
   *   Object with needed information.
   *
   * @return array
   *   Return array of cta links.
   */
  private function getLinks($node) {
    $links = [];

    $hero_entity = !empty($node->field_block_hero) ? $node->field_block_hero->entity : NULL;
    $cta_link = NULL;
    if (!empty($hero_entity) && !empty($hero_entity->field_slides) && !empty($hero_entity->field_slides->entity->field_link)) {
      $cta_link = $hero_entity->field_slides->entity->field_link;
    }

    if (!empty($cta_link)) {
      foreach ($cta_link as $link) {
        $cta = $link->entity;
        if ($cta && isset($cta->field_label) && method_exists($cta->field_target[0], 'getUrl')) {
          $links[] = [
            'url' => $cta->field_target[0]->getUrl()->toString(),
            'title' => $cta->field_label->value,
          ];
        }
      }
    }
    return $links;
  }

  /**
   * Prepare json format response to return.
   *
   * @param array $data
   *   Array with needed information. Ready for json formating.
   * @param string $pagination
   *   The pagination for json response.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Return json format of data.
   */
  public function prepareJsonResponse(array $data, $pagination) {

    $jsonResponse = new Response();

    $response = [
      'pagination' => $pagination,
      'statusCode' => '200',
      'data' => $data,
    ];

    $jsonResponse->setContent(json_encode($response));
    $jsonResponse->headers->set('Content-Type', 'application/json');
    $jsonResponse->headers->set('Max-Age', 0);
    return $jsonResponse;
  }

}
