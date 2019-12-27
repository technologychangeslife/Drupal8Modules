<?php

namespace Drupal\ge_marketo_form\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Speaker edit forms.
 *
 * @ingroup ge_digital_events
 */
class MarketoFormForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ge_digital_events\Entity\Speaker */
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Marketo Form entity.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Marketo Form entity.', [
          '%label' => $entity->label(),
        ]));
    }

    // Clear all plugin caches.
    \Drupal::service('plugin.cache_clearer')->clearCachedDefinitions();

    $form_state->setRedirect('entity.marketo_form.edit_form', ['marketo_form' => $entity->id()]);
  }

}
