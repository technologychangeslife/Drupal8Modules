<?php

namespace Drupal\bh_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\datetime\Plugin\Field\FieldFormatter\DateTimePlainFormatter;
use Drupal\datetime_range\DateTimeRangeTrait;

/**
 * Plugin implementation of the 'BH Styled Date Range' formatter for 'daterange' fields.
 *
 * This formatter renders the data range as a plain text string, with a
 * configurable separator using an ISO-like date format string.
 *
 * @FieldFormatter(
 *   id = "daterange_bh",
 *   label = @Translation("BH Styled Date Range"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class DateRangeBhFormatter extends DateTimePlainFormatter {

  use DateTimeRangeTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'separator' => '-',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $separator = $this->getSetting('separator');

    foreach ($items as $delta => $item) {
      if (!empty($item->start_date) && !empty($item->end_date)) {
        /** @var \Drupal\Core\Datetime\DrupalDateTime $start_date */
        $start_date = $item->start_date;
        /** @var \Drupal\Core\Datetime\DrupalDateTime $end_date */
        $end_date = $item->end_date;

        if ($start_date->getTimestamp() == $end_date->getTimestamp()) {
          $elements[$delta] = $this->buildBhSingleDate($start_date, 'F jS Y');

          if (!empty($item->_attributes)) {
            $elements[$delta]['#attributes'] += $item->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and should not be rendered in the field template.
            unset($item->_attributes);
          }
        }
        // Months are different so we include them in the end date.
        elseif ($start_date->format('n') !== $end_date->format('n')) {
          $elements[$delta] = [
            'start_date' => $this->buildBhSingleDate($start_date, 'F jS'),
            'separator' => ['#plain_text' => ' ' . $separator . ' '],
            'end_date' => $this->buildBhSingleDate($end_date, 'F jS Y'),
          ];

          if (!empty($item->_attributes)) {
            $elements[$delta]['#attributes'] += $item->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and should not be rendered in the field template.
            unset($item->_attributes);
          }
        }
        else {
          $elements[$delta] = [
            'start_date' => $this->buildBhSingleDate($start_date, 'F jS'),
            'separator' => ['#plain_text' => ' ' . $separator . ' '],
            'end_date' => $this->buildBhSingleDate($end_date, 'jS Y'),
          ];

          if (!empty($item->_attributes)) {
            $elements[$delta]['#attributes'] += $item->_attributes;
            // Unset field item attributes since they have been included in the
            // formatter output and should not be rendered in the field template.
            unset($item->_attributes);
          }
        }
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['separator'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date separator'),
      '#description' => $this->t('The string to separate the start and end dates'),
      '#default_value' => $this->getSetting('separator'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    if ($separator = $this->getSetting('separator')) {
      $summary[] = $this->t('Separator: %separator', ['%separator' => $separator]);
    }

    return $summary;
  }

  /**
   * Creates a render array from a date object.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $date
   *   A date object.
   * @param string $format
   *   A default format string to use.
   *
   * @return array
   *   A render array.
   */
  protected function buildBhSingleDate(DrupalDateTime $date, $format = 'F j Y') {

    $build = [
      '#markup' => $date->format($format),
      '#cache' => [
        'contexts' => [
          'timezone',
        ],
      ],
    ];

    return $build;
  }

}
