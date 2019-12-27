<?php

namespace Drupal\gemc_components\Plugin\ThemeEntityProcessor\ParagraphsBlock;

use Drupal\Core\Url;
use Drupal\handlebars_theme_handler\Plugin\ThemeEntityProcessorBase;

/**
 * Returns the structured data of an entity.
 *
 * @ThemeEntityProcessor(
 *   id = "c07_social_share",
 *   label = @Translation("C07 Social share"),
 *   entity_type = "paragraph",
 *   bundle = "c07_social_share",
 *   view_mode = "default"
 * )
 */
class ParagraphsBlockC07SocialShare extends ThemeEntityProcessorBase {

  /**
   * {@inheritdoc}
   */
  public function preprocessItemData(&$variables) {

    $current_url = Url::fromRoute('<current>', [], ['absolute' => TRUE])
      ->toString();
    $twitterShareButton = [
      'link' => "javascript:window.open('http://twitter.com/share?url=$current_url', '_blank', 'width=500,height=400')",
      'icon' => 'twitter',
    ];
    $facebookShareButton = [
      'link' => "javascript:window.open('https://www.facebook.com/sharer/sharer.php?u=$current_url', '_blank', 'width=630,height=500')",
      'icon' => 'facebook',
    ];
    $linkedinShareButton = [
      'link' => "javascript:window.open('https://www.linkedin.com/shareArticle?mini=true&url=$current_url', '_blank', 'width=590,height=600')",
      'icon' => 'linkedin',
    ];
    $social_share_title = $this->getSocialShareTitle();

    $variables['data'] = [
      'scrollComponent' => TRUE,
      'blockTopOffset' => 3,
      'heading' => !empty($social_share_title) ? $social_share_title : $this->t('Share'),
      'shareButtons' => [
        $twitterShareButton,
        $facebookShareButton,
        $linkedinShareButton,
      ],
    ];
  }

  /**
   * Get title of social share.
   */
  private function getSocialShareTitle() {
    $socialConfig = \Drupal::configFactory()->get('bhge.social_settings');
    return $socialConfig->get('social_share_title');
  }

}
