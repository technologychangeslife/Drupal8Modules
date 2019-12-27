<?php

namespace Drupal\bhge_c34_product_comparison;

/**
 * Comprastion data implementation for bhge site.
 */
class ComparisonData extends ComparisonDataBase {

  /**
   * {@inheritdoc}
   */
  protected function getRelatedProductsQuery($parentSectionId, $onlyPublished = TRUE) {
    $query = parent::getRelatedProductsQuery($parentSectionId, $onlyPublished);

    $query->leftJoin('node__field_product_information', 'inf', 'inf.entity_id = n.nid');

    // Image.
    $query->leftJoin('paragraph__field_image', 'image', 'image.entity_id = inf.field_product_information_target_id');
    $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
    $query->addField('file_img', 'uri', 'image_uri');

    // DAM Image.
    $query->leftJoin('paragraph__field_dam_image', 'dam_image', 'dam_image.entity_id = inf.field_product_information_target_id');
    $query->leftJoin('media__field_asset', 'field_asset', 'field_asset.entity_id = dam_image.field_dam_image_target_id');
    $query->leftJoin('file_managed', 'field_dam_file', 'field_dam_file.fid = field_asset.field_asset_target_id');
    $query->addField('field_dam_file', 'uri', 'image_dam_uri');

    // Get only active products.
    $query->leftJoin('node__field_active', 'active', 'active.entity_id = n.nid');
    $query->condition('active.field_active_value', 1);

    return $query;
  }

}
