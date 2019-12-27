<?php

namespace Drupal\gemc_search;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;
use Drupal\search_api\Entity\Index;

/**
 * Search related helper methods.
 */
class SearchHelpers {

  private $searchExtension;

  /**
   * The search extension.
   */
  public function __construct($searchExtension) {
    $this->searchExtension = $searchExtension;
  }

  /**
   * Get description.
   */
  private function getDescription($result) {
    $copy = '';
    if (!empty($result['body']) && !empty($result['body']->getValues()) && !empty($result['body']->getValues()[0])) {
      $copy = $result['body']->getValues()[0];
    }
    $strippedTags = strip_tags(str_replace(['<', '>'], [' <', '> '], $copy));
    $strippedTags = Html::decodeEntities($strippedTags);

    return Unicode::truncate($strippedTags, 197, TRUE, TRUE);
  }

  /**
   * Get Image.
   */
  private function getImage($result) {
    $image = '';
    if (!empty($result['field_image_uri']) && !empty($result['field_image_uri']->getValues()) && !empty($result['field_image_uri']->getValues()[0])) {
      $image = $result['field_image_uri']->getValues()[0];
      $image = [
        'normal' => ImageStyle::load('s01_image')->buildUrl($image),
        'small' => ImageStyle::load('s01_image_small')->buildUrl($image),
        'alt' => !empty($result['field_image_alt']->getValues()) ? $result['field_image_alt']->getValues()[0] : '',
      ];
    }
    return $image;
  }

  /**
   * Get Url.
   */
  private function getUrl($item) {
    /** @var \Drupal\search_api\Item\Item $item */
    return $item->getDatasource()->getItemUrl($item->getOriginalObject())->toString();
  }

  /**
   * Get Title.
   */
  private function getTitle($result) {
    $values = $result['title']->getValues();
    $value = '';
    if (!empty($values)) {
      $value = is_object($values[0]) ? $values[0]->getOriginalText() : $values[0];
    }

    return $value;
  }

  /**
   * Get File Url.
   */
  private function getFileUrl($result) {
    return !empty($result['file_url']->getValues()) ? file_create_url($result['file_url']->getValues()[0]) : '';
  }

  /**
   * Get The File Extension.
   */
  private function getFileExtension($result) {
    return !empty($result['file_extension']->getValues()) ? $result['file_extension']->getValues()[0] : NULL;
  }

  /**
   * Get the file type.
   */
  private function getType($result) {
    return !empty($result['type']->getValues()) ? $result['type']->getValues()[0] : '';
  }

  /**
   * Get the type label.
   */
  private function getTypeLabel($result) {
    return !empty($result['content_type_label']->getValues()) ? $result['content_type_label']->getValues()[0] : '';
  }

  /**
   * Parsing results to array.
   *
   * @param array $results
   *   Passing the results from query.
   *
   * @return array
   *   Returning the formatted array.
   */
  public function parseResults(array $results) {
    $list = [];

    foreach ($results as $item) {
      $result = $item->getFields();
      $url = $this->getUrl($item);
      $title = $this->getTitle($result);
      $description = $this->getDescription($result);
      $type = $this->getType($result);
      $isDownloadable = FALSE;
      if ($type == 'download' || $type == 'orbit_article') {
        if (empty($result['field_gated_content']) || empty($result['field_gated_content']->getValues())) {
          $isDownloadable = TRUE;
          $url = $this->getFileUrl($result);
        }

      }
      $fileExtension = $this->getFileExtension($result);
      $list[] = [
        'url' => $url,
        'title' => $this->searchExtension->highlightSearchedWord($title),
        'image' => $this->getImage($result),
        'description' => $this->searchExtension->highlightSearchedWord($description),
        'type' => $this->getTypeLabel($result),
        'fileExtension' => $fileExtension,
        'isDownloadable' => $isDownloadable,
      ];
    }

    return $list;
  }

  /**
   * Get working search index.
   *
   * @return mixed
   *   returns the index of the search.
   */
  public function getSearchIndex() {
    $allSearchIndexes = Index::loadMultiple();
    foreach ($allSearchIndexes as $index) {
      if ($index->status()) {
        return $index;
      }
    }
    return NULL;
  }

}
