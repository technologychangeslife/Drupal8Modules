<?php

namespace Drupal\bhge_digital_binder;

use Drupal\bhge_digital_binder\TCPDI;
use Drupal\bhge_digital_binder\TCPDF;

/**
 *
 */
class PDFMerger {
  /**
   * ['form.pdf']  ["1,2,4, 5-19"].
   */
  private $_files;
  private $_fpdi;

  /**
   * Merge PDFs.
   *
   * @return void
   */
  public function __construct() {
    require_once $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/src/tcpdf/tcpdf.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . base_path() . 'modules/custom/bhge_digital_binder/src/tcpdf/tcpdi.php';
  }

  /**
   * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
   *
   * @param $filepath
   * @param $pages
   *
   * @return void
   */
  public function addPDF($filepath, $pages = 'all') {
    if (file_exists($filepath)) {
      if (strtolower($pages) != 'all') {
        $pages = $this->_rewritepages($pages);
      }

      $this->_files[] = [$filepath, $pages];
    }
    else {
      throw new exception("Could not locate PDF on '$filepath'");
    }

    return $this;
  }

  /**
   * Merges your provided PDFs and outputs to specified location.
   *
   * @param $outputmode
   * @param $outputname
   *
   * @return PDF
   */
  public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf') {
    if (!isset($this->_files) || !is_array($this->_files)) :
    endif;

    $fpdi = new TCPDI();
    $fpdi->SetPrintHeader(FALSE);
    $fpdi->SetPrintFooter(FALSE);

    // Merger operations.
    foreach ($this->_files as $file) {
      $filename = $file[0];
      $filepages = $file[1];

      $count = $fpdi->setSourceFile($filename);

      // Add the pages.
      if ($filepages == 'all') {
        for ($i = 1; $i <= $count; $i++) {
          $template = $fpdi->importPage($i);
          $size = $fpdi->getTemplateSize($template);
          $orientation = ($size['h'] > $size['w']) ? 'P' : 'L';

          $fpdi->AddPage($orientation, [$size['w'], $size['h']]);
          $fpdi->useTemplate($template);
        }
      }
      else {
        foreach ($filepages as $page) {
          if (!$template = $fpdi->importPage($page)) :
            throw new exception("Could not load page '$page' in PDF '$filename'. Check that the page exists.");
          endif;
          $size = $fpdi->getTemplateSize($template);
          $orientation = ($size['h'] > $size['w']) ? 'P' : 'L';

          $fpdi->AddPage($orientation, [$size['w'], $size['h']]);
          $fpdi->useTemplate($template);
        }
      }
    }

    // Output operations.
    $mode = $this->_switchmode($outputmode);

    if ($mode == 'S') {
      return $fpdi->Output($outputpath, 'S');
    }
    elseif ($mode == 'F') {
      $fpdi->Output($outputpath, $mode);
      return TRUE;
    }
    else {
      if ($fpdi->Output($outputpath, $mode) == '') {
        return TRUE;
      }
      else {
        throw new exception("Error outputting PDF to '$outputmode'.");
        return FALSE;
      }
    }

  }

  /**
   * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
   *
   * @param $mode
   *
   * @return Character
   */
  private function _switchmode($mode) {
    switch (strtolower($mode)) {
      case 'download':
        return 'D';

      break;
      case 'browser':
        return 'I';

      break;
      case 'file':
        return 'F';

      break;
      case 'string':
        return 'S';

      break;
      default:
        return 'I';
      break;
    }
  }

  /**
   * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages.
   *
   * @param $pages
   *
   * @return unknown_type
   */
  private function _rewritepages($pages) {
    $pages = str_replace(' ', '', $pages);
    $part = explode(',', $pages);

    // Parse hyphens.
    foreach ($part as $i) {
      $ind = explode('-', $i);

      if (count($ind) == 2) {
        // Start page.
        $x = $ind[0];
        // End page.
        $y = $ind[1];

        if ($x > $y) :
          throw new exception("Starting page, '$x' is greater than ending page '$y'."); return FALSE;
        endif;

        // Add middle pages.
        while ($x <= $y) :
          $newpages[] = (int) $x; $x++;
        endwhile;
      }
      else {
        $newpages[] = (int) $ind[0];
      }
    }

    return $newpages;
  }

}
