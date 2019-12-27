<?php

namespace Drupal\bhge_c55_product_gallery;

use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 * Load product related data.
 */
class ProductData {

  public static $limitSection = 6;

  public static $limitSubsection = 8;

  protected $connection;

  private $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   * @param \Drupal\bhge_c55_product_gallery\DataHelpers $dataHelpers
   *   Class with helper methods.
   */
  public function __construct(Connection $connection, DataHelpers $dataHelpers) {
    $this->connection = $connection;
    $this->dataHelpers = $dataHelpers;
  }

  /**
   * Get filters (subsection titles) that are related to given section.
   *
   * @param int $nid
   *   Given section.
   *
   * @return array
   *   List of filter data.
   */
  public function getFilters($nid) {
    $data = $this->getSubsections($nid, 0, 200, TRUE);
    $filterIds = [];

    // Prepare array of nids.
    foreach ($data['rows'] as $row) {
      $filterIds[] = $row->id;
    }
    $data['nids'] = $filterIds;
    return $data;
  }

  /**
   * Get all published and active main sections.
   *
   * @param bool $mainLinks
   *   Main Links.
   * @param bool $featuredProducts
   *   The featured products.
   * @param bool $onlyPublished
   *   Only published items.
   * @param bool $onlyWithPage
   *   Only with Page.
   *
   * @return mixed
   *   Found sections.
   */
  public function getMainsections($mainLinks = FALSE, $featuredProducts = FALSE, $onlyPublished = TRUE, $onlyWithPage = FALSE) {

    // Get published, active nodes, of type section.
    $query = $this->connection->select('node_field_data', 'n');

    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.type', 'section');

    // Get basic values.
    $query->fields('n', ['title', 'status']);
    $query->addField('n', 'nid', 'id');

    // Orphaned that contain no references to other sections.
    $query->leftJoin('node__field_section_parents', 'sect', 'sect.entity_id = n.nid');
    $query->isNull('sect.field_section_parents_target_id');

    // Has page join.
    $query->leftJoin('node__field_has_page', 'has_page', 'has_page.entity_id = n.nid');
    $query->fields('has_page', ['field_has_page_value']);
    if ($onlyWithPage) {
      $query->condition('has_page.field_has_page_value', 1);
    }

    // Active only.
    $query->leftJoin('node__field_active', 'active', 'active.entity_id = n.nid');
    $query->condition('active.field_active_value', 1);

    // Order by weight.
    $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
    $query->orderBy('weight.field_weight_value', 'DESC');
    $query->orderBy('n.title');

    $data = $query->execute()->fetchAll();

    // Retrieve the path aliases.
    if (!empty($data) && ($mainLinks || $featuredProducts)) {

      foreach ($data as $key => $item) {
        // Retrieve main links.
        if ($mainLinks) {
          $data[$key]->url = '';
          if (!empty($item->field_has_page_value)) {
            $data[$key]->url = new \stdClass();
            $data[$key]->url = $this->dataHelpers->getPathFromNid($item->id);
          }
        }

        // Retrieve first featured product only.
        if ($featuredProducts) {
          $product = $this->getFeaturedProduct($item->id);
          if (!empty($product[0])) {
            $data[$key]->featured_product = new \stdClass();
            $data[$key]->featured_product = $product[0];
          }
        }
      }
    }

    return $data;
  }

