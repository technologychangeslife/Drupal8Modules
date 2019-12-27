<?php

namespace Drupal\bhge_comment\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Database\Database;

/**
 * Controller routines for BHGE comments.
 */
class Comment extends ControllerBase {

  public $request;

  public $route;

  public $entityQuery;

  public $entityTypeManager;

  public $invalidator;

  /**
   * CommentData object.
   *
   * @var \Drupal\bhge_comment\CommentData
   */
  public $commentDataService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('cache_tags.invalidator'),
      $container->get('bhge_comment.comment_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityQuery, $entityTypeManager, $route, $invalidator, $commentDataService) {
    $this->request = $request;
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->route = $route;
    $this->invalidator = $invalidator;
    $this->commentDataService = $commentDataService;
  }

  /**
   * Add new comment.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Json array.
   */
  public function add() {
    $eid = $this->route->getParameter('eid');
    $pid = $this->route->getParameter('cid');
    $uid = $this->currentUser()->id();

    /* @var \Symfony\Component\HttpFoundation\Request $request */
    $request = $this->request;
    $body = trim(strip_tags($request->request->get('body')));

    $values = [
      'entity_type' => 'node',
      'entity_id' => $eid,
      'field_name' => 'field_comments',
      'comment_type' => 'comment',
      'uid' => $uid,
      'field_comment_body' => $body,
    ];
    if ($pid) {
      $values['pid'] = $pid;
      $isParent = FALSE;
    }
    else {
      $isParent = TRUE;
    }

    /* @var \Drupal\comment\Entity\Comment $comment */
    $comment = $this->entityTypeManager->getStorage('comment')->create($values);
    if ($comment) {

      $comment->save();

      // Trigger email on comment addition starts here.
      $parent_comment_email = '';
      if ($comment->hasParentComment()) {
        $parent_comment_email = $comment->getParentComment()->getAuthorEmail();
      }
      $node = entity_load('node', $eid);
      $node_content_type = $node->bundle();
      $content_types_with_comments_enabled = [
        'page',
        'article',
        'blog_post',
        'news_item',
      ];

      if (in_array($node_content_type, $content_types_with_comments_enabled)) {

        $send_comment_notification = $node->get('field_enable_comment_notificatio')->value;
        if ($send_comment_notification == '1') {
          $this->triggerMail($eid, $node, $parent_comment_email);
        }

      }
      // Trigger email on comment addition ends here.
      $this->invalidator->invalidateTags(['node:' . $eid, 'comment:' . $pid]);

      $commentData = $this->commentDataService->loadCommentData([$comment->id()]);

      $build = [
        '#theme' => 'bhge_comment',
        '#comment_data' => $commentData[$comment->id()],
        '#entity_id' => $comment->getCommentedEntityId(),
        '#is_parent' => $isParent,
        '#cache' => ['contexts' => ['user', 'url']],
      ];

      $response = [
        'error' => FALSE,
        'error_message' => '',
        'html' => render($build),
      ];

      // Cant use JsonResponse, bcs it escapes too much for frontend framework.
      $json = json_encode($response);
      return new Response($json);
    }
    throw new NotFoundHttpException();
  }

