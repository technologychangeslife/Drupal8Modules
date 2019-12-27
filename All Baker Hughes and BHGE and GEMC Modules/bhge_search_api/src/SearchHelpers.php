<?php

namespace Drupal\bhge_search_api;

use Drupal\Component\Utility\Unicode;
use Drupal\search_api\Entity\Index;

/**
 * Search related helper methods.
 */
class SearchHelpers {

  /**
   * The get copy function.
   *
   * @return string
   *   Returns stripped copy field.
   */
  public function getCopy($result) {
    $copy = '';
    $copy_fields = [
    'body',
    'field_copy',
    'field_description',
    'product_copy',
    'field_section_description',
    'field_section_copy'
    ];
    foreach ($copy_fields as $field) {
      if (!empty($result[$field]) && !empty($result[$field]->getValues()) && !empty($result[$field]->getValues()[0])) {
        $copy = $result[$field]->getValues()[0];
      }
    }
    $strippedTags = str_replace("&nbsp;", "", strip_tags($copy));
    return Unicode::truncate($strippedTags, 197, TRUE, TRUE);

  }

  /**
   * The get Image function.
   */
  public function getImage($result) {
    $image = '';
    if (!empty($result['main_image_url']) && !empty($result['main_image_url']->getValues()) && !empty($result['main_image_url']->getValues()[0])) {
      $image = $result['main_image_url']->getValues()[0];
    }

    return $image;
  }

  /**
   * The Get Url function.
   */
  public function getUrl($result) {
    return !empty($result['page_url']) && !empty($result['page_url']->getValues()) ? $result['page_url']->getValues()[0] : '';
  }

  /**
   * The get title function.
   */
  public function getTitle($result) {
    return !empty($result['title']->getValues()) ? $result['title']->getValues()[0] : '';
  }

  /**
   * The get category function.
   */
  public function getCategory($result) {
    if (!empty($result['topic_name']) && !empty($result['topic_name']->getValues()[0]) && !empty($result['topic_name']->getValues()[0])) {
      return $result['topic_name']->getValues()[0];
    }
    elseif (!empty($result['product_tag']) && !empty($result['product_tag']->getValues()[0]) && !empty($result['product_tag']->getValues()[0])) {
      return $result['product_tag']->getValues()[0];
    }
    else {
      $contentType = $result['type']->getValues()[0];
      if ($contentType == 'section') {
        return 'Products Sectionpage';
      }
      elseif ($contentType == 'blog_post') {
        return 'Blog Post';
      }
      elseif ($contentType == 'news_item') {
        return 'News Item';
      }
      elseif ($contentType == 'event_item') {
        return 'Event Session';
      }
      return $contentType !== 'product' ? ucwords($contentType) : '';
    }
  }

  /**
   * The get download link function.
   */
  public function getDownloadLink($result) {
    if (!empty($result['download_link']) && !empty($result['download_link']->getValues())) {
      return $result['download_link']->getValues()[0];
    }
    return '';
  }

  /**
   * The get CTA link Function.
   */
  public function getCtaLink($result) {
    if (!empty($result['cta_link']) && !empty($result['cta_link']->getValues()) && !empty($result['cta_link_title']->getValues())) {
      return [
      'label' => $result['cta_link_title']->getValues()[0],
      'url' => $result['cta_link']->getValues()[0]
      ];
    }
    return [];
  }

  /**
   * The get file data function.
   */
  public function getFileData($result) {
    if ($result['type']->getValues()[0] == 'document') {
      // File type.
      $type = '';
      if (!empty($result['field_file_type']) && !empty($result['field_file_type']->getValues())) {
        $type = pathinfo($result['field_file_type']->getValues()[0])['filename'];
      }

      // Language.
      $languageManager = \Drupal::service('language_manager');
      $language = '';
      if (!empty($result['field_file_langcode']) && !empty($result['field_file_langcode']->getValues())) {
        $language = $result['field_file_langcode']->getValues()[0];
        if (!empty($language) && !empty($languageManager->getLanguage($language))) {
          $language = $languageManager->getLanguage($language);
          $language = $language->getName();
        }
      }

      // File size.
      $filesize = '';
      if (!empty($result['field_file_filesize']) && !empty($result['field_file_filesize']->getValues())) {
        $filesize = format_size($result['field_file_filesize']->getValues()[0]);
      }

      // File url.
      $fileurl = '';
      $gated = FALSE;
      if (!empty($result['file_url']) && !empty($result['file_url']->getValues())) {
        if (!empty($result['field_gated_content']) && !empty($result['field_gated_content']->getValues()) && $result['field_gated_content']->getValues()[0]) {
          $gated = $result['field_gated_content']->getValues()[0];
          $fileurl = $result['page_url']->getValues()[0];
        }
        else {
          $fileurl = $result['file_url']->getValues()[0];
        }
      }

      return [
        'type' => $type,
        'language' => $language,
        'size' => $filesize,
        'url' => $fileurl,
        'gated' => $gated,
      ];
    }

    return [];
  }

