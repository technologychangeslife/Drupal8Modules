<?php

namespace Drupal\bhge_mail_share\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Access\AccessResult;

/**
 * Controller routines for share info.
 */
class Share extends ControllerBase {

  protected $request;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      \Drupal::request()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($request) {
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Initial validate mailadress.
   */
  protected function validEmailAddress($mail) {
    return \Drupal::service('email.validator')->isValid($mail);
  }

  /**
   * Send mail message.
   */
  public function sendEmail() {
    $response = new Response();
    $response->headers->set('Content-Type', 'application/json');
    $response->setContent(json_encode(['success' => 0]));

    $subject = Xss::filter(escapeshellarg($this->request->get('subject')));
    $body = Xss::filter(htmlentities($this->request->get('body')));
    $email = Xss::filter($this->request->get('email'));
    $url = Xss::filter($this->request->get('url'));

    if (!$email || !$subject || !$body || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return $response;
    }

    mail($email, $subject, $body . PHP_EOL . 'Shared page url: ' . $url, 'From: ' . \Drupal::service('state')->get('email_share_sender'));

    $response->setContent(json_encode(['success' => 1]));
    return $response;
  }

}
