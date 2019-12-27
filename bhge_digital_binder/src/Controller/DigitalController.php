<?php

namespace Drupal\bhge_digital_binder\Controller;

use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * This class contain methods used for multiple steps of binder.
 */
class DigitalController extends ControllerBase {

  /**
   * This will list items selected form previous search.
   *
   * Which are selected from Binder form after search.
   */
  public function listSearchItems() {

    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data');
    $search_results_list = [];

    foreach ($search_result_data as $key => $val) {
      $node = Node::load($key);
      $search_results = '<div>' . $node->title->value . '</div>' . $search_results;
      $search_results_list[] = $node->title->value;
    }

    $search_results = '<div><b>Your Selected Results</b></div>' . $search_results . "<div><a href='/binder'>Click Here</a> to proceed to the binder.</div>";
    $search_results = $search_results . "<div><a href='/binder-form'>Click Here</a> to go back.</div>";
    return [
      '#search_results_list' => $search_results_list,
      '#theme' => 'digital_binder_list',
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    ];

  }

  /**
   * This function will merge multiple PDF into 1.
   */
  public function binder() {
    $tempstore = \Drupal::service('tempstore.shared')->get('bhge_digital_binder');
    $search_result_data = $tempstore->get('search_result_data_new');
    $pdf_merge_service = \Drupal::service('bhge_digital_binder.pdf_merge');
    
    $pdf_write_service = \Drupal::service('bhge_digital_binder.pdf_write');
    
    // test some inline CSS
     $html = '<p>This is just an example of html code to demonstrate some supported CSS inline styles.
     <span style="font-weight: bold;">bold text</span>
     <span style="text-decoration: line-through;">line-trough</span>
     <span style="text-decoration: underline line-through;">underline and line-trough</span>
     <span style="color: rgb(0, 128, 64);">color</span>
     <span style="background-color: rgb(255, 0, 0); color: rgb(255, 255, 255);">background color</span>
     <span style="font-weight: bold;">bold</span>
     <span style="font-size: xx-small;">xx-small</span>
     <span style="font-size: x-small;">x-small</span>
     <span style="font-size: small;">small</span>
     <span style="font-size: medium;">medium</span>
     <span style="font-size: large;">large</span>
     <span style="font-size: x-large;">x-large</span>
     <span style="font-size: xx-large;">xx-large</span>
     </p>';
    
    // output the HTML content
    $pdf_write_service->writeHTML($html, true, false, true, false, '');
    //Close and output PDF document
    $pdf_write_service->Output('example_006.pdf', 'I');
    
    
    $dc = \Drupal::service('file_system')->realpath(file_default_scheme() . "://");
    foreach ($search_result_data as $key => $val) {
      $node = Node::load($key);

      $dam_field_file = $node->get('field_dam_file')->getValue();

      if (!empty($dam_field_file[0]['target_id'])) {

        $dam_url = $this->getfileurl($dam_field_file[0]['target_id']);
        $dam_url = str_replace("%20", " ", $dam_url);
        $dam_url = str_replace("%28", "(", $dam_url);
        $dam_url = str_replace("%29", ")", $dam_url);
        $files_path = explode("sites", $dam_url);
        $dam_file_path = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'sites' . $files_path[1];
        if (file_exists($dam_file_path)) {
          \Drupal::logger('bhge_digital_binder')->notice("inside if loop");
          $pdf_merge_service->addPDF($dam_file_path, 'all');
        }

        $search_results = '<div>' . $node->title->value . '</div><div>Url:' . $dam_url . '</div>' . $search_results;
      }

      if (empty($dam_field_file[0]['target_id'])) {
        $search_results = '<div>' . $node->title->value . '</div>' . $search_results;
      }

    }
    $string = date("Y_m_d_h_i_s");
    $pdf_merge_service->merge('file', $dc . '/pdf_merge_' . $string . '.pdf');
    $search_results = '<div><b>Your Selected Results</b></div>' . $search_results . "<div>";
    $click_here = $dc . '/pdf_merge_' . $string . '.pdf';
    $download_path = file_create_url('public://pdf_merge_' . $string . '.pdf');
    $search_results = $search_results . "<div>Merged PDF:" . $click_here . "</div><div><a href='/binder-form'>Click Here</a> to go back.</div>";
    $merged_pdf = $download_path;
    $this->bhgeDigitalBinderPageCache();
    return [
      '#merged_pdf' => $merged_pdf,
      '#theme' => 'digital_binder_merged_pdf',
      '#prefix' => '<div>',
      '#suffix' => '</div>',
    ];
  }

  /**
   * This function will give the DAM file Url.
   */
  public function getfileurl($dam_field_file_target_id) {
    $media = Media::load($dam_field_file_target_id);
    $media_field_asset = $media->get('field_asset')->getValue();
    $file = File::load($media_field_asset[0]['target_id']);
    $dam_file_uri = $file->getFileUri();
    $dam_url = file_create_url($dam_file_uri);
    return $dam_url;
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

  /**
   * Testing function for Binder/PDF Merge.
   */
  public function page() {

    $current_path = \Drupal::service('path.current')->getPath();
    $dc = \Drupal::service('file_system')->realpath(file_default_scheme() . "://");
    $uri = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf1.pdf';
    $uri2 = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf2.pdf';
    if (file_exists($uri) && file_exists($uri2)) {

    }
    else {

    }

    $string = date("Y_m_d_h_i_s");

    $pdf_merge_service = \Drupal::service('bhge_digital_binder.pdf_merge');
    \Drupal::logger('bhge_digital_binder')->notice('<pre><code>' . print_r($pdf_merge_service, TRUE) . '<code></pre>');
    if (file_exists($uri) && file_exists($uri2)) {
      \Drupal::logger('bhge_digital_binder')->notice("inside if loop");
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');

      $pdf_merge_service->merge('file', $dc . '/pdf_merge_' . $string . '.pdf');

      $click_here = $dc . '/pdf_merge_' . $string . '.pdf';
      \Drupal::logger('bhge_digital_binder')->notice("end of inside if loop" . $click_here);
    }

    $this->bhgeDigitalBinderPageCache();

    $items = [
      ['name' => 'File 1'],
      ['name' => 'File 2'],
      ['name' => '<a href=' . $click_here . '>Click here </a> to view the merged pdf file.'],
    ];

    return [
      '#theme' => 'article_list',
      '#items' => $items,
      '#title' => 'Our article list',
    ];
  }

}
