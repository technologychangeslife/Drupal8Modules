<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\taxonomy\Entity\Term;

/**
 * Adds the topic name to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "topic_name",
 *   label = @Translation("Topic name"),
 *   description = @Translation("Adds the Topic name of page to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class TopicName extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'topic_name'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Topic name'),
        'description' => $this->t('Topic name'),
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
        $terms = Term::loadMultiple($termIds);

        if (!empty($terms)) {
          /** @var \Drupal\taxonomy\Entity\Term $term */
          foreach ($terms as $term) {
            foreach ($fields as $field) {
              $field->addValue($term->getName());
            }
          }
        }
      }
    }
  }

}
