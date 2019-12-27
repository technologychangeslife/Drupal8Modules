<?php

namespace Drupal\bhge_core;

use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Url;

/**
 * Data related helper methods.
 */
class DataHelpers {

  /**
   * Auxiliary functions. Get image from node.
   *
   * @param object $node
   *   Object with needed information.
   * @param string $local_field
   *   Field main image string.
   * @param string $dam_field
   *   Field for dam main image.
   * @param string $style
   *   Field for gallery image.
   *
   * @return string
   *   Return image from node if exist.
   */
  public function getImage($node, $local_field = 'field_main_image', $dam_field = 'field_dam_main_img', $style = 'gallery_image') {

    $image = NULL;

    /** @var \Drupal\file\Entity\File $file */
    if (!empty($node->{$dam_field}->entity) && !empty($node->{$dam_field}->entity->field_asset->entity)) {
      $file = $node->{$dam_field}->entity->field_asset->entity;
    }
    elseif (!empty($node->{$local_field}->entity)) {
      $file = $node->{$local_field}->entity;
    }

    $originalUrl = !empty($file) ? $file->getFileUri() : '';

    if ($node->getType() == 'product' && !empty($node->get('field_product_information'))) {
      $productInformation = $node->get('field_product_information')->entity;
      if (!empty($productInformation->field_dam_image->entity) && !empty($productInformation->field_dam_image->entity->field_asset->entity)) {
        $file = $productInformation->field_dam_image->entity->field_asset->entity;
      }
      elseif (!empty($productInformation->field_image->entity)) {
        $file = $productInformation->field_image->entity;
      }
      $originalUrl = !empty($file) ? $file->getFileUri() : '';
    }

    if ($node->getType() == 'section') {
      if (!empty($node->get('field_features_and_benefits'))) {
        $featuresAndBenefits = $node->get('field_features_and_benefits')->entity;
        if (!empty($featuresAndBenefits->field_dam_image->entity) && !empty($featuresAndBenefits->field_dam_image->entity->field_asset->entity)) {
          $file = $featuresAndBenefits->field_dam_image->entity->field_asset->entity;
        }
        elseif (!empty($featuresAndBenefits->field_image->entity)) {
          $file = $featuresAndBenefits->field_image->entity;
        }
        $originalUrl = !empty($file) ? $file->getFileUri() : '';
      }
      if (!empty($node->get('field_block_standalone_content'))) {
        $standaloneContent = $node->get('field_block_standalone_content')->entity;
        if (!empty($standaloneContent->field_dam_image->entity) && !empty($standaloneContent->field_dam_image->entity->field_asset->entity)) {
          $file = $standaloneContent->field_dam_image->entity->field_asset->entity;
        }
        elseif (!empty($standaloneContent->field_image->entity)) {
          $file = $standaloneContent->field_image->entity;
        }
        $originalUrl = !empty($file) ? $file->getFileUri() : '';
      }
    }

    if (!empty($originalUrl)) {
      $image = !empty(ImageStyle::load($style)) ? ImageStyle::load($style)->buildUrl($originalUrl) : '';
    }

    return $image;
  }

  /**
   * Auxiliary functions. Get and prepare description from node.
   *
   * @param object $node
   *   Object with needed information.
   * @param bool $truncate
   *   Truncate or not truncate description.
   *
   * @return string
   *   Return description without html elements.
   */
  public function getDescription($node, $truncate = TRUE) {
    $description = '';
    if (isset($node->body->value) ||
      isset($node->field_copy->value) ||
      isset($node->field_description->value) ||
      isset($node->field_bio->value)) {
      if (isset($node->body->value)) {
        $description = $node->body->value;
      }
      elseif (isset($node->field_copy->value)) {
        $description = $node->field_copy->value;
      }
      elseif (isset($node->field_description->value)) {
        $description = $node->field_description->value;
      }
      else {
        $description = $node->field_bio->value;
      }
    }

    // Product description.
    if ($node->getType() == 'product' && !empty($node->get('field_product_information'))) {
      $productInformation = $node->get('field_product_information')->entity;
      $description = $productInformation->field_copy->value;
    }
    $strippedTags = str_replace("&nbsp;", "", strip_tags($description));
    $replace_ampersand = str_replace("&amp;", "&", $strippedTags);
    if ($truncate) {
      return Unicode::truncate($replace_ampersand, 240, TRUE, TRUE);
    }
    else {
      return $replace_ampersand;
    }
  }

