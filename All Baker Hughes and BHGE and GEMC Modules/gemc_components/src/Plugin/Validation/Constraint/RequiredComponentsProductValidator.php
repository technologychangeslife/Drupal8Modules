<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates required components.
 */
class RequiredComponentsProductValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {

    if (!$items->getValue()) {
      return;
    }

    $c1_pass = FALSE;
    $c2_pass = FALSE;

    foreach ($items as $item) {
      $bundle = $item->entity->bundle();

      // Condition 1: C100 Product Hero.
      if (in_array($bundle, ['c100_product_hero', 'hero_video'])) {
        $c1_pass = TRUE;
      }

      // Condition 2: C04 Contact (at any position as currently it's sticky to the left side).
      if ($bundle === 'c04_contact') {
        $c2_pass = TRUE;
      }
    }

    if (!$c1_pass || !$c2_pass) {
      $this->context->addViolation($constraint->productMessage);
    }
  }

}
