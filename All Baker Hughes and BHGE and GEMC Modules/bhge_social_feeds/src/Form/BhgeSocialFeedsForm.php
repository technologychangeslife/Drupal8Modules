<?php

namespace Drupal\bhge_social_feeds\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * The BHGE Social Feeds Form Class.
 */
class BhgeSocialFeedsForm extends ConfigFormBase {
  protected $config;

  /**
   * The Config Factory Interface.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->configFactory->getEditable('bhge_social_feeds.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bhge_social_feeds_form_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // return;.
  }

  /**
   * Build Form.
   *
   * {@inheritdoc}
   *
   * Username: bhgeco
   * Password: Baker@Hughes2015!
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $access_key = $this->config->get('instagram_bhge_social_feeds_access_token');
    if (isset($_GET['code']) && $_GET['code'] != '') {
      if ($access_key == '') {
        try {
          $fields = [
            'client_id'     => $this->config->get('instagram_bhge_social_feeds_client_id'),
            'client_secret' => $this->config->get('instagram_bhge_social_feeds_client_secret'),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->config->get('instagram_bhge_social_feeds_redirect_uri'),
            'code'          => $_GET['code'],
          ];
          $url = 'https://api.instagram.com/oauth/access_token';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 20);
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
          $result = curl_exec($ch);
          curl_close($ch);
          $result = json_decode($result);
        }
        catch (RequestException $e) {
          watchdog_exception('bhge_social_feeds', $e->getMessage());
        }

        if (empty($result->error_message)) {
          $this->config->set('instagram_bhge_social_feeds_access_token', $result->access_token)
            ->set('instagram_bhge_social_feeds_user_id', $result->user->id)
            ->set('instagram_bhge_social_feeds_username', $result->user->username)
            ->save();
          $access_key = $result->access_token;
          drupal_set_message(t('Instagram authentication successful'));
        }
        else {
          drupal_set_message($result->error_message, 'error');
        }
      }
    }
    elseif (array_key_exists('code', $_GET) && $_GET['code'] == '') {
      // Remove api key for re-authentication.
      \Drupal::configFactory()->getEditable('bhge_social_feeds.settings')
        ->set('instagram_bhge_social_feeds_access_token', NULL)
        ->save();
      // Unset variable for form.
      $access_key = '';
    }
    if ($access_key == '') {
      // Non-authenticated settings form.
      $form['instagram_bhge_social_feeds_client_id'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram Client ID'),
        '#default_value' => $this->config->get('instagram_bhge_social_feeds_client_id'),
        '#required' => TRUE,
        '#size' => 60,
        '#maxlength' => 255,
        '#description' => $this->t('You must register an Instagram client key to use this module. You can register a client by
          <a href="http://instagram.com/developer/clients/manage/"
          target="_blank">clicking here</a>.'),
      ];
      $form['instagram_bhge_social_feeds_client_secret'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram Client Secret'),
        '#required' => TRUE,
        '#default_value' => $this->config->get('instagram_bhge_social_feeds_client_secret'),
        '#size' => 60,
        '#maxlength' => 255,
        '#description' => $this->t('The client secret can be found after
          creating an Instagram client in the API console.'),
      ];
      $form['instagram_bhge_social_feeds_redirect_uri'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram Redirect URI'),
        '#default_value' => $this->config->get('instagram_bhge_social_feeds_redirect_uri'),
        '#required' => TRUE,
        '#size' => 60,
        '#maxlength' => 255,
        '#description' => $this->t('Set the redirect URI to :url </br>
            After a succesful authentication you should see here the authorized Instagram username.', [
              ':url' => $base_url . \Drupal::url('bhge_social_feeds_form'),
            ]),
      ];
      $url = Url::fromUri('https://api.instagram.com/oauth/authorize/?client_id=' .
        $this->config->get('instagram_bhge_social_feeds_client_id') .
        '&redirect_uri=' . $this->config->get('instagram_bhge_social_feeds_redirect_uri') .
        '&response_type=code');
      if ($this->config->get('instagram_bhge_social_feeds_client_id') != '' &&
        $this->config->get('instagram_bhge_social_feeds_redirect_uri') != '') {
        $form['authenticate'] = [
          '#markup' =>
          \Drupal::l(t('Click here to authenticate via Instagram and create an access token'),
          $url
          ),
        ];
      }
    }
    else {
      // Authenticated user settings form.
      $form['instagram_bhge_social_feeds_access_token'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram API Key'),
        '#default_value' => $this->config->get('instagram_bhge_social_feeds_access_token'),
        '#size' => 60,
        '#maxlength' => 255,
        '#disabled' => TRUE,
        '#description' => $this->t('Stored access key for accessing the API key'),
      ];
      $form['instagram_bhge_social_feeds_username'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Instagram User name'),
        '#default_value' => $this->config->get('instagram_bhge_social_feeds_username'),
        '#size' => 60,
        '#maxlength' => 255,
        '#disabled' => TRUE,
        '#description' => $this->t("Authorized user\'s user name"),
      ];
      $url = Url::fromRoute('bhge_social_feeds_form', [
        'code' => '',
      ]);
      $form['authenticate'] = [
        '#markup' => \Drupal::l(
            $this->t('Click here to remove the access key and re-authenticate
              via Instagram'), $url
        ),
      ];
    }
    $form['instagram_item_to_fetch'] = [
      '#type' => 'number',
      '#required' => TRUE,
      '#title' => $this->t('Number of feed to fetch'),
      '#min' => 10,
      '#max' => 100,
      '#default_value' => $this->config->get('instagram_item_to_fetch'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config->set('instagram_bhge_social_feeds_client_id', $form_state->getValue('instagram_bhge_social_feeds_client_id'))
      ->set('instagram_bhge_social_feeds_client_secret', $form_state->getValue('instagram_bhge_social_feeds_client_secret'))
      ->set('instagram_bhge_social_feeds_redirect_uri', $form_state->getValue('instagram_bhge_social_feeds_redirect_uri'))
      ->set('instagram_item_to_fetch', $form_state->getValue('instagram_item_to_fetch'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
