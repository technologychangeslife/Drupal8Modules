<?php

namespace Drupal\bhge_c55_gallery;

use Drupal\Component\Utility\Unicode;
use Symfony\Component\HttpFoundation\Response;

/**
 * Data related helper methods for gallery component.
 */
class DataHelpers {

  protected $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\bhge_core\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(\Drupal\bhge_core\DataHelpers $dataHelpers) {
    $this->dataHelpers = $dataHelpers;
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

    $row = [
      'contentType' => 'text',
      'title' => (isset($node->title->value)) ? Unicode::truncate($node->title->value, 80, TRUE, TRUE) : '',
      'description' => $this->dataHelpers->getDescription($node),
      'url' => $node->url(),
      'id' => $node->id(),
      'type' => $this->getLabel($node),
      'links' => $this->getLinks($node),
      'buttons' => [],
      'created' => format_date($node->getCreatedTime(), '', $format = 'F j, Y', $timezone = NULL, $langcode = NULL),
      'target' => '',
    ];
    $image = $this->dataHelpers->getImage($node);

    if (!empty($image)) {
      $row['image'] = $image;
      $row['gradient'] = '';
      $row['contentType'] = 'image';
    }

    switch ($node->getType()) {
      case 'webcast_item':
        // Ctype webcast_item.
        $row['contentType'] = 'webcast_item';
        $row = array_merge($row, bhge_components_get_webcast_data($row, $node));
        break;

      case 'person':
        // Ctype person.
        $row['contentType'] = 'person';
        $row['image'] = $this->dataHelpers->getImage($node);
        $row['title'] = $node->getTitle() . ' ' . $node->field_last_name->value;
        $row['position'] = $node->field_role->value;
        $row['description'] = $this->dataHelpers->getDescription($node);
        $row['type'] = !empty($node->field_team->entity) ? $node->field_team->entity->getName() : '';
        $row['links'][] = [
          'title' => t('View Bio'),
          'url' => $node->url(),
        ];
        break;

      case 'video_item':
        // Ctype video_item.
        $row['contentType'] = 'video';
        $row['isVideoType'] = TRUE;
        $row['video'] = $node->field_video->value;

        if (empty($image)) {
          $row['image'] = '';
        }

        $this->processVideoUrl($row);
        break;

      case 'question':
        $author = $node->get('uid')->entity;

        if (!empty($author->user_picture) && !empty($author->user_picture->entity)) {
          $row['authorImage'] = $this->dataHelpers->getImage($author, 'user_picture', NULL, 'thumbnail');
        }
        else {
          $row['authorImage'] = '/' . drupal_get_path('theme', 'bhge') . '/image/user/person-icon.png';
        }

        $row['isQuestionType'] = TRUE;
        $row['contentType'] = 'question';
        $row['authorName'] = $node->get('uid')->entity->mail->value;
        $row['likeCount'] = $node->field_votes->value;
        $row['commentsCount'] = $node->field_comments->comment_count;
        $row['url'] = $node->url();
        $row['state'] = $this->getQuestionState($node);
        $row['title'] = $node->getTitle();
        $row['description'] = $this->dataHelpers->getDescription($node);
        $row['contentId'] = $node->id();
        $row['likeAttribute'] = !empty($node->get('field_users_voted')) ? $this->getLikeAttribute(\Drupal::currentUser(), $node->get('field_users_voted')->getValue()) : '';
        $row['type'] = !empty($node->field_topic->entity) ? $node->field_topic->entity->getName() : '';
        break;
    }

    return $row;
  }

  /**
   * Auxiliary functions. Prepare values for video card in c55.
   *
   * @param object $currentUser
   *   Current user object.
   * @param array $usersVoted
   *   Users that voted for question.
   *
   * @return string
   *   Return a string with current user voting information.
   */
  public function getLikeAttribute($currentUser, array $usersVoted) {
    if (!empty($usersVoted)) {
      return array_search($currentUser->id(), array_column($usersVoted, 'target_id')) !== FALSE ? 'liked' : '';
    }
    return '';
  }

  /**
   * Auxiliary functions. Get question state.
   *
   * @param object $question
   *   Object of content type question.
   *
   * @return string
   *   Return the information about the question answered or not.
   */
  public function getQuestionState($question) {
    $created = $question->getCreatedTime();
    $time_week_ago = time() - (7 * 24 * 60 * 60);
    $build['question_state'] = '';
    if ($created < $time_week_ago) {
      return '';
    }
    elseif (!empty($question->body->value)) {
      return 'answered';
    }
    else {
      return 'new';
    }
  }

  /**
   * Auxiliary functions. Prepare values for video card in c55.
   *
   * @param array $row
   *   Array to store information for card.
   */
  public function processVideoUrl(array &$row) {
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
  public function getLabel($node, $optionFieldName = 'field_topic') {
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
   * @param int $pagination
   *   The pagination variable for json.
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
