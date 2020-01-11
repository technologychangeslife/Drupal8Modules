<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

/**
 * StatusCodeEnum Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class StatusCodeEnum {
  /**
     * Possible values of this enum.
     */
  const CREATED = 'CREATED';
  const PENDING = 'PENDING';
  const SENDING = 'SENDING';
  const SENDING_TO_COPY_BACK = 'SENDING_TO_COPY_BACK';
  const SENT_TO_PLATFORM = 'SENT_TO_PLATFORM';
  const SENT_TO_TRANSLATOR = 'SENT_TO_TRANSLATOR';
  const IN_TRANSLATION = 'IN_TRANSLATION';
  const REVIEW_TRANSLATION = 'REVIEW_TRANSLATION';
  const TRANSLATION_REJECTED = 'TRANSLATION_REJECTED';
  const COMPLETED = 'COMPLETED';
  const COMPLETED_NO_NEED_TO_TRANSLATE = 'COMPLETED_NO_NEED_TO_TRANSLATE';
  const COMPLETED_COPY_BACK = 'COMPLETED_COPY_BACK';
  const CANCELLED = 'CANCELLED';

  /**
   * Gets allowable values of the enum.
   *
   * @return string[]
   */
  public static function getAllowableEnumValues() {
    return [
      self::CREATED,
      self::PENDING,
      self::SENDING,
      self::SENDING_TO_COPY_BACK,
      self::SENT_TO_PLATFORM,
      self::SENT_TO_TRANSLATOR,
      self::IN_TRANSLATION,
      self::REVIEW_TRANSLATION,
      self::TRANSLATION_REJECTED,
      self::COMPLETED,
      self::COMPLETED_NO_NEED_TO_TRANSLATE,
      self::COMPLETED_COPY_BACK,
      self::CANCELLED,
    ];
  }

}