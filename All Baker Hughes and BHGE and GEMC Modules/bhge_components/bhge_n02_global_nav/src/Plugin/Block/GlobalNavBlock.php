<?php

namespace Drupal\bhge_n02_global_nav\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Common nav block.
 *
 * @Block(
 *   id = "bhge_n02_global_nav_block",
 *   admin_label = @Translation("Global Navigation Block")
 * )
 */
class GlobalNavBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $data = $this->fetchGlobalNavData();

    \Drupal::logger('bhge_core')->notice('Got into build function at ' . date("Y/m/d H:i:s", time()) . json_encode($data));

    $build = [
      '#theme' => 'n02_global_nav_block',
      '#user' => $this->getUserData(),
      '#subsites' => $data['subsites_navigation'],
      '#microsites' => $data['microsites_navigation'],
      '#hsedata' => $data['hse'],
      '#cache' => [
        'max-age' => 86400,
      ],
      '#welcome_text' => t('Welcome to My BHGE'),
    ];

    return $build;
  }

  /**
   * Get HSE Days.
   */
  protected function fetchGlobalNavData() {
    $config = \Drupal::configFactory()->getEditable('bhge.hse_info_settings');
    $fetchUrl = $config->get('hse_fetch_url');

    if (empty($fetchUrl)) {
      return;
    }

    try {
      $client = \Drupal::httpClient();
      $request = $client->get($fetchUrl);

      if ($request->getStatusCode() != 200) {
        return;
      }

      $body = $request->getBody()->getContents();

      if (!empty($body)) {
        $result = json_decode($body);

        if (!empty($result->data)) {
          return (array) $result->data;
        }
      }

    }
    catch (\Exception $e) {
      \Drupal::logger('bhge_n02_global_nav')
        ->error('Failed to parse global nav data from public site. Error ' . $e->getCode() . ':' . $e->getMessage());
    }

  }

  /**
   * Get user picture and name.
   */
  protected function getUserData() {

    $picture = '/' . drupal_get_path('theme', 'bhge') . '/image/user/author.jpg';

    return [
      'name' => t('@name', ['@name' => '']),
      'picture' => $picture,
      'employee_profile' => [
        'label' => t('Employee Profile'),
        'url' => 'https://employeeprofile.ge.com/',
      ],
    ];
  }

}
