<?php

namespace Drupal\gemc_product\Plugin\Block;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Product Brand Logo' Block.
 *
 * @Block(
 *   id = "product_brand_block",
 *   admin_label = @Translation("Product Brand Logo"),
 *   category = @Translation("Product Brand Logo"),
 * )
 */
class ProductBrandBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $renderer = \Drupal::service('renderer');
    $cache = ['#cache' => ['contexts' => ['url.path']]];
    $render['frontpage'] = [
      '#plain_text' => Url::fromRoute('<front>')->toString(),
    ];

    $entity = \Drupal::routeMatch()->getParameter('node');
    if ($entity instanceof NodeInterface && in_array($entity->bundle(), ['product', 'section'])) {
      $brand_found = FALSE;

      foreach (['field_prod_section', 'field_section_parents'] as $field) {
        if ($entity->hasField($field)) {
          $parents = $entity->get($field)->getValue();

          // Add current entity id as search option.
          $parents[] = ['target_id' => $entity->id()];

          $processed_tids = [];
          foreach (array_reverse($parents) as $parent) {
            $target_id = $parent['target_id'];
            $term_ids = \Drupal::entityQuery('taxonomy_term')
              ->condition('vid', 'product_type')
              ->condition('field_product_category', $target_id)
              ->execute();

            if ($term_ids) {
              foreach ($term_ids as $term_id) {
                if (in_array($term_id, $processed_tids)) {
                  continue;
                }
                $ancestors = \Drupal::service('entity_type.manager')
                  ->getStorage("taxonomy_term")
                  ->loadAllParents($term_id);
                foreach ($ancestors as $ancestor) {
                  $processed_tids[] = $ancestor->id();
                  $renderer->addCacheableDependency($cache, $ancestor);
                  $has_logo = $ancestor->hasField('field_logo') && !$ancestor->get('field_logo')
                    ->isEmpty();
                  $has_display_logo_name = $ancestor->hasField('field_display_logo_name') && $ancestor->get('field_display_logo_name')->value;
                  if (!$brand_found && ($has_logo || $has_display_logo_name)) {
                    if ($has_logo) {
                      $file = $ancestor->get('field_logo')->entity;
                      $variables = [
                        'style_name' => 'brand_logo',
                        'uri' => $file->getFileUri(),
                      ];

                      // The image.factory service will check if our image is valid.
                      $image = \Drupal::service('image.factory')
                        ->get($file->getFileUri());
                      if ($image->isValid()) {
                        $variables['width'] = $image->getWidth();
                        $variables['height'] = $image->getHeight();
                      }
                      else {
                        $variables['width'] = $variables['height'] = NULL;
                      }

                      $render['brand_logo'] = [
                        '#theme' => 'image_style',
                        '#width' => $variables['width'],
                        '#height' => $variables['height'],
                        '#style_name' => $variables['style_name'],
                        '#uri' => $variables['uri'],
                        '#alt' => '',
                      ];

                      $brand_found = TRUE;
                    }

                    if ($has_display_logo_name) {
                      $render['brand_logo_name'] = [
                        '#plain_text' => $ancestor->label(),
                      ];

                      $brand_found = TRUE;
                    }
                    if ($brand_found) {
                      $cat_id = $ancestor->get('field_product_category')
                        ->getValue()[0]['target_id'];
                      $render['brand_logo_url'] = [
                        '#plain_text' => Url::fromRoute('entity.node.canonical', ['node' => $cat_id], ['absolute' => TRUE])
                          ->toString(),
                      ];
                      break;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    foreach ($render as &$item) {
      $item = array_merge($item, $cache);
    }

    return $render;
  }

}
