<?php

namespace Drupal\bhge_c55_product_gallery;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\TranslationManager;
use Drupal\image\Entity\ImageStyle;

/**
 * Data related helper methods for products/sections.
 */
class DataHelpers {

  public $stringTranslation;

  /**
   * {@inheritdoc}
   */
  public function __construct(TranslationManager $stringTranslation) {
    $this->stringTranslation = $stringTranslation;
  }

  /**
   * Structure data for json output.
   *
   * @param array $data
   *   Input result data.
   * @param int $offset
   *   Range offset.
   * @param int $limit
   *   Range limit.
   *
   * @return array
   *   Structured C55 data.
   */
  public function prepareData(array $data, $offset, $limit) {
    $preparedData = [];
    $preparedData['data'] = [];
    $preparedData['pagination'] = [
      'total' => $data['total'],
      'offset' => $offset,
      'limit' => $limit,
    ];
    $preparedData['statuscode'] = 200;
    if ($data['excerpt']) {
      $description = html_entity_decode($data['excerpt']->copy);
      $preparedData['data'][] = [
        'contentType' => 'text',
        'title' => Unicode::truncate($data['excerpt']->title, 80, TRUE, TRUE),
        'description' => $this->parseCopy($description),
        'url' => $data['excerpt']->url,
      ];
    }

    foreach ($data['rows'] as $row) {
      // Prepare CTA links.
      $links = [];
      if (isset($row->link_url)) {
        $links[] = [
          'url' => $row->link_url,
          'title' => $row->link_title,
        ];
      }
      if (isset($row->download_url)) {
        $links[] = [
          'url' => $row->download_url,
          'title' => $this->stringTranslation->translate('Download'),
        ];
      }
      $image = '';

      if (!empty($row->dam_image_uri_features)) {
        $image = $row->dam_image_uri_features;
      }
      elseif (!empty($row->image_uri_features)) {
        $image = $row->image_uri_features;
      }
      elseif (!empty($row->dam_image_uri)) {
        $image = $row->dam_image_uri;
      }
      elseif (!empty($row->image_uri)) {
        $image = $row->image_uri;
      }
      $description = html_entity_decode($row->copy);
      $preparedData['data'][] = [
        'contentType' => 'image',
        'gradient' => '',
        'title' => Unicode::truncate($row->title, 80, TRUE, TRUE),
        'description' => $this->parseCopy($description),
        'url' => $row->url,
        'type' => Unicode::truncate($row->tags, 30, TRUE, TRUE),
        'image' => !empty($image) ? ImageStyle::load('gallery_image')->buildUrl($image) : '',
        'links' => $links,
        'buttons' => [],
        'created' => '',
        'target' => '',
      ];

    }
    return $preparedData;
  }

  /**
   * Get usable URL's from drupal URI's.
   *
   * @param array $rows
   *   Input data from query.
   * @param string $type
   *   Node type.
   *
   * @return array
   *   Parsed data.
   */
  public function pathsFromData(array $rows, $type) {
    foreach ($rows as &$row) {
      foreach ($row as $key => $value) {
        if (substr($key, strlen($key) - 4) == '_uri' && $value) {
          if (substr($value, 0, 9) == 'public://') {
            $url = file_create_url($value);
          }
          elseif (substr($value, 0, 7) == 'entity:') {
            $url = Url::fromUri($value)->toString();
          }
          else {
            // Always add to datastructure to prevent notices.
            $url = '';
          }
          // TODO: parse external url?
          if (isset($url) && $url) {
            $row->{substr($key, 0, strlen($key) - 4) . '_url'} = $url;
          }
        }

        // Add link to result node.
        $row->url = '';
        if (($type == 'product' && $row->id) || ($row->id && !empty($row->field_has_page_value))) {
          $row->url = $this->getPathFromNid($row->id);
        }
      }
    }
    return $rows;
  }

  /**
   * Parse body text, truncate + strip tags.
   *
   * @param array $rows
   *   Input data from query.
   *
   * @return array
   *   Parsed data.
   */
  public function parseCopyRows(array $rows) {
    foreach ($rows as &$row) {
      if (isset($row->description) && $row->description) {
        $row->copy = $this->parseCopy($row->description);
      }
      if (isset($row->copy) && $row->copy) {
        $row->copy = $this->parseCopy($row->copy);
      }
    }
    return $rows;
  }

  /**
   * Parse copy text, truncate + strip tags.
   *
   * @param string $copy
   *   Input string.
   *
   * @return string
   *   Parsed copy.
   */
  public function parseCopy($copy) {
    if ($copy) {
      return Unicode::truncate(strip_tags($copy), 240, TRUE, TRUE);
    }
  }

  /**
   * Return full path for this node id.
   *
   * @param int $nid
   *   Node id.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   Path for this node id.
   */
  public function getPathFromNid($nid) {
    return Url::fromUri('entity:node/' . $nid)->toString();
  }

  /**
   * Validate if filter type is one of allowed.
   *
   * @param string $type
   *   Given filter type.
   *
   * @return string
   *   Validated type.
   */
  public function validateFilterType($type) {
    $allowedTypes = [
      'highlights',
      'mainsection',
      'section',
      'subsection',
      'childproducts',
    ];
    if (isset($type) && in_array($type, $allowedTypes)) {
      return $type;
    }
  }

}
