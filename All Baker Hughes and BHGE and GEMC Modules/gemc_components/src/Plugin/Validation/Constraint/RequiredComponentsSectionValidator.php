<?php

namespace Drupal\gemc_components\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates required components.
 */
class RequiredComponentsSectionValidator extends ConstraintValidator {

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
    $c1_pos = NULL;
    $c2_pos = NULL;

    foreach ($items as $pos => $item) {
      $bundle = $item->entity->bundle();

      // Condition 1: Carousel or C131 Section Hero component.
      if (!$c1_pos && in_array($bundle, ['carousel', 'c131_section_hero', 'hero_video'])) {
        $c1_pos = $pos;
        $c1_pass = TRUE;
      }

      // Condition 2: Condition 1 followed by C33 Long Text Image or C19 Block Copy.
      if (!$c2_pos && in_array($bundle, ['c33_long_text_image', 'c19_block_copy'])) {
        $c2_pos = $pos;
        if ($c2_pos === ($c1_pos + 1)) {
          $c2_pass = TRUE;
        }
      }

      // Condition 3: C04 Contact (at any position as currently it's sticky to the left side).
      if ($bundle === 'c04_contact') {
        $c3_pass = TRUE;
      }
    }

    if (!$c1_pass || !$c2_pass || !$c3_pass) {
      $this->context->addViolation($constraint->sectionMessage);
    }
  }

}
