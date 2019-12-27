<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

/**
 * ListenerAuthEnum Class Doc Comment.
 *
 * @category Class
 * @description Authentication type for listener, if left empty then CTT_GENERATED_TOKEN will be used.
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class ListenerAuthEnum {
  /**
     * Possible values of this enum.
     */
  const CTT_GENERATED_TOKEN = 'CTT_GENERATED_TOKEN';
  const NONE = 'NONE';

  /**
   * Gets allowable values of the enum.
   *
   * @return string[]
   */
  public static function getAllowableEnumValues() {
    return [
      self::CTT_GENERATED_TOKEN,
      self::NONE,
    ];
  }

}
