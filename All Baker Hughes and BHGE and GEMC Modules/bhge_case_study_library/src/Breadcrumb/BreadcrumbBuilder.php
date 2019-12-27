<?php

namespace Drupal\bhge_case_study_library\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\node\Entity\Node;

/**
 * Class BreadcrumbBuilder.
 *
 * @package Drupal\bhge_case_study_library
 */
class BreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $parameters = $route_match->getParameters()->all();
    // I need my breadcrumbs for a few node types ONLY,
    // so it should be applied on node page ONLY.
    if (isset($parameters['node']) && !empty($parameters['node'])) {
      $node = $route_match->getParameter('node');
      if ($node instanceof Node && in_array($node->bundle(), ['case_study_summary'])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $actual_node = $route_match->getParameter('node');
    $this->breadcrumb = new Breadcrumb();
    // Home - 1st level.
    $this->breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));
    // Find the case study libbary page URL.
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'page')
      ->condition('field_case_study_landing', '1')
      ->range(0, 1);

    $nids = $query->execute();
    if (!empty($nids)) {
      foreach ($nids as $nid) {
        if (!empty($nid)) {
          $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
          $this->breadcrumb->addLink(Link::createFromRoute($node->getTitle(), 'entity.node.canonical', ['node' => $node->id()]));
        }
      }
    }
    $this->breadcrumb->addLink(Link::createFromRoute($actual_node->getTitle(), '<none>'));
    $this->breadcrumb->addCacheContexts(['route',
      'url.path',
      'user.roles:anonymous',
    ]);
    return $this->breadcrumb;
  }

}
