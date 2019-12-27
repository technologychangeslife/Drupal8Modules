<?php

namespace Drupal\bh_component_library\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\bh_layout_preview\Services\GetLayoutBuilderPlugins;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Derivative class that provides the menu links for the Products.
 */
class BhDynamicMenu extends DeriverBase implements ContainerDeriverInterface {

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
   * Constructs a BhDynamicMenu object.
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
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('config.factory'),
      $container->get('bh_layout_preview.layout_builder_plugins')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    $getPluginList = $this->layoutBuilderPlugins->getPluginList('key');
    $config = $this->configFactory->getEditable('bh_layout_preview.layout_builder_settings');
    foreach ($getPluginList as $key => $plugin) {
      $link = $config->get($key);
      if (!empty($link)) {
        $links[$key] = [
          'title' => $plugin['title'],
          'route_name' => 'bh_component_library.' . $key,
          'parent' => 'bh_component_library.settings',
        ] + $base_plugin_definition;
      }
    }
    return $links;
  }

}
