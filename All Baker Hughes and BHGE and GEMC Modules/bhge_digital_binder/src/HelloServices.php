<?php

namespace Drupal\bhge_digital_binder;

/**
 *
 */
class HelloServices {

  protected $say_something;

  /**
   *
   */
  public function __construct() {
    $this->say_something = 'Hello World!';
  }

  /**
   *
   */
  public function sayHello($name = '') {
    if (empty($name)) {
      return $this->say_something;
    }
    else {
      return "Hello " . $name . "!";
    }
  }

}
