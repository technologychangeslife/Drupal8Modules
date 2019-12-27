<?php

namespace Drupal\bhge_search_api\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the site privacy to the indexed data.
 *
 * @SearchApiProcessor(
 *   id = "privacy",
 *   label = @Translation("Site privacy"),
 *   description = @Translation("Adds the site privacy to the indexed data."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = false,
 * )
 */
class Privacy extends ProcessorPluginBase {

  /**
   * Machine name of the processor.
   *
   * @var string
   */
  protected $processor_id = 'privacy'; // phpcs:ignore
  protected $internalSiteConfig;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->internalSiteConfig = \Drupal::service('config.factory')->get('config_split.config_split.internal_site');
  }

  /**
   * The internal site config function.
   *
   * @return mixed
   *   Returns internal site config.
   */
  public function getInternalSiteConfig() {
    return $this->internalSiteConfig;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('Site privacy'),
        'description' => $this->t('Privacy of the site'),
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
    $privacy = 'internal';

    if (empty($this->getInternalSiteConfig()->get('status'))) {
      $privacy = 'public';
    }

    $fields = $this->getFieldsHelper()
      ->filterForPropertyPath($item->getFields(), NULL, $this->processor_id);
    foreach ($fields as $field) {
      $field->addValue($privacy);
    }
  }

}