  /**
   * Auxiliary functions. Get and prepare links from card.
   *
   * @param object $card
   *   Object with needed information.
   *
   * @return array
   *   Return links in array.
   */
  public function getLinks($card) {
    $links = [];

    if (!empty($card->field_link)) {
      foreach ($card->field_link as $key => $link) {
        if (!empty($link->getValue()['target_id'])) {
          $linkEntity = Paragraph::load($link->getValue()['target_id']);
          $label = $linkEntity->field_label->value;
          if (!empty($linkEntity->field_target_destination->entity)) {
            $urldest = $linkEntity->field_target_destination->entity->field_link_target_destination->value;
          }
          else {
            $urldest = '_self';
          }
          foreach ($linkEntity->field_target as $entity) {
            if (!empty($entity->uri) && !empty($label)) {
              $links[] = [
                'title' => $label,
                'url' => Url::fromUri($entity->uri),
                'class' => ($key == 0) ? 'full' : 'outline',
                'target' => $urldest,
              ];
            }
          }
        }
      }
    }

    if (!empty($card->field_dam_cta_dld->entity) && !empty($card->field_dam_cta_dld->entity->field_asset->entity)) {
      $links[] = [
        'title' => t('Download'),
        'url' => file_create_url($card->field_dam_cta_dld->entity->field_asset->entity->uri->value),
        'class' => 'outline',
        'target' => '_blank',
      ];
    }
    elseif (!empty($card->field_cta_download->entity)) {
      $links[] = [
        'title' => t('Download'),
        'url' => file_create_url($card->field_cta_download->entity->uri->value),
        'class' => 'outline',
        'target' => '_blank',
      ];
    }
    return $links;
  }

  /**
   * Auxiliary functions. Get image from node.
   *
   * @param object $node
   *   Object with needed information.
   * @param string $local_field
   *   Field for main image.
   * @param string $dam_field
   *   Field for dam main image.
   *
   * @return string
   *   Return image from node if exist.
   */
  public function getImageUri($node, $local_field = 'field_main_image', $dam_field = 'field_dam_main_img') {

    $image = NULL;

    /** @var \Drupal\file\Entity\File $file */
    if (!empty($node->{$dam_field}->entity) && !empty($node->{$dam_field}->entity->field_asset->entity)) {
      $file = $node->{$dam_field}->entity->field_asset->entity;
    }
    elseif (!empty($node->{$local_field}->entity)) {
      $file = $node->{$local_field}->entity;
    }

    $originalUrl = !empty($file) ? $file->getFileUri() : '';

    if ($node->getType() == 'product' && !empty($node->get('field_product_information'))) {
      $productInformation = $node->get('field_product_information')->entity;
      if (!empty($productInformation->field_dam_image->entity) && !empty($productInformation->field_dam_image->entity->field_asset->entity)) {
        $file = $productInformation->field_dam_image->entity->field_asset->entity;
      }
      elseif (!empty($productInformation->field_image->entity)) {
        $file = $productInformation->field_image->entity;
      }
      $originalUrl = !empty($file) ? $file->getFileUri() : '';
    }

    if ($node->getType() == 'section') {
      if (!empty($node->get('field_features_and_benefits'))) {
        $featuresAndBenefits = $node->get('field_features_and_benefits')->entity;
        if (!empty($featuresAndBenefits->field_dam_image->entity) && !empty($featuresAndBenefits->field_dam_image->entity->field_asset->entity)) {
          $file = $featuresAndBenefits->field_dam_image->entity->field_asset->entity;
        }
        elseif (!empty($featuresAndBenefits->field_image->entity)) {
          $file = $featuresAndBenefits->field_image->entity;
        }
        $originalUrl = !empty($file) ? $file->getFileUri() : '';
      }
      if (!empty($node->get('field_block_standalone_content'))) {
        $standaloneContent = $node->get('field_block_standalone_content')->entity;
        if (!empty($standaloneContent->field_dam_image->entity) && !empty($standaloneContent->field_dam_image->entity->field_asset->entity)) {
          $file = $standaloneContent->field_dam_image->entity->field_asset->entity;
        }
        elseif (!empty($standaloneContent->field_image->entity)) {
          $file = $standaloneContent->field_image->entity;
        }
        $originalUrl = !empty($file) ? $file->getFileUri() : '';
      }
    }

    if (!empty($originalUrl)) {
      $image = $originalUrl;
    }

    return $image;
  }

}
