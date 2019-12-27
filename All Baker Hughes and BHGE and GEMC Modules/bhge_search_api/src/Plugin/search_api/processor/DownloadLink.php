<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the Download link URL to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "download_link",
 *   label = @Translation("Download link field"),
 *   description = @Translation("Adds the download link to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class DownloadLink extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'download_link'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Download link'),
        'description' => $this->t('Download link field'),
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

    $download_link = '';
    if (!empty($entity->field_product_information) && !empty($entity->field_product_information->entity)) {
      $product_information = $entity->field_product_information->entity;
      $download_link = !empty($product_information->field_cta_download->entity) ? $product_information->field_cta_download->entity->uri->value : '';
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      if (!empty($download_link)) {
        $field->addValue(file_create_url($download_link));
      }
      else {
        $field->addValue('');
      }
    }
  }

}
