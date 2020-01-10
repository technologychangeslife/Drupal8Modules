<?php

namespace Drupal\bhge_digital_binder\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This class contain methods used for multiple steps of binder.
 */
class DigitalController extends ControllerBase {

  /**
   * Output of the merged PDF.
   */
  public function binder() {
    $this->bhgeDigitalBinderPageCache();
    $request = \Drupal::request();
    $binder_session = $request->getSession();
    $merged_pdf = $binder_session->get('binder_form_pdf_url', '');
    return [
      '#merged_pdf' => $merged_pdf,
      '#theme' => 'digital_binder_merged_pdf',
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    ];
  }

  /**
   * Function to kill the cache for the page.
   */
  public function bhgeDigitalBinderPageCache() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    return [
      '#markup' => time(),
    ];
  }

}
