<?php

namespace Drupal\form_hero\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Our custom hero form.
 */
class HeroForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "form_hero_heroform";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['rival_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rival one'),
    ];

    $form['rival_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rival two'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Who will win?'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    user_cookie_delete('key');
    
    $a=array("red","green");
    array_push($a, $form_state->getValue('rival_1'));
    
    $cookies_val = '';
    foreach($a as $key => $value) {
     $cookies_val = $value. ",". $cookies_val;
    }

    user_cookie_save(array('key'=> $cookies_val));
    
    $winner = rand(1, 2);
    drupal_set_message(
      'The winner between ' . $form_state->getValue('rival_1') . ' and ' .
      $form_state->getValue('rival_2') . ' is: ' . $form_state->getValue('rival_' . $winner) .' Session Variable '. $details
    );
    
    $response = new RedirectResponse('/drupal8/herolist');
    $response->send();
  }
}

