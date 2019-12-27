<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

/**
 * ListenerTypeEnum Class Doc Comment.
 *
 * @category Class
 * @description The type of listner. JOB_STATUS_UPDATED will send events only about the job as a whole. REQUEST_STATUS_UPDATED will send events on a request by request basis for the job.
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class ListenerTypeEnum {
  /**
     * Possible values of this enum.
     */
  const JOB_STATUS_UPDATED = 'JOB_STATUS_UPDATED';
  const REQUEST_STATUS_UPDATED = 'REQUEST_STATUS_UPDATED';

  /**
   * Gets allowable values of the enum.
   *
   * @return string[]
   */
  public static function getAllowableEnumValues() {
    return [
      self::JOB_STATUS_UPDATED,
      self::REQUEST_STATUS_UPDATED,
    ];
  }

}
