<?php

namespace Drupal\bh_settings\Extension;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Create custom Twig UI extentions.
 */
class UIUtilsExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_util_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('processLinkField', [
        $this,
        'processLinkField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('formatSize', [
        $this,
        'formatSize',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getUrlFromEntity', [
        $this,
        'getUrlFromEntity',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('labelFromLinkField', [
        $this,
        'labelFromLinkField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('processDateField', [
        $this,
        'processDateField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('determineHrefLinkTarget', [
        $this,
        'determineHrefLinkTarget',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('showStockChangeIcon', [
        $this,
        'showStockChangeIcon',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getFileNameWithoutExtension', [
        $this,
        'getFileNameWithoutExtension',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomy', [
        $this,
        'getTaxonomy',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isSiteInternal', [
        $this,
        'isSiteInternal',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getMunchkinId', [
        $this,
        'getMunchkinId',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getPageMetatags', [
        $this,
        'getPageMetatags',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getNodeUrl', [
        $this,
        'getNodeUrl',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getFileInformation', [
        $this,
        'getFileInformation',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('stripHtml', [
        $this,
        'stripHtml',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('imageFileExists', [
        $this,
        'imageFileExists',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('processFileField', [
        $this,
        'processFileField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isLoggedInUser', [
        $this,
        'isLoggedInUser',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('decodeStringCharacters', [
        $this,
        'decodeStringCharacters',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomyTermName', [
        $this,
        'getTaxonomyTermName',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Returns true if user is logged-in, otherwise false.
   *
   * @return bool
   *   Returns wether the current user is authenticated.
   */
  public function isLoggedInUser() {
    $currentUser = \Drupal::currentUser();
    return $currentUser->isAuthenticated();
  }

  /**
   * Return URL or empty string of link field.
   */
  public function processLinkField($linkField) {
    return $linkField && $linkField->uri ? Url::fromUri($linkField->uri) : '';
  }

  /**
   * Get label of link field.
   */
  public function labelFromLinkField($linkField) {
    return $linkField && $linkField->title ? $linkField->title : '';
  }

  /**
   * Get label of link field.
   */
  public function getUrlFromEntity($entity) {
    return Url::fromRoute('entity.node.canonical', ['node' => $entity->get('nid')->value], ['absolute' => TRUE])->toString();
  }

  /**
   * Strip extention from filename.
   */
  public function getFileNameWithoutExtension($filename) {
    $file_info = pathinfo($filename);
    return $file_info['filename'];
  }

  /**
   * Human readable file size.
   */
  public function formatSize($size) {
    return format_size($size);
  }

  /**
   * Human readable date.
   */
  public function processDateField($dateField) {
    if ($dateField) {
      return date('M d, Y', strtotime($dateField));
    }
    // return;.
  }

  /**
   * Determine if href target must be _blank.
   */
  public function determineHrefLinkTarget($linkField) {
    if (is_object($linkField)) {
      $url = !empty($linkField->uri) ? Url::fromUri($linkField->uri) : '';
      if (!is_string($url)) {
        if ($url->isExternal()) {
          return '_blank';
        }
      }
    }
    // return;.
  }

  /**
   * Determine if we should show the stock icon in the menu.
   */
  public function showStockChangeIcon() {
    $config = \Drupal::configFactory()->get('bh.general_settings');
    return $config->get('show_stock_change_icon_in_menu');
  }

  /**
   * Determine if current site is internal.
   */
  public function isSiteInternal() {
    $config = \Drupal::configFactory()->get('bh.general_settings');
    return $config->get('internal_site');
  }

  /**
   * Determine if current site has search field enabled.
   */
  public function getMunchkinId() {
    $config = \Drupal::configFactory()->get('bh.general_settings');
    return $config->get('marketo_munchkin_id');
  }

  /**
   * Get contacts terms.
   */
  public function getTaxonomy($vid, $loadEntity = FALSE) {
    $entityTypeManager = \Drupal::service('entity_type.manager');
    return $entityTypeManager->getStorage('taxonomy_term')->loadTree($vid, 0, NULL, $loadEntity);
  }

  /**
   * Get meta description.
   */
  public function getPageMetatags() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if (is_object($node) && !is_null($node->field_metatags)) {
      $metatags = unserialize($node->field_metatags->value);
      return isset($metatags['description']) ? $metatags['description'] : '';
    }
    return '';
  }

  /**
   * Get url of node.
   */
  public function getNodeUrl($node) {
    global $base_url;
    if (!is_null($node)) {
      $nodeUrl = $node->url();
      return $base_url . $nodeUrl;
    }
    return '#';
  }

  /**
   * Get file information from file media bundle.
   */
  public function getFileInformation($fileMediaBundle) {
    $file = $fileMediaBundle->field_file;
    if (!is_null($file)) {
      $file = $file->entity;
      return $this->processFileField($file);
    }
    return NULL;
  }

  /**
   * Sanetize HTML.
   */
  public function stripHtml($text) {
    if (is_string($text)) {
      return html_entity_decode(strip_tags(preg_replace("/&nbsp;/", ' ', $text)));
    }
  }

  /**
   * Check if image file exists.
   */
  public function imageFileExists($image) {
    return file_exists($image);
  }

  /**
   * Process file field.
   */
  public function processFileField($file) {
    if (!empty($file) && $file instanceof File) {
      $filesize = format_size($file->filesize->value);
      $file_path_info = pathinfo($file->filename->value);
      $url = $file->hasField('uri') ? file_create_url($file->get('uri')->value) : '';
      $language = $file->language();
      return [
        'name' => !empty($file_path_info) ? $file_path_info['filename'] : '',
        'size' => $filesize,
        'type' => !empty($file_path_info) ? $file_path_info['extension'] : '',
        'language' => !empty($language) ? $language->getName() : '',
        'url' => $url,
      ];
    }
    return NULL;
  }

  /**
   * Decode string special characters.
   *
   * Return string.
   */
  public function decodeStringCharacters($string) {
    return htmlspecialchars_decode($string);
  }

  /**
   * Return taxonomy term name from tid.
   */
  public function getTaxonomyTermName($tid) {
    $name = '';
    if (!empty($tid)) {
      $term = Term::load($tid);
      if (!empty($term)) {
        $name = $term->getName();
      }
    }
    return $name;
  }

}
