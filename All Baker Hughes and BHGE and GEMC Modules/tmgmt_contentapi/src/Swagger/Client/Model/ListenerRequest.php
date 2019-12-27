<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * ListenerRequest Class Doc Comment.
 *
 * @category Class
 * @description Object format for what a Listener will send to a URI
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class ListenerRequest implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'ListenerRequest';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'listener_id' => 'string',
        'job_id' => 'string',
        'request_ids' => 'string[]',
        'status_code' => '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'listener_id' => NULL,
        'job_id' => NULL,
        'request_ids' => NULL,
        'status_code' => NULL
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
        'listener_id' => 'listenerId',
        'job_id' => 'jobId',
        'request_ids' => 'requestIds',
        'status_code' => 'statusCode'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'listener_id' => 'setListenerId',
        'job_id' => 'setJobId',
        'request_ids' => 'setRequestIds',
        'status_code' => 'setStatusCode'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'listener_id' => 'getListenerId',
        'job_id' => 'getJobId',
        'request_ids' => 'getRequestIds',
        'status_code' => 'getStatusCode'
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
    $this->container['listener_id'] = isset($data['listener_id']) ? $data['listener_id'] : NULL;
    $this->container['job_id'] = isset($data['job_id']) ? $data['job_id'] : NULL;
    $this->container['request_ids'] = isset($data['request_ids']) ? $data['request_ids'] : NULL;
    $this->container['status_code'] = isset($data['status_code']) ? $data['status_code'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    if ($this->container['listener_id'] === NULL) {
      $invalidProperties[] = "'listener_id' can't be null";
    }
    if ($this->container['job_id'] === NULL) {
      $invalidProperties[] = "'job_id' can't be null";
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

    if ($this->container['listener_id'] === NULL) {
      return FALSE;
    }
    if ($this->container['job_id'] === NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Gets listener_id.
   *
   * @return string
   */
  public function getListenerId() {
    return $this->container['listener_id'];
  }

  /**
   * Sets listener_id.
   *
   * @param string $listener_id
   *   The Id of the listener that generated this request.
   *
   * @return $this
   */
  public function setListenerId($listener_id) {
    $this->container['listener_id'] = $listener_id;

    return $this;
  }

  /**
   * Gets job_id.
   *
   * @return string
   */
  public function getJobId() {
    return $this->container['job_id'];
  }

  /**
   * Sets job_id.
   *
   * @param string $job_id
   *   The Id of the translation job for this update.
   *
   * @return $this
   */
  public function setJobId($job_id) {
    $this->container['job_id'] = $job_id;

    return $this;
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
   *   The request Ids for this update.
   *
   * @return $this
   */
  public function setRequestIds($request_ids) {
    $this->container['request_ids'] = $request_ids;

    return $this;
  }

  /**
   * Gets status_code.
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum
   */
  public function getStatusCode() {
    return $this->container['status_code'];
  }

  /**
   * Sets status_code.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum $status_code
   *   status_code.
   *
   * @return $this
   */
  public function setStatusCode($status_code) {
    $this->container['status_code'] = $status_code;

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
