<?php

namespace Drupal\bhge_comment;

use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Url;
use Drupal\Node\Entity\Node;

/**
 * Defines the storage handler class for comments.
 */
class CommentData implements CommentDataInterface {

  /**
   * Maximum comments to show.
   *
   * @var int
   */
  public static $showMax = 15;

  /**
   * Load the comment tree.
   *
   * @param \Drupal\node\Entity\Node $entity
   *   Node entity.
   * @param string $fieldName
   *   Field name.
   * @param int $commentStatus
   *   Comment status.
   *
   * @return array
   *   Nested list of comment data for theming.
   */
  public function loadCommentTree(Node $entity, $fieldName, $commentStatus) {
    $comments = [];
    $parentCids = $this->loadCommentIds($entity, $fieldName);
    $parentComments = $this->loadCommentData($parentCids);

    foreach ($parentComments as $pcid => $parentComment) {
      $childCids = $this->loadCommentIds($entity, $fieldName, [$pcid]);
      if (count($childCids)) {
        $childComments = $this->loadCommentData($childCids);
        $parentComment['child_count'] = count($childComments);
        foreach ($childComments as $ccid => $childComment) {
          $parentComment['childs'][$ccid] = [
            '#theme' => 'bhge_comment',
            '#comment_data' => $childComment,
            '#comments_status' => $commentStatus,
            '#entity_id' => $entity->id(),
            '#is_parent' => FALSE,
            '#cache' => ['contexts' => ['user', 'url']],
          ];
        }
      }

      $comments[$pcid]['parent'] = [
        '#theme' => 'bhge_comment',
        '#comment_data' => $parentComment,
        '#comments_status' => $commentStatus,
        '#entity_id' => $entity->id(),
        '#is_parent' => TRUE,
        '#cache' => ['contexts' => ['user', 'url']],
      ];
    }

    return $comments;
  }

  /**
   * Get comment id's for parent entity.
   *
   * @param \Drupal\Node\Entity\Node $entity
   *   Parent entity.
   * @param string $field_name
   *   Comment field name.
   * @param array $pids
   *   ID's of parent comments.
   *
   * @return array
   *   List of comment id's
   */
  public function loadCommentIds(Node $entity, $field_name, array $pids = []) {

    $connection = Database::getConnection();
    $query = $connection->select('comment_field_data', 'c');
    $query->addField('c', 'cid');
    $query
      ->condition('c.entity_id', $entity->id())
      ->condition('c.entity_type', $entity->getEntityTypeId())
      ->condition('c.field_name', $field_name)
      ->condition('c.default_langcode', 1)
      ->addTag('entity_access')
      ->addTag('comment_filter')
      ->addMetaData('base_table', 'comment')
      ->addMetaData('entity', $entity)
      ->addMetaData('field_name', $field_name)
      ->condition('c.status', 1)
      ->orderBy('c.cid', 'ASC');

    if (count($pids)) {
      $query
        ->condition('c.pid', $pids, 'IN');
    }
    else {
      $query->condition('c.pid', NULL, 'IS');
    }
    $cids = $query->execute()->fetchCol();

    return $cids;
  }

  /**
   * Load data for comment id's.
   *
   * @param array $cids
   *   Comment id's.
   *
   * @return array
   *   Array of values per comment for theme usage.
   */
  public function loadCommentData(array $cids = []) {
    $uid = \Drupal::currentUser()->id();
    $user = user_load($uid);
    $return = [];
    $comments = entity_load_multiple('comment', $cids);
    $countBack = count($comments);
    $count = 0;

    /* @var \Drupal\comment\Entity\comment $comment */
    foreach ($comments as $key => &$comment) {
      $count++;
      if ($countBack <= $this::$showMax) {
        $return[$key]['show'] = 'false';
      }
      else {
        $return[$key]['show'] = 'true';
      }

      // Time since now in readable format.
      $return[$key]['timediff'] = \Drupal::service('date.formatter')
        ->formatTimeDiffSince($comment->getCreatedTime(), ['granularity' => 1]);

      /* @var \Drupal\user\Entity\User $owner */
      $owner = $comment->getOwner();

      // Allow editting.
      if ($user->hasPermission('administer comments') || $owner->id() == $uid) {
        $return[$key]['may_edit'] = TRUE;
      }
      else {
        $return[$key]['may_edit'] = FALSE;
      }

      // Set avatar.
      if (!$owner->user_picture->isEmpty()) {
        $return[$key]['user_picture'] = $owner->user_picture->view('thumbnail');
      }
      else {
        $return[$key]['user_picture'] = '';
      }

      $return[$key]['user_email'] = $comment->getAuthorEmail();
      $return[$key]['user_name'] = $comment->getAuthorName();
      $return[$key]['body_full'] = trim(strip_tags($comment->get('field_comment_body')->first()->value));
      $return[$key]['body_desktop'] = Unicode::truncate($return[$key]['body_full'], 500, TRUE, TRUE);
      $return[$key]['body_mobile'] = Unicode::truncate($return[$key]['body_full'], 250, TRUE, TRUE);

      // Should we show "more" links.
      if ($return[$key]['body_full'] != $return[$key]['body_desktop']) {
        $return[$key]['body_desktop_more'] = TRUE;
      }
      if ($return[$key]['body_full'] != $return[$key]['body_mobile']) {
        $return[$key]['body_mobile_more'] = TRUE;
      }

      // Did current user liked this comment.
      $usersThatLiked = $comment->get('field_comment_users_liked')->getValue();
      $iLiked = FALSE;
      foreach ($usersThatLiked as $userThatLiked) {
        if ($userThatLiked['target_id'] == $uid) {
          $iLiked = TRUE;
          break;
        }
      }
      $return[$key]['i_liked'] = $iLiked;

      $return[$key]['likes'] = (int) $comment->get('field_comment_likes')->value;
      $return[$key]['id'] = $key;
      $return[$key]['link'] = Url::fromRoute('entity.node.canonical', ['node' => $comment->getCommentedEntityId()], ['absolute' => 'true'])->toString() . '#comment-' . $key;
      $return[$key]['count'] = $count;
      $return[$key]['count_back'] = $countBack;
      $countBack--;
    }
    return $return;
  }

  /**
   * Show maximum comments.
   *
   * @return int
   *   Number of comments.
   */
  public function getShowMax() {
    return $this::$showMax;
  }

}
