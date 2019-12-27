<?php

namespace Drupal\bhge_blog_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller routines for Question votes.
 */
class Voting extends ControllerBase {

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
   * Add vote.
   */
  public function add() {
    $nid = $this->request->query->get('contentId');
    $pid = $this->route->getParameter('pid');
    $uid = $this->currentUser()->id();

    $node = $this->entityTypeManager->getStorage('node')->load($nid);
    if ($node) {
      $usersThatLiked = $node->get('field_users_voted')->getValue();
      $dontAdd = FALSE;
      foreach ($usersThatLiked as $userThatLiked) {
        if ($userThatLiked['target_id'] == $uid) {
          $dontAdd = TRUE;
          break;
        }
      }
      $currentLike = $node->get('field_votes')->value;
      if ($dontAdd) {
        $likes = $currentLike;
      }
      else {
        $newLike = $currentLike + 1;
        $node->set('field_votes', $newLike);
        $node->field_users_voted[] = $uid;
        $node->save();
        $this->invalidator->invalidateTags(['node:' . $nid, 'node:' . $pid]);
        $likes = $newLike;
      }

      return new JsonResponse(['data' => ['like' => $likes]]);
    }
    throw new NotFoundHttpException();
  }

  /**
   * Remove vote.
   */
  public function remove() {
    $nid = $this->request->query->get('contentId');
    $pid = $this->route->getParameter('pid');
    $uid = $this->currentUser()->id();

    $node = $this->entityTypeManager->getStorage('node')->load($nid);
    if ($node) {
      $usersThatLiked = $node->get('field_users_voted')->getValue();
      $remove = FALSE;
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
      $currentLike = $node->get('field_votes')->value;
      if ($remove) {
        $newLike = $currentLike - 1;
        $node->set('field_votes', $newLike);
        // TODO: test remove uid from list.
        $node->field_users_voted = $newUsersLiked;
        $node->save();
        $this->invalidator->invalidateTags(['node:' . $nid, 'node:' . $pid]);
        $likes = $newLike;
      }
      else {
        $likes = $currentLike;
      }

      return new JsonResponse(['data' => ['like' => $likes]]);
    }
    throw new NotFoundHttpException();
  }

}
