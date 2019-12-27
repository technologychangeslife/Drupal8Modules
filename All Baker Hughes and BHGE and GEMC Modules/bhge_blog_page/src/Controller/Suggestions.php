<?php

namespace Drupal\bhge_blog_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Database\Database;

/**
 * Controller routines for Question suggestions.
 */
class Suggestions extends ControllerBase {

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
   * Load suggestions.
   */
  public function load() {
    $data = [];
    $search = Xss::filter(strip_tags($this->request->query->get('q')));

    if ($search) {
      $connection = Database::getConnection();
      $query = $connection->select('node_field_data', 'n');
      $query->addField('n', 'title');
      $query->addField('n', 'nid');
      $query->leftJoin('node__field_message', 'message', 'message.entity_id = n.nid');
      $query->addField('message', 'field_message_value', 'message');
      $query
        ->condition('n.type', 'question')
        ->condition('n.status', 1)
        ->condition('n.title', '%' . $search . '%', 'LIKE');

      $results = $query->execute()->fetchAll();
      foreach ($results as $result) {
        $data[] = [
          'title' => $result->title,
          'description' => (string) $result->message,
          'url' => '/node/' . $result->nid,
        ];
      }

    }
    return new JsonResponse($data);
  }

}
