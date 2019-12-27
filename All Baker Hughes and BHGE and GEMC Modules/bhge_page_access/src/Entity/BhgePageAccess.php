<?php

namespace Drupal\bhge_page_access\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\bhge_page_access\BhgePageAccessInterface;

/**
 * Defines the page access entity class.
 *
 * @ContentEntityType(
 *   id = "bhge_page_access",
 *   label = @Translation("Page access"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *   },
 *   admin_permission = "administer content",
 *   base_table = "bhge_page_access",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 * )
 */
class BhgePageAccess extends ContentEntityBase implements BhgePageAccessInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    $values += ['bundle' => 'bhge_page_access'];
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Entity ID'))
      ->setDescription(t('The entity ID for this page access content entity.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The content page access UUID.'))
      ->setReadOnly(TRUE);

    $fields['nid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Node ID'))
      ->setDescription(t('The Node ID of the node.'))
      ->setSettings([
        'target_type' => 'node',
        'default_value' => 0,
      ]);

    $fields['value'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Value'))
      ->setDescription(t('Page access value as a serialized array'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the menu link was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * Method to load page access entity using node id.
   *
   * @param int $nid
   *   Node id of the content.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Return Entity object of the node.
   */
  public function loadByNid($nid) {
    $bhge_page_access_storage = \Drupal::entityManager()->getStorage('bhge_page_access');
    $bhge_page_access = $bhge_page_access_storage->loadByProperties(['nid' => $nid]);
    $bhge_page_access_entity = array_shift($bhge_page_access);
    return $bhge_page_access_entity;
  }

  /**
   * Method to get value of page access settings.
   *
   * @return mixed
   *   Return value.
   */
  public function value() {
    return $this->get('value')->get(0)->toArray();
  }

}
