<?php

namespace Drupal\gemc_c119_featured_industries;

use Drupal\Core\Database\Connection;
use Drupal\image\Entity\ImageStyle;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Featured industries data provider.
 */
class IndustriesData {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get featured industries for given product category node.
   *
   * @param \Drupal\node\ParagraphInterface $paragraph
   *   Product category node.
   *
   * @return array
   *   Featured industries data.
   */
  public function getFeaturedIndustries(ParagraphInterface $paragraph) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = $this->connection->select('node_field_data', 'n');
    $query->distinct();
    $query->condition('n.type', ['industry', 'industry_segment'], 'IN');
    $query->condition('n.status', 1);
    $query->condition('n.langcode', $language);
    $query->fields('n', ['nid', 'title']);

    $query->leftJoin('paragraph__field_featured_industries', 'reference', 'reference.field_featured_industries_target_id = n.nid');
    $query->condition('reference.entity_id', $paragraph->id());

    $query->leftJoin('node__field_parent_industry', 'parent', 'parent.entity_id = n.nid');
    $query->leftJoin('node__field_industry_color', 'industry_color', 'industry_color.entity_id = parent.field_parent_industry_target_id');
    $query->addField('industry_color', 'field_industry_color_value', 'parent_color');

    $query->leftJoin('node__field_industry_color', 'color', 'color.entity_id = n.nid');
    $query->addField('color', 'field_industry_color_value', 'color');

    // Add image.
    $query->leftJoin('node__field_image', 'image', 'image.entity_id = n.nid');
    $query->leftJoin('file_managed', 'file_img', 'file_img.fid = image.field_image_target_id');
    $query->addField('file_img', 'uri');

    $query->orderBy('reference.delta');
    return $query->execute()->fetchAll();
  }

  /**
   * Prepare featured industries data.
   *
   * @param \Drupal\node\ParagraphInterface $paragraph
   *   Product category node.
   *
   * @return array
   *   Prepared featured industries data.
   */
  public function prepareData(ParagraphInterface $paragraph) {
    $result = [];
    $industries = $this->getFeaturedIndustries($paragraph);
    foreach ($industries as $industry) {
      $result[] = [
        'contentType' => 'industry',
        'title' => $industry->title,
        'themeId' => !empty($industry->color) ? $industry->color : $industry->parent_color,
        'image' => [
          'normal' => ImageStyle::load('cards_carousel_image')
            ->buildUrl($industry->uri),
          'small' => ImageStyle::load('cards_carousel_image')
            ->buildUrl($industry->uri),
        ],
        'link' => [
          'url' => "/node/{$industry->nid}",
        ],
      ];
    }
    return $result;
  }

}
