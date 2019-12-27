<?php

namespace Drupal\bhge_document_center\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Identifying Dam File Size.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bhge_dam_file_size")
 */
class BhgeDamFileSize extends FieldPluginBase {

  /**
   * The query function.
   *
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * The render function.
   *
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    if (isset($values->media_field_data_node__field_dam_file_mid)) {
      $entity = Media::load($values->media_field_data_node__field_dam_file_mid);
      $file_size = format_size(File::load($entity->get('field_asset')->target_id)
        ->getSize(), '')->__toString();
      return $file_size;
    }
  }

}
