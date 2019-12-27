<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the topic id to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "field_topic",
 *   label = @Translation("Topic id"),
 *   description = @Translation("Adds the topic id of page to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class TopicId extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'field_topic'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Topic id'),
        'description' => $this->t('Topic id'),
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

    if ($entity->hasField('field_topic')) {
      $termIds = $entity->get('field_topic')->getValue();

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
