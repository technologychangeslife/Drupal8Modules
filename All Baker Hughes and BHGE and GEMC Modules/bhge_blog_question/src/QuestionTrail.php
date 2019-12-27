<?php

namespace Drupal\bhge_blog_question;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Class QuestionTrail.
 *
 * @package Drupal\bhge_blog_question
 */
class QuestionTrail {


  protected $entityTypeManager;

  protected $currentRouteMatch;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   Entity type manager service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $currentRouteMatch
   *   The currently active route match object.
   */
  public function __construct(EntityTypeManager $entityTypeManager, RouteMatchInterface $currentRouteMatch) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentRouteMatch = $currentRouteMatch;
  }

  /**
   * Get parent trail upwards for current question.
   *
   * @param object $node
   *   Node, optional.
   * @param bool $publishedOnly
   *   Load only published.
   *
   * @return array
   *   Parent/child trail.
   */
  public function currentTrail($node = NULL, $publishedOnly = TRUE) {
    $return = [
      'current' => NULL,
      'parent' => NULL,
    ];
    if (!$node || $node == NULL) {
      $node = $this->currentRouteMatch->getParameter('node');
    }
    if ($node && in_array($node->bundle(), ['question'])) {
      $return['current'] = $node;
      $fieldParent = $node->get('field_blog_page');
      if (method_exists($fieldParent, 'first')) {
        $first = $fieldParent->first();
        if (method_exists($first, 'getValue')) {
          $value = $first->getValue();
          if (isset($value['target_id']) && is_numeric($value['target_id'])) {
            $parentNode = $this->entityTypeManager->getStorage('node')
              ->load($value['target_id']);
            if ($parentNode->isPublished() || !$publishedOnly) {
              $return['parent'] = $parentNode;
            }
          }
        }
      }
    }

    return $return;
  }

}
