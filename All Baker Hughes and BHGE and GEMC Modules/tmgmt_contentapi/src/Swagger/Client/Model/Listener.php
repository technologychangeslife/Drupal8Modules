<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * Listener Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class Listener implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'Listener';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'listener_id' => 'string',
        'job_id' => 'string',
        'uri' => 'string',
        'type' => '\Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerTypeEnum',
        'status_codes' => '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum[]',
        'auth_type' => '\Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerAuthEnum'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'listener_id' => NULL,
        'job_id' => NULL,
        'uri' => NULL,
        'type' => NULL,
        'status_codes' => NULL,
        'auth_type' => NULL
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
        'uri' => 'uri',
        'type' => 'type',
        'status_codes' => 'statusCodes',
        'auth_type' => 'authType'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'listener_id' => 'setListenerId',
        'job_id' => 'setJobId',
        'uri' => 'setUri',
        'type' => 'setType',
        'status_codes' => 'setStatusCodes',
        'auth_type' => 'setAuthType'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'listener_id' => 'getListenerId',
        'job_id' => 'getJobId',
        'uri' => 'getUri',
        'type' => 'getType',
        'status_codes' => 'getStatusCodes',
        'auth_type' => 'getAuthType'
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
    $this->container['uri'] = isset($data['uri']) ? $data['uri'] : NULL;
    $this->container['type'] = isset($data['type']) ? $data['type'] : NULL;
    $this->container['status_codes'] = isset($data['status_codes']) ? $data['status_codes'] : NULL;
    $this->container['auth_type'] = isset($data['auth_type']) ? $data['auth_type'] : NULL;
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
    if ($this->container['uri'] === NULL) {
      $invalidProperties[] = "'uri' can't be null";
    }
    if ($this->container['type'] === NULL) {
      $invalidProperties[] = "'type' can't be null";
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
    if ($this->container['uri'] === NULL) {
      return FALSE;
    }
    if ($this->container['type'] === NULL) {
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
   *   The Id of the Listener.
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
   *   The Id of the translation job.
   *
   * @return $this
   */
  public function setJobId($job_id) {
    $this->container['job_id'] = $job_id;

    return $this;
  }

  /**
   * Gets uri.
   *
   * @return string
   */
  public function getUri() {
    return $this->container['uri'];
  }

  /**
   * Sets uri.
   *
   * @param string $uri
   *   The URI that Clay Tablet should ping once a specific event has fired.
   *
   * @return $this
   */
  public function setUri($uri) {
    $this->container['uri'] = $uri;

    return $this;
  }

  /**
   * Gets type.
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerTypeEnum
   */
  public function getType() {
    return $this->container['type'];
  }

  /**
   * Sets type.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerTypeEnum $type
   *   type.
   *
   * @return $this
   */
  public function setType($type) {
    $this->container['type'] = $type;

    return $this;
  }

  /**
   * Gets status_codes.
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum[]
   */
  public function getStatusCodes() {
    return $this->container['status_codes'];
  }

  /**
   * Sets status_codes.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusCodeEnum[] $status_codes
   *   Status code types that you wish to monitor. If none specified, then all will be monitored.
   *
   * @return $this
   */
  public function setStatusCodes($status_codes) {
    $this->container['status_codes'] = $status_codes;

    return $this;
  }

  /**
   * Gets auth_type.
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerAuthEnum
   */
  public function getAuthType() {
    return $this->container['auth_type'];
  }

  /**
   * Sets auth_type.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\ListenerAuthEnum $auth_type
   *   Authentication type for listener, if left empty then CTT_GENERATED_TOKEN will be used.
   *
   * @return $this
   */
  public function setAuthType($auth_type) {
    $this->container['auth_type'] = $auth_type;

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
