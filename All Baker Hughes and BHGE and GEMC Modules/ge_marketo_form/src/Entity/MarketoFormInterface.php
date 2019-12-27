<?php

namespace Drupal\ge_marketo_form\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Speaker entities.
 *
 * @ingroup ge_digital_events
 */
interface MarketoFormInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   *
   * Gets the MarketoForm name.
   *
   * @return string
   *   Name of the MarketoForm.
   */
  public function getName();

  /**
   * Sets the MarketoForm name.
   *
   * @param string $name
   *   The MarketoForm name.
   *
   * @return \Drupal\ge_marketo_form\Entity\MarketoFormInterface
   *   The called MarketoForm entity.
   */
  public function setName($name);

  /**
   * Gets the Marketo Form ID.
   *
   * @return int
   *   ID of the MarketoForm.
   */
  public function getFormId();

  /**
   * Sets the MarketoForm ID.
   *
   * @param int $mid
   *   The MarketoForm name.
   *
   * @return \Drupal\ge_marketo_form\Entity\MarketoFormInterface
   *   The called MarketoForm entity.
   */
  public function setFormId($mid);

  /**
   * Get button text.
   *
   * @return mixed
   *   Returns button text.
   */
  public function getButtonText();

  /**
   * Set the button text.
   *
   * @param string $buttonText
   *   The button text.
   *
   * @return mixed
   *   Setting the button text.
   */
  public function setButtonText($buttonText);

  /**
   * Gets the MarketoForm creation timestamp.
   *
   * @return int
   *   Creation timestamp of the MarketoForm.
   */
  public function getCreatedTime();

  /**
   * Sets the MarketoForm creation timestamp.
   *
   * @param int $timestamp
   *   The MarketoForm creation timestamp.
   *
   * @return \Drupal\ge_marketo_form\Entity\MarketoFormInterface
   *   The called MarketoForm entity.
   */
  public function setCreatedTime($timestamp);

}
