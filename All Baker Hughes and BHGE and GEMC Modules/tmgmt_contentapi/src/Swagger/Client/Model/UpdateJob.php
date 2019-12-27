<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * UpdateJob Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class UpdateJob implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'UpdateJob';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'job_name' => 'string',
        'description' => 'string',
        'po_reference' => 'string',
        'due_date' => 'string',
        'custom_data' => 'string',
        'should_quote' => 'bool'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'job_name' => NULL,
        'description' => NULL,
        'po_reference' => NULL,
        'due_date' => NULL,
        'custom_data' => NULL,
        'should_quote' => NULL
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
        'job_name' => 'jobName',
        'description' => 'description',
        'po_reference' => 'poReference',
        'due_date' => 'dueDate',
        'custom_data' => 'customData',
        'should_quote' => 'shouldQuote'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'job_name' => 'setJobName',
        'description' => 'setDescription',
        'po_reference' => 'setPoReference',
        'due_date' => 'setDueDate',
        'custom_data' => 'setCustomData',
        'should_quote' => 'setShouldQuote'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'job_name' => 'getJobName',
        'description' => 'getDescription',
        'po_reference' => 'getPoReference',
        'due_date' => 'getDueDate',
        'custom_data' => 'getCustomData',
        'should_quote' => 'getShouldQuote'
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
    $this->container['job_name'] = isset($data['job_name']) ? $data['job_name'] : NULL;
    $this->container['description'] = isset($data['description']) ? $data['description'] : NULL;
    $this->container['po_reference'] = isset($data['po_reference']) ? $data['po_reference'] : NULL;
    $this->container['due_date'] = isset($data['due_date']) ? $data['due_date'] : NULL;
    $this->container['custom_data'] = isset($data['custom_data']) ? $data['custom_data'] : NULL;
    $this->container['should_quote'] = isset($data['should_quote']) ? $data['should_quote'] : FALSE;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    return $invalidProperties;
  }

  /**
   * Validate all the properties in the model
   * return true if all passed.
   *
   * @return bool True if all properties are valid
   */
  public function valid() {

    return TRUE;
  }

  /**
   * Gets job_name.
   *
   * @return string
   */
  public function getJobName() {
    return $this->container['job_name'];
  }

  /**
   * Sets job_name.
   *
   * @param string $job_name
   *   The name of the translation job.
   *
   * @return $this
   */
  public function setJobName($job_name) {
    $this->container['job_name'] = $job_name;

    return $this;
  }

  /**
   * Gets description.
   *
   * @return string
   */
  public function getDescription() {
    return $this->container['description'];
  }

  /**
   * Sets description.
   *
   * @param string $description
   *   A description of the translation job.
   *
   * @return $this
   */
  public function setDescription($description) {
    $this->container['description'] = $description;

    return $this;
  }

  /**
   * Gets po_reference.
   *
   * @return string
   */
  public function getPoReference() {
    return $this->container['po_reference'];
  }

  /**
   * Sets po_reference.
   *
   * @param string $po_reference
   *   PO Reference of the translation job.
   *
   * @return $this
   */
  public function setPoReference($po_reference) {
    $this->container['po_reference'] = $po_reference;

    return $this;
  }

  /**
   * Gets due_date.
   *
   * @return string
   */
  public function getDueDate() {
    return $this->container['due_date'];
  }

  /**
   * Sets due_date.
   *
   * @param string $due_date
   *   Due date of the translation job. Expected format is \"yyyy-MM-dd\" or with time expressed in GMT as: \"yyyy-MM-ddTHH:mm:ssZ\".
   *
   * @return $this
   */
  public function setDueDate($due_date) {
    $this->container['due_date'] = $due_date;

    return $this;
  }

  /**
   * Gets custom_data.
   *
   * @return string
   */
  public function getCustomData() {
    return $this->container['custom_data'];
  }

  /**
   * Sets custom_data.
   *
   * @param string $custom_data
   *   User specified custom data.
   *
   * @return $this
   */
  public function setCustomData($custom_data) {
    $this->container['custom_data'] = $custom_data;

    return $this;
  }

  /**
   * Gets should_quote.
   *
   * @return bool
   */
  public function getShouldQuote() {
    return $this->container['should_quote'];
  }

  /**
   * Sets should_quote.
   *
   * @param bool $should_quote
   *   Request a quote for the job before translation proceeds.  TODO -  describe how quoting happens outside of REST API.
   *
   * @return $this
   */
  public function setShouldQuote($should_quote) {
    $this->container['should_quote'] = $should_quote;

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
