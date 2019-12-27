<?php

namespace Drupal\gemc_c130_downloads\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\gemc_c130_downloads\DownloadsData;
use Drupal\paragraphs\Entity\Paragraph;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Downloads controller.
 */
class DownloadsController extends ControllerBase {

  /**
   * Current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * Downloads data service.
   *
   * @var \Drupal\gemc_c130_downloads\DownloadsData
   */
  private $downloadsData;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('gemc_c130_downloads.downloads_data'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(Request $request, DownloadsData $downloadsData, LanguageManagerInterface $languageManager) {
    $this->request = $request;
    $this->downloadsData = $downloadsData;
    $this->languageManager = $languageManager;
  }

  /**
   * Get topic from topic string.
   *
   * @return int
   *   Return topic id.
   */
  private function getTopic() {
    return Xss::filter($this->request->get('topic'));
  }

  /**
   * Get parent id from url.
   *
   * @return mixed
   *   Return id of parent element.
   */
  private function getParentId() {
    return (int) Xss::filter($this->request->get('pid'));
  }

  /**
   * Get offset from url.
   *
   * @return mixed
   *   Return offset.
   */
  private function getOffset() {
    return (int) Xss::filter($this->request->get('offset'));
  }

  /**
   * Get limit from url.
   *
   * @return mixed
   *   Return limit.
   */
  private function getLimit() {
    return (int) Xss::filter($this->request->get('limit'));
  }

  /**
   * Get downlaods. Load from query, check and set data in array.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Downloads JSON response.
   */
  public function getDownloads() {
    $offset = $this->getOffset();
    $limit = $this->getLimit();
    $topic = $this->getTopic();
    $paragraph = Paragraph::load($this->getParentId());
    // Get Current language code.
    $langCode = $this->languageManager->getCurrentLanguage()->getId();
    // Get content from current language if paragraph has translation.
    if ($paragraph->hasTranslation($langCode)) {
      $paragraph = $paragraph->getTranslation($langCode);
    }
    $downloads = $this->downloadsData->getFilteredDownloads($paragraph, $topic, $limit, $offset);
    $downloadsTotalCount = $this->downloadsData->getFilteredDownloadsCount($paragraph, $topic);

    $result = [
      'data' => $downloads,
      'pagination' => [
        'total' => $downloadsTotalCount,
        'offset' => $offset,
        'limit' => $this->getLimit(),
      ],
      'statusCode' => 200,
    ];

    return new JsonResponse($result);
  }

}
