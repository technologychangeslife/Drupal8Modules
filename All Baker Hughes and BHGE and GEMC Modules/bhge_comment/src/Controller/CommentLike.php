<?php

namespace Drupal\bhge_comment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller routines for Comment likes.
 */
class CommentLike extends ControllerBase {

  public $request;

  public $route;

  public $entityQuery;

  public $entityTypeManager;

  public $invalidator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('entity.query'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityQuery, $entityTypeManager, $route, $invalidator) {
    $this->request = $request;
    $this->entityQuery = $entityQuery;
    $this->entityTypeManager = $entityTypeManager;
    $this->route = $route;
    $this->invalidator = $invalidator;
  }

  /**
   * Add like.
   */
  public function add() {
    $cid = $this->route->getParameter('cid');
    $uid = $this->currentUser()->id();

    $comment = $this->entityTypeManager->getStorage('comment')->load($cid);
    if ($comment) {
      $usersThatLiked = $comment->get('field_comment_users_liked')->getValue();
      $dontAdd = FALSE;
      foreach ($usersThatLiked as $userThatLiked) {
        if ($userThatLiked['target_id'] == $uid) {
          $dontAdd = TRUE;
          break;
        }
      }
      $currentLike = $comment->get('field_comment_likes')->value;
      if ($dontAdd) {
        $likes = $currentLike;
      }
      else {
        $newLike = $currentLike + 1;
        $comment->set('field_comment_likes', $newLike);
        $comment->field_comment_users_liked[] = $uid;
        $comment->save();
        $this->invalidator->invalidateTags([$comment->getCommentedEntityTypeId() . ':' . $comment->getCommentedEntityId()]);
        $likes = $newLike;
      }

      return new JsonResponse(['likes' => $likes]);
    }
    throw new NotFoundHttpException();
  }

  /**
   * Remove like.
   */
  public function remove() {
    $cid = $this->route->getParameter('cid');
    $uid = $this->currentUser()->id();

    $comment = $this->entityTypeManager->getStorage('comment')->load($cid);
    if ($comment) {
      $usersThatLiked = $comment->get('field_comment_users_liked')->getValue();
      $dontAdd = FALSE;
      $newUsersLiked = [];
      foreach ($usersThatLiked as $userThatLiked) {
        if ($userThatLiked['target_id'] == $uid) {
          $remove = TRUE;
          break;
        }
        else {
          $newUsersLiked[] = $userThatLiked['target_id'];
        }
      }
      $currentLike = $comment->get('field_comment_likes')->value;
      if ($remove) {
        $newLike = $currentLike - 1;
        $comment->set('field_comment_likes', $newLike);
        // TODO: test remove uid from list.
        $comment->field_comment_users_liked = $newUsersLiked;
        $comment->save();
        $this->invalidator->invalidateTags([$comment->getCommentedEntityTypeId() . ':' . $comment->getCommentedEntityId()]);
        $likes = $newLike;
      }
      else {
        $likes = $currentLike;
      }

      return new JsonResponse(['likes' => $likes]);
    }
    throw new NotFoundHttpException();
  }

}
