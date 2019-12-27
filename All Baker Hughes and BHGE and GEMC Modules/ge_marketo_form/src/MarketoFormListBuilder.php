<?php

namespace Drupal\ge_marketo_form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of MarketoForm entities.
 *
 * @ingroup ge_marketo_form
 */
class MarketoFormListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('MArketo Form Entity ID');
    $header['name'] = $this->t('Name');
    $header['marketo_form_id'] = $this->t('Form ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ge_marketo_form\Entity\MarketoFormInterface */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.marketo_form.edit_form',
      ['marketo_form' => $entity->id()]
    );
    $row['marketo_form_id'] = $entity->getFormId();
    return $row + parent::buildRow($entity);
  }

}
