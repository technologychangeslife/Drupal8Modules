<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Validation constraint, check if fields has required paragraphs.
 *
 * @Constraint(
 *   id = "RequiredComponents",
 *   label = @Translation("Required components constraint", context = "Validation"),
 * )
 */
class RequiredComponents extends Constraint {

  public $sectionMessage = 'The component display is currently expecting either a Carousel, C131 Section Hero, or Hero Video component followed by a C33 Long Text Image component.</br>
    Required components:
    <ul>
    <li>
    Carousel, C131 Section Hero, <strong>or</strong> Hero Video
    </li>
    <li>
    C33 Long Text Image <strong>or</strong> C19 Block Copy
    </li>
    <li>
    C04 Contact
    </li>
    </ul>';

  public $productMessage = 'The component display is currently expecting C100 Product Hero, or Hero Video components and a C04 Contact component.</br>
    Required components:
    <ul>
    <li>
    C100 Product Hero, <strong>or</strong> Hero Video
    </li>
    <li>
    C04 Contact
    </li>
    </ul>';

  public $industryMessage = 'The component display is currently expecting C131 Section Hero, C19 Block Copy component and C04 Contact component.</br>
    Required components:
    <ul>
    <li>
    C131 Section Hero
    </li>
    <li>
    C19 Block Copy
    </li>
    <li>
    C04 Contact
    </li>
    </ul>';

  /**
   * The bundle option.
   *
   * @var string
   */
  public $bundle;

  /**
   * {@inheritdoc}
   */
  public function getDefaultOption() {
    return 'bundle';
  }

  /**
   * {@inheritdoc}
   */
  public function getRequiredOptions() {
    return ['bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function validatedBy() {
    return \get_class($this) . ucfirst($this->bundle) . 'Validator';
  }

}
