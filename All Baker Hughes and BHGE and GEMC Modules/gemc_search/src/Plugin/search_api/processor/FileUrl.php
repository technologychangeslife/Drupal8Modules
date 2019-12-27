<?php

namespace Drupal\gemc_search\Plugin\search_api\processor;

use Drupal\Core\Url;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the File field URL to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "file_url",
 *   label = @Translation("File url"),
 *   description = @Translation("Adds the file url to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class FileUrl extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'file_url'; // phpcs:ignore

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('File url'),
        'description' => $this->t('URL of file field'),
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

    $file_url = '';
    $dam_fields = ['field_download_dam_media', 'field_document'];
    foreach ($dam_fields as $dam_field) {
      if ($entity->hasField($dam_field) && !empty($entity->$dam_field->getValue())) {
        // Use field_download_dam_media > field_asset > url, when present and set.
        $media_ref_field = 'field_asset';
        $media_references = $entity->$dam_field->referencedEntities();
        if (!empty($media_references) && is_array($media_references) && !empty($media_references[0])) {
          $media = $media_references[0];
        }
        if (!empty($media) && $media->hasField($media_ref_field)
          && !empty($media->{$media_ref_field})
          && !empty($media->{$media_ref_field}->entity->uri->value)
        ) {
          $file_url = $media->{$media_ref_field}->entity->uri->value;
        }
      }
    }

    if ($file_url == '') {
      $file_url = !empty($entity->field_file->entity) ? $entity->field_file->entity->uri->value : '';
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      if (!empty($file_url)) {
        $field->addValue($file_url);
      }
      else {
        $field->addValue('');
      }
    }
  }

}
