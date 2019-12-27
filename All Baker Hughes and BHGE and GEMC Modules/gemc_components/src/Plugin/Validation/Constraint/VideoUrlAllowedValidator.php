<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Drupal\Core\Url;
use Drupal\Component\Utility\UrlHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the VideoUrlAllowed constraint.
 */
class VideoUrlAllowedValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    $allowed_sites = [
      'youtube',
      'youtu.be',
      'facebook',
      'brightcove',
      'office365',
    ];
    $allowed_url = FALSE;
    foreach ($items as $item) {
      if (!UrlHelper::isValid($item->value, TRUE)) {
        $this->context->addViolation('This value is not a valid URL.');
      }
      else {
        $url = Url::fromUri($item->value, ['exteranl' => TRUE]);
        $host = parse_url($url->getUri(), PHP_URL_HOST);
        foreach ($allowed_sites as $allowed_site) {
          if (strpos($host, $allowed_site) !== FALSE) {
            $allowed_url = TRUE;
            break;
          }
        }
        if (!$allowed_url) {
          $this->context->addViolation($constraint->message);
        }
      }
    }
  }

}
