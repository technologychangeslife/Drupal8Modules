<?php

namespace Drupal\gemc_product;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Used to update product filter fields.
 */
class ProductFiltersService {

  /**
   * Update product filter fields.
   *
   * @param \Drupal\node\NodeInterface $product
   *   Product node.
   */
  public function updateFilterFields(NodeInterface $product) {
    $filterSectionField = $product->get('field_filter_main_section');
    $firstLevelSections = $this->getFirstLevelProductSections($product);
    $filterSectionsValues = [];
    foreach ($firstLevelSections as $firstLevelSection) {
      $filterSectionsValues[] = ['target_id' => $firstLevelSection->id()];
    }
    $filterSectionField->setValue($filterSectionsValues);

    $filterIndustryField = $product->get('field_filter_industry');
    $firstLevelIndustries = $this->getFirstLevelProductIndustries($product);
    $filterIndustryValues = [];
    foreach ($firstLevelIndustries as $firstLevelIndustry) {
      $filterIndustryValues[] = ['target_id' => $firstLevelIndustry->id()];
    }
    $filterIndustryField->setValue($filterIndustryValues);
  }

  /**
   * Get first level product industries.
   *
   * @param \Drupal\node\NodeInterface $product
   *   Product node.
   *
   * @return \Drupal\node\NodeInterface[]
   *   First level product industries.
   */
  public function getFirstLevelProductIndustries(NodeInterface $product) {
    $result = [];
    $industryField = $product->get('field_prod_industry');
    if (!$industryField->isEmpty()) {
      foreach ($industryField as $industryItem) {
        $industry = Node::load($industryItem->getValue()['target_id']);
        if ($industry->bundle() == 'industry_segment') {
          $industry = Node::load($industry->get('field_parent_industry')
            ->get(0)
            ->getValue()['target_id']);
        }
        $result[] = $industry;
      }
    }
    return $result;
  }

  /**
   * Get first level product sections.
   *
   * @param \Drupal\node\NodeInterface $product
   *   Product node.
   *
   * @return \Drupal\node\NodeInterface[]
   *   First level product sections.
   */
  public function getFirstLevelProductSections(NodeInterface $product) {
    $result = [];
    if ($product->hasField('field_prod_section') && !$product->get('field_prod_section')->isEmpty()) {
      /** @var \Drupal\Core\Field\FieldItemInterface $prodSection */
      foreach ($product->get('field_prod_section') as $prodSection) {
        $resultSection = Node::load($prodSection->getValue()['target_id']);
        if ($resultSection instanceof NodeInterface) {
          while ($parent = $this->getParentSection($resultSection)) {
            $resultSection = $parent;
          };
          $result[] = $resultSection;
        }
      }
    }

    return $result;
  }

  /**
   * Get parent section.
   *
   * @param \Drupal\node\NodeInterface $section
   *   Section node.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   Parent section node.
   */
  private function getParentSection(NodeInterface $section) {
    $parentField = $section->get('field_section_parents');
    if (!$parentField->isEmpty()) {
      $parentId = $parentField->get(0)->getValue()['target_id'];
      return Node::load($parentId);
    }
  }

}
