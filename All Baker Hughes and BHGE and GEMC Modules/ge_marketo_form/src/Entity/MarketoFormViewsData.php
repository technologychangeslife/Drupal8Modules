<?php

namespace Drupal\ge_marketo_form\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Speaker entities.
 */
class MarketoFormViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
