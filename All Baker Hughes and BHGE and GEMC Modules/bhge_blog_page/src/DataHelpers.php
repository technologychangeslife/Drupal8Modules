<?php

namespace Drupal\bhge_blog_page;

/**
 * Data related helper methods for blog page.
 */
class DataHelpers {

  /**
   * Get image from node.
   *
   * @param object $node
   *   Object with needed information.
   * @param string $local_field
   *   Passing the machine name of the main image field.
   * @param string $dam_field
   *   Passing the machine name of the DAM main image field.
   *
   * @return string
   *   Return image from node if exist.
   */
  public function getImage($node, $local_field = 'field_main_image', $dam_field = 'field_dam_main_img') {

    $image = NULL;

    if (!empty($node->{$dam_field}->entity) || !empty($node->{$local_field}->entity)) {

      /** @var File $file */
      if (!empty($node->{$dam_field}->entity) && !empty($node->{$dam_field}->entity->field_asset->entity)) {
        $file = $node->{$dam_field}->entity->field_asset->entity;
      }
      else {
        $file = $node->{$local_field}->entity;
      }

      $image = !empty($file) ? $file->getFileUri() : '';
    }

    return $image;
  }

}
