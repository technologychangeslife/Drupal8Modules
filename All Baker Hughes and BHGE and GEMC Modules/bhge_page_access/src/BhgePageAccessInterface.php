<?php

namespace Drupal\bhge_page_access;

use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for custom menu links.
 */
interface BhgePageAccessInterface extends ContentEntityInterface, EntityChangedInterface {

}
