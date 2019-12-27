<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the CTA link title to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "cta_link_title",
 *   label = @Translation("Title of CTA link field"),
 *   description = @Translation("Adds the title of CTA link to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class CtaLinkTitle extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'cta_link_title'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('CTA link title'),
        'description' => $this->t('Title of CTA link field'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties[$this->processor_id] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getOriginalObject()->getValue();

    $cta_link_title = '';
    if (!empty($entity->field_product_information) && !empty($entity->field_product_information->entity)) {
      $product_information = $entity->field_product_information->entity;
      $cta_link_title = !empty($product_information->field_cta_link->title) ? $product_information->field_cta_link->title : '';
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      if (!empty($cta_link_title)) {
        $field->addValue($cta_link_title);
      }
      else {
        $field->addValue('');
      }
    }
  }

}
