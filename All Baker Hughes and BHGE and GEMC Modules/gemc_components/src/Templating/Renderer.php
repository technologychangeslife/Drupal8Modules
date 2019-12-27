<?php

namespace Drupal\gemc_components\Templating;

use Drupal\gemc_components\Handlebars\Helpers\ArrayLookupHelper;
use Drupal\gemc_components\Handlebars\Helpers\ConditionHelper;
use Drupal\handlebars_theme_handler\FilesUtility;
use Drupal\handlebars_theme_handler\Templating\Renderer as HandlebarsRenderer;

/**
 * Service to render handlebars templates.
 */
class Renderer extends HandlebarsRenderer {

  /**
   * Constructor.
   *
   * @throws \InvalidArgumentException
   *   If no template directories got defined.
   */
  public function __construct(FilesUtility $filesUtility) {
    parent::__construct($filesUtility);
    $this->addHelper('condition', new ConditionHelper());
    $this->addHelper('arrayLookup', new ArrayLookupHelper());
  }

}
