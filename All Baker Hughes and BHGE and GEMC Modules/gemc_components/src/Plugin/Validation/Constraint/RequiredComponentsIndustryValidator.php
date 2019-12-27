<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates required components.
 */
class RequiredComponentsIndustryValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {

    if (!$items->getValue()) {
      return;
    }

    $c1_pass = FALSE;
    $c2_pass = FALSE;
    $c3_pass = FALSE;

    foreach ($items as $item) {
      $bundle = $item->entity->bundle();

      // Condition 1: C131 Section Hero.
      if ($bundle === 'c131_section_hero') {
        $c1_pass = TRUE;
      }

      // Condition 2: C19 Block Copy.
      if ($bundle === 'c19_block_copy') {
        $c2_pass = TRUE;
      }

      // Condition 3: C04 Contact (at any position as currently it's sticky to the left side).
      if ($bundle === 'c04_contact') {
        $c3_pass = TRUE;
      }
    }

    if (!$c1_pass || !$c2_pass || !$c3_pass) {
      $this->context->addViolation($constraint->industryMessage);
    }
  }

}
