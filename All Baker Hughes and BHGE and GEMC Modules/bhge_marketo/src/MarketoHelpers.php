<?php

namespace Drupal\bhge_marketo;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * The MarketoHelpers.
 */
class MarketoHelpers {

  /**
   * Load Paragraph referenced term.
   *
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   *   The Paragraph Entity.
   * @param string $field
   *   The field string.
   *
   * @return string|null
   *   Returns the term Name.
   */
  public function loadTermName(Paragraph $paragraph, $field) {

    if ($paragraph->getParentEntity()->hasField($field)) {
      if (!empty($paragraph->getParentEntity()->get($field)->getValue()[0]['target_id'])) {

        $term = Term::load($paragraph->getParentEntity()
          ->get($field)
          ->getValue()[0]['target_id']);

        if (!empty($term)) {
          return $term->getName();
        }
      }
    }
  }

  /**
   * Populate Lead Source function.
   *
   * @param array $meta
   *   The Meta array.
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   *   The paragraph entity.
   *
   * @return array
   *   Returns meta data.
   */
  public function populateLeadSource($meta = [],$paragraph) { //phpcs:ignore
    // Lead Source Meta.
    $parent = $paragraph->getParentEntity();

    $meta['lead_source'] = 'websitessss';
    $meta['lead_source_details'] = $parent->get('title')->value;
    $meta['lead_source_url'] = \Drupal::request()->getHost();

    return $meta;
  }

  /**
   * Populate Marketo meta data.
   *
   * @param array $meta
   *   The Meta array.
   * @param \Drupal\bhge_marketo\Paragraph $paragraph
   *   The paragraph entity.
   *
   * @return array
   *   Returns meta.
   */
  public function populateMarketoMeta($meta = [], $paragraph) { //phpcs:ignore
    // Populate ProductofInterest with node title.
    $ctype = $paragraph->getParentEntity()->getType();
    // Populate meta only on following CTs.
    if (in_array($ctype, ['section', 'product'])) {

      if (!empty($paragraph->getParentEntity()->title) && !empty($paragraph->getParentEntity()->title->value)) {
        $meta['mCProductofInterestGEMKTO'] = urldecode($paragraph->getParentEntity()->title->value);
      }

      // Populate Tier 1.
      $tier1 = $this->loadTermName($paragraph, 'field_tier_1_internal_name');
      if (!empty($tier1)) {
        $meta['GE_HQ_Business_Tier2__c'] = urldecode($tier1);
      }

      // Populate Tier 2.
      $tier2 = $this->loadTermName($paragraph, 'field_tier_2_internal_name');
      if (!empty($tier2)) {
        $meta['GE_ES_Sub_PL__c'] = urldecode($tier2);
      }

      // Populate N Levels.
      $meta = $this->populateNData($meta, $paragraph->getParentEntity());
    }
    // Populate meta only for case study summary.
    if (in_array($ctype, ['case_study_summary'])) {
      $meta = $this->populateMarketoMetaCaseStudy($meta, $paragraph);
    }
    return $meta;
  }

  /**
   * Populate Marketo meta data.
   *
   * @param array $meta
   *   The meta array.
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   *   The paragraph entity.
   *
   * @return array
   *   Return meta array.
   */
  public function populateMarketoMetaCaseStudy($meta = [], $paragraph) { //phpcs:ignore
    // Pass the title of the case study.
    if (!empty($paragraph->getParentEntity()->title) && !empty($paragraph->getParentEntity()->title->value)) {
      $meta['mCProductDownloadGEMkto'] = urldecode($paragraph->getParentEntity()->title->value);
    }
    // Pass the product of interest value of the case study.
    if (!empty($paragraph->getParentEntity()->title) && !empty($paragraph->getParentEntity()->field_product_interest->value)) {
      $meta['mCProductofInterestGEMKTO'] = urldecode($paragraph->getParentEntity()->field_product_interest->value);
    }
    // Level N1.
    if (!empty($paragraph->getParentEntity()->field_n1_application_product_app) && !empty($paragraph->getParentEntity()->field_n1_application_product_app->target_id)) {
      $node = Node::load($paragraph->getParentEntity()->field_n1_application_product_app->target_id);
      if (!empty($node->get('title')->value)) {
        $meta['mCProductApplicationGEMkto'] = urldecode($node->get('title')->value);
      }
    }
    // Level N2 - Product Category.
    if (!empty($paragraph->getParentEntity()->n2_product_category) && !empty($paragraph->getParentEntity()->n2_product_category->target_id)) {
      $node = Node::load($paragraph->getParentEntity()->n2_product_category->target_id);
      if (!empty($node->get('title')->value)) {
        $meta['mCProductCategoryGEMkto'] = urldecode($node->get('title')->value);
      }
    }
    // Level N3 - Product sub Category.
    if (!empty($paragraph->getParentEntity()->n3_product_sub_category) && !empty($paragraph->getParentEntity()->n3_product_sub_category->target_id)) {
      $node = Node::load($paragraph->getParentEntity()->n3_product_sub_category->target_id);
      if (!empty($node->get('title')->value)) {
        $meta['mCProductSubCategoryGEMkto'] = urldecode($node->get('title')->value);
      }
    }
    // URL of the case study summary page / node views page.
    $meta['mCProductDownloadURLGEMkto'] = urldecode($paragraph->getParentEntity()->toUrl()->setAbsolute()->toString());
    return $meta;
  }

  /**
   * The populateNdata function.
   *
   * @param array $meta
   *   The meta array.
   * @param \Drupal\node\Entity\Node $entity
   *   The entity object.
   *
   * @return array
   *   Return meta array.
   */
  public function populateNdata(array $meta, Node $entity) {

    $hierarchy = $this->getNhierarchy($entity, TRUE);

    foreach ($hierarchy as $key => $item) {
      switch ($key) {

        // N2 - level 2 data of hierarchy.
        case 2:
          $meta['mCProductApplicationGEMkto'] = urldecode($item);
          break;

        // N3 - level 3 data of hierarchy.
        case 3:
          $meta['mCProductCategoryGEMkto'] = urldecode($item);
          break;

        // N4 - level 4 data of hierarchy.
        case 4:
          $meta['mCProductSubCategoryGEMkto'] = urldecode($item);
          break;
      }
    }
    return $meta;
  }

  /**
   * Get N Levels.
   *
   * @param int $node
   *   The node id.
   * @param bool $reverse
   *   Array reverse variable.
   *
   * @return array
   *   Returns hierarchy of nav.
   */
  public function getNhierarchy($node, $reverse = FALSE) {

    /** @var \Drupal\bhge_c01a_product_nav\SectionTrail $trail */
    $trail = \Drupal::service('bhge_c01a_product_nav.section_trail');
    $trail = $trail->currentTrail($node);
    $hierarchy = [];

    if (!empty($trail['parents'])) {
      $hierarchy[] = $trail['current']->title;

      foreach (array_reverse($trail['parents']) as $item) {
        $hierarchy[] = $item->title;
      }

      if ($reverse) {
        $hierarchy = array_reverse($hierarchy);
      }

      $hierarchy = array_combine(range(1, count($hierarchy)), array_values($hierarchy));
    }

    return $hierarchy;
  }

}
