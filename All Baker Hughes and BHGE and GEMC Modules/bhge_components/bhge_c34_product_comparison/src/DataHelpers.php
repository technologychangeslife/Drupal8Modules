<?php

namespace Drupal\bhge_c34_product_comparison;

use Drupal\image\Entity\ImageStyle;

/**
 * Data related helper methods for products/sections.
 */
class DataHelpers extends DataHelpersBase {

  /**
   * {@inheritdoc}
   */
  protected function transformData($item) {
    $image = !empty($item->image_dam_uri) ? $item->image_dam_uri : $item->image_uri;
    return [
      'title' => $item->title,
      'url' => $this->getPathFromNid($item->nid),
      'image' => ImageStyle::load('gallery_image')->buildUrl($image),
      'thumb' => ImageStyle::load('thumbnail_cropped')->buildUrl($image),
      'attributes' => [],
    ];
  }

}
