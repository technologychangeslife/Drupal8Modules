<?php

namespace Drupal\bhge_user_registration\Form;

use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Database\Connection;
use Drupal\user\Entity;

/**
 * The GE Form Class.
 */
class GeForm extends FormBase {

  /**
   * The get Form ID function.
   */
  public function getFormId() {
    return 'ge_form';
  }

  /**
   * The build form function.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $user = User::load(\Drupal::currentUser()->id());
    $user->get('init')->value;
    $str = $user->get('init')->value;
    $sso_array = [];
    $sso_array = explode("simplesamlphp_auth_", $str);

    $form['firstname_ge'] = [
      '#type' => 'textfield',
      '#title' => t('Firstname:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],

    ];
    $form['lastname_ge'] = [
      '#type' => 'textfield',
      '#title' => t('Lastname:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],

    ];
    $form['ssoid_ge'] = [
      '#type' => 'textfield',
      '#title' => t('GE Single sign on(SSO)'),
      '#required' => TRUE,
      '#size' => 255,
      '#default_value' => $sso_array[1],
      '#attributes' => ['class' => ['geform'], 'readonly' => 'readonly'],

    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#required' => TRUE,
      '#size' => 255,
      '#default_value' => $user->get('mail')->value,
      '#attributes' => ['class' => ['geform'], 'readonly' => 'readonly'],

    ];
    $form['issueloggingin_ge'] = [
      '#type' => 'button',
      '#value' => t('Issues logging in'),
      '#attributes' => ['class' => ['gesubmit gefull']],

    ];
    $form['submit_ge'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#attributes' => ['class' => ['gesubmit gefull']],

    ];
    $form['#attached']['library'][] = 'bhge_user_registration/ge-form-js';
    $form['#theme'] = 'ge_registrationform';
    return $form;

  }

  /**
   * Form Submission.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    foreach ($form_state->getValues() as $key => $value) {
      if ($key == 'email') {
        $emailid = $value;
      }

      if ($key == 'firstname_ge') {
        $firstname = $value;
      }

      if ($key == 'lastname_ge') {
        $lastname = $value;
      }

      if ($key == 'ssoid_ge') {
        $sso = $value;
      }
    }

    $uid = \Drupal::currentUser()->id();
    $user = User::load($uid);

    if ($user) {
      $user->set('field_first_name', $firstname);
      $user->set('field_last_name', $lastname);
      $user->set('field_sso_id', $sso);
      $user->save();
    }

    $dlemailidconfig = \Drupal::config('bhge_user_registration.engagereceipsettings');
    $dlemailid = $dlemailidconfig->get('gedlemailid');
    $to = $dlemailid;
    $cc = $emailid;

    $mailManager = \Drupal::service('plugin.manager.mail');
    $message = '
  <html>
  <body>
  <h2>Hello ,</h2>
      <p style="padding-left:50px;">Contact details are submitted successfully with following details :</p>
  <table>
  <tr>
    <td>First Name:</td>
  <td>' . $firstname . '</td>
  </tr>
  <tr>
      <td>Last Name:</td>
    <td>' . $lastname . '</td>
  </tr>
  <tr>
    <td>GE Single sign on (SSO):</td>
  <td>' . $sso . '</td>
  </tr>
    <tr>
    <td>email:</td>
  <td>' . $emailid . '</td>
  </tr>
 <tr>
    <td>Job Title:</td>
  <td></td>
  </tr>
 <tr>
    <td>Company Name:</td>
  <td></td>
  </tr>
 <tr>
    <td>Phone Number:</td>
  <td></td>
  </tr>
 <tr>
    <td>Address:</td>
  <td></td>
  </tr>
<tr>
    <td>City:</td>
  <td></td>
  </tr>
<tr>
    <td>State or Province:</td>
  <td></td>
  </tr>
<tr>
    <td>Country:</td>
  <td></td>
  </tr>
<tr>
    <td>Zip or Postal code:</td>
  <td></td>
  </tr>
<tr>
    <td>GE Employee Contact:</td>
  <td></td>
  </tr>
  </table>
  </body>
  </html>';

    $module = 'bhge_user_registration';
    $key = 'ge_form_submit';
    $params['Cc'] = $cc;
    $params['message'] = $message;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    $form_state->setRedirect('bhge_user_registration.requsetpendingmessage');
    return;

  }

}
