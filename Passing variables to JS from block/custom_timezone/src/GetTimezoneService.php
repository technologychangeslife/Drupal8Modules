<?php

namespace Drupal\custom_timezone;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Our hero article service class.
 */
class GetTimezoneService {

  /**
   * Methood for getting Articles, regarding heroes.
   */
  public function getCurrentTime($new_timezone) {
    $datetime = new DrupalDateTime();
    $current_timezone = $datetime->setTimezone(new \DateTimeZone($new_timezone))->format('jS M o g:i a');

    return $current_timezone;
  }
}
