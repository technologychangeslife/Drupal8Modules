<?php

namespace Drupal\bhge_c34_product_comparison;

use Drupal\Core\Url;

/**
 * Data related helper methods for products/sections.
 *
 * It's a base class that is used both for BHGE and GEMC.
 */
abstract class DataHelpersBase {

  private $comparisonData;

  /**
   * Constructor.
   *
   * @param \Drupal\bhge_c34_product_comparison\ComparisonDataBase $comparisonData
   *   Class with helper methods.
   */
  public function __construct(ComparisonDataBase $comparisonData) {
    $this->comparisonData = $comparisonData;
  }

  /**
   * Group queried product attributes to products.
   *
   * @param array $data
   *   Query result.
   * @param int $curentNid
   *   Current product NID.
   *
   * @return array
   *   Grouped result.
   */
  public function groupData(array $data, int $curentNid) {
    $usedAttributes = $newData = [];

    foreach ($data as $item) {
      if (!isset($newData[$item->nid])) {
        $newData[$item->nid] = $this->transformData($item);
      }
      $newData[$item->nid]['attributes'][$item->attribute_id] = [
        'value' => $item->attribute_value,
      ];
      $usedAttributes[$item->attribute_id] = $item->attribute_id;
    }
    if (isset($newData[$curentNid])) {
      $currentItem = $newData[$curentNid];
      unset($newData[$curentNid]);
      $newData = [$curentNid => $currentItem] + $newData;
    }
    else {
      // This should not happen: current product is not in results.
      return [];
    }

    // Get same terms from database to get the right order.
    $usedAttributes = $this->comparisonData->getProductAttributes($usedAttributes);

    foreach ($newData as &$product) {
      $product = $this->orderAttributes($product, $usedAttributes);
    }
    return ['products' => $newData, 'attributes' => $usedAttributes];
  }

  /**
   * Transform data.
   */
  abstract protected function transformData($item);

  /**
   * This funstion sets the order of attributes.
   *
   * @param array $product
   *   The set of products.
   * @param array $usedAttributes
   *   Thes set of used attributes.
   *
   * @return array
   *   Mixed $product.
   */
  private function orderAttributes(array $product, array $usedAttributes) {
    foreach ($usedAttributes as $tid => $usedAttribute) {
      $newAttributes[$tid] = '';

      if (isset($product['attributes'][$tid])) {
        $newAttributes[$tid] = $product['attributes'][$tid]['value'];
      }
    }
    $product['attributes'] = $newAttributes;
    return $product;
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
  protected function getPathFromNid($nid) {
    return Url::fromUri('entity:node/' . $nid)->toString();
  }

}
