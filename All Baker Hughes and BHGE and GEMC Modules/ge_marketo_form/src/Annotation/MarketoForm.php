<?php

namespace Drupal\ge_marketo_form\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Class MarketoForm.
 *
 * @Annotation
 */
class MarketoForm extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The client ID.
   *
   * @var string
   */
  public $clientId;

  /**
   * The form ID.
   *
   * @var string
   */
  public $formId;

}