  /**
   * Get event Details.
   */
  public function getEventDetails($result) {

    if ($result['type']->getValues()[0] == 'event') {
      $event_start = '';
      $event_end = '';
      $event_single_day = FALSE;
      $event_start_date = '';
      $event_end_date = '';
      if (!empty($result['field_eventdate']) && !empty($result['field_eventdate']->getValues()) && !empty($result['field_event_end_date']) && !empty($result['field_event_end_date']->getValues())) {
        $event_start = $result['field_eventdate']->getValues()[0];
        $event_end = $result['field_event_end_date']->getValues()[0];

        if ($event_start == $event_end) {
          // Event start date.
          $event_start_date = format_date($result['field_eventdate']->getValues()[0], '', $format = 'j F Y ', $timezone = NULL, $langcode = NULL);
          $event_single_day = TRUE;
        }
        else {
          // Event start date.
          $event_start_date = format_date($result['field_eventdate']->getValues()[0], '', $format = 'j F - ', $timezone = NULL, $langcode = NULL);
          // Event end date.
          $event_end_date = format_date($result['field_event_end_date']->getValues()[0], '', $format = 'j F Y', $timezone = NULL, $langcode = NULL);
        }
      }
      // Event start date
      /* $event_start_date = '';
      if (!empty($result['field_eventdate']) && !empty($result['field_eventdate']->getValues())) {
      $event_start_date = format_date($result['field_eventdate']->getValues()[0], '', $format = 'j F - ', $timezone = NULL, $langcode = NULL);
      }

      //Event end date
      $event_end_date = '';
      if (!empty($result['field_event_end_date']) && !empty($result['field_event_end_date']->getValues())) {
      $event_end_date = format_date($result['field_event_end_date']->getValues()[0], '', $format = 'j F Y', $timezone = NULL, $langcode = NULL);
      }*/

      // Event description.
      $event_desc = '';
      if (!empty($result['field_event_description']) && !empty($result['field_event_description']->getValues())) {
        // $event_desc = $result['field_event_description']->getValues()[0];
        $event_desc = str_replace("&nbsp;", "", strip_tags($result['field_event_description']->getValues()[0]));
        // Return Unicode::truncate($strippedTags, 197, TRUE, TRUE);.
      }

      // Event type.
      $event_type = '';
      if (!empty($result['field_event_type']) && !empty($result['field_event_type']->getValues())) {
        $event_type = $result['name']->getValues()[0];
      }

      // Event venue.
      $event_venue = [];

      if (!empty($result['address_line1']) && !empty($result['address_line1']->getValues())) {
        $event_venue['address_line1'] = $result['address_line1']->getValues()[0];
      }

      if (!empty($result['address_line2']) && !empty($result['address_line2']->getValues())) {
        $event_venue['address_line2'] = $result['address_line2']->getValues()[0];
      }

      if (!empty($result['locality']) && !empty($result['locality']->getValues())) {
        $event_venue['locality'] = $result['locality']->getValues()[0];
      }

      if (!empty($result['administrative_area']) && !empty($result['administrative_area']->getValues())) {
        $event_venue['administrative_area'] = $result['administrative_area']->getValues()[0];
      }

      if (!empty($result['postal_code']) && !empty($result['postal_code']->getValues())) {
        $event_venue['postal_code'] = $result['postal_code']->getValues()[0];
      }

      if (!empty($result['country_code']) && !empty($result['country_code']->getValues())) {
        $event_venue['country_code'] = $result['country_code']->getValues()[0];
      }
      // End of event venue.

      // Event register url and title.
      $event_register_url = [];

      if (!empty($result['field_registration_url']) && !empty($result['field_registration_url']->getValues())) {
        $event_register_url['uri'] = $result['field_registration_url']->getValues()[0];
      }

      if (!empty($result['title_2']) && !empty($result['title_2']->getValues())) {
        $event_register_url['title'] = $result['title_2']->getValues()[0];
      }
      // End of registration url and title.

      // Event landing page url and title.
      $event_view_url = [];

      if (!empty($result['field_view_event']) && !empty($result['field_view_event']->getValues())) {
        $event_view_url['uri'] = $result['field_view_event']->getValues()[0];
      }

      if (!empty($result['title_1']) && !empty($result['title_1']->getValues())) {
        $event_view_url['title'] = $result['title_1']->getValues()[0];
      }
      // End of event landing page url and title.

      return [
        'start_date' => $event_start_date,
        'end_date' => $event_end_date,
        'single_day_event' => $event_single_day,
        'description' => $event_desc,
        'type' => $event_type,
        'venue' => $event_venue,
        'register_url' => $event_register_url,
        'event_page_url' => $event_view_url,
      ];

    }
    return [];
  }

  /**
   * Get working search index.
   *
   * @return mixed
   *   Returns the indexing.
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
