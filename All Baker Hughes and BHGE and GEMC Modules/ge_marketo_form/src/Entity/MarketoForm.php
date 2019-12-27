<?php

namespace Drupal\ge_marketo_form\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Speaker entity.
 *
 * @ingroup ge_digital_events
 *
 * @ContentEntityType(
 *   id = "marketo_form",
 *   label = @Translation("Marketo Form"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ge_marketo_form\MarketoFormListBuilder",
 *     "views_data" = "Drupal\ge_marketo_form\Entity\MarketoFormViewsData",
 *     "translation" = "Drupal\ge_marketo_form\MarketoFormTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\ge_marketo_form\Form\MarketoFormForm",
 *       "add" = "Drupal\ge_marketo_form\Form\MarketoFormForm",
 *       "edit" = "Drupal\ge_marketo_form\Form\MarketoFormForm",
 *       "delete" = "Drupal\ge_marketo_form\Form\MarketoFormDeleteForm",
 *     },
 *     "access" = "Drupal\ge_marketo_form\MarketoFormAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "marketo_form",
 *   data_table = "marketo_form_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer marketo form entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/marketo-form/{marketo_form}",
 *     "add-form" = "/admin/content/marketo-form/add",
 *     "edit-form" = "/admin/content/marketo-form/{marketo_form}/edit",
 *     "delete-form" = "/admin/content/marketo-form/{marketo_form}/delete",
 *     "collection" = "/admin/content/marketo-forms",
 *   },
 * )
 */
class MarketoForm extends ContentEntityBase implements MarketoFormInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return $this->get('marketo_form_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFormId($mid) {
    $this->set('marketo_form_id', $mid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtonText() {
    return $this->get('button_text')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setButtonText($buttonText) {
    $this->set('button_text', $buttonText);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Marketo Form entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Marketo Form entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setRequired(TRUE)
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['button_text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The button text of the Marketo Form entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setRequired(TRUE)
      ->setDefaultValue('Submit')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['marketo_form_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Form ID'))
      ->setDescription(t('The Marketo Form ID.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_decimal',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -5,
      ])
      ->setRequired(TRUE)
      ->addConstraint('UniqueField');

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
