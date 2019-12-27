<?php

namespace Drupal\bhge_user_registration\Form;

use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The Non-GE Form Class.
 */
class NongeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nonge_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = User::load(\Drupal::currentUser()->id());
    $user->get('init')->value;
    $str = $user->get('init')->value;
    $sso_array = [];
    $sso_array = explode("simplesamlphp_auth_", $str);
    $selectvalues = \Drupal::config('bhge_user_registration.engagereceipsettings');

    $form['firstname_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('First Name:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['lastname_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['ssoid_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('GE Single sign on(SSO)'),
      '#required' => TRUE,
      '#size' => 255,
      '#default_value' => $sso_array[1],
      '#attributes' => ['class' => ['geform'], 'readonly' => 'readonly'],
    ];

    $form['companyname_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Company Name:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['jobtitle_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Job Title:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['phonenumber_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Phone number:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['email_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Company email address'),
      '#required' => TRUE,
      '#size' => 255,
      '#default_value' => $user->get('mail')->value,
      '#attributes' => ['class' => ['geform'], 'readonly' => 'readonly'],
    ];

    $form['address_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Address:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['city_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('City:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['state_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('State or Province:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['country_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Country:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['zipcode_nonge'] = [
      '#type' => 'textfield',
      '#title' => t('Zip or Postal code:'),
      '#required' => TRUE,
      '#size' => 255,
      '#attributes' => ['class' => ['geform']],
    ];

    $dlemailidconfig = \Drupal::config('bhge_user_registration.engagereceipsettings');
    $text = $dlemailidconfig->get('gecontact_all');
    $array = preg_split('/\R/', $text);
    $first = '';
    foreach ($array as $name) {
      $pieces = explode('|', $name);
      $contact[$pieces[0]] = $pieces[0];
      if (empty($first)) {
        $first = $pieces[0];
      }
    }
    $contact['Other'] = "Other";

    $form['geemployeecontant_nonge'] = [
      '#type' => 'select',
      '#title' => t('GE Employee contact:'),
      '#required' => TRUE,
      '#options' => $contact,
      '#default_value' => $first,
      '#attributes' => ['class' => ['geform']],
    ];

    $form['display2'] = [
      '#title' => t('Contact Name:'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#size' => 255,
      '#default_value' => 'Contact',
      '#states' => ['visible' => [':input[name="geemployeecontant_nonge"]' => ['value' => "Other"]]],
      '#attributes' => ['class' => ['geform']],
    ];

    $form['display3'] = [
      '#title' => t('Contact email:'),
      '#type' => 'textfield',
      '#size' => 255,
      '#states' => ['visible' => [':input[name="geemployeecontant_nonge"]' => ['value' => "Other"]]],
      '#attributes' => ['class' => ['geform']],
    ];

    $form['issuelogin_nonge'] = [
      '#type' => 'button',
      '#value' => t('Issues logging in'),
      '#attributes' => ['class' => ['gesubmit gefull']],

    ];

    $form['submit_nonge'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#attributes' => ['class' => ['gesubmit gefull']],

    ];

    $form['#attached']['library'][] = 'bhge_user_registration/nonge-form-js';
    $form['#theme'] = 'nonge_registrationform';
    return $form;
  }

  /**
   * The select names configure.
   */
  public  function selectNamesConfigured($selectvalues) {
    $selectvalues = \Drupal::config('bhge_user_registration.engagereceipsettings');
    $text = $selectvalues->getValue('group_geinstructs');
    $array = preg_split('/\R/', $text);
    return $array;
  }

  /**
   * {@inheritdoc}
   */

  /**
   * Non-GE employee registration form processing.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state array.
   *
   * @return mixed
   *   The submit form method and email sending functionality on form submit.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Initiate the email addresses for a GE Contact.
    $dlemailidconfig = \Drupal::config('bhge_user_registration.engagereceipsettings');
    $text = $dlemailidconfig->get('gecontact_all');
    $default_nonge_email = $dlemailidconfig->get('bhcustomerdefaultemailid');
    $array = preg_split('/\R/', $text);
    $first = '';
    foreach ($array as $name) {
      $pieces = explode('|', $name);
      $contact[$pieces[0]] = $pieces[1];
    }
    // Add the case of an email address being entered.
    $contact['Other'] = 'Other';

    foreach ($form_state->getValues() as $key => $value) {
      if ($key == 'firstname_nonge') {
        $firstname = $value;
      }

      if ($key == 'lastname_nonge') {
        $lastname = $value;
      }

      if ($key == 'ssoid_nonge') {
        $sso = $value;
      }

      if ($key == 'companyname_nonge') {
        $companyname_nonge = $value;
      }

      if ($key == 'jobtitle_nonge') {
        $jobtitle_nonge = $value;
      }

      if ($key == 'phonenumber_nonge') {
        $phonenumber_nonge = $value;
      }

      if ($key == 'email_nonge') {
        $email_nonge = $value;
      }

      if ($key == 'address_nonge') {
        $address_nonge = $value;
      }

      if ($key == 'city_nonge') {
        $city_nonge = $value;
      }

      if ($key == 'state_nonge') {
        $state_nonge = $value;
      }

      if ($key == 'country_nonge') {
        $country_nonge = $value;
      }

      if ($key == 'zipcode_nonge') {
        $zipcode_nonge = $value;
      }

      if ($key == 'geemployeecontant_nonge') {
        $geemployeecontant_nonge = $value;
      }
      if ($key == 'display2') {
        $othergeemployeecontant_name = $value;
      }
      if ($key == 'display3') {
        $othergeemployeecontant_emailid = $value;
      }
    } // end form value retrieval

    // Current user is logged in with simplesaml via SSO, get user id.
    $uid = \Drupal::currentUser()->id();
    // Load current user data.
    $user = User::load($uid);

    if ($user) {
      // If the GE contact is filled in.
      if ($geemployeecontant_nonge == 'Other') {
        if (!empty($othergeemployeecontant_emailid)) {
          $selectemail = $deault_nonge_email;
          $geempcontact = $othergeemployeecontant_name;
        }
        else {
          $selectemail = $deault_nonge_email;
          $geempcontact = $othergeemployeecontant_name;
        }
      }
      // Else selected from the contact list.
      else {
        $selectemail = $contact[$geemployeecontant_nonge];
        $emailcontact = $selectemail[0];
        $geempcontact = $selectemail[1];
      }

      $user->set('field_first_name', $firstname);
      $user->set('field_last_name', $lastname);
      $user->set('field_sso_id', $sso);
      $user->set('field_company_non_ge', $companyname_nonge);
      $user->set('field_jobtitle', $jobtitle_nonge);
      $user->set('field_phonenumber', $phonenumber_nonge);
      $user->set('field_address', $address_nonge);
      $user->set('field_city', $city_nonge);
      $user->set('field_state', $state_nonge);
      $user->set('field_country', $country_nonge);
      $user->set('field_zip_code', $zipcode_nonge);
      $user->set('field_ge_emp_contact', $geempcontact);
      $user->set('field_customer_group', $companyname_nonge);
      $user->save();
    }

    $dlemailidconfig = \Drupal::config('bhge_user_registration.engagereceipsettings');
    $dlemailid = $dlemailidconfig->get('gedlemailid');
    $mailManager = \Drupal::service('plugin.manager.mail');
    if ($geemployeecontant_nonge == 'Other') {

      if (!empty($othergeemployeecontant_emailid)) {

        $selectemail = $othergeemployeecontant_emailid;
        $to = $default_nonge_email;
        $cc = $dlemailid;
        $namecontact = $othergeemployeecontant_name;
        $formcontact = $othergeemployeecontant_name;

      }
      else {

        $to = $default_nonge_email;
        $namecontact = '';
        $formcontact = $othergeemployeecontant_name;

      }
    }
    else {

      $emailcontact = $contact[$geemployeecontant_nonge];
      $namecontact = $geemployeecontant_nonge;
      $formcontact = $geemployeecontant_nonge;
      $to = $emailcontact;
      $cc = $dlemailid;

    }

    $message = '
		<html>
		<body>
		  <h2>Hello ' . $namecontact . ',</h2>
				<p style="padding-left:50px;">' . $firstname . '   ' . $lastname . ' from ' . $companyname_nonge . '  has requested access to engageRecip. You have been identified as the key contact for approval/denial of this user. Please "Reply All" to this email on whether to accept or deny user. If you are not the right person to approve access, please reply to this email with the appropriate contact CC"d if you are aware.</p>
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
			  <td>' . $email_nonge . '</td>
			</tr>
			 <tr>
			  <td>Job Title:</td>
			  <td>' . $jobtitle_nonge . '</td>
			</tr>
			 <tr>
			  <td>Company Name:</td>
			  <td>' . $companyname_nonge . '</td>
			</tr>
			 <tr>
			  <td>Phone Number:</td>
			  <td>' . $phonenumber_nonge . '</td>
			</tr>
			 <tr>
			  <td>Address:</td>
			  <td>' . $address_nonge . '</td>
			</tr>
			<tr>
			  <td>City:</td>
			  <td>' . $city_nonge . '</td>
			</tr>
			<tr>
			  <td>State or Province:</td>
			  <td>' . $state_nonge . '</td>
			</tr>
			<tr>
			  <td>Country:</td>
			  <td>' . $country_nonge . '</td>
			</tr>
			<tr>
			  <td>Zip or Postal code:</td>
			  <td>' . $zipcode_nonge . '</td>
			</tr>
			<tr>
			  <td>GE Employee Contact:</td>
			  <td>' . $formcontact . '</td>
			</tr>
		  </table>
		</body>
		</html>';
    $module = 'bhge_user_registration';
    $key = 'nonge_form_submit';

    $params['message'] = $message;
    $params['Cc'] = $cc;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    $form_state->setRedirect('bhge_user_registration.requsetpendingmessage');
    // return;.
  }

}
