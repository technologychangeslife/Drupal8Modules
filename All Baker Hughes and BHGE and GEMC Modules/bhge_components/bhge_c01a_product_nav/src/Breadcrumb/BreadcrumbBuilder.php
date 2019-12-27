<?php

namespace Drupal\bhge_c01a_product_nav\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountProxy;
use Drupal\node\Entity\Node;
use Drupal\bhge_c01a_product_nav\SectionTrail;

/**
 * Class BreadcrumbBuilder.
 *
 * @package Drupal\bhge_c01a_product_nav
 */
class BreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * Gallery data provider.
   *
   * @var \Drupal\bhge_c01a_product_nav\SectionTrail
   */
  protected $sectionTrail;

  protected $user;

  /**
   * Constructor.
   *
   * @param \Drupal\bhge_c55_product_gallery\SectionTrail $sectionTrail
   *   Gallery data provider.
   * @param \Drupal\Core\Session\AccountProxy $user
   *   User account proxy.
   */
  public function __construct(SectionTrail $sectionTrail, AccountProxy $user) {
    $this->sectionTrail = $sectionTrail;
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
      // Could be nid on revision view.
      if (is_numeric($node)) {
        $node = Node::load($node);
      }
      if ($node && in_array($node->bundle(), ['section', 'product'])) {
        if ($attributes->getRouteName() !== 'entity.node.edit_form') {
          return TRUE;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addLink(Link ::createFromRoute('Home', '<front>'));

    $trail = $this->sectionTrail->currentTrail(NULL, $this->user->isAnonymous());

    foreach ($trail['parents'] as $parent) {
      // Only add as link if has_page enabled.
      if ($parent->has_page) {
        $breadcrumb->addLink(Link::createFromRoute($parent->title, 'entity.node.canonical', ['node' => $parent->id]));
      }
      else {
        $breadcrumb->addLink(Link::createFromRoute($parent->title, '<none>'));
      }
      // Expire cache when parent changes.
      $breadcrumb->addCacheTags(['node:' . $parent->id]);
    }
    if (isset($trail['current']->title)) {
      $breadcrumb->addLink(Link::createFromRoute($trail['current']->title, '<none>'));
    }

    // Set cache control.
    if (isset($trail['current']->id) && $trail['current']->id) {
      $breadcrumb->addCacheTags(['node:' . $trail['current']->id]);
    }
    $breadcrumb->addCacheContexts(['route', 'url.path', 'user.roles:anonymous']);

    return $breadcrumb;
  }

}