  /**
   * Get featured product information.
   *
   * @param int $categoryNid
   *   The category Node ID.
   *
   * @return mixed
   *   Returns featured products.
   */
  public function getFeaturedProduct($categoryNid) {

    // Get fietered product by categoryId.
    $query = $this->connection->select('node__field_featured_product', 'featured_product');
    $query->condition('featured_product.entity_id', $categoryNid);

    // Get product node information.
    $query->leftJoin('node_field_data', 'product', 'product.nid = featured_product.field_featured_product_target_id');
    $query->condition('product.type', 'product');

    // Title.
    $query->addField('product', 'title', 'product_title');

    // Pid as product id.
    $query->addField('product', 'nid', 'pid');
    $query->leftJoin('node__field_product_information', 'inf', 'inf.entity_id = product.nid');

    // DAM Image.
    $query->leftJoin('paragraph__field_dam_image', 'dam_image', 'dam_image.entity_id = inf.field_product_information_target_id');
    $query->leftJoin('media__field_asset', 'dam_asset', 'dam_asset.entity_id = dam_image.field_dam_image_target_id');
    $query->leftJoin('file_managed', 'dam_file_img', 'dam_file_img.fid = dam_asset.field_asset_target_id');
    $query->addField('dam_file_img', 'uri', 'dam_image_uri');

    // Image.
    $query->leftJoin('paragraph__field_image', 'image', 'image.entity_id = inf.field_product_information_target_id');
    $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
    $query->addField('file_img', 'uri', 'image_uri');

    $products = $query->execute()->fetchAll();

    // Append main link to product.
    foreach ($products as $key => $product) {
      $products[$key]->url = new \stdClass();
      $products[$key]->url = $this->dataHelpers->getPathFromNid($product->pid);
    }

    return $products;
  }

