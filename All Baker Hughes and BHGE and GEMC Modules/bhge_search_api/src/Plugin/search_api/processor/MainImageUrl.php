<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the main image URL to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "main_image_url",
 *   label = @Translation("Main image URL"),
 *   description = @Translation("Adds the main image URL to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class MainImageUrl extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'main_image_url'; // phpcs:ignore
  protected $dataHelpers;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->dataHelpers = \Drupal::service('bhge_core.data_helpers');
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Main image URL'),
        'description' => $this->t('URL main image field'),
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

    $main_image = $this->dataHelpers->getImage($entity, 'field_main_image', 'field_dam_main_img', 'search_result_image');

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      if (!empty($main_image)) {
        $field->addValue($main_image);
      }
      else {
        $field->addValue('');
      }
    }
  }

}
