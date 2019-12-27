<?php

namespace Drupal\bhge_events\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountProxy;
use Drupal\bhge_events\EventTrail;
use Drupal\node\Entity\Node;

/**
 * Class BreadcrumbBuilder.
 *
 * @package Drupal\bhge_events
 */
class BreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * Gallery data provider.
   *
   * @var \Drupal\bhge_events\EventTrail
   */
  protected $eventTrail;

  protected $user;

  /**
   * Constructor.
   *
   * @param \Drupal\bhge_events\EventTrail $eventTrail
   *   Event parent trail provider.
   * @param \Drupal\Core\Session\AccountProxy $user
   *   User object.
   */
  public function __construct(EventTrail $eventTrail, AccountProxy $user) {
    $this->eventTrail = $eventTrail;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $attributes) {
    $parameters = $attributes->getParameters()->all();
    // I need my breadcrumbs for a few node types ONLY,
    // so it should be applied on node page ONLY.
    if (isset($parameters['node']) && !empty($parameters['node'])) {
      $node = $attributes->getParameter('node');
      if ($node instanceof Node && in_array($node->bundle(), ['event_item'])) {
        return TRUE;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addLink(Link ::createFromRoute('Home', '<front>'));

    $trail = $this->eventTrail->currentTrail(NULL, $this->user->isAnonymous());

    if ($trail['parent']) {
      $breadcrumb->addLink(Link::createFromRoute($trail['parent']->get('title')->first()->getValue()['value'], 'entity.node.canonical', ['node' => $trail['parent']->id()]));
      // Expire cache when parent changes.
      $breadcrumb->addCacheTags(['node:' . $trail['parent']->id()]);
    }
    if ($trail['current']) {
      $breadcrumb->addLink(Link::createFromRoute($trail['current']->get('title')->first()->getValue()['value'], '<none>'));
    }

    // Set cache control.
    if ($trail['current']->id()) {
      $breadcrumb->addCacheTags(['node:' . $trail['current']->id()]);
    }
    $breadcrumb->addCacheContexts(['route', 'url.path', 'user.roles:anonymous']);

    return $breadcrumb;
  }

}
