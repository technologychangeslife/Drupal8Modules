<?php

namespace Drupal\gemc_c115_120_section;

use Drupal\Core\Database\Connection;

/**
 * Load product related data.
 */
class ProductData {

  public static $limitSection = 8;

  public static $limitSubsection = 10;

  protected $connection;

  private $dataHelpers;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   * @param \Drupal\gemc_c115_120_section\DataHelpers $dataHelpers
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
    foreach ($data['items'] as $row) {
      $filterIds[] = $row->id;
    }
    $data['nids'] = $filterIds;
    return $data;
  }

  /**
   * Get all published and active main sections.
   *
   * @param bool $mainLinks
   *   Load main links.
   * @param bool $onlyPublished
   *   Load only published.
   *
   * @return mixed
   *   Found sections.
   */
  public function getMainsections($mainLinks = FALSE, $onlyPublished = TRUE) {

    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    // Get published, active nodes, of type section.
    $query = $this->connection->select('node_field_data', 'n');
    $query->distinct();

    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.type', 'section');
    $query->condition('n.langcode', $language);

    // Get basic values.
    $query->fields('n', ['title', 'status']);
    $query->addField('n', 'nid', 'id');

    // Orphaned that contain no references to other sections.
    $query->leftJoin('node__field_section_parents', 'sect', 'sect.entity_id = n.nid');
    $query->isNull('sect.field_section_parents_target_id');

    // Order by weight.
    $query->leftJoin('node__field_weight', 'weight', 'weight.entity_id = n.nid');
    $query->orderBy('weight.field_weight_value', 'DESC');
    $query->orderBy('n.title');

    $data = $query->execute()->fetchAll();

    return $data;
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
   *   Get only published.
   * @param int $current_nid
   *   The current node id.
   *
   * @return array
   *   List of section data.
   */
  public function getSubsections($nid, $offset = 0, $limit = 200, $titlesOnly = FALSE, $getExcerpt = TRUE, $onlyPublished = TRUE, $current_nid = NULL) {
    $data = [];
    $data['excerpt'] = $excerpt = '';
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    if ($getExcerpt) {
      $excerpt = $this->getSubsectionexcerpt($nid);
      if ($excerpt) {
        if ($offset > 0) {
          $offset -= 1;
        }
        else {
          $limit -= 1;
        }
      }
    }

    $query = $this->connection->select('node__field_section_parents', 'sect');
    $query->distinct();
    $query->addField('sect', 'entity_id', 'id');
    $query->addField('sect', 'field_section_parents_target_id', 'section_id');
    $query->innerJoin('node_field_data', 'n', 'n.nid = sect.entity_id');

    // Add basic values.
    $query->fields('n', ['title', 'status']);

    $query->leftJoin('node__body', 'body', 'body.entity_id = n.nid');
    if (!$titlesOnly) {
      // Add textfield.
      // $query->leftJoin('node__body', 'body', 'body.entity_id = n.nid');.
      $query->addField('body', 'body_value', 'body');

      // Add image.
      $query->leftJoin('node__field_image', 'image', 'image.entity_id = n.nid');
      $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
      $query->addField('file_img', 'uri', 'image_uri');
    }

    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.type', ['section', 'product'], 'IN');
    $query->condition('sect.field_section_parents_target_id', $nid);
    $query->condition('sect.langcode', $language);
    $query->condition('n.langcode', $language);
    $query->condition('body.langcode', $language);

    if ($current_nid) {
      // Also we can have items placed in subcategory field.
      $query->leftJoin('paragraph__field_subcategory', 'p_fsc', 'p_fsc.field_subcategory_target_id = sect.entity_id AND p_fsc.bundle = :bundle', [':bundle' => 'category_solutions']);
      $query->orderBy('p_fsc.delta');
    }
    else {
      $query->leftJoin('paragraph__field_category', 'p_fc', 'p_fc.field_category_target_id = sect.entity_id AND p_fc.bundle = :bundle', [':bundle' => 'category_solutions']);
    }

    $query->orderBy('n.title');
    $data['total'] = $query->countQuery()->execute()->fetchField();

    if ($getExcerpt && $excerpt) {
      $data['total'] += 1;
    }

    $query->range($offset, $limit);
    $rows = $query->execute()->fetchAll();
    $data['items'] = $rows;
    $data['count'] = count($rows);
    if ($getExcerpt && $excerpt && $offset == 0) {
      $data['excerpt'] = $excerpt;
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
   * @param bool $getExcerpt
   *   Include excerpt.
   * @param string $onlyInMenu
   *   Only in menu.
   * @param string $titlesOnly
   *   Titles only.
   *
   * @return array
   *   List of product data.
   */
  public function getSubproducts(array $nids, $offset = 0, $limit = 200, $getExcerpt = TRUE, $onlyInMenu = FALSE, $titlesOnly = FALSE) {
    $data = [];
    $data['excerpt'] = $excerpt = '';
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    if ($getExcerpt && $offset == 0) {
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

    $query = \Drupal::database()->select('node__field_prod_section', 'sect');
    $query->distinct();
    $query->addField('sect', 'entity_id', 'id');
    $query->addField('sect', 'field_prod_section_target_id', 'section_id');
    $query->leftJoin('node_field_data', 'n', 'n.nid = sect.entity_id');

    // Add title.
    $query->fields('n', ['title']);

    // Add tag.
    $query->leftJoin('node__field_prod_tags', 'tags', 'tags.entity_id = sect.entity_id');
    $query->leftJoin('taxonomy_term_field_data', 'term', 'term.tid = tags.field_prod_tags_target_id');
    $query->addField('term', 'name', 'tags');

    if (!$titlesOnly) {
      // Add textfield.
      $query->leftJoin('node__body', 'body', 'body.entity_id = n.nid');
      $query->addField('body', 'body_value', 'body');

      // Add image.
      $query->leftJoin('node__field_image', 'image', 'image.entity_id = n.nid');
      $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
      $query->addField('file_img', 'uri', 'image_uri');
    }

    // Filters and sorters.
    $query->condition('n.status', 1);
    $query->condition('n.type', ['section', 'product'], 'IN');
    $query->condition('n.langcode', $language);
    $query->condition('sect.field_prod_section_target_id', $nids, 'IN');
    $query->condition('sect.langcode', $language);
    $query->condition('body.langcode', $language);
    if ($onlyInMenu) {
      $query->leftJoin('node__field_show_in_menu', 'menu_enable', 'menu_enable.entity_id = sect.entity_id');
      $query->condition('menu_enable.field_show_in_menu_value', 1);
    }
    $query->orderBy('n.title');

    $data['total'] = $query->countQuery()->execute()->fetchField();
    if ($getExcerpt && $excerpt) {
      $data['total'] += 1;
    }

    $query->range($offset, $limit);
    $rows = $query->execute()->fetchAll();
    $data['items'] = $rows;
    $data['count'] = count($rows);
    if ($excerpt) {
      $data['excerpt'] = $excerpt;
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
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->fields('n', ['title']);
    $query->addField('n', 'nid', 'id');
    $query->condition('n.nid', $nid);
    $query->condition('n.langcode', $language);

    // Add textfield.
    $query->leftJoin('node__body', 'body', 'body.entity_id = n.nid');
    $query->addField('body', 'body_value', 'body');

    // Filters and sorters.
    $query->range(0, 1);

    $result = $query->execute()->fetch();

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

    // Published only.
    if ($onlyPublished) {
      $query->condition('n.status', 1);
    }
    $query->condition('n.nid', $nid);
    $query->range(0, 1);
    $result = $query->execute()->fetch();
    return $result;
  }

}