  /**
   * Get subsections that are directly related to given section.
   *
   * @param int $nid
   *   Given section.
   * @param int $offset
   *   Range offset.
   * @param int $limit
   *   Range limit.
   * @param bool $titlesOnly
   *   Load only titles.
   * @param bool $getExcerpt
   *   Include excerpt.
   * @param bool $onlyPublished
   *   Only published items.
   * @param bool $onlyWithPage
   *   Only with Page.
   *
   * @return array
   *   List of section data.
   */
  public function getSubsections($nid, $offset = 0, $limit = 200, $titlesOnly = FALSE, $getExcerpt = TRUE, $onlyPublished = TRUE, $onlyWithPage = FALSE) {
    $data = [];
    $data['excerpt'] = $excerpt = '';

    if ($getExcerpt) {
      $excerpt = $this->getSubsectionexcerpt($nid);
      if ($offset > 0 && $excerpt) {
        if ($offset > 0) {
          $offset -= 1;
        }
        else {
          $limit -= 1;
        }
      }
    }

    foreach ([TRUE, FALSE] as $getCount) {
      $query = $this->connection->select('node__field_section_parents', 'sect');
      $query->addField('sect', 'entity_id', 'id');
      $query->addField('sect', 'field_section_parents_target_id', 'section_id');
      $query->leftJoin('node__field_block_standalone_content', 'content', 'content.entity_id = sect.entity_id');
      $query->leftJoin('node__field_features_and_benefits', 'features', 'features.entity_id = sect.entity_id');
      $query->innerJoin('node_field_data', 'n', 'n.nid = sect.entity_id');

      // Dont query fields if only count is requested.
      if (!$getCount) {
        // Add basic values.
        $query->fields('n', ['title', 'status']);

        if (!$titlesOnly) {
          // Add tag.
          $query->leftJoin('paragraph__field_label', 'tag', 'tag.entity_id = content.field_block_standalone_content_target_id');
          $query->addField('tag', 'field_label_value', 'tags');

          // Add textfield.
          $query->leftJoin('paragraph__field_copy', 'copy', 'copy.entity_id = content.field_block_standalone_content_target_id');
          $query->addField('copy', 'field_copy_value', 'copy');
          $query->leftJoin('paragraph__field_description', 'description', 'description.entity_id = features.field_features_and_benefits_target_id');
          $query->addField('description', 'field_description_value', 'description');

          // DAM Image.
          $query->leftJoin('paragraph__field_dam_image', 'dam_image', 'dam_image.entity_id = content.field_block_standalone_content_target_id');
          $query->leftJoin('media__field_asset', 'dam_asset', 'dam_asset.entity_id = dam_image.field_dam_image_target_id');
          $query->leftJoin('file_managed', 'dam_file_img', 'dam_file_img.fid = dam_asset.field_asset_target_id');
          $query->addField('dam_file_img', 'uri', 'dam_image_uri');

          // Add image.
          $query->leftJoin('paragraph__field_image', 'image', 'image.entity_id = content.field_block_standalone_content_target_id');
          $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
          $query->addField('file_img', 'uri', 'image_uri');

          // DAM Image Features and Benefits.
          $query->leftJoin('paragraph__field_dam_image', 'dam_image_features', 'dam_image_features.entity_id = features.field_features_and_benefits_target_id');
          $query->leftJoin('media__field_asset', 'dam_asset_features', 'dam_asset_features.entity_id = dam_image_features.field_dam_image_target_id');
          $query->leftJoin('file_managed', 'dam_file_img_features', 'dam_file_img_features.fid = dam_asset_features.field_asset_target_id');
          $query->addField('dam_file_img_features', 'uri', 'dam_image_uri_features');

          // Add image Features and Benefits.
          $query->leftJoin('paragraph__field_image', 'image_features', 'image_features.entity_id = features.field_features_and_benefits_target_id');
          $query->leftJoin('file_managed', 'file_img_features', 'file_img_features.fid = image_features.field_image_target_id');
          $query->addField('file_img_features', 'uri', 'image_uri_features');

          // Add cta's.
          $query->leftJoin('paragraph__field_link', 'cta_link', 'cta_link.entity_id = content.field_block_standalone_content_target_id');
          $query->leftJoin('paragraph__field_target', 'cta_target', 'cta_target.entity_id = cta_link.field_link_target_id');
          $query->addField('cta_target', 'field_target_uri', 'link_uri');
          $query->leftJoin('paragraph__field_label', 'cta_label', 'cta_label.entity_id = cta_link.field_link_target_id');
          $query->addField('cta_label', 'field_label_value', 'link_title');
          $query->leftJoin('paragraph__field_cta_download', 'cta_download', 'cta_download.entity_id = content.field_block_standalone_content_target_id');
          $query->leftJoin('file_managed', 'file_dld', 'file_dld.fid = cta_download.field_cta_download_target_id');
          $query->addField('file_dld', 'uri', 'download_uri');
        }

      }
      // Filters and sorters.
      $query->leftJoin('node__field_has_page', 'has_page', 'has_page.entity_id = sect.entity_id');
      $query->leftJoin('node__field_separate_menu_column', 'has_sep_menu', 'has_sep_menu.entity_id = sect.entity_id');
      $query->leftJoin('node__field_active', 'active', 'active.entity_id = sect.entity_id');
      $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = sect.entity_id');
      $query->fields('has_page', ['field_has_page_value']);
      $query->fields('has_sep_menu', ['field_separate_menu_column_value']);

      if ($onlyPublished) {
        $query->condition('n.status', 1);
      }
      if ($onlyWithPage) {
        $query->condition('has_page.field_has_page_value', 1);
      }
      $query->condition('active.field_active_value', 1)
        ->condition('sect.field_section_parents_target_id', $nid);

      // Dont order if only count is requested.
      if (!$getCount) {
        $query->orderBy('weight.field_weight_value', 'DESC');
        $query->orderBy('n.title');
      }
      if ($getCount) {
        $data['total'] = $query->countQuery()->execute()->fetchField();
        if ($getExcerpt && $excerpt) {
          $data['total'] += 1;
        }
      }
      else {
        $query->range($offset, $limit);
        $rows = $query->execute()->fetchAll();
        $data['rows'] = $this->dataHelpers->parseCopyRows($this->dataHelpers->pathsFromData($rows, 'section'));
        $data['count'] = count($rows);
        if ($getExcerpt && $excerpt && $offset == 0) {
          $data['excerpt'] = $excerpt;
        }
      }
    }

    return $data;
  }

