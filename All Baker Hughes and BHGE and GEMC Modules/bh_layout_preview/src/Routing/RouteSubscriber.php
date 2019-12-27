<?php

namespace Drupal\bh_layout_preview\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Override the controller for layout_builder.choose_block.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('layout_builder.choose_block')) {
      $defaults = $route->getDefaults();
      $defaults['_controller'] = '\Drupal\bh_layout_preview\Controller\BHLayoutsChooseBlockController::build';
      $route->setDefaults($defaults);
    }
  }

}