  /**
   * Edit comment.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Json array.
   */
  public function edit() {
    $cid = $this->route->getParameter('cid');
    $uid = $this->currentUser()->id();

    /* @var \Drupal\comment\Entity\Comment $comment */
    $comment = $this->entityTypeManager->getStorage('comment')->load($cid);
    if ($comment) {
      if ($comment->getOwnerId() == $uid || $this->currentUser->hasPermission('administer comments')) {

        /* @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->request;
        $body = trim(strip_tags($request->request->get('body')));

        $comment->set('field_comment_body', $body);
        if (strlen($body)) {
          $comment->save();

          $entity = $comment->getCommentedEntity();
          /* @var  $test */
          $fieldSettings = $entity->get($comment->getFieldName())->first()->getValue();
          $commentTree = $this->commentDataService->loadCommentTree($entity, $comment->getFieldName(), $fieldSettings['status']);

          if ($comment->hasParentComment()) {
            $isParent = FALSE;
            $commentData = $commentTree[$comment->getParentComment()->id()];
            $commentData['parent']['#comment_data']['expand'] = TRUE;
          }
          else {
            $isParent = TRUE;
            $commentData = $commentTree[$comment->id()];
          }

          $build = $commentData;

          $response = [
            'error' => FALSE,
            'error_message' => '',
            'html' => render($build),
          ];
        }
        else {
          $response = [
            'error' => TRUE,
            'error_message' => 'Commment cannot be empty',
            'html' => 'Comment not editted',
          ];
        }
        // Cant use JsonResponse.
        // Because it escapes too much for frontend framework.
        $json = json_encode($response);
        return new Response($json);
      }
    }
    throw new NotFoundHttpException();
  }

  /**
   * Delete comment.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Json array.
   */
  public function delete() {
    $cid = $this->route->getParameter('cid');
    $uid = $this->currentUser()->id();

    $comment = $this->entityTypeManager->getStorage('comment')->load($cid);
    if ($comment) {
      $checkForChilds = $this->checkForChilds($comment->id());
      if ($comment->getOwnerId() == $uid && !count($checkForChilds)) {
        $comment->delete();
        $this->invalidator->invalidateTags([$comment->getCommentedEntityTypeId() . ':' . $comment->getCommentedEntityId()]);

        $response = [
          'error' => FALSE,
          'error_message' => '',
          'html' => 'Comment deleted',
        ];
      }
      else {
        $response = [
          'error' => TRUE,
          'error_message' => 'Not allowed to remove comment.',
          'html' => '',
        ];
      }

      return new JsonResponse($response);
    }
    throw new NotFoundHttpException();
  }

  /**
   * Check child id's of parent comment.
   *
   * @param int $pid
   *   Parent comment id.
   *
   * @return array
   *   List of child id's.
   */
  protected function checkForChilds($pid) {

    $connection = Database::getConnection();
    $query = $connection->select('comment_field_data', 'c');
    $query->addField('c', 'cid');
    $query
      ->condition('c.pid', $pid)
      ->condition('c.default_langcode', 1);

    $cids = $query->execute()->fetchCol();

    return $cids;
  }

  /**
   * Trigger email on comment addition.
   *
   * @param int $eid
   *   Entity id.
   * @param object $node
   *   Node object.
   * @param string $parent_comment_email
   *   Email id of Parent comment.
   */
  public function triggerMail($eid, $node, $parent_comment_email) {
    $commenter = User::load(\Drupal::currentUser()->id());
    $page_owner_mail_id = $node->getOwner()->getEmail();
    $email = $page_owner_mail_id;

    $params['page_owner'] = $node->getOwner()->getDisplayName();
    $params['page_title'] = $node->getTitle();
    $params['commenter_name'] = $commenter->get('name')->value;
    $params['commenter_email'] = $commenter->getEmail();

    $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $eid);
    $host = \Drupal::request()->getHost();
    $params['comment_page'] = "https://" . $host . $alias;
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $newMail = \Drupal::service('plugin.manager.mail');
    // syntax: $newMail->mail($module,$key,$to,$langcode,$params,NULL,$send);.
    if (!empty($parent_comment_email) && ($commenter->getEmail() != $parent_comment_email)) {
      // $email = $page_owner_mail_id . "," . $parent_comment_email;.
      $email = $parent_comment_email;
      $params['headers']['Cc'] = $page_owner_mail_id;
      $newMail->mail('bhge_comment', 'post_comment_multiple', $email, $langcode, $params, $reply = NULL, $send = TRUE);
    }
    else {
      $newMail->mail('bhge_comment', 'post_comment_single', $email, $langcode, $params, $reply = NULL, $send = TRUE);
    }
  }

}
