<?php

namespace Drupal\bhge_core;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * ServiceProvider class for overriding link_tree.
 */
class BhgeOilServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('menu.link_tree');
    $definition->setClass('Drupal\bhge_core\Menu\BhgeOilMenuLinkTree');
  }

}
