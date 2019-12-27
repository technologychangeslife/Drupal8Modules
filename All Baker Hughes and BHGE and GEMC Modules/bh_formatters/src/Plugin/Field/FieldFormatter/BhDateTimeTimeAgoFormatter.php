<?php

namespace Drupal\bh_formatters\Plugin\Field\FieldFormatter;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldFormatter\DateTimeTimeAgoFormatter;

/**
 * Plugin implementation of the 'Time ago' formatter for 'datetime' fields.
 *
 * @FieldFormatter(
 *   id = "bh_datetime_time_ago",
 *   label = @Translation("BH Time ago"),
 *   field_types = {
 *     "datetime"
 *   }
 * )
 */
class BhDateTimeTimeAgoFormatter extends DateTimeTimeAgoFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'interval_cutoff' => 7
      ] + parent::defaultSettings();
  }

  /**
   * Formats a date/time as a time interval. Only include hours if less than 1 week.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime|object $date
   *   A date/time object.
   *
   * @return array
   *   The formatted date/time string using the past or future format setting.
   */
  protected function formatDate(DrupalDateTime $date) {
    // Test to see if it's in the past or not.
    if ($this->request->server->get('REQUEST_TIME') > $date->getTimestamp()) {
      if ($this->isLessThanSixDays($this->request->server->get('REQUEST_TIME'), $date->getTimestamp())) {
        $this->setSetting('granularity', 1);
      }
      else {
        $new = new DrupalDateTime($date);
        $build = [
          '#markup' => new FormattableMarkup('@date', ['@date' => $new->format('F j, Y')]),
        ];
        return $build;
      }
    }
    else {
      if ($this->isLessThanSixDays($date->getTimestamp(), $this->request->server->get('REQUEST_TIME'))) {
        $this->setSetting('granularity', 1);
      }
      else {
        $new = new DrupalDateTime($date);
        $build = [
          '#markup' => new FormattableMarkup('@date', ['@date' => $new->format('F j, Y')]),
        ];
        return $build;
      }
    }

    return parent::formatTimestamp($date->getTimestamp());
  }

  /**
   * Test to see if it's less than one day interval.
   *
   * @param int $higher
   *   The higher value.
   * @param int $lower
   *   The lower value.
   *
   * @return bool
   *   True if less than one day, otherwise false.
   */
  protected function isLessThanSixDays($higher, $lower) {
    $interval_cutoff = $this->getSetting('interval_cutoff');
    return $higher < ($lower + (60 * 60 * 24 * $interval_cutoff)) ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['interval_cutoff'] = [
      '#type' => 'number',
      '#title' => $this->t('Interval Cutoff'),
      '#default_value' => $this->getSetting('interval_cutoff') ?: 7,
      '#min' => 1,
      '#max' => 32,
      '#description' => $this->t('Enter the number of days that pass before using date instead of "time ago".'),
    ];
    return $form;
  }

}
