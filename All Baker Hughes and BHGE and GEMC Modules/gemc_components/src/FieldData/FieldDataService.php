<?php

namespace Drupal\gemc_components\FieldData;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldException;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\gemc_components\FieldData\Helper\VideoDataHelper;
use Drupal\image\Entity\ImageStyle;

/**
 * Helper service to get field data in needed format.
 */
class FieldDataService {

  /**
   * Video data helper.
   *
   * @var \Drupal\gemc_components\FieldData\Helper\VideoDataHelper
   */
  private $videoDataHelper;

  /**
   * FieldDataService constructor.
   *
   * @param \Drupal\gemc_components\FieldData\Helper\VideoDataHelper $video_data_helper
   *   Video data helper.
   */
  public function __construct(VideoDataHelper $video_data_helper) {
    $this->videoDataHelper = $video_data_helper;
  }

  /**
   * Get video field data.
   *
   * @param array $field
   *   Field array that can be fetched from preprocess function.
   *
   * @return array
   *   Video field data.
   */
  public function getVideoInformation(array $field) {
    if (isset($field['#items'])) {
      $field_item_list = $field['#items'];
      $field_item = $field_item_list->get(0);
      return $this->videoDataHelper->getVideoInfoByUrl($field_item->value);
    }
  }

  /**
   * Get field data for responsive image output.
   *
   * Works only for single fields.
   *
   * @param array $field
   *   Field array that can be fetched from preprocess function.
   * @param string $normal_image_style
   *   Name of normal image style.
   * @param string $small_image_style
   *   Name of small image style.
   *
   * @return array
   *   Field data for responsive image output.
   */
  public function getResponsiveImageData(array $field, $normal_image_style, $small_image_style) {
    $data = [
      'normal' => '',
      'small' => '',
      'alt' => '',
    ];
    if (isset($field['#items'])) {
      /** @var \Drupal\Core\Field\FieldItemListInterface $field_item_list */
      $field_item_list = $field['#items'];
      try {
        $field_item = $field_item_list->get(0);
      }
      catch (MissingDataException $e) {
        throw new FieldException("Field {$field_item_list->getName()} is empty");
      }
      $data = [
        'normal' => $this->getStyledImageUrl($field_item, $normal_image_style),
        'small' => $this->getStyledImageUrl($field_item, $small_image_style),
        'alt' => Xss::filter($field_item->alt),
      ];
    }
    return $data;
  }

  /**
   * Returns structured reference field data.
   *
   * @param array $field_list
   *   The array of field list.
   * @param array $options
   *   The options array.
   *
   * @return array|string
   *   Returns the target id element of object.
   *
   * @throws \Exception
   */
  public function getReferenceFieldData(array $field_list, array $options = []) {
    $data = NULL;
    if (isset($field_list['#items']) && $field_list['#items'] instanceof EntityReferenceFieldItemListInterface) {
      // Load plugin that matches the field.
      $data = $field_list['#items']->getValue()[0]['target_id'];
    }

    return $data;
  }

  /**
   * Returns first key of field data.
   *
   * @param array $field_list
   *   The array of field list.
   * @param array $options
   *   The options array.
   *
   * @return array|string
   *   Returns the object element of array.
   *
   * @throws \Exception
   */
  public function getFieldKey(array $field_list, array $options = []) {
    $data = NULL;
    if (isset($field_list['#items']) && $field_list['#items'] instanceof FieldItemListInterface) {
      $data = $field_list['#items']->getValue()[0]['value'];
    }

    return $data;
  }

  /**
   * Returns the URL of a styled image.
   *
   * This is copy-pasted from 'handlebars_theme_handler' module.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $field
   *   The image field.
   * @param string $style
   *   The image style.
   *
   * @return string
   *   The URL of the styled image. Or the URL of the original image if the
   *   style is unknown. This will generate the requested styled image.
   */
  protected function getStyledImageUrl(FieldItemInterface $field, $style) {
    $originalUrl = $field->entity->uri->value;

    $url = $this->getStyleUrl($originalUrl, $style);

    return $url;
  }

  /**
   * Returns the URL of a styled image.
   *
   * This is copy-pasted from 'handlebars_theme_handler' module.
   *
   * @param string $originalUrl
   *   The image URL.
   * @param string $style
   *   The image style.
   *
   * @return string
   *   The URL of the styled image. Or the URL of the original image if the
   *   style is unknown. This will generate the requested styled image.
   */
  public function getStyleUrl($originalUrl, $style) {
    $style = ImageStyle::load($style);
    if ($style) {
      $url = $style->buildUrl($originalUrl);
    }
    else {
      $url = file_create_url($originalUrl);
    }

    return $url;
  }

}
