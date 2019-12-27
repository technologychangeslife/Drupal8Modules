<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * ArrayOfRequestIdsNote Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class ArrayOfRequestIdsNote implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'ArrayOfRequestIdsNote';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'request_ids' => 'string[]',
        'note' => 'string'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'request_ids' => NULL,
        'note' => NULL
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
        'request_ids' => 'requestIds',
        'note' => 'note'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'request_ids' => 'setRequestIds',
        'note' => 'setNote'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'request_ids' => 'getRequestIds',
        'note' => 'getNote'
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
    $this->container['request_ids'] = isset($data['request_ids']) ? $data['request_ids'] : NULL;
    $this->container['note'] = isset($data['note']) ? $data['note'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    if ($this->container['request_ids'] === NULL) {
      $invalidProperties[] = "'request_ids' can't be null";
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

    if ($this->container['request_ids'] === NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Gets request_ids.
   *
   * @return string[]
   */
  public function getRequestIds() {
    return $this->container['request_ids'];
  }

  /**
   * Sets request_ids.
   *
   * @param string[] $request_ids
   *   ID of translation request that will be retrieved.
   *
   * @return $this
   */
  public function setRequestIds($request_ids) {
    $this->container['request_ids'] = $request_ids;

    return $this;
  }

  /**
   * Gets note.
   *
   * @return string
   */
  public function getNote() {
    return $this->container['note'];
  }

  /**
   * Sets note.
   *
   * @param string $note
   *   Note which explains the reason(s) for rejection.
   *
   * @return $this
   */
  public function setNote($note) {
    $this->container['note'] = $note;

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
