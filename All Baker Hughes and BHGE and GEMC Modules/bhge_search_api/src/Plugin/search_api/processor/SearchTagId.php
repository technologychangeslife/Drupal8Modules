<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the search tag id to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "field_search_tag",
 *   label = @Translation("Search Tag id"),
 *   description = @Translation("Adds the search tag id of page to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class SearchTagId extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'field_search_tag'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Search tag id'),
        'description' => $this->t('Search tag id'),
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

    /** @var \Drupal\node\Entity\Node $entity */
    $entity = $item->getOriginalObject()->getValue();
    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);

    if ($entity->hasField('field_search_tag')) {
      $termIds = $entity->get('field_search_tag')->getValue();

      if (!empty($termIds)) {
        $termIds = array_column($termIds, 'target_id');

        if (!empty($termIds)) {
          foreach ($termIds as $term) {
            foreach ($fields as $field) {
              $field->addValue($term);
            }
          }
        }
      }
    }
  }

}
