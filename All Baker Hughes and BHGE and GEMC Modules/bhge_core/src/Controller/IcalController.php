<?php

namespace Drupal\bhge_core\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gallery controller.
 */
class IcalController extends ControllerBase {

  public $request;

  protected $id;

  /**
   * Get content type from contenttype string.
   *
   * @return string
   *   Return content type.
   */
  private function getId() {
    return Xss::filter($this->request->get('id'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(Request $request) {
    $this->request = $request;
  }

  /**
   * Ical return.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Return json response with all requested data.
   */
  public function downloadIcal() {

    $nid = $this->getId();

    if (!empty($nid) && is_numeric($nid)) {

      /** @var \Drupal\node\Entity\Node $node */
      $node = Node::load($nid);

      $fields = [
        '#startdate' => 'field_start_date_time',
        '#enddate' => 'field_end_date_time',
        '#location' => 'field_location',
      ];

      $data = [];

      if (!empty($node) && !empty($node->bundle()) && $node->bundle() == 'webcast_item') {
        foreach ($fields as $key => $field) {

          if ($node->hasField($field) && $node->get($field)->getValue()[0]) {
            $data[$key] = $node->get($field)->getValue()[0]['value'];
          }
        }

        if (!empty($data)) {
          $data['#timezone'] = drupal_get_user_timezone();
          $data['#theme'] = 'ical_template';
          $data = render($data);
          var_dump($data); die();
        }
      }

    }

    $response = new Response();
    // Prepare $response.
    $response->setContent(\GuzzleHttp\json_encode($data));
    return $response;
  }

}
