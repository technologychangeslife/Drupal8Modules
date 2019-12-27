<?php

namespace Drupal\bhge_comment\Plugin\Field\FieldFormatter;

use Drupal\comment\Plugin\Field\FieldType\CommentItemInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\comment\Plugin\Field\FieldFormatter\CommentDefaultFormatter;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bhge_comment\CommentDataInterface;

/**
 * Provides a custom comment formatter.
 *
 * @FieldFormatter(
 *   id = "comment_bhge",
 *   module = "bhge_comment",
 *   label = @Translation("BHGE comment list"),
 *   field_types = {
 *     "comment"
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
 *   }
 * )
 */
class CommentFormatter extends CommentDefaultFormatter implements ContainerFactoryPluginInterface {

  /**
   * CommentData object.
   *
   * @var \Drupal\bhge_comment\CommentData
   */
  public $commentDataService;

  /**
   * Constructs a new CommentDefaultFormatter.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Third party settings.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   * @param \Drupal\bhge_comment\CommentDataInterface $commentDataService
   *   Comment data for BHGE.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, AccountInterface $current_user, EntityManagerInterface $entity_manager, EntityFormBuilderInterface $entity_form_builder, RouteMatchInterface $route_match, CommentDataInterface $commentDataService) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $current_user, $entity_manager, $entity_form_builder, $route_match);
    $this->commentDataService = $commentDataService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('current_user'),
      $container->get('entity.manager'),
      $container->get('entity.form_builder'),
      $container->get('current_route_match'),
      $container->get('bhge_comment.comment_data')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $config = \Drupal::configFactory()->get('bhge.general_settings');
    $isInternal = $config->get('internal_site');

    if (!$isInternal) {
      return [];
    }

    $elements = [];
    $output = [];

    $fieldName = $this->fieldDefinition->getName();
    $entity = $items->getEntity();

    $status = $items->status;

    if (!in_array($status, [CommentItemInterface::HIDDEN, CommentItemInterface::CLOSED]) && empty($entity->in_preview) &&
      // Comments are added to the search results and search index by
      // comment_node_update_index() instead of by this formatter, so don't
      // return anything if the view mode is search_index or search_result.
      !in_array($this->viewMode, ['search_result', 'search_index'])) {
      $commentSettings = $this->getFieldSettings();

      // Only attempt to render comments if the entity has visible comments.
      // Unpublished comments are not included in
      // $entity->get($field_name)->comment_count, but unpublished comments
      // should display if the user is an administrator.
      $elements['#cache']['contexts'][] = 'user.permissions';
      if ($this->currentUser->hasPermission('access comments') || $this->currentUser->hasPermission('administer comments')) {
        $output['comments'] = [];

        $mode = $commentSettings['default_mode'];

        $commentData = $this->commentDataService->loadCommentTree($entity, $fieldName, $status);
        $commentCount = $entity->get($fieldName)->comment_count;
        $parentCount = count($commentData);

        $build = [
          '#theme' => 'bhge_comments',
          '#comments_data' => $commentData,
          '#comment_count' => $commentCount,
          '#comments_hidden' => max($parentCount - $this->commentDataService->getShowMax(), 0),
          '#comments_status' => $status,
          '#entity_id' => $entity->id(),
          '#cache' => ['contexts' => ['user', 'url']],
        ];
        $output['comments'] += $build;
      }

      $elements[] = $output + [
        '#comment_type' => $this->getFieldSetting('comment_type'),
        '#comment_display_mode' => $this->getFieldSetting('default_mode'),
        'comment_form' => [],
        'comments' => [],
      ];
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $viewModes = $this->getViewModes();
    $element['view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('Comments view mode'),
      '#description' => $this->t('Select the view mode used to show the list of comments.'),
      '#default_value' => $this->getSetting('view_mode'),
      '#options' => $viewModes,
      // Only show the select element when there are more than one options.
      '#access' => count($viewModes) > 1,
    ];
    $element['pager_id'] = [
      '#type' => 'hidden',
      '#default_value' => 0,
    ];
    return $element;
  }

}
