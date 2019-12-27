<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor;

use Drupal\gemc_components\FieldData\FieldDataService;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorManager;
use Drupal\handlebars_theme_handler\Plugin\ThemeFieldProcessorManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base entity processor for GEMC.
 *
 * Injects FieldDataService that allows to get field data in project-specific
 * format.
 */
abstract class GemcThemeEntityProcessorBase extends ThemeEntityProcessorBase {

  /**
   * Field data service.
   *
   * @var \Drupal\gemc_components\FieldData\FieldDataService
   */
  protected $fieldDataService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.handlebars_theme_handler_entity_processor'),
      $container->get('plugin.manager.handlebars_theme_handler_field_processor'),
      $container->get('gemc_components.field_data_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ThemeEntityProcessorManager $themeEntityProcessorManager, ThemeFieldProcessorManager $themeFieldProcessorManager, FieldDataService $fieldDataService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $themeEntityProcessorManager, $themeFieldProcessorManager);
    $this->fieldDataService = $fieldDataService;
  }

}
