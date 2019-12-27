<?php

namespace Drupal\ge_marketo_form;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the MarketoForm entity.
 *
 * @see \Drupal\ge_marketo_form\Entity\MarketoForm.
 */
class MarketoFormAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ge_marketo_form\Entity\MarketoFormInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view published marketo form entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit marketo form entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete marketo form entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add marketo form entities');
  }

}
