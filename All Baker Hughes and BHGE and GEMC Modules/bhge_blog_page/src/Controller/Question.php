<?php

namespace Drupal\bhge_blog_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller routines for ask question form.
 */
class Question extends ControllerBase {

  public $request;

  public $route;

  public $entityTypeManager;

  public $invalidator;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  public $messenger;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  public $stringTranslation;

  /**
   * {@inheritdoc}
   */
  public function __construct($request, $entityTypeManager, $route, $invalidator, $messenger, $stringTranslation) {
    $this->request = $request;
    $this->entityTypeManager = $entityTypeManager;
    $this->route = $route;
    $this->invalidator = $invalidator;
    $this->messenger = $messenger;
    $this->stringTranslation = $stringTranslation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('cache_tags.invalidator'),
      $container->get('messenger'),
      $container->get('string_translation')
    );
  }

  /**
   * Add new question.
   *
   * Return \Symfony\Component\HttpFoundation\RedirectResponse.
   */
  public function add() {

    $pid = $this->route->getParameter('pid');
    $uid = $this->currentUser()->id();

    /* @var \Symfony\Component\HttpFoundation\Request $request */
    $request = $this->request;
    $question = trim(strip_tags($request->request->get('question')));
    $message = trim(strip_tags($request->request->get('message')));
    $category = trim(strip_tags($request->request->get('category')));

    $values = [
      'entity_type' => 'node',
      'type' => 'question',
      'uid' => $uid,
      'title' => $question,
      'body' => '',
      'field_message' => $message,
      'field_topic' => is_numeric($category) ? $category : NULL,
      'field_blog_page' => is_numeric($pid) ? $pid : NULL,
    ];

    /* @var $node \Drupal\node\nodeInterface */
    $node = $this->entityTypeManager->getStorage('node')->create($values);
    if ($node) {
      try {
        $node->save();
        $this->invalidator->invalidateTags(['node:' . $pid]);

        $response = new RedirectResponse('/node/' . $node->id());
        $this->messenger->addStatus($this->stringTranslation->translate('Your question has been saved, thank you.'));
        $response->send();

      }
      catch (Exception $e) {
        $this->messenger->addError($this->stringTranslation->translate('Your question could not be saved, something went wrong.'));
      }
    }
    else {
      throw new NotFoundHttpException();
    }
  }

  /**
   * Edit question.
   *
   * Return Symfony\Component\HttpFoundation\RedirectResponse.
   */
  public function edit() {
    // Not implemented.
    throw new NotFoundHttpException();
  }

  /**
   * Delete question.
   *
   * Return Symfony\Component\HttpFoundation\RedirectResponse.
   */
  public function delete() {
    // Not implemented.
    throw new NotFoundHttpException();
  }

}
