<?php

namespace Drupal\ge_marketo_form\Plugin\Marketo;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Url;
use Drupal\ge_marketo_form\Plugin\MarketoFormBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Defines a generic custom block type.
 *
 * @MarketoForm(
 *   id = "gated_marketo_form",
 *   deriver = "Drupal\ge_marketo_form\Plugin\Derivative\GatedMarketoForm"
 * )
 */
class GatedMarketoForm extends MarketoFormBase {

  /**
   * Get Form.
   *
   * @param array $variables
   *   The form variables array.
   *
   * @return array
   *   Returns The Marketo Form.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function getForm(array $variables = []) {
    $entity = $this->getContextValue('node');

    $marketoForm = parent::getForm($variables);
    $marketoForm['#theme'] = 'marketo_form__gated';
    $marketoForm['#data']['entity'] = $entity;
    $marketoForm['#data']['buttonText'] = $this->getButtonText();

    $marketoForm['#data'] += [
      'kapostId' => $this->getKapostId($entity),
      'contentType' => $this->getContentType($entity),
      'contentTypeMachineName' => $entity->bundle(),
      'trackingKey' => $this->getTrackingKey($entity),
      'dateCreated' => date('Y-m-d', $entity->get('created')->value),
      'gatedUrl' => $this->getGatedUrl($entity),
      'fullGatedUrl' => $this->getFullGatedUrl($entity),
      'legacyGatedId' => $this->getLegacyGatedId($entity),
      'gatedId' => $entity->id(),
      'isFile' => $this->isFile(),
    ];

    return $marketoForm;
  }

  /**
   * Get Button text Function.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|mixed
   *   Returns button text.
   */
  protected function getButtonText() {
    if (isset($this->getPluginDefinition()['buttonText'])) {
      return $this->getPluginDefinition()['buttonText'];
    }

    return parent::getButtonText();
  }

  /**
   * {@inheritdoc}
   */
  public function isFile() {
    if ($this->getPluginDefinition()['isFile']) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getKapostId(ContentEntityInterface $entity) {
    if ($entity->hasField('field_kapost_id') && !empty($entity->get('field_kapost_id')
      ->getValue())) {
      return $entity->get('field_kapost_id')->value;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getTrackingKey(ContentEntityInterface $entity) {
    return 'smart systems asset management and digital innovation in the chemical industry';
  }

  /**
   * Get Content Type.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The Entity Object.
   *
   * @return mixed
   *   Return Content Type.
   */
  public function getContentType(ContentEntityInterface $entity) {
    return $entity->type->entity->label();
  }

  /**
   * {@inheritdoc}
   */
  public function hasFile(ContentEntityInterface $entity) {
    if ($entity->hasField('field_file') && !empty($entity->get('field_file')
      ->getValue())) {
      return TRUE;
    }
    elseif ($entity->hasField('field_dam_file') && !empty($entity->get('field_dam_file')
      ->getValue())) {
      return TRUE;
    }
    elseif ($entity->hasField('field_download_dam_media') && !empty($entity->get('field_download_dam_media')
      ->getValue())) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Get The Gated URL.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $node
   *   The node object.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   *   Returns file create url.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function getGatedUrl(ContentEntityInterface $node) {
    if ($this->isFile() && $this->hasFile($node)) {
      if ($node->hasField('field_dam_file') && !empty($node->get('field_dam_file')->getValue())) {
        $dam_field_file = $node->get('field_dam_file')->getValue();
        $media = Media::load($dam_field_file[0]['target_id']);
        $media_field_asset = $media->get('field_asset')->getValue();
        $file = File::load($media_field_asset[0]['target_id']);
        if ($file) {
          $dam_file_uri = $file->getFileUri();
          return file_create_url($dam_file_uri);
        }

      }
      elseif ($node->hasField('field_download_dam_media') && !empty($node->get('field_download_dam_media')->getValue())) {
        $dam_field_file = $node->get('field_download_dam_media')->getValue();
        $media = Media::load($dam_field_file[0]['target_id']);
        $media_field_asset = $media->get('field_asset')->getValue();
        $file = File::load($media_field_asset[0]['target_id']);
        if ($file) {
          $dam_file_uri = $file->getFileUri();
          return file_create_url($dam_file_uri);
        }

      }
      else {
        return file_create_url($node->get('field_file')->entity->uri->value);
      }
    }

    return $node->toUrl()->setAbsolute()->toString();
  }

  /**
   * {@inheritdoc}
   */
  public function getLegacyGatedId(ContentEntityInterface $entity) {
    if ($entity->hasField('field_legacy_nid') && !empty($entity->get('field_legacy_nid')
      ->getValue())) {
      return $entity->get('field_legacy_nid')->value;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getFullGatedUrl(ContentEntityInterface $entity) {
    $gatedUrl = $this->getGatedUrl($entity);
    $option = [
      'query' => ['gated-id-d8' => $entity->id()],
    ];
    if ($this->getLegacyGatedId($entity)) {
      $option['query']['gated-id'] = $this->getLegacyGatedId($entity);
    }
    if (UrlHelper::isExternal($gatedUrl)) {
      return Url::fromUri($gatedUrl, $option)->toString();
    }

    return Url::fromUserInput($gatedUrl, $option)->setAbsolute()->toString();

  }

}