  /**
   * Get products that are directly related to given sections.
   *
   * @param array $nids
   *   Given sections.
   * @param int $offset
   *   Range offset.
   * @param int $limit
   *   Range limit.
   * @param bool $getHighlights
   *   Get all highlighted products.
   * @param bool $getExcerpt
   *   Include excerpt.
   * @param bool $onlyInMenu
   *   Only in menu.
   *
   * @return array
   *   List of product data.
   */
  public function getSubproducts(array $nids, $offset = 0, $limit = 200, $getHighlights = FALSE, $getExcerpt = TRUE, $onlyInMenu = FALSE) {
    $data = [];
    $data['excerpt'] = $excerpt = '';

    if ($getExcerpt) {
      $excerpt = $this->getSubsectionexcerpt($nids[0]);
      if (count($nids) == 1 && $excerpt) {
        if ($offset > 0) {
          $offset -= 1;
        }
        else {
          $limit -= 1;
        }
      }
    }

    foreach ([TRUE, FALSE] as $getCount) {
      $query = \Drupal::database()->select('node__field_prod_section', 'sect');
      $query->addField('sect', 'entity_id', 'id');
      $query->addField('sect', 'field_prod_section_target_id', 'section_id');
      $query->leftJoin('node__field_product_information', 'inf', 'inf.entity_id = sect.entity_id');
      $query->leftJoin('node_field_data', 'n', 'n.nid = sect.entity_id');

      // For debug.
      $query->addField('inf', 'field_product_information_target_id', 'inf_par_id');

      // Dont query fields if only count is requested.
      if (!$getCount) {
        // Add title.
        $query->fields('n', ['title']);

        // Add tag.
        $query->leftJoin('node__field_prod_tags', 'tags', 'tags.entity_id = sect.entity_id');
        $query->leftJoin('taxonomy_term_field_data', 'term', 'term.tid = tags.field_prod_tags_target_id');
        $query->addField('term', 'name', 'tags');

        // Add textfield.
        $query->leftJoin('paragraph__field_copy', 'copy', 'copy.entity_id = inf.field_product_information_target_id');
        $query->addField('copy', 'field_copy_value', 'copy');

        // DAM Image.
        $query->leftJoin('paragraph__field_dam_image', 'dam_image', 'dam_image.entity_id = inf.field_product_information_target_id');
        $query->leftJoin('media__field_asset', 'dam_asset', 'dam_asset.entity_id = dam_image.field_dam_image_target_id');
        $query->leftJoin('file_managed', 'dam_file_img', 'dam_file_img.fid = dam_asset.field_asset_target_id');
        $query->addField('dam_file_img', 'uri', 'dam_image_uri');

        // Add image.
        $query->leftJoin('paragraph__field_image', 'image', 'image.entity_id = inf.field_product_information_target_id');
        $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
        $query->addField('file_img', 'uri', 'image_uri');

        // Add cta's.
        $query->leftJoin('paragraph__field_cta_link', 'cta_link', 'cta_link.entity_id = inf.field_product_information_target_id');
        $query->addField('cta_link', 'field_cta_link_uri', 'link_uri');
        $query->addField('cta_link', 'field_cta_link_title', 'link_title');
        $query->leftJoin('paragraph__field_cta_download', 'cta_download', 'cta_download.entity_id = inf.field_product_information_target_id');
        $query->leftJoin('file_managed', 'file_dld', 'file_dld.fid = cta_download.field_cta_download_target_id');
        $query->addField('file_dld', 'uri', 'download_uri');
      }

      // Filters and sorters.
      $query->leftJoin('node__field_active', 'active', 'active.entity_id = sect.entity_id');
      $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = sect.entity_id');
      $query->condition('n.status', 1);
      $query->condition('active.field_active_value', 1);
      $query->condition('sect.field_prod_section_target_id', $nids, 'IN');
      if ($getHighlights) {
        $query->leftJoin('node__field_highlight', 'highlight', 'highlight.entity_id = sect.entity_id');
        $query->condition('highlight.field_highlight_value', 1);
      }
      if ($onlyInMenu) {
        $query->leftJoin('node__field_show_in_menu', 'menu_enable', 'menu_enable.entity_id = sect.entity_id');
        $query->condition('menu_enable.field_show_in_menu_value', 1);
      }

      // Dont order if only count is requested.
      if (!$getCount) {
        $query->orderBy('weight.field_weight_value', 'DESC');
        $query->orderBy('n.title');
      }
      if ($getCount) {
        $data['total'] = $query->countQuery()->execute()->fetchField();
        if ($getExcerpt && $excerpt) {
          $data['total'] += 1;
        }
      }
      else {
        $query->range($offset, $limit);
        $rows = $query->execute()->fetchAll();
        $data['rows'] = $this->dataHelpers->parseCopyRows($this->dataHelpers->pathsFromData($rows, 'product'));
        $data['count'] = count($rows);
        if ($getExcerpt && $excerpt && $offset == 0) {
          $data['excerpt'] = $excerpt;
        }
      }
    }
    return $data;
  }

