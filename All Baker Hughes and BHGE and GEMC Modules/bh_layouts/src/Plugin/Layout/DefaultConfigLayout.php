<?php

namespace Drupal\bh_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 *
 */
class DefaultConfigLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function getWidthClasses() {
    $base = [
      'padding-normal' => 'Padding Normal',
      'padding-small' => 'Padding Small',
      'padding-large' => 'Padding Large',
      'full-width' => 'Span Full Width',
      'contain-in-grid' => 'Contain In Grid (Full Width Only)',
      'remove-gutters' => 'Remove Column Gutters',
      'color-primary' => 'Background Color Primary',
      'color-secondary' => 'Background Color Secondary',
      'color-tertiary' => 'Background Color Tertiary',
      'border-above' => 'Border Above',
      'border-below' => 'Border Below'
    ];

    if ($this::getPluginDefinition()->getTemplate() == 'bh-twocol-section') {
      return array_merge($base, [
        '50-50' => '6 columns 6 columns',
        '33-67' => '4 columns 8 columns',
        '67-33' => '8 columns 4 columns',
        '25-75' => '3 columns 9 columns',
        '75-25' => '9 columns 3 columns',
      ]);
    }

    if ($this::getPluginDefinition()->getTemplate() == 'bh-threecol-section') {
      return array_merge($base, [
        '25-50-25' => '3 columns 6 columns 3 columns',
        '33-34-33' => '4 columns 4 columns 4 columns',
        '25-25-50' => '3 columns 3 columns 6 columns',
        '50-25-25' => '6 columns 3 columns 3 columns',
      ]);
    }
    return $base;
  }

  /**
   *
   */
  public function defaultConfiguration() {
    $width_classes = array_keys($this->getWidthClasses());
    return [
      'additional_classes' => array_shift($width_classes),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['section_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section Title'),
      '#default_value' => $this->configuration['section_title'],
      '#maxlength' => 120,
      '#size' => 60,
      '#description' => $this->t('Title of the section.'),
    ];
    $form['additional_classes'] = [
      '#type' => 'select',
      '#chosen' => TRUE,
      '#multiple' => TRUE,
      '#title' => $this->t('Section Options'),
      '#default_value' => $this->configuration['additional_classes'],
      '#options' => $this->getWidthClasses(),
      '#description' => $this->t('Choose the additional options for this layout.'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['additional_classes'] = $form_state->getValue('additional_classes');
    $this->configuration['section_title'] = $form_state->getValue('section_title');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);

    $classes = implode(
        " ",
        array_map(function ($val) {
            return 'bh-layouts__' . $this->getPluginDefinition()->getTemplate() . '--' . $val;
        }, $this->configuration['additional_classes'])
    );

    $build['#attributes']['class'] = [
          "bh-layouts ",
          "bh-layouts__" . $this->getPluginDefinition()->getTemplate(),
          $classes
    ];

    return $build;
  }

}
