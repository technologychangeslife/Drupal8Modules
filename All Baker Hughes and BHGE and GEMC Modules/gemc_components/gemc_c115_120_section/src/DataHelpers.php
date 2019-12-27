<?php

namespace Drupal\gemc_c115_120_section;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\TranslationManager;

/**
 * Data related helper methods for products/sections.
 */
class DataHelpers {

  public $stringTranslation;

  protected $fieldDataService;

  /**
   * {@inheritdoc}
   */
  public function __construct(TranslationManager $stringTranslation, $fieldDataService) {
    $this->stringTranslation = $stringTranslation;
    $this->fieldDataService = $fieldDataService;
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
    if ($data['excerpt']) {
      $preparedData[] = [
        'contentType' => 'text',
        'type' => '',
        'title' => $data['excerpt']->title,
        'description' => Unicode::truncate($data['excerpt']->body, 240, TRUE, TRUE),
        'link' => [
          'label' => $data['excerpt']->title,
          'url' => $this->getPathFromNid($data['excerpt']->id),
        ],
      ];
    }

    foreach ($data['items'] as $item) {
      // Prepare CTA links.
      $image = $links = [];

      $image_small = $this->fieldDataService->getStyleUrl($item->image_uri, 'medium');
      $image_normal = $this->fieldDataService->getStyleUrl($item->image_uri, 'thumbnail_normal');
      if (!empty($image_normal)) {
        $image = [
          'normal' => $image_normal,
          'small' => $image_small,
          'alt' => $item->title,
        ];
      }
      $preparedData[] = [
        'contentType' => 'image',
        'title' => $item->title,
        'type' => property_exists($item, 'tags') ? Unicode::truncate($item->tags, 30, TRUE, FALSE) : '',
        'image' => $image,
        'link' => [
          'label' => $item->title,
          'url' => $this->getPathFromNid($item->id),
        ],
      ];

    }
    return $preparedData;
  }

  /**
   * Format filter array.
   *
   * @param array $filters
   *   Range limit.
   *
   * @return array
   *   Structured C55 data.
   */
  public function prepareFilters(array $filters) {
    $newFilters = [];
    foreach ($filters as $key => $filter) {
      $newFilters[] = [
        'contentType' => !empty($filter->type) ? $filter->type : 'subsection',
        'sort' => NULL,
        'topic' => !empty($filter->id) ? $filter->id : NULL,
        'pid' => NULL,
        'label' => $filter->title,
      ];
    }

    // Set first filter active.
    if (isset($newFilters[0])) {
      $newFilters[0]['class'] = 'selected is-selected';
    }
    return $newFilters;
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
