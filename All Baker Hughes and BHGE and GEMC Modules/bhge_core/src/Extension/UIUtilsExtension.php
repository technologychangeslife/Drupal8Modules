<?php

namespace Drupal\bhge_core\Extension;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Create custom Twig UI extentions.
 */
class UIUtilsExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ui_util_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('processLinkField', [
        $this,
        'processLinkField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('formatSize', [
        $this,
        'formatSize',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getUrlFromEntity', [
        $this,
        'getUrlFromEntity',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('labelFromLinkField', [
        $this,
        'labelFromLinkField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('processDateField', [
        $this,
        'processDateField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('determineHrefLinkTarget', [
        $this,
        'determineHrefLinkTarget',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getFileNameWithoutExtension', [
        $this,
        'getFileNameWithoutExtension',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('makeSectorTabs', [
        $this,
        'makeSectorTabs',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getSocialPageLinks', [
        $this,
        'getSocialPageLinks',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getArrayOfStatesForCountries', [
        $this,
        'getArrayOfStatesForCountries',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomy', [
        $this,
        'getTaxonomy',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isSiteInternal', [
        $this,
        'isSiteInternal',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isSearchEnabled', [
        $this,
        'isSearchEnabled',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getSocialShareTitle', [
        $this,
        'getSocialShareTitle',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getPageMetatags', [
        $this,
        'getPageMetatags',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getBrightcovePlayer', [
        $this,
        'getBrightcovePlayer',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getBrightcoveAccount', [
        $this,
        'getBrightcoveAccount',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('hideBhgemetrics', [
        $this,
        'hideBhgemetrics',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('hideBreadcrumbs', [
        $this,
        'hideBreadcrumbs',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('showFilled', [
        $this,
        'showFilled',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getOrganizationSchemaMarkup', [
        $this,
        'getOrganizationSchemaMarkup',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getOffice365Data', [
        $this,
        'getOffice365Data',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getNodeUrl', [
        $this,
        'getNodeUrl',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getFileInformation', [
        $this,
        'getFileInformation',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('stripHtml', [
        $this,
        'stripHtml',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('imageFileExists', [
        $this,
        'imageFileExists',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('processFileField', [
        $this,
        'processFileField',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isOnSearchPage', [
        $this,
        'isOnSearchPage',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isLoggedInUser', [
        $this,
        'isLoggedInUser',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('decodeStringCharacters', [
        $this,
        'decodeStringCharacters',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('useCurrentThemeLogo', [
        $this,
        'useCurrentThemeLogo',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('getTaxonomyTermName', [
        $this,
        'getTaxonomyTermName',
      ], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('isMiniBanner', [
        $this,
        'isMiniBanner',
      ], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Hide BHGEMetrics.
   *
   * @return bool
   *   Returns wether to hide or show stock metrics.
   */
  public function hideBhgemetrics() {
    $config = \Drupal::configFactory()->get('bhge.general_settings');
    if (!empty($config->get('enable_global_nav'))) {
      return 'hide-stock-metrics';
    }
  }

  /**
   * Returns true if user is logged-in, otherwise false.
   *
   * @return bool
   *   Returns wether the current user is authenticated.
   */
  public function isLoggedInUser() {
    $currentUser = \Drupal::currentUser();
    return $currentUser->isAuthenticated();
  }

  /**
   * Tabs per sector.
   */
  public function makeSectorTabs($externalLinks) {
    $sectorTabs = [];
    foreach ($externalLinks as $link) {
      $externalLink = $link->entity;

      $sector = $externalLink->field_sector->entity;
      $sectorImage = !empty($sector->field_svg_image) ? $sector->field_svg_image->entity : '';
      $sectorName = $sector ? $sector->getName() : '';

      $linkImage = !empty($externalLink->field_image) ? $externalLink->field_image->entity : '';
      $copyField = html_entity_decode($externalLink->field_copy->value);
      $copyField = strip_tags($copyField);
      $copyField = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $copyField);

      if (!isset($sectorTabs[$sectorName])) {
        $sectorTabs[$sectorName] = [
          'title' => $sectorName,
          'subtitle' => !empty($sector) ? $sector->field_subtitle->value : '',
          'sectorImage' => $sectorImage,
          'hasOneLine' => TRUE,
          'hasIcon' => !empty($sector) ? $sector->field_icon_image->value : '',
        ];
      }

      if ($externalLink->field_target->title && $externalLink->field_ge_target->title) {
        $sectorTabs[$sectorName]['hasOneLine'] = FALSE;
      }

      $sectorTabs[$sectorName]['links'][] = [
        'title' => $externalLink->getTitle(),
        'copy' => $copyField,
        'sourceName' => $externalLink->field_source_name->value,
        'image' => $linkImage,
        'targetBH' => ['title' => $externalLink->field_target->title, 'uri' => $externalLink->field_target],
        'targetGE' => ['title' => $externalLink->field_ge_target->title, 'uri' => $externalLink->field_ge_target],
      ];
    }
    return $sectorTabs;
  }

  /**
   * Return URL or empty string of link field.
   */
  public function processLinkField($linkField) {
    return $linkField && $linkField->uri ? Url::fromUri($linkField->uri) : '';
  }

  /**
   * Get label of link field.
   */
  public function labelFromLinkField($linkField) {
    return $linkField && $linkField->title ? $linkField->title : '';
  }

  /**
   * Get label of link field.
   */
  public function getUrlFromEntity($entity) {
    return Url::fromRoute('entity.node.canonical', ['node' => $entity->get('nid')->value], ['absolute' => TRUE])->toString();
  }

  /**
   * Strip extention from filename.
   */
  public function getFileNameWithoutExtension($filename) {
    $file_info = pathinfo($filename);
    return $file_info['filename'];
  }

  /**
   * Human readable file size.
   */
  public function formatSize($size) {
    return format_size($size);
  }

  /**
   * Human readable date.
   */
  public function processDateField($dateField) {
    if ($dateField) {
      return date('M d, Y', strtotime($dateField));
    }
    // return;.
  }

  /**
   * Determine if href target must be _blank.
   */
  public function determineHrefLinkTarget($linkField) {
    if (is_object($linkField)) {
      $url = !empty($linkField->uri) ? Url::fromUri($linkField->uri) : '';
      if (!is_string($url)) {
        if ($url->isExternal()) {
          return '_blank';
        }
      }
    }
    // return;.
  }

  /**
   * Social platform links.
   */
  public function getSocialPageLinks() {
    $socialConfig = \Drupal::configFactory()->get('bhge.social_settings');

    $socialLinks = [];
    $socialFields = [
      'facebook',
      'twitter',
      'linkedin',
      'instagram',
      'youtube',
    ];
    foreach ($socialFields as $field) {
      $link = $socialConfig->get($field . '_page_url');
      if (!empty($link)) {
        $socialLinks[$field] = $link;
      }
    }

    return $socialLinks;
  }

  /**
   * Determine if current site is internal.
   */
  public function isSiteInternal() {
    $config = \Drupal::configFactory()->get('bhge.general_settings');
    return $config->get('internal_site');
  }

  /**
   * Determine if current site has search field enabled.
   */
  public function isSearchEnabled() {
    $config = \Drupal::configFactory()->get('bhge.general_settings');
    return $config->get('enable_search');
  }

  /**
   * Get title of social share.
   */
  public function getSocialShareTitle() {
    $socialConfig = \Drupal::configFactory()->get('bhge.social_settings');
    return $socialConfig->get('social_share_title');
  }

  /**
   * Nested array of states per country.
   */
  public function getArrayOfStatesForCountries() {
    $states = $this->getTaxonomy('state_province', TRUE);
    $result = [];

    foreach ($states as $state) {
      $country = $state->field_country->entity;

      if ($country) {
        $countryName = $country->getName();

        if (!isset($result[$countryName])) {
          $result[$countryName] = [
            'countryName' => $countryName,
            'states' => [],
          ];
        }

        $result[$countryName]['states'][] = [
          'id' => $state->id(),
          'name' => $state->getName(),
        ];
      }
    }

    return $result;
  }

  /**
   * Get contacts terms.
   */
  public function getTaxonomy($vid, $loadEntity = FALSE) {
    $entityTypeManager = \Drupal::service('entity_type.manager');
    return $entityTypeManager->getStorage('taxonomy_term')->loadTree($vid, 0, NULL, $loadEntity);
  }

  /**
   * Get scheme of organisation.
   */
  public function getOrganizationSchemaMarkup() {
    $organizationSchemaConfig = \Drupal::configFactory()->get('bhge.organization_schema_settings');
    $organizationLogoFileId = $organizationSchemaConfig->get('organization_logo');
    $organizationLogoUrl = '';
    if ($organizationLogoFileId !== NULL && count($organizationLogoFileId) > 0) {
      if (!empty($organizationLogoFileId[0])) {
        $organizationLogoFile = File::load($organizationLogoFileId[0]);
        if ($organizationLogoFile) {
          $organizationLogoUrl = $organizationLogoFile->url();
        }
      }
    }

    return [
      'organization_name' => $organizationSchemaConfig->get('organization_name'),
      'organization_description' => $organizationSchemaConfig->get('organization_description'),
      'organization_url' => $organizationSchemaConfig->get('organization_url'),
      'organization_logo' => $organizationLogoUrl,
      'organization_facebook_logo' => $organizationSchemaConfig->get('organization_facebook_logo'),
      'organization_twitter_logo' => $organizationSchemaConfig->get('organization_twitter_logo'),
      'organization_linkedin_logo' => $organizationSchemaConfig->get('organization_linkedin_logo'),
      'organization_instagram_logo' => $organizationSchemaConfig->get('organization_instagram_logo'),
    ];
  }

  /**
   * Get meta description.
   */
  public function getPageMetatags() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if (is_object($node) && !is_null($node->field_metatags)) {
      $metatags = unserialize($node->field_metatags->value);
      return isset($metatags['description']) ? $metatags['description'] : '';
    }
    return '';
  }

  /**
   * Get Brightcove player from url.
   */
  public function getBrightcovePlayer($url) {
    preg_match("/\/(\d+)\/(.*)\//", $url, $result);
    return $result[2];
  }

  /**
   * Get Brightcove account from url.
   */
  public function getBrightcoveAccount($url) {
    preg_match("/\/(\d+)\/(.*)\//", $url, $result);
    return $result[1];
  }

  /**
   * Get Office365 video chid and vid from url.
   */
  public function getOffice365Data($url) {
    $parts = parse_url(urldecode(htmlspecialchars_decode($url)));
    if (array_key_exists('query', $parts)) {
      parse_str($parts['query'], $queryParameters);
      return [
        'chId' => $queryParameters['chId'],
        'vId' => $queryParameters['vId'],
      ];
    }
    else {
      return '';
    }
  }

  /**
   * Hide breadcrumbs if on search page.
   */
  public function hideBreadcrumbs() {
    $routeName = \Drupal::routeMatch()->getRouteName();
    $pages = ['entity.user.canonical', 'bhge_search_api.search', 'system.403'];
    if (in_array($routeName, $pages)) {
      return 'no-breadcrumbs';
    }
    if (\Drupal::request()->getPathInfo() == '/' || \Drupal::request()->getPathInfo() == '/intranet') {
      return 'hide-breadcrumbs';
    }
  }

  /**
   * Show navigation filed if on search page.
   */
  public function showFilled() {
    $routeName = \Drupal::routeMatch()->getRouteName();
    if ($routeName == 'bhge_search_api.search' || $routeName == 'contact.site_page' || $routeName == 'system.403') {
      // Some static pages.
      return 'filled';
    }
    if (\Drupal::request()->attributes->has('node')) {
      $node = \Drupal::routeMatch()->getParameter('node');
      if ($node instanceof NodeInterface) {
        if ($node->getType() == 'product') {
          // You can get nid and anything else you need from the node object.
          if (isset($node->get('field_is_launch')
            ->first()
            ->getValue()['value']) && $node->get('field_is_launch')
            ->first()
            ->getValue()['value']) {
            // Product nodes in "latest launch" mode.
            return 'filled';
          }
        }
        if ($node->getType() == 'person' || $node->getType() == 'question') {
          return 'filled';
        }
        if ($node->getType() == 'blog_page' && !empty($_GET['display']) && $_GET['display'] == 'question-form') {
          return 'filled';
        }
      }
    }
  }

  /**
   * Get url of node.
   */
  public function getNodeUrl($node) {
    global $base_url;
    if (!is_null($node)) {
      $nodeUrl = $node->url();
      return $base_url . $nodeUrl;
    }
    return '#';
  }

  /**
   * Get file information from file media bundle.
   */
  public function getFileInformation($fileMediaBundle) {
    $file = $fileMediaBundle->field_file;
    if (!is_null($file)) {
      $file = $file->entity;
      return $this->processFileField($file);
    }
    return NULL;
  }

  /**
   * Sanetize HTML.
   */
  public function stripHtml($text) {
    if (is_string($text)) {
      return html_entity_decode(strip_tags(preg_replace("/&nbsp;/", ' ', $text)));
    }
  }

  /**
   * Check if image file exists.
   */
  public function imageFileExists($image) {
    return file_exists($image);
  }

  /**
   * Process file field.
   */
  public function processFileField($file) {
    if (!empty($file) && $file instanceof File) {
      $filesize = format_size($file->filesize->value);
      $file_path_info = pathinfo($file->filename->value);
      $url = $file->hasField('uri') ? file_create_url($file->get('uri')->value) : '';
      $language = $file->language();
      return [
        'name' => !empty($file_path_info) ? $file_path_info['filename'] : '',
        'size' => $filesize,
        'type' => !empty($file_path_info) ? $file_path_info['extension'] : '',
        'language' => !empty($language) ? $language->getName() : '',
        'url' => $url,
      ];
    }
    return NULL;
  }

  /**
   * Determine if current page is search.
   */
  public function isOnSearchPage() {

    if (\Drupal::routeMatch()->getRouteName() == 'bhge_search_api.search') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Decode string special characters.
   *
   * Return string.
   */
  public function decodeStringCharacters($string) {
    return htmlspecialchars_decode($string);
  }

  /**
   * Determines to use current theme logo.
   *
   * Return logo link|null.
   */
  public function useCurrentThemeLogo() {
    $config = \Drupal::configFactory()->get('bhge.general_settings');
    if (!empty($config->get('use_theme_logo'))) {
      // $logo = theme_get_setting('logo', 'bhge');.
      $logofield = $config->get('website_logo');
      $faviconfield = $config->get('website_favicon');
      if ($logofield !== NULL && count($logofield) > 0) {
        if (!empty($logofield[0])) {
          $logofieldfile = File::load($logofield[0]);
          if ($logofieldfile) {
            $logofieldfileurl = $logofieldfile->url();
            $logosvgdata = file_get_contents($logofieldfile->getFileUri());
          }
        }
      }
      if ($faviconfield !== NULL && count($faviconfield) > 0) {
        if (!empty($faviconfield[0])) {
          $faviconfieldfile = File::load($faviconfield[0]);
          if ($faviconfieldfile) {
            // $faviconfieldfileurl = $faviconfieldfile->url();
            $faviconsrc = file_create_url($faviconfieldfile->getFileUri());
          }
        }
      }
      if (!empty($logofieldfileurl)) {
        return [
          'noline' => 'noline',
          'nolinebefore' => 'nolinebefore',
          'logourl' => $logofieldfileurl,
          'logosvgdata' => $logosvgdata,
          'faviconsrc' => !empty($faviconsrc) ? $faviconsrc : '',
        ];
      }
    }
  }

  /**
   * Return taxonomy term name from tid.
   */
  public function getTaxonomyTermName($tid) {
    $name = '';
    if (!empty($tid)) {
      $term = Term::load($tid);
      if (!empty($term)) {
        $name = $term->getName();
      }
    }
    return $name;
  }

  /***
   * Return if mini banner is available.
   */
  public function isMiniBanner() {
    if (\Drupal::request()->attributes->has('node')) {
      $node = \Drupal::routeMatch()->getParameter('node');
      if ($node->hasfield('field_mini_banner') && $node->get('field_mini_banner')->value) {
        $isMiniBanner = 'is-mini-banner';
      }
    }
    return $isMiniBanner;
  }

}
