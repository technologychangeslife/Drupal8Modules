<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\gemc_components\FieldData\FieldDataService;
use Drupal\gemc_components\Plugin\ThemeEntityProcessor\GemcThemeEntityProcessorBase;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorManager;
use Drupal\handlebars_theme_handler\Plugin\ThemeFieldProcessorManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c14_carousel",
 *   label = @Translation("C16 Accordion"),
 *   entity_type = "paragraph",
 *   bundle = "c14_case_studies",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC14CaseStudies extends GemcThemeEntityProcessorBase {

  /**
   * Data helpers.
   *
   * @var object
   */
  private $dataHelpers;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
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
      $container->get('gemc_components.field_data_service'),
      $container->get('bhge_core.data_helpers'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ThemeEntityProcessorManager $themeEntityProcessorManager, ThemeFieldProcessorManager $themeFieldProcessorManager, FieldDataService $fieldDataService, $dataHelpers, LanguageManagerInterface $languageManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $themeEntityProcessorManager, $themeFieldProcessorManager, $fieldDataService);
    $this->dataHelpers = $dataHelpers;
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {
    $items = [];

    if (!empty($variables['elements']['field_case_studies']['#items'])) {
      $caseStudies = $variables['elements']['field_case_studies']['#items'];
      // Get Current language code.
      $langCode = $this->languageManager->getCurrentLanguage()->getId();
      foreach ($caseStudies as $caseStudy) {
        $caseStudy = $caseStudy->entity;
        // Get content from current language if case study has translation.
        if ($caseStudy->hasTranslation($langCode)) {
          $caseStudy = $caseStudy->getTranslation($langCode);
        }
        $image = $this->dataHelpers->getImage($caseStudy, 'field_image', 'field_dam_main_img', 'c14_carousel_image');
        $items[] = [
          'image' => [
            'normal' => $image,
            'small' => $image,
            'alt' => !empty($caseStudy->field_image) ? $caseStudy->field_image->alt : '',
          ],
          'title' => $caseStudy->title->value,
          'subtitle' => $caseStudy->body->value,
          'link' => [
            'url' => $caseStudy->toUrl()->toString(),
            'label' => $caseStudy->getTitle(),
          ],
        ];
      }
    }
    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'items' => $items,
    ];
  }

}
