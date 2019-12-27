<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * SupportAsset Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class SupportAsset implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'SupportAsset';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'supportasset_id' => 'string',
        'file_id' => 'string',
        'job_id' => 'string',
        'filename' => 'string',
        'filetype' => 'string'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'supportasset_id' => NULL,
        'file_id' => NULL,
        'job_id' => NULL,
        'filename' => NULL,
        'filetype' => NULL
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
        'supportasset_id' => 'supportassetId',
        'file_id' => 'fileId',
        'job_id' => 'jobId',
        'filename' => 'filename',
        'filetype' => 'filetype'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'supportasset_id' => 'setSupportassetId',
        'file_id' => 'setFileId',
        'job_id' => 'setJobId',
        'filename' => 'setFilename',
        'filetype' => 'setFiletype'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'supportasset_id' => 'getSupportassetId',
        'file_id' => 'getFileId',
        'job_id' => 'getJobId',
        'filename' => 'getFilename',
        'filetype' => 'getFiletype'
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
    $this->container['supportasset_id'] = isset($data['supportasset_id']) ? $data['supportasset_id'] : NULL;
    $this->container['file_id'] = isset($data['file_id']) ? $data['file_id'] : NULL;
    $this->container['job_id'] = isset($data['job_id']) ? $data['job_id'] : NULL;
    $this->container['filename'] = isset($data['filename']) ? $data['filename'] : NULL;
    $this->container['filetype'] = isset($data['filetype']) ? $data['filetype'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    if ($this->container['supportasset_id'] === NULL) {
      $invalidProperties[] = "'supportasset_id' can't be null";
    }
    if ($this->container['file_id'] === NULL) {
      $invalidProperties[] = "'file_id' can't be null";
    }
    if ($this->container['job_id'] === NULL) {
      $invalidProperties[] = "'job_id' can't be null";
    }
    if ($this->container['filename'] === NULL) {
      $invalidProperties[] = "'filename' can't be null";
    }
    if ($this->container['filetype'] === NULL) {
      $invalidProperties[] = "'filetype' can't be null";
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

    if ($this->container['supportasset_id'] === NULL) {
      return FALSE;
    }
    if ($this->container['file_id'] === NULL) {
      return FALSE;
    }
    if ($this->container['job_id'] === NULL) {
      return FALSE;
    }
    if ($this->container['filename'] === NULL) {
      return FALSE;
    }
    if ($this->container['filetype'] === NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Gets supportasset_id.
   *
   * @return string
   */
  public function getSupportassetId() {
    return $this->container['supportasset_id'];
  }

  /**
   * Sets supportasset_id.
   *
   * @param string $supportasset_id
   *   Unique identifier representing support asset.
   *
   * @return $this
   */
  public function setSupportassetId($supportasset_id) {
    $this->container['supportasset_id'] = $supportasset_id;

    return $this;
  }

  /**
   * Gets file_id.
   *
   * @return string
   */
  public function getFileId() {
    return $this->container['file_id'];
  }

  /**
   * Sets file_id.
   *
   * @param string $file_id
   *   Unique identifier representing asset file.
   *
   * @return $this
   */
  public function setFileId($file_id) {
    $this->container['file_id'] = $file_id;

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
   *   Unique identifier representing a translation job.
   *
   * @return $this
   */
  public function setJobId($job_id) {
    $this->container['job_id'] = $job_id;

    return $this;
  }

  /**
   * Gets filename.
   *
   * @return string
   */
  public function getFilename() {
    return $this->container['filename'];
  }

  /**
   * Sets filename.
   *
   * @param string $filename
   *   Name of file.
   *
   * @return $this
   */
  public function setFilename($filename) {
    $this->container['filename'] = $filename;

    return $this;
  }

  /**
   * Gets filetype.
   *
   * @return string
   */
  public function getFiletype() {
    return $this->container['filetype'];
  }

  /**
   * Sets filetype.
   *
   * @param string $filetype
   *   Type of file.
   *
   * @return $this
   */
  public function setFiletype($filetype) {
    $this->container['filetype'] = $filetype;

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
