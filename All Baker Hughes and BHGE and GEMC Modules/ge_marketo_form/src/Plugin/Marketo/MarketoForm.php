<?php

namespace Drupal\ge_marketo_form\Plugin\Marketo;

use Drupal\ge_marketo_form\Plugin\MarketoFormBase;

/**
 * Defines a generic custom block type.
 *
 * @MarketoForm(
 *   id = "marketo_form",
 *   deriver = "Drupal\ge_marketo_form\Plugin\Derivative\MarketoForm"
 * )
 */
class MarketoForm extends MarketoFormBase {

  /**
   * {@inheritdoc}
   */
  public function getForm(array $variables = []) {
    $marketoForm = parent::getForm($variables);
    $marketoForm['#data']['marketoForm'] = $this->getEntity();
    $marketoForm['#data']['buttonText'] = $this->getButtonText();
    return $marketoForm;
  }

}
