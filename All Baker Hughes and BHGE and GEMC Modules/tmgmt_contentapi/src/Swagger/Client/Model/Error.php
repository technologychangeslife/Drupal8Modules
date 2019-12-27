<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * Error Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class Error implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'Error';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'code' => 'int',
        'message' => 'string',
        'fields' => 'string'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'code' => 'int32',
        'message' => NULL,
        'fields' => NULL
    ];

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @return array
   */
  public static function swaggerTypes() {
    return self::$swaggerTypes;
  }

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @return array
   */
  public static function swaggerFormats() {
    return self::$swaggerFormats;
  }

  /**
   * Array of attributes where the key is the local name,
   * and the value is the original name.
   *
   * @var string[]
   */
  protected static $attributeMap = [
        'code' => 'code',
        'message' => 'message',
        'fields' => 'fields'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'code' => 'setCode',
        'message' => 'setMessage',
        'fields' => 'setFields'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'code' => 'getCode',
        'message' => 'getMessage',
        'fields' => 'getFields'
    ];

  /**
   * Array of attributes where the key is the local name,
   * and the value is the original name.
   *
   * @return array
   */
  public static function attributeMap() {
    return self::$attributeMap;
  }

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @return array
   */
  public static function setters() {
    return self::$setters;
  }

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @return array
   */
  public static function getters() {
    return self::$getters;
  }

  /**
   * The original name of the model.
   *
   * @return string
   */
  public function getModelName() {
    return self::$swaggerModelName;
  }

  /**
   * Associative array for storing property values.
   *
   * @var mixed[]
   */
  protected $container = [];

  /**
   * Constructor.
   *
   * @param mixed[] $data
   *   Associated array of property values
   *   initializing the model.
   */
  public function __construct(array $data = NULL) {
    $this->container['code'] = isset($data['code']) ? $data['code'] : NULL;
    $this->container['message'] = isset($data['message']) ? $data['message'] : NULL;
    $this->container['fields'] = isset($data['fields']) ? $data['fields'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    if ($this->container['code'] === NULL) {
      $invalidProperties[] = "'code' can't be null";
    }
    return $invalidProperties;
  }

  /**
   * Validate all the properties in the model
   * return true if all passed.
   *
   * @return bool True if all properties are valid
   */
  public function valid() {

    if ($this->container['code'] === NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Gets code.
   *
   * @return int
   */
  public function getCode() {
    return $this->container['code'];
  }

  /**
   * Sets code.
   *
   * @param int $code
   *   code.
   *
   * @return $this
   */
  public function setCode($code) {
    $this->container['code'] = $code;

    return $this;
  }

  /**
   * Gets message.
   *
   * @return string
   */
  public function getMessage() {
    return $this->container['message'];
  }

  /**
   * Sets message.
   *
   * @param string $message
   *   message.
   *
   * @return $this
   */
  public function setMessage($message) {
    $this->container['message'] = $message;

    return $this;
  }

  /**
   * Gets fields.
   *
   * @return string
   */
  public function getFields() {
    return $this->container['fields'];
  }

  /**
   * Sets fields.
   *
   * @param string $fields
   *   fields.
   *
   * @return $this
   */
  public function setFields($fields) {
    $this->container['fields'] = $fields;

    return $this;
  }

  /**
   * Returns true if offset exists. False otherwise.
   *
   * @param int $offset
   *   Offset.
   *
   * @return bool
   */
  public function offsetExists($offset) {
    return isset($this->container[$offset]);
  }

  /**
   * Gets offset.
   *
   * @param int $offset
   *   Offset.
   *
   * @return mixed
   */
  public function offsetGet($offset) {
    return isset($this->container[$offset]) ? $this->container[$offset] : NULL;
  }

  /**
   * Sets value based on offset.
   *
   * @param int $offset
   *   Offset.
   * @param mixed $value
   *   Value to be set.
   *
   * @return void
   */
  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->container[] = $value;
    }
    else {
      $this->container[$offset] = $value;
    }
  }

  /**
   * Unsets offset.
   *
   * @param int $offset
   *   Offset.
   *
   * @return void
   */
  public function offsetUnset($offset) {
    unset($this->container[$offset]);
  }

  /**
   * Gets the string presentation of the object.
   *
   * @return string
   */
  public function __toString() {
    // Use JSON pretty print.
    if (defined('JSON_PRETTY_PRINT')) {
      return json_encode(
        ObjectSerializer::sanitizeForSerialization($this),
        JSON_PRETTY_PRINT
      );
    }

    return json_encode(ObjectSerializer::sanitizeForSerialization($this));
  }

}
