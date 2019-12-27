<?php

namespace Drupal\bhge_digital_binder\Controller;

use Drupal\Core\Controller\ControllerBase;
use mikehaertl\pdftk\Pdf;
use Drupal\bhge_digital_binder\PDFMerger;

/**
 *
 */
class DigitalController extends ControllerBase {

  /**
   *
   */
  public function list_search_items() {
      
      $tempstore = \Drupal::service('user.private_tempstore')->get('bhge_digital_binder');
      $search_result_data = $tempstore->get('search_result_data');
       
      print '<pre>'; print_r($search_result_data); print '</pre>';
      
      foreach ($search_result_data as $key => $val) {
          $node = \Drupal\node\Entity\Node::load($key);
          $search_results = '<div>'.$node->title->value.'</div>'.$search_results;
      }
      $search_results = '<div><b>Your Selected Results</b></div>'.$search_results."<div><a href='/binder-form'>Click Here</a> to go back.</div>";
      return array (
      '#markup' => $search_results,
      '#prefix' => '<div>',
      '#suffix' => '</div>',
      );
      
  }
  
  /**
   *
   */
  public function page() {

    $current_path = \Drupal::service('path.current')->getPath(); print '<br>';
    // Print $directory = \Drupal::service('file_system')->realpath(""); print '<br>';.
    print "dc = " . $dc = \Drupal::service('file_system')->realpath(file_default_scheme() . "://");
    print "<br>";
    // $dc = $_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files';

    $uri = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf1.pdf';
    $uri2 = $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf2.pdf';
    if (file_exists($uri) && file_exists($uri2)) {
      print 'both file exists from 1st check'; print '<br>';
    }
    else {
      print 'No such file exists.'; print '<br>';
    }

    print $string = date("Y_m_d_h_i_s"); print "<br>";

    $pdf_merge_service = \Drupal::service('bhge_digital_binder.pdf_merge');
    \Drupal::logger('bhge_digital_binder')->notice('<pre><code>' . print_r($pdf_merge_service, TRUE) . '<code></pre>');
    if (file_exists($uri) && file_exists($uri2)) {
      \Drupal::logger('bhge_digital_binder')->notice("inside if loop");
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf3.pdf', '1,2'); not working.

      // Working below files
      /*$pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf1.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf2.pdf', 'all');*/
      // working with above files.

      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/CN-Power-Broch-GEA19389C-English.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/CN-Cond-Catalog-GEA18713D-RU-Russian.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/MN-77000-77003_TechSpec-GEA20210C-English.pdf', 'all');.

      // Sucessfully merged upto 24 pages including russian file.

      // Test with 1 large and 1 small files worked fine.
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf_merge_2019_07_19_01_12_48.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');.

      // Test with 2 large file worked fine created 44 page pdf
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf_merge_2019_07_19_01_38_26.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf_merge_2019_07_19_01_12_48.pdf', 'all');.

      // Test with pdf3 mb_detect_encoding(); failed with pdf3.pdf.
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf3.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');
      // print mb_detect_encoding($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf3.pdf'); print "<br>";
      // print mb_detect_encoding($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf'); print "<br>";.

      // Worked fine with russian and pdf4.pdf. output pdf_merge_2019_07_19_04_07_30.pdf
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/CN-Cond-Catalog-GEA18713D-RU-Russian.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');.

      // Worked fine with 8-pages-8mb and pdf4.pdf. output pdf_merge_2019_07_19_04_12_17.pdf
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');.

      // Worked fine with 8-pages-8mb and 16-mb-1-page.pdf.
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');.

      // Worked fine with 7mb-28-page and pdf4.pdf. output pdf_merge_2019_07_19_04_40_06.pdf
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');.

      // Worked fine with 8-pages-8mb , 16-mb-1-page.pdf , 7mb-28-page output pdf_merge_2019_07_22_08_43_03.pdf total 37 pages and 31mb.
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');.

      // Worked fine with 8-pages-8mb , 16-mb-1-page.pdf , 7mb-28-page , 16mb-188pages output pdf_merge_2019_07_22_09_13_15. 225 pages 47mb .
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16mb-188pages.pdf', 'all');.

      // Worked fine with 8-pages-8mb , 16-mb-1-page.pdf , 7mb-28-page , 16mb-188pages output pdf_merge_2019_07_22_09_13_15. 225 pages 47mb .
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16mb-188pages.pdf', 'all');.

      // Worked fine with 8-pages-8mb , 16-mb-1-page.pdf , 7mb-28-page , 16mb-188pages , 9mb-72pages output repeated thrice worked fine with
      // 747 pages and
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16mb-188pages.pdf', 'all');
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/9mb-72pages.pdf', 'all');.

      // Worked fine with 8-pages-8mb , 16-mb-1-page.pdf , 7mb-28-page , 16mb-188pages , 9mb-72pages , 8mb-8pages, 3m-12pages , 2mb-24pages ,
      // 3-16mb-20pages , 16mb-20pages fine with output
      // worked fine with 12 docs below created a doc of 447 pages 82 mb.
      // worked fine with 20 docs below created a doc of 925 pages 100 mb.
      // worked fine with 25 docs below created a doc of 1095 pages 113 mb
      // $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16-mb-1-page.pdf', 'all');.

      // Worked fine with 25 docs below created a doc of 1025 pages 100 mb output pdf_merge_2019_07_22_04_39_38.pdf.
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/pdf4.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/files/8-pages-8mb.pdf', 'all');
      /*$pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/7mb-28-page.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16mb-188pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/9mb-72pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/8mb-8pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/3mb-12pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2mb-24pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/3-16mb-20pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/16mb-20pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/6-27mb-48pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/3-26-16pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/1-23mb-20pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/5-3mb-92pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/4-25mb-46pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/3-39mb-71pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/3-67mb-203pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/1-48mb-20pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2-94mb-16pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2-3mb-32pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/1-56mb-12pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2-25mb-40pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/6-12mb-72pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2-54mb-24pages.pdf', 'all');
      $pdf_merge_service->addPDF($_SERVER['DOCUMENT_ROOT'].base_path().'modules/custom/bhge_digital_binder/files/2mb-22pages.pdf', 'all');*/

      $pdf_merge_service->merge('file', $dc . '/pdf_merge_' . $string . '.pdf');

      print $click_here = $dc . '/pdf_merge_' . $string . '.pdf'; print "<br>";
      \Drupal::logger('bhge_digital_binder')->notice("end of inside if loop" . $click_here);
    }

    $items = [
      ['name' => 'File 1'],
      ['name' => 'File 2'],
      ['name' => '<a href=' . $click_here . '>Click here </a> to view the merged pdf file.'],
    ];

    return [
      '#theme' => 'article_list',
      '#items' => $items,
      '#title' => 'Our article list'
    ];
  }

}
