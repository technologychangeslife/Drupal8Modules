<?php

namespace Drupal\bhge_user_registration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Class SpectrumSettingsForm.
 *
 * @package Drupal\spin_settings\Form
 */
class EngagereceipSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'engagereceip_settings_form';
  }

  /**
   * Get Editable Config Names.
   */
  protected function getEditableConfigNames() {
    return [
      'bhge_user_registration.engagereceipsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bhge_user_registration.engagereceipsettings');
    $form['group_geinstructs'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('GE REGISTRATION INSTRUCTIONS'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        'group_geinstructs' => [
          '#attributes' => ['style' => 'width: 50%; float: left; margin-right:1%;'],
          '#type' => 'fieldset',
          '#title' => $this->t('Ge Registration Instructions'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          "options" => [
            "group_geinstructs" => [
              '#type' => 'text_format',
              '#title' => $this->t('GE Registration Instructions'),
              '#description' => t('Enter all contacts at once. Use the form of Name|Email Address'),
              '#default_value' => $config->get('group_geinstructs'),
              '#cols' => 90,
              '#resizable' => TRUE,
              '#rows' => 5,
              '#format' => $term->format,
            ],
          ],
        ],
      ],
    ];

    $form['group_gecontactform'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('GE CONTACT FIELD DROPDOWN VALUES IN NON GE FORM'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        'gecontact_all' => [
          '#attributes' => ['style' => 'width: 29%; float: left; margin-right:1%;'],
          '#type' => 'fieldset',
          '#title' => $this->t('Ge All Contact Options'),
          '#collapsible' => FALSE,
          '#collapsed' => FALSE,
          "options" => [
            "gecontact_all" => [
              '#type' => 'textarea',
              '#title' => $this->t('Gecontact All Contact Options'),
              '#description' => t('Enter all contacts at once. Use the form of Name|Email Address'),
              '#default_value' => $config->get('gecontact_all'),
              '#cols' => 60,
              '#resizable' => TRUE,
              '#rows' => 5,
            ],
          ],
        ],
      ],
    ];
    $deault_nonge_email = (!empty($config->get('bhcustomerdefaultemailid'))) ? $config->get('bhcustomerdefaultemailid') : $config->get('gedlemailid');
    $default_delete_email = (!empty($config->get('bh_account_delete_email'))) ? $config->get('bh_account_delete_email') : $config->get('gedlemailid');
    $form['group_gedlemailform'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Engagereceip DL Email ID to receive Emails regarding Request Access'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        "bhcustomerdefaultemailid" => [
          '#type' => 'textfield',
          '#title' => $this->t('Default account to notify for non-BH signup'),
          '#default_value' => $config->get('bhcustomerdefaultemailid'),
          '#size' => 50,
        ],
        "bh_account_delete_email" => [
          '#type' => 'textfield',
          '#title' => $this->t("Account to notify when users are deleted"),
          '#default_value' => $default_delete_email,
          '#size' => 50,
        ],
        "gedlemailid" => [
          '#type' => 'textfield',
          '#title' => $this->t('Engagereceip DL Email Id'),
          '#default_value' => $config->get('gedlemailid'),
          '#size' => 50,
        ],

      ],

    ];
    $form['group_googleanalyticsid'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enter Google analytics ID which will be used by bhge_analytics module'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        "googleanalyticsid" => [
          '#type' => 'textfield',
          '#title' => $this->t('Google Analytics Id'),
          '#default_value' => $config->get('googleanalyticsid'),
          '#size' => 50,
        ],

      ],

    ];

    $form['group_software'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enter Software file link here which will be used in Software Page'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      "options" => [
        "softwarefile" => [
          '#type' => 'textfield',
          '#title' => $this->t('Software file Link'),
          '#default_value' => $config->get('softwarefile'),
          '#size' => 50,

        ],
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('bhge_user_registration.engagereceipsettings');
    $hold = $form_state->getValue('group_geinstructs')['value'];
    $config->set('gecontact_all', $form_state->getValue('gecontact_all'));
    $config->set('group_geinstructs', $hold);
    $config->set('gedlemailid', $form_state->getValue('gedlemailid'));
    $config->set('bhcustomerdefaultemailid', $form_state->getValue('bhcustomerdefaultemailid'));
    $config->set('googleanalyticsid', $form_state->getValue('googleanalyticsid'));
    $config->set('softwarefile', $form_state->getValue('softwarefile'));
    $config->save();
  }

}
