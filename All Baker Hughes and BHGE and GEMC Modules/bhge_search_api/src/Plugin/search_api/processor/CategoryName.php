<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\taxonomy\Entity\Term;

/**
 * Adds the category name to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "field_category_name",
 *   label = @Translation("Category name"),
 *   description = @Translation("Adds the category name of page to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class CategoryName extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'field_category_name'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Category name'),
        'description' => $this->t('Category name'),
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

    if ($entity->hasField('field_categories')) {
      $termIds = $entity->get('field_categories')->getValue();

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
