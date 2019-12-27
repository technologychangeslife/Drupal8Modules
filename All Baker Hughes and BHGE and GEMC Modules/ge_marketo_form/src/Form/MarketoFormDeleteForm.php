<?php

namespace Drupal\ge_marketo_form\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting Speaker entities.
 *
 * @ingroup ge_digital_events
 */
class MarketoFormDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);

    // Clear all plugin caches.
    \Drupal::service('plugin.cache_clearer')->clearCachedDefinitions();

  }

}
