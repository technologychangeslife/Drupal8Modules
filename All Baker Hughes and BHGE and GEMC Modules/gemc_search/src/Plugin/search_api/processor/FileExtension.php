<?php

namespace Drupal\gemc_search\Plugin\search_api\processor;

use Drupal\Core\Url;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the File field extension to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "file_extension",
 *   label = @Translation("File extension"),
 *   description = @Translation("Adds the file extension to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class FileExtension extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'file_extension'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('File extension'),
        'description' => $this->t('Adds the file extension to the indexed data'),
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

    $file_extension = '';
    $dam_fields = ['field_download_dam_media', 'field_document'];
    foreach ($dam_fields as $dam_field) {
      if ($entity->hasField($dam_field) && !$entity->get($dam_field)
        ->isEmpty()) {
        $media = $entity->$dam_field->entity;
        $media_source = $media->getSource();
        $file_extension = $media_source->getMetadata($media, 'filetype');
      }
    }

    if ($entity->hasField('field_file') && !$entity->get('field_file')->isEmpty()) {
      $pathinfo = pathinfo($entity->field_file->entity->uri->value);
      $file_extension = !empty($pathinfo['extension']) ? $pathinfo['extension'] : '';
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      $field->addValue($file_extension);
    }
  }

}
