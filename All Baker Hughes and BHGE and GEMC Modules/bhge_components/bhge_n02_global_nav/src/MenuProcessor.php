<?php

namespace Drupal\bhge_n02_global_nav;

use Drupal\Core\Menu\MenuTreeParameters;

/**
 * The Menu processor.
 */
class MenuProcessor {

  /**
   * Menu formatted array.
   */
  public function processor($menuName) {

    $menu_tree_service = \Drupal::service('menu.link_tree');

    $menu = \Drupal::entityTypeManager()->getStorage('menu')->load($menuName);

    if (!empty($menu)) {
      $menu_parameters = new MenuTreeParameters();
      $menu_parameters->setMaxDepth(1);
      $menu_parameters->onlyEnabledLinks();

      $items = $menu_tree_service->load($menuName, $menu_parameters);
      $manipulators = [
        // Use the default sorting of menu links.
        [
          'callable' => 'menu.default_tree_manipulators:generateIndexAndSort',
        ],
      ];
      $items = $menu_tree_service
        ->transform($items, $manipulators);
      $menuTree = ['label' => $menu->label(), 'data' => []];

      if (!empty($items)) {

        /** @var \Drupal\Core\Menu\MenuLinkTreeElement $item */
        foreach ($items as $item) {

          $menuTree['data'][] = [
            'title' => $item->link->getTitle(),
            'link' => $item->link->getUrlObject()->toString(),
          ];
        }
      }

      return $menuTree;
    }
  }

}
