<?php

namespace Drupal\gemc_c101_sticky_header\StickyHeader;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Base sticky header variant.
 */
abstract class VariantBase implements VariantInterface {

  use StringTranslationTrait;

  /**
   * Node object.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * VariantBase constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Displayed node.
   */
  public function __construct(NodeInterface $node) {
    $this->node = $node;
  }

  /**
   * Get contact link from C04 component field.
   *
   * @param string $fieldName
   *   Name of field with C04 component.
   *
   * @return array
   *   Contact link from C04 component field.
   *
   * @throws MissingDataException
   */
  public function getContactLink($fieldName) {
    if ($this->node->hasField($fieldName) && !$this->node->get($fieldName)->isEmpty()) {
      /** @var \Drupal\paragraphs\Entity\Paragraph $contactParagraph */
      $contactParagraph = $this->node->get($fieldName)->get(0)->entity;
      $contactFieldName = 'field_contact_links';

      // Get content type of node.
      $ctype = $contactParagraph->getParentEntity()->getType();

      $options = [];

      $marketoOptions = [];

      /** @var \Drupal\gemc_components\MarketoHelpers $marketoHelpers */
      $marketoHelpers = \Drupal::service('gemc_components.helpers');

      // Populate meta only on following CTs.
      if (in_array($ctype, ['section', 'product'])) {

        // Populate ProductofInterest with node title.
        $marketoOptions['mCProductofInterestGEMKTO'] = urldecode($contactParagraph->getParentEntity()->title->value);

        // Populate N Levels.
        $marketoOptions = $marketoHelpers->populateNdata($contactParagraph->getParentEntity(), $marketoOptions);
      }

      if ($contactParagraph->hasField($contactFieldName) && !$contactParagraph->get($contactFieldName)->isEmpty()) {
        $contactLink = $contactParagraph->get($contactFieldName)->get(0)->getValue();

        if (!empty($marketoOptions)) {
          $options = ['query' => $marketoOptions];
        }

        return [
          'href' => !empty($contactLink) ? Url::fromUri($contactLink['uri'], $options)->toString() : '',
          'description' => $this->t('Contact us'),
          'solid' => TRUE,
        ];
      }
    }
    else {
      return [];
    }
  }

  /**
   * Get 'All solutions' with optional prefiltering.
   *
   * @param string $filterName
   *   Filter name.
   * @param string $filterValue
   *   Filter value.
   *
   * @return array
   *   'All solutions' link.
   */
  public function getAllSolutionsLink($filterName = NULL, $filterValue = NULL) {
    $query = [];
    if ($filterName) {
      $query = [
        $filterName => $filterValue,
      ];
    }
    return [
      'href' => Url::fromUserInput('/solutions', ['query' => $query])
        ->toString(),
      'description' => $this->t('View all solutions'),
      'solid' => TRUE,
    ];
  }

  /**
   * Get URL to node with selected display.
   *
   * @param string $displayName
   *   Display machine name.
   *
   * @return string
   *   URL to node with selected display.
   */
  protected function getDisplayUrl($displayName = '') {
    $query = [];
    if (!empty($displayName)) {
      $query['display'] = $displayName;
    }
    return $this->node->toUrl('canonical', ['query' => $query])->toString();
  }

  /**
   * Get active display name.
   *
   * @return string
   *   Active display name.
   */
  protected function getActiveDisplayName() {
    $request = \Drupal::request();
    return $request->query->get('display', 'full');
  }

}
