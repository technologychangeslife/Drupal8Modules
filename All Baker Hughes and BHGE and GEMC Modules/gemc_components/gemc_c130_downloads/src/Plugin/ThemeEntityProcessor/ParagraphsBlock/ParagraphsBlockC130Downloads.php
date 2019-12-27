<?php

namespace Drupal\gemc_c130_downloads\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_c130_downloads\DownloadsData;
use Drupal\gemc_components\FieldData\FieldDataService;
use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorManager;
use Drupal\handlebars_theme_handler\Plugin\ThemeFieldProcessorManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c130_downloads_module",
 *   label = @Translation("C130 Downloads"),
 *   entity_type = "paragraph",
 *   bundle = "c130_downloads",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC130Downloads extends GemcThemeEntityProcessorBase {

  /**
   * Downloads data service.
   *
   * @var \Drupal\gemc_c130_downloads\DownloadsData
   */
  protected $downloadsDataService;

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
      $container->get('gemc_components.field_data_service'),
      $container->get('gemc_c130_downloads.downloads_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ThemeEntityProcessorManager $themeEntityProcessorManager, ThemeFieldProcessorManager $themeFieldProcessorManager, FieldDataService $fieldDataService, DownloadsData $downloadsDataService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $themeEntityProcessorManager, $themeFieldProcessorManager, $fieldDataService);
    $this->downloadsDataService = $downloadsDataService;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $topic = '';
    $limit = 6;
    $paragraph = $variables["paragraph"];
    $pid = $paragraph->id();
    $categories = $this->downloadsDataService->getFilters($variables["paragraph"]);
    if (!empty($categories)) {
      $firstCategory = reset($categories);
      $topic = $firstCategory->topic;
    }
    $items = $this->downloadsDataService->getFilteredDownloads($paragraph, $topic, $limit);

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'componentClass' => 'toggle-view sidebar-filter load-more triple-card',
      'categories' => $categories,
      'items' => $items,
      'selectedItemId' => '1',
      'loadMore' => $this->t('Load More'),
      'header' => [
        'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
        'subHeading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_sub_title']),
        'image' => $this->fieldDataService->getResponsiveImageData($variables['elements']['field_image'], 'product_image_normal', 'product_image_small'),
      ],
      'api' => [
        'action' => Url::fromUserInput('/api/v1/downloads', ['query' => ['pid' => $pid]])->toString(),
        'pagination' => [
          'limit' => $limit,
          'offset' => 0,
          'total' => $this->downloadsDataService->getFilteredDownloadsCount($paragraph, $topic),
        ],
      ],

    ];
  }

}
