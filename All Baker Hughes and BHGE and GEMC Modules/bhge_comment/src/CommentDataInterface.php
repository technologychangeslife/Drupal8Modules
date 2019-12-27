<?php

namespace Drupal\bhge_comment;

use Drupal\Node\Entity\Node;

/**
 * Defines the storage handler class for comments.
 */
interface CommentDataInterface {

  /**
   * Load the comment tree.
   *
   * @param \Drupal\node\Entity\Node $entity
   *   Node entity.
   * @param string $fieldName
   *   Field name.
   * @param int $commentStatus
   *   Comment status.
   *
   * @return array
   *   Nested list of comment data for theming.
   */
  public function loadCommentTree(Node $entity, $fieldName, $commentStatus);

  /**
   * Get comment id's for parent entity.
   *
   * @param \Drupal\Node\Entity\Node $entity
   *   Parent entity.
   * @param string $field_name
   *   Comment field name.
   * @param array $pids
   *   ID's of parent comments.
   *
   * @return array
   *   List of comment id's
   */
  public function loadCommentIds(Node $entity, $field_name, array $pids = []);

  /**
   * Load data for comment id's.
   *
   * @param array $cids
   *   Comment id's.
   *
   * @return array
   *   Array of values per comment for theme usage.
   */
  public function loadCommentData(array $cids = []);

  /**
   * Show maximum comments.
   *
   * @return int
   *   Number of comments.
   */
  public function getShowMax();

}
