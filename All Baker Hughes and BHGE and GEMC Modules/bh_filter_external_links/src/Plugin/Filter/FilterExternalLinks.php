<?php

namespace Drupal\bh_filter_external_links\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a filter to force external links to open in new window.
 *
 * @Filter(
 *   id = "bh_filter_external_links",
 *   title = @Translation("Open external links in new window"),
 *   description = @Translation("Adds target='_blank' to external links"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 *   weight = 9
 * )
 */
class FilterExternalLinks extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    return new FilterProcessResult(_filter_external_links_process($text));
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    return $this->t('External links will be opened in a new window.');
  }

}
