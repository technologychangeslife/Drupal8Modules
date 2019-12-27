<?php

namespace Drupal\bhge_c55_content_repo\Extension;

use Drupal\Core\Url;

/**
 * Create custom Twig "contentrepository" extentions.
 */
class ContentRepositoryExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'content_repository_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('downloadDocumentLinkFromId', [
        $this,
        'downloadDocumentLinkFromId',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomy', [
        $this,
        'getTaxonomy',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomyTab', [
        $this,
        'getTaxonomyTab',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Donwload link.
   */
  public function downloadDocumentLinkFromId($fid) {
    return Url::fromRoute('bhge_content_repo.content.repository.download', ['fid' => $fid]);
  }

  /**
   * Get terms of given vocab id.
   */
  public function getTaxonomy($vid) {
    return \Drupal::service('entity_type.manager')
      ->getStorage("taxonomy_term")
      ->loadTree($vid, 0, NULL, FALSE);
  }

  /**
   * Get terms.
   */
  public function getTaxonomyTab($taxonomy, $pid) {
    $taxonomyTerms = $this->getTaxonomy($taxonomy);
    $tabs = [];
    foreach ($taxonomyTerms as $taxonomyTerm) {
      $tabs[] = [
        'topic' => $taxonomyTerm->tid,
        'label' => $taxonomyTerm->name,
        'pid' => $pid,
      ];
    }
    return $tabs;
  }

}
