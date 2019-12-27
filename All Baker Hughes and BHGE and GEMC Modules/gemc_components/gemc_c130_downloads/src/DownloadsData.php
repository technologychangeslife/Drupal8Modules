<?php

namespace Drupal\gemc_c130_downloads;

use Drupal\Core\Database\Connection;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Downloads data layer.
 */
class DownloadsData {

  use StringTranslationTrait;

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The active database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get downloads filters for given paragraph.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $downloadsParagraph
   *   Paragraph entity.
   *
   * @return array
   *   Downloads filters.
   */
  public function getFilters(ParagraphInterface $downloadsParagraph) {
    $allResultsFilter = [
      'topic' => '',
      'pid' => $downloadsParagraph->id(),
      'label' => $this->t('All'),
      'class' => 'selected is-selected',
    ];
    $downloadsField = $downloadsParagraph->get('field_downloads')->getValue();
    $nids = array_map(function ($downloadsFieldItem) {
      return $downloadsFieldItem['target_id'];
    }, $downloadsField);

    $query = $this->connection->select('node__field_filter_facets', 'ft');
    $query->condition('ft.entity_id', $nids, 'IN');
    $query->leftJoin('taxonomy_term_field_data', 'td', 'ft.field_filter_facets_target_id = td.tid');
    $query->addField('td', 'tid', 'topic');
    $query->addField('td', 'name', 'label');
    $query->addExpression("{$downloadsParagraph->id()}", 'pid');
    $query->orderBy('td.weight');
    $query->distinct();
    $result = $query->execute()->fetchAll();
    if (!empty($result)) {
      array_unshift($result, $allResultsFilter);
    }
    return $result;
  }

  /**
   * Get base query to fetch filtered downloads from database.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $downloadsParagraph
   *   Paragraph entity to filter downloads by.
   * @param int $topicId
   *   Topic id to filter by.
   * @param int $limit
   *   Number of results to return.
   * @param int $offset
   *   Query offset.
   *
   * @return \Drupal\Core\Database\Query\SelectInterface
   *   Base query to fetch filtered downloads from database.
   */
  private function getDownloadsBaseQuery(ParagraphInterface $downloadsParagraph, $topicId, $limit = 0, $offset = 0) {
    $query = $this->connection->select('node_field_data', 'n');
    $query->condition('n.type', 'download');
    $query->condition('n.status', 1);
    $query->addField('n', 'nid', 'nid');

    $query->leftJoin('paragraph__field_downloads', 'reference', 'reference.field_downloads_target_id = n.nid');
    $query->condition('reference.entity_id', $downloadsParagraph->id());
    $query->condition('reference.langcode', $downloadsParagraph->language()->getId());

    if (!empty($topicId) && is_numeric($topicId)) {
      $query->leftJoin('node__field_filter_facets', 'topic', 'topic.entity_id = n.nid');
      $query->condition('topic.field_filter_facets_target_id', $topicId);
      $query->addField('topic', 'entity_id', 'eid');
    }

    if ($limit) {
      $query->range($offset, $limit);
    }

    $query->orderBy('reference.delta');
    return $query;

  }

  /**
   * Gen number of downloads for given topic and paragraph.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $downloadsParagraph
   *   Paragraph entity to filter by.
   * @param int $topicId
   *   Topic id to filter by.
   *
   * @return int
   *   Downloads count.
   */
  public function getFilteredDownloadsCount(ParagraphInterface $downloadsParagraph, $topicId) {
    $query = $this->getDownloadsBaseQuery($downloadsParagraph, $topicId);
    return (int) $query->countQuery()->execute()->fetchField();
  }

  /**
   * Get filtered downloads from database.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $downloadsParagraph
   *   Paragraph entity to filter downloads by.
   * @param int $topicId
   *   Topic id to filter by.
   * @param int $limit
   *   Number of results to return.
   * @param int $offset
   *   Query offset.
   *
   * @return \Drupal\node\NodeInterface[]|null
   *   Filtered downloads from database.
   */
  public function getFilteredDownloads(ParagraphInterface $downloadsParagraph, $topicId, $limit = 0, $offset = 0) {
    $query = $this->getDownloadsBaseQuery($downloadsParagraph, $topicId, $limit, $offset);
    $nids = $query->execute()->fetchCol();

    if (!empty($nids)) {
      return $this->processDownloads(Node::loadMultiple($nids));
    }
  }

  /**
   * Convert downloads to array applicable for JSON response.
   *
   * @param \Drupal\node\NodeInterface[]array $downloads
   *   Downloads nodes.
   *
   * @return array
   *   Array applicable for JSON response.
   */
  private function processDownloads(array $downloads) {
    $result = [];
    $media_ref_field = 'field_asset';
    foreach ($downloads as $download) {
      $file = NULL;
      if (!empty($download->get('field_download_dam_media')->referencedEntities())) {
        $media_references = $download->get('field_download_dam_media')->referencedEntities();
        if (!empty($media_references) && is_array($media_references) && !empty($media_references[0])) {
          $media = $media_references[0];
          unset($media_references);
        }
        if (!empty($media) && $media->hasField($media_ref_field)
          && !empty($media->{$media_ref_field})
          && !empty($media->{$media_ref_field}->entity)
        ) {
          $file = $media->{$media_ref_field}->entity;
          unset($media);
        }
      }
      if (!isset($file) && !empty($download->get('field_file')->entity)) {
        $file = $download->get('field_file')->entity;
      }
      if (!$file) {
        continue;
      }
      $filesize = format_size($file->filesize->value);
      $language = '';
      if (!$download->get('field_language')->isEmpty()) {
        $languageEntity = $download->get('field_language')->entity;
        $language = $languageEntity->label();
      }
      $filePathInfo = pathinfo($file->filename->value);
      $isDownloadble = FALSE;
      $target = '';
      if ($download->get('field_gated_content')->value) {
        $url = $download->toUrl()->toString();
        $target = '_blank';
      }
      else {
        $url = file_create_url($file->get('uri')->value);
        $isDownloadble = TRUE;
      }
      $result[] = [
        'isDownloadType' => 'true',
        'contentType' => 'download',
        'title' => $download->getTitle(),
        'fileExtension' => $filePathInfo['extension'],
        'fileLanguage' => $language,
        'fileSize' => $filesize,
        'url' => $url,
        'target' => $target,
        'isDownloadble' => $isDownloadble,
      ];
      unset($file);
    }
    return $result;
  }

}
