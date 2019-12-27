<?php

namespace Drupal\bh_layout_preview\Services;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of GetLayoutBuilderPlugins.
 */
class GetLayoutBuilderPlugins {

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

  /**
   * Constructs a GetLayoutBuilderPlugins object.
   *
   * @param \Drupal\Core\Block\BlockManagerInterface $block_manager
   *   The block manager.
   */
  public function __construct(BlockManagerInterface $block_manager) {
    $this->blockManager = $block_manager;
  }

  /**
   * Return list of Plugin group by category.
   *
   * @param string $group_by
   *   Group the plugin list based on group by value.
   *   options key or category.
   *
   * @return array
   *   List of plugins.
   */
  public function getPluginList($group_by = 'category') {
    $plugin_definitions = $this->blockManager->getDefinitions();
    $allowed_plugin_cateory = $this->getAllowedPluginCategory();
    $plugin_def = [];
    foreach ($plugin_definitions as $key => $def) {
      $category = (string) $def['category'];
      if (in_array($category, $allowed_plugin_cateory)) {
        // Group by key. example value "entity_browser:xxx".
        if ($group_by == 'key') {
          $plugin_def[$key]['title'] = (string) $def['admin_label'];
        }
        else {
          // Group by category. example value "BH BLocks".
          $plugin_def[$category][$key]['title'] = (string) $def['admin_label'];
        }
      }
    }

    return $plugin_def;
  }

  /**
   * Return allowed category list of Plugin.
   */
  protected function getAllowedPluginCategory() {
    return [
      'BH Blocks',
      'Custom',
      'Entity Browser',
      'Lists (Views)'
    ];
  }

}
