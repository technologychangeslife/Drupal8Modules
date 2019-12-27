<?php

namespace Drupal\ge_marketo_form\Plugin;

/**
 * Interface MarketoFromInterface.
 *
 * @package Drupal\ge_marketo_form\Plugin
 */
interface MarketoFormInterface {

  /**
   * The get form function.
   *
   * @param array $variables
   *   The form variables array.
   *
   * @return mixed
   *   Returns the form.
   */
  public function getForm(array $variables = []);

  /**
   * The get Form ID.
   *
   * @param array $variables
   *   The Form variables.
   *
   * @return int
   *   Returns the form ID.
   */
  public function getFormId(array $variables = []);

  /**
   * The get client ID.
   *
   * @return int
   *   Returns Client ID.
   */
  public function getClientId();

}
