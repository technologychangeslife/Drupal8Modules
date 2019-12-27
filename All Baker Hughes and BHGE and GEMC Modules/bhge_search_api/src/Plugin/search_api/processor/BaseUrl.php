<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the author id to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "base_site_url",
 *   label = @Translation("Base url of site"),
 *   description = @Translation("Adds the base url of site to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class BaseUrl extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'base_site_url'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Base site url'),
        'description' => $this->t('Base site url'),
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
    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      $field->addValue($_SERVER['HTTP_HOST']);
    }
  }

}
