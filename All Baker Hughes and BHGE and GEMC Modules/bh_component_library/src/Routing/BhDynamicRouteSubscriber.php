<?php

namespace Drupal\bh_component_library\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Defines dynamic routes for component library.
 */
class BhDynamicRouteSubscriber implements ContainerInjectionInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The layout builder plugins.
   *
   * @var \Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins
   */
  protected $layoutBuilderPlugins;

  /**
   * Constructs a BhDynamicRouteSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins $layout_builder_plugins
   *   The layout builder plugins.
   */
  public function __construct(ConfigFactoryInterface $config_factory, GetLayoutBuilderPlugins $layout_builder_plugins) {
    $this->configFactory = $config_factory;
    $this->layoutBuilderPlugins = $layout_builder_plugins;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('bh_layout_preview.layout_builder_plugins')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {

    $route_collection = new RouteCollection();

    $getPluginList = $this->layoutBuilderPlugins->getPluginList('key');
    $config = $this->configFactory->getEditable('bh_layout_preview.layout_builder_settings');

    foreach ($getPluginList as $key => $plugin) {
      if (!empty($config->get($key))) {
        $link = $config->get($key);
        $route = new Route(
          $link, [
          '_title' => $plugin['title'],
          'type' => $key
          ],
          // The requirements.
          [
            '_permission' => 'can view bh component library',
            '_user_is_logged_in' => 'TRUE'
          ]
        );
        // Add our route to the collection with a unique key.
        $route_collection->add('bh_component_library.' . $key, $route);
      }
    }

    return $route_collection;
  }

}