  /**
   * Get excerpt for active section.
   *
   * @param int $nid
   *   Section nid.
   *
   * @return mixed
   *   Excerpt data if found.
   */
  public function getSubsectionexcerpt($nid) {
    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->fields('n', ['title']);
    $query->addField('n', 'nid', 'id');
    $query->condition('n.nid', $nid);

    $query->leftJoin('node__field_block_standalone_content', 'content', 'content.entity_id = n.nid');
    $query->addField('content', 'field_block_standalone_content_target_id', 'section_id');

    $query->leftJoin('node__field_features_and_benefits', 'features', 'features.entity_id = n.nid');

    // Add textfield.
    $query->leftJoin('paragraph__field_copy', 'copy', 'copy.entity_id = content.field_block_standalone_content_target_id');
    $query->addField('copy', 'field_copy_value', 'copy');
    $query->leftJoin('paragraph__field_description', 'description', 'description.entity_id = features.field_features_and_benefits_target_id');
    $query->addField('description', 'field_description_value', 'description');

    // Has page join.
    $query->leftJoin('node__field_has_page', 'has_page', 'has_page.entity_id = n.nid');
    $query->addField('has_page', 'field_has_page_value', 'has_page');

    // Filters and sorters.
    $query->range(0, 1);

    $result = $query->execute()->fetchObject();

    if ($result) {
      if (isset($result->description)) {
        $result->copy = $this->dataHelpers->parseCopy($result->description);
      }
      elseif (isset($result->copy)) {
        $result->copy = $this->dataHelpers->parseCopy($result->copy);
      }
      $result->url = '';
      if ($result->has_page) {
        $result->url = $this->dataHelpers->getPathFromNid($result->id);
      }
    }

    return $result;
  }

  /**
   * Get parent section of given nid.
   *
   * @param int $nid
   *   Node id.
   * @param string $ctype
   *   Node type.
   * @param bool $onlyPublished
   *   Load only published.
   *
   * @return mixed
   *   Parent section.
   */
  public function getParentSection($nid, $ctype, $onlyPublished = TRUE) {
    $query = $this->connection->select('node_field_data', 'n');

    if ($ctype == 'section') {
      $query->leftJoin('node__field_section_parents', 'sect', 'n.nid = sect.entity_id');
      $query->addField('sect', 'field_section_parents_target_id', 'section_id');
    }
    elseif ($ctype == 'product') {
      $query->leftJoin('node__field_prod_section', 'sect', 'n.nid = sect.entity_id');
      $query->addField('sect', 'field_prod_section_target_id', 'section_id');
    }

    // Add title.
    $query->fields('n', ['title', 'status']);
    $query->addField('n', 'nid', 'id');

    // Has page join.
    $query->leftJoin('node__field_has_page', 'has_page', 'has_page.entity_id = n.nid');
    $query->addField('has_page', 'field_has_page_value', 'has_page');

    // Active only.
    $query->leftJoin('node__field_active', 'active', 'active.entity_id = n.nid');
    $query->condition('active.field_active_value', 1);
    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.nid', $nid);
    $query->range(0, 1);
    $result = $query->execute()->fetchObject();
    return $result;
  }

}
