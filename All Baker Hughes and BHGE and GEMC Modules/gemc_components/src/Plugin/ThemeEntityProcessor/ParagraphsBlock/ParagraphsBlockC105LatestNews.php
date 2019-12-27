<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorManager;
use Drupal\handlebars_theme_handler\Plugin\ThemeFieldProcessorManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c105_latest_news",
 *   label = @Translation("C105 Latest News"),
 *   entity_type = "paragraph",
 *   bundle = "c105_latest_news",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC105LatestNews extends ThemeEntityProcessorBase {

  /**
   * Content lists.
   *
   * @var object
   */
  private $contentLists;

  /**
   * Route Match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  private $routeMatch;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  private $languageManager;

  /**
   * The create function.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.handlebars_theme_handler_entity_processor'),
      $container->get('plugin.manager.handlebars_theme_handler_field_processor'),
      $container->get('gemc_components.content_lists'),
      $container->get('current_route_match'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ThemeEntityProcessorManager $themeEntityProcessorManager, ThemeFieldProcessorManager $themeFieldProcessorManager, $contentLists, CurrentRouteMatch $routeMatch, LanguageManagerInterface $languageManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $themeEntityProcessorManager, $themeFieldProcessorManager);
    $this->contentLists = $contentLists;
    $this->routeMatch = $routeMatch;
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $newsItem = NULL;
    if (!empty($variables['elements']['field_highlighted_news']['#items']) && count($variables['elements']['field_highlighted_news']['#items']) > 0) {
      $newsHighlighted = $variables['elements']['field_highlighted_news']['#items']->entity;
      // Get Current language code.
      $langCode = $this->languageManager->getCurrentLanguage()->getId();
      // Get content from current language if highlighted news has translation.
      if ($newsHighlighted->hasTranslation($langCode)) {
        $newsItem = $newsHighlighted->getTranslation($langCode);
      }
      else {
        $newsItem = $newsHighlighted;
      }
    }

    $currentNode = $this->routeMatch->getParameter('node');
    if (in_array($currentNode->getType(), ['industry', 'industry_segment'])) {
      $newsItems = $this->contentLists->getContentList('news_item', 2, $currentNode->id());
    }
    elseif ($currentNode->getType == 'section') {
      $newsItems = $this->contentLists->getContentList('news_item', 2, NULL, $currentNode->id());
    }
    else {
      $newsItems = $this->contentLists->getContentList('news_item', 4);
    }

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 1,
      'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_title']),
      'newsItems' => $newsItems,
    ];

    $cta = $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_cta_link']);

    if (empty($newsItem)) {
      $variables['data']['type'] = 'default';
      $variables['data']['featureBlock'] = [
        'heading' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_intro_title']),
        'description' => $this->themeFieldProcessorManager->getFieldData($variables['elements']['field_intro_subtitle']),
      ];

      if (!empty($cta)) {
        $variables['data']['type'] = 'default-cta';
        $variables['data']['featureBlock']['cta'] = [
          'description' => $cta['text'],
          'href' => $cta['url'],
        ];
      }
    }
    else {
      $variables['data']['type'] = 'featured';
      $variables['data']['featureBlock'] = [
        'subHeading' => $newsItem->getTitle(),
        'description' => strip_tags($newsItem->body->value),
      ];
      if (!empty($cta)) {
        $variables['data']['featureBlock']['cta'] = [
          'href' => $cta['url'],
        ];
      }
    }
  }

}
