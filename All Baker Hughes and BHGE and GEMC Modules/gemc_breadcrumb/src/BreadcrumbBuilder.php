<?php

namespace Drupal\gemc_breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\system\PathBasedBreadcrumbBuilder;

/**
 * {@inheritdoc}
 */
class BreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  /**
   * Breadcrumb object.
   *
   * @var \Drupal\Core\Breadcrumb\Breadcrumb
   */
  protected $breadcrumb;

  /**
   * Node object.
   *
   * @var \Drupal\node\Entity\Node
   */
  private $node;

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    if (($this->node = $route_match->getParameter('node'))
      && ($this->node instanceof NodeInterface)) {
      $this->breadcrumb = new Breadcrumb();
      $this->breadcrumb->addCacheContexts(['url.path']);
      $this->breadcrumb->addCacheableDependency($this->node);
      $links = $this->getNodeBreadcrumbs();

      if ($links) {
        $this->breadcrumb->setLinks($links);
        return $this->breadcrumb;
      }
    }

    // Fallback to default.
    return parent::build($route_match);
  }

  /**
   * Build breadcrumbs for current node.
   *
   * @return array
   *   List of links used to build breadcrumbs.
   */
  private function getNodeBreadcrumbs() {
    $links = [];

    if (in_array($this->node->getType(), ['product', 'section'])) {
      $links[] = Link::createFromRoute($this->t('Home'), '<front>');
      $property = $this->node;
      $current_lng = \Drupal::languageManager()->getCurrentLanguage()->getId();
      foreach (['field_prod_section', 'field_section_parents'] as $field) {
        if ($property->hasField($field)) {
          $parents = $property->$field->referencedEntities();

          foreach ($parents as $parent) {
            $parent_lng = $parent->get('langcode')->value;
            if ($current_lng !== $parent_lng && $parent->hasTranslation($current_lng)) {
              $parent = $parent->getTranslation($current_lng);
            }

            $this->breadcrumb->addCacheableDependency($parent);

            $access = $parent->access('view', $this->currentUser, TRUE);
            // The set of breadcrumb links depends on the access result,
            // So merge the access result's cacheability metadata.
            $this->breadcrumb->addCacheableDependency($access);

            if ($access->isAllowed()) {
              $url = Url::fromRoute('entity.node.canonical', ['node' => $parent->id()]);
              $links[] = Link::fromTextAndUrl($parent->label(), $url);
            }
          }
        }
      }
    }

    return $links;
  }

}
