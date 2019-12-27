<?php

namespace Drupal\bhge_rigcount\Extra;

/**
 * The RigCount Class.
 */
class RigCount {
  const UP = '+';
  const DOWN = '-';

  /**
   * Rig count area.
   *
   * @var string
   */
  protected $area = '';

  /**
   * The count variable.
   *
   * @var int
   */
  protected $count = 0;

  /**
   * The direction string.
   *
   * @var string
   */
  protected $direction = self::UP;

  /**
   * The Last Count Date string.
   *
   * @var string
   */
  protected $lastCountDate = '';

  /**
   * RigCount constructor.
   *
   * @param \SimpleXMLElement $data
   *   The simple XML element.
   */
  public function __construct(\SimpleXMLElement $data) {
    $this->area = (string) $data->area;
    $this->count = (int) str_replace(['+', '-'], '', $data->count);
    $this->direction = (string) (strpos($data->count, '-') === FALSE ? self::UP : self::DOWN);
    $this->lastCountDate = (string) $data->lstCntDt;
  }

  /**
   * Get the area.
   *
   * @return string
   *   Returns the area string.
   */
  public function getArea() {
    return $this->area;
  }

  /**
   * Get the count value.
   *
   * @return int
   *   Returns the count.
   */
  public function getCount() {
    return $this->count;
  }

  /**
   * Get the count direction.
   *
   * @return string
   *   Returns the direction string.
   */
  public function getDirection() {
    return $this->direction;
  }

  /**
   * Get the last count date.
   *
   * @return string
   *   Returns the lastCountDate string.
   */
  public function getLastCountDate() {
    return $this->lastCountDate;
  }

}
