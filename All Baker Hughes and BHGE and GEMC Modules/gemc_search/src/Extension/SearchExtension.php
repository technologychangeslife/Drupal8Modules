<?php

namespace Drupal\gemc_search\Extension;

use Drupal\Component\Utility\Html;

/**
 * Create custom Twig UI extentions.
 */
class SearchExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'search_api_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('addPageToUrl', [
        $this,
        'addPageToUrl',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Get search key from request.
   *
   * Return string.
   */
  public function getSearchTerm() {
    return \Drupal::request()->get('search');
  }

  /**
   * Highlight searched word.
   *
   * Return string.
   */
  public function highlightSearchedWord($string) {
    $searchTerm = strip_tags(str_replace(['<', '>'], [' <', '> '], $this->getSearchTerm()));
    $searchTerm = Html::decodeEntities($searchTerm);
    if (!empty($searchTerm)) {
      return preg_replace("/" . preg_quote($searchTerm, '/') . "/i", "<strong>\$0</strong>", $string);
    }
    return $string;
  }

  /**
   * Add page to url.
   */
  public function addPageToUrl($page) {
    $request = \Drupal::request();

    $searchQuery = [
      'search' => $request->get('search'),
      'page' => $page,
    ];

    return '?' . http_build_query($searchQuery);
  }

}
