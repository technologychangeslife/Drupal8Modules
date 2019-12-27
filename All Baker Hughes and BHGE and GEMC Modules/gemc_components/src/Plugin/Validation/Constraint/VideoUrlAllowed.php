<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Validation constraint, check if video url is supported.
 *
 * @Constraint(
 *   id = "VideoUrlAllowed",
 *   label = @Translation("Video Url Allowed", context = "Validation"),
 * )
 */
class VideoUrlAllowed extends Constraint {

  public $message = 'Only videos from <em>youtube, facebook, brightcove</em> and <em>office365</em> are allowed.';

}
