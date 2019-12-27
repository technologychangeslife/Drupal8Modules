<?php

namespace Drupal\gemc_components\FieldData\Helper;

/**
 * Allows to fetch video information from URL.
 */
class VideoDataHelper {

  /**
   * Get video information by URL.
   *
   * @param string $url
   *   Video Url.
   *
   * @return array
   *   Video information array with next keys:
   *    - type: video type (youtube, brightcove, ...)
   *    - id: video id extracted from url
   *   Also provider-specific information can be returned.
   */
  public function getVideoInfoByUrl($url) {
    $videoInfo = [];
    $uri_parts = parse_url(trim($url));
    if (!$uri_parts || !empty($uri_parts['host'])) {
      $host = $uri_parts['host'];
      if (!empty($uri_parts['query'])) {
        parse_str($uri_parts['query'], $query_array);
      }
      $pat_info = pathinfo($uri_parts['path']);
      $video_id = NULL;
      if (strpos($host, 'youtu.be') !== FALSE || strpos($host, 'youtube') !== FALSE) {
        // Possible YouTube URL formats:
        // http://www.youtube.com/watch?v=My2FRPA3Gf8
        // http://youtu.be/My2FRPA3Gf8
        // https://youtube.googleapis.com/v/My2FRPA3Gf8
        if ($pat_info['basename'] === 'watch' && !empty($query_array['v'])) {
          $video_id = $query_array['v'];
        }
        else {
          $video_id = $pat_info['filename'];
        }

        $videoInfo['type'] = 'youtube';
        $videoInfo['id'] = $video_id;
      }
      elseif (strpos($host, 'facebook') !== FALSE) {
        $videoInfo['type'] = 'facebook';
        $videoInfo['id'] = $url;
      }
      elseif (strpos($host, 'brightcove') !== FALSE) {
        if (!empty($query_array['videoId'])) {
          $video_id = $query_array['videoId'];
        }
        $videoInfo['type'] = 'brightcove';
        $videoInfo['id'] = $video_id;
        $videoInfo['brightcoveAccount'] = $this->getBrightcoveAccount($url);
        $videoInfo['brightcovePlayer'] = $this->getBrightcovePlayer($url);

      }
      elseif (strpos($host, 'office365')) {
        $videoInfo['type'] = 'office365';
        if (!empty($query_array['chId'])) {
          $videoInfo['cid'] = $query_array['chId'];
        }
        if (!empty($query_array['vId'])) {
          $videoInfo['id'] = $query_array['vId'];
        }
      }
    }

    return $videoInfo;
  }

  /**
   * Get Brightcove player from url.
   *
   * @param string $url
   *   Video URL.
   *
   * @return string
   *   Brightcove player id.
   */
  public function getBrightcovePlayer($url) {
    preg_match("/\/(\d+)\/(.*)\//", $url, $result);
    return $result[2];

  }

  /**
   * Get Brightcove account from url.
   *
   * @param string $url
   *   Video URL.
   *
   * @return string
   *   Brightcove account id.
   */
  public function getBrightcoveAccount($url) {
    preg_match("/\/(\d+)\/(.*)\//", $url, $result);
    return $result[1];
  }

}
