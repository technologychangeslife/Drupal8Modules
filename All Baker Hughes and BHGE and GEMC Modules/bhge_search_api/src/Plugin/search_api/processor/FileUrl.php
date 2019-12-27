<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

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

    if (!empty($entity->field_file->entity)) {
      $file_url = !empty($entity->field_file->entity) ? $entity->field_file->entity->uri->value : '';
    }
    elseif ($entity->hasField('field_dam_file') && !empty($entity->get('field_dam_file')->getValue())) {

      $dam_field_file = $entity->get('field_dam_file')->getValue();
      $media = Media::load($dam_field_file[0]['target_id']);
      if (!empty($media)) {
        $media_field_asset = $media->get('field_asset')->getValue();
        if (!empty($media_field_asset)) {
          $file = File::load($media_field_asset[0]['target_id']);
        }
        if (!empty($file)) {
          $file_url = !empty($file->getFileUri()) ? $file->getFileUri() : '';
        }
      }
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      if (!empty($file_url)) {
        $field->addValue(file_create_url($file_url));
      }
      else {
        $field->addValue('');
      }
    }
  }

}
