<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * Token Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class Token implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'Token';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'access_token' => 'string',
        'token_type' => 'string',
        'expires_in' => 'int',
        'refresh_token' => 'string'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'access_token' => NULL,
        'token_type' => NULL,
        'expires_in' => 'int32',
        'refresh_token' => NULL
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
        'access_token' => 'accessToken',
        'token_type' => 'tokenType',
        'expires_in' => 'expiresIn',
        'refresh_token' => 'refreshToken'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'access_token' => 'setAccessToken',
        'token_type' => 'setTokenType',
        'expires_in' => 'setExpiresIn',
        'refresh_token' => 'setRefreshToken'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'access_token' => 'getAccessToken',
        'token_type' => 'getTokenType',
        'expires_in' => 'getExpiresIn',
        'refresh_token' => 'getRefreshToken'
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
    $this->container['access_token'] = isset($data['access_token']) ? $data['access_token'] : NULL;
    $this->container['token_type'] = isset($data['token_type']) ? $data['token_type'] : NULL;
    $this->container['expires_in'] = isset($data['expires_in']) ? $data['expires_in'] : NULL;
    $this->container['refresh_token'] = isset($data['refresh_token']) ? $data['refresh_token'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    if ($this->container['access_token'] === NULL) {
      $invalidProperties[] = "'access_token' can't be null";
    }
    if ($this->container['token_type'] === NULL) {
      $invalidProperties[] = "'token_type' can't be null";
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

    if ($this->container['access_token'] === NULL) {
      return FALSE;
    }
    if ($this->container['token_type'] === NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Gets access_token.
   *
   * @return string
   */
  public function getAccessToken() {
    return $this->container['access_token'];
  }

  /**
   * Sets access_token.
   *
   * @param string $access_token
   *   This access token can be used to authorize requests.
   *
   * @return $this
   */
  public function setAccessToken($access_token) {
    $this->container['access_token'] = $access_token;

    return $this;
  }

  /**
   * Gets token_type.
   *
   * @return string
   */
  public function getTokenType() {
    return $this->container['token_type'];
  }

  /**
   * Sets token_type.
   *
   * @param string $token_type
   *   The token type will always be \"bearer\".
   *
   * @return $this
   */
  public function setTokenType($token_type) {
    $this->container['token_type'] = $token_type;

    return $this;
  }

  /**
   * Gets expires_in.
   *
   * @return int
   */
  public function getExpiresIn() {
    return $this->container['expires_in'];
  }

  /**
   * Sets expires_in.
   *
   * @param int $expires_in
   *   The time in seconds the token will expire.
   *
   * @return $this
   */
  public function setExpiresIn($expires_in) {
    $this->container['expires_in'] = $expires_in;

    return $this;
  }

  /**
   * Gets refresh_token.
   *
   * @return string
   */
  public function getRefreshToken() {
    return $this->container['refresh_token'];
  }

  /**
   * Sets refresh_token.
   *
   * @param string $refresh_token
   *   This token can be used to get a new access token.
   *
   * @return $this
   */
  public function setRefreshToken($refresh_token) {
    $this->container['refresh_token'] = $refresh_token;

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
