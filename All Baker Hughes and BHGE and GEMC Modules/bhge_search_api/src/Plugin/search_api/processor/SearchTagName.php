<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\taxonomy\Entity\Term;

/**
 * Adds the search tag name to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "search_tag_name",
 *   label = @Translation("Search tag name"),
 *   description = @Translation("Adds the search tag name of page to the
 *   indexed data."), stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class SearchTagName extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'search_tag_name'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Search tag name'),
        'description' => $this->t('Search tag name'),
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
