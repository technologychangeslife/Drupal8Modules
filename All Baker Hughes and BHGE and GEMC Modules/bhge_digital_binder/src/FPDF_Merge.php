<?php

namespace Drupal\bhge_digital_binder;

/**
 * FPDF_Merge
 * Tool to merge PDFs created by fpdf 1.6         .
 *
 * @version 1.0
 * @date 2011-02-18
 * @author DEMONTE Jean-Baptiste <jbdemonte@gmail.com>
 * @copyright © Digitick <www.digitick.net> 2011
 * @license GNU Lesser General Public License v3.0
 *
 * Why this tool ?
 *
 * All the library tested (fpdi, ...) produce too heavy pdf
 * because, these are not optimized.
 * This library parses the pages, get the objects included (font, images)
 * generate a hash to store only once these objects or reuse previous stored ones.
 *
 *
 * Notes
 *
 * - links are not supported in this version
 * - all pages are included (because this was what we needed) but update it
 *   to create an add(array pages) should be easy
 *
 *
 * I tried to optimize a lot this tool using X-Debug, let me know if you do best :)
 * If you get trouble or want to comment, feel free to send me an email.
 *
 * Use
 *
 *  $merge = new FPDF_Merge();
 *  $merge->add('/tmp/pdf-1.pdf');
 *  $merge->add('/tmp/pdf-2.pdf');
 *  $merge->output('/tmp/pdf-merge.pdf'); // or $merge->output(); to open it directly in the browser
 **/
class FPDF_Merge {
  const   TYPE_NULL              = 0,
                TYPE_TOKEN       = 1,
                TYPE_REFERENCE   = 2,
                TYPE_REFERENCE_F = 3,
                TYPE_NUMERIC     = 4,
                TYPE_HEX         = 5,
                TYPE_BOOL        = 6,
                TYPE_STRING      = 7,
                TYPE_ARRAY       = 8,
                TYPE_DICTIONARY  = 9,
                TYPE_STREAM      = 10;

  private $buffer, $compress, $fonts, $objects, $pages, $ref, $n, $xref;

  /**************************************************
   * /*                    CONSTRUCTOR.
   /**************************************************/
  public function __construct() {
    $this->buffer   = '';
    $this->fonts    = [];
    $this->objects  = [];
    $this->pages    = [];
    $this->ref      = [];
    $this->xref     = [];
    $this->n        = 0;
    $this->compress = function_exists('gzcompress');
  }

  /**************************************************
   * /*                      PRIVATE.
   /**************************************************/
  private function error($msg) {
    return $msg;
    // die;.
  }

  // ================================================
  // FONCTIONS D'IMPORT.

  /**
   * ================================================.
   */
  private function parse($buffer, &$len, &$off) {
    if ($len === $off) {
      return NULL;
    }

    if (!preg_match('`\s*(.)`', $buffer, $m, PREG_OFFSET_CAPTURE, $off)) {
      return NULL;
    }
    $off = $m[1][1];

    switch ($buffer[$off]) {
      case '<':
        if ($buffer[$off + 1] === '<') {
          // Dictionnary.
          $v = [];
          $off += 2;
          while (1) {
            $key = $this->parse($buffer, $len, $off);
            if ($key === NULL) {
              break;
            }
            if ($key[0] !== self::TYPE_TOKEN) {
              break;
            }
            $value = $this->parse($buffer, $len, $off);
            $v[$key[1]] = $value;
          }
          $off += 2;
          return [self::TYPE_DICTIONARY, $v];
        }
        else {
          // Hex.
          $p = strpos($buffer, '>', $off);
          if ($p !== FALSE) {
            $v = substr($buffer, $off + 1, $p - $off - 1);
            $off = $p + 1;
            return [self::TYPE_HEX, $v];
          }
        }
        break;

      case '(':
        // String.
        $p = $off;
        while (1) {
          $p++;
          if ($p === $len) {
            break;
          }
          if (($buffer[$p] === ')') && ($buffer[$p - 1] !== '\\')) {
            break;
          }
        }
        if ($p < $len) {
          $v = substr($buffer, $off + 1, $p - $off - 1);
          $off = $p + 1;
          return [self::TYPE_STRING, $v];
        }
        break;

      case '[':
        $v = [];
        // Jump the [.
        $off++;
        while (1) {
          $value = $this->parse($buffer, $len, $off);
          if ($value === NULL) {
            break;
          }
          $v[] = $value;
        }
        // Jump the ].
        $off++;
        return [self::TYPE_ARRAY, $v];

      break;
      // Dictionnary : end.
      case '>':
        // Array : end.
      case ']':
        return NULL;

      break;
      // Comments : jump.
      case '%':
        $p = strpos($buffer, "\n", $off);
        if ($p !== FALSE) {
          $off = $p + 1;
          return $this->parse($buffer, $len, $off);
        }

        break;

      default:
        if (preg_match('`^\s*([0-9]+) 0 R`', substr($buffer, $off, 32), $m)) {
          $off += strlen($m[0]);
          return [self::TYPE_REFERENCE, $m[1]];
        }
        else {
          $p = strcspn($buffer, " %[]<>()\r\n\t/", $off + 1);
          $v = substr($buffer, $off, $p + 1);
          $off += $p + 1;
          if (is_numeric($v)) {
            $type = self::TYPE_NUMERIC;
          }
          elseif (($v === 'true') || ($v === 'false')) {
            $type = self::TYPE_BOOL;
          }
          elseif ($v === 'null') {
            $type = self::TYPE_NULL;
          }
          else {
            $type = self::TYPE_TOKEN;
          }
          return [$type, $v];
        }
        break;
    }
    return NULL;
  }

  /**
   *
   */
  private function getObject($f, $xref, $index, $includeSubObject = FALSE) {

    $type = self::TYPE_TOKEN;

    if (!isset($xref[$index])) {
      $this->error('reference d\'object inconnue');
    }

    fseek($f, $xref[$index]);

    $data   = '';
    $len    = 0;
    $offset = 0;
    $expLen = 1024;
    do {
      $prev = $len;
      $data .= fread($f, $expLen);
      $len = strlen($data);
      $p = strpos($data, "endobj", $offset);
      if ($p !== FALSE) {
        if ($data[$p - 1] !== "\n") {
          $offset = $p + 6;
          $p = FALSE;
        }
        else {
          if ($len < $p + 8) {
            $data .= fread($f, 1);
            $len = strlen($data);
          }
          if ($data[$p + 6] !== "\n") {
            // Not the endobj markup, maybe a string content.
            $offset = $p + 6;
            $p = FALSE;
          }
        }
      }
      $expLen *= 2;
    } while (($p === FALSE) && ($prev !== $len));

    if ($p === FALSE) {
      $this->error('object [' . $index . '] non trouve');
    }

    $p--;
    $data = substr($data, 0, $p);

    if (!preg_match('`^([0-9]+ 0 obj)`', $data, $m, PREG_OFFSET_CAPTURE)) {
      $this->error('object [' . $index . '] invalide');
    }

    $p = $m[0][1] + strlen($m[1][0]) + 1;
    $data = substr($data, $p);

    if (substr($data, 0, 2) === '<<') {
      $type = self::TYPE_DICTIONARY;
      $off = 0;
      $len = strlen($data);
      $dictionary = $this->parse($data, $len, $off);
      $off++;
      $data = substr($data, $off);
      if ($data === FALSE) {
        $data = '';
      }
      elseif (substr($data, 0, 7) === "stream\n") {
        $data = substr($data, 7, strlen($data) - 17);
        $type = self::TYPE_STREAM;
      }
      if ($includeSubObject) {
        $dictionary = $this->_resolveValues($f, $xref, $dictionary);
      }
    }
    else {
      $dictionary = NULL;
    }
    return [$type, $dictionary, $data];
  }

  /**
   *
   */
  private function _resolveValues($f, $xref, $item) {
    switch ($item[0]) {
      case self::TYPE_REFERENCE:
        $object = $this->getObject($f, $xref, $item[1], TRUE);
        if ($object[0] === self::TYPE_TOKEN) {
          return [self::TYPE_TOKEN, $object[2]];
        }
        $ref = $this->storeObject($object);
        return [self::TYPE_REFERENCE_F, $this->_getObjectType($object), $ref];

      break;
      case self::TYPE_ARRAY:
      case self::TYPE_DICTIONARY:
        $r = [];
        foreach ($item[1] as $key => $val) {
          if (($val[0] == self::TYPE_REFERENCE) ||
          ($val[0] == self::TYPE_ARRAY) ||
          ($val[0] == self::TYPE_DICTIONARY)) {
            $r[$key] = $this->_resolveValues($f, $xref, $val);
          }
          else {
            $r[$key] = $val;
          }
        }
        return [$item[0], $r];

      break;
      default:
        return $item;
    }
  }

  /**
   *
   */
  private function getResources($f, $xref, $page) {
    if ($page[0] !== self::TYPE_DICTIONARY) {
      $this->error('getResources necessite un dictionaire');
    }
    if (isset($page[1]['/Resources'])) {
      if ($page[1]['/Resources'][0] === self::TYPE_REFERENCE) {
        return $this->getObject($f, $xref, $page[1]['/Resources'][1]);
      }
      else {
        return [$page[1]['/Resources'][1]];
      }
    }
    elseif (isset($page[1]['/Parent'])) {
      return $this->getResources($f, $xref, $page[1]['/Parent']);
    }
    return NULL;
  }

  /**
   *
   */
  private function getContent($f, $xref, $page) {
    if ($page[0] !== self::TYPE_DICTIONARY) {
      $this->error('getContent necessite un dictionaire');
    }
    $stream = '';
    if (isset($page[1]['/Contents'])) {
      $stream = $this->_getContent($f, $xref, $page[1]['/Contents']);
    }
    return $stream;
  }

  /**
   *
   */
  private function _getContent($f, $xref, $content) {
    $stream = '';
    if ($content[0] === self::TYPE_REFERENCE) {
      $stream .= $this->getStream($f, $xref, $this->getObject($f, $xref, $content[1]));
    }
    elseif ($content[0] === self::TYPE_ARRAY) {
      foreach ($content[1] as $sub) {
        $stream .= $this->_getContent($f, $xref, $sub);
      }
    }
    else {
      $stream .= $this->getStream($f, $xref, $item);
    }
    return $stream;
  }

  /**
   *
   */
  private function getCompression($f, $xref, $item) {
    if ($item[0] === self::TYPE_TOKEN) {
      return [$item[1]];
    }
    elseif ($item[0] === self::TYPE_ARRAY) {
      $r = [];
      foreach ($item[1] as $sub) {
        $r = array_merge($r, $this->getCompression($f, $xref, $sub));
      }
      return $r;
    }
    elseif ($item[0] === self::TYPE_REFERENCE) {
      return $this->getCompression($f, $xref, $this->getObject($f, $xref, $item[1]));
    }
    return [];
  }

  /**
   *
   */
  private function getStream($f, $xref, $item) {
    $methods = isset($item[1][1]['/Filter']) ? $this->getCompression($f, $xref, $item[1][1]['/Filter']) : [];

    $raw = $item[2];
    foreach ($methods as $method) {
      switch ($method) {
        case '/FlateDecode':
          if (function_exists('gzuncompress')) {
            $raw = !empty($raw) ? @gzuncompress($raw) : '';
          }
          else {
            $this->error('gzuncompress necessaire pour decompresser ce stream');
          }
          if ($raw === FALSE) {
            $this->error('erreur de decompression du stream');
          }
          break;

        default:
          $this->error($method . ' necessaire pour decompresser ce stream');
      }
    }
    return $raw;
  }

  /**
   *
   */
  private function storeObject($item, $type = FALSE) {
    $md5 = md5(serialize($item));
    if ($type === '/Font') {
      $array = & $this->fonts;
      $prefix = '/F';
    }
    else {
      $array = & $this->objects;
      $prefix = '/Obj';
    }
    if (!isset($array[$md5])) {
      $index = count($array) + 1;
      $array[$md5] = [
      'name'  => $prefix . $index,
      'item'  => $item,
      'type'  => $type,
      'index' => $index
];
    }
    elseif ($type) {
      $array[$md5]['type'] = $type;
    }
    return $array[$md5][$type ? 'name' : 'index'];
  }

  // ================================================
  // FONCTIONS D'IMPRESSION.

  /**
   * ================================================.
   */
  private function _out($raw) {
    $this->buffer .= $raw . "\n";
  }

  /**
   *
   */
  private function _strval($value) {
    $value += 0;
    if ($value) {
      return strval($value);
    }
    return '0';
  }

  /**
   *
   */
  private function _toStream($item) {
    switch ($item[0]) {
      case self::TYPE_NULL:
        return 'null';

      case self::TYPE_TOKEN:
        return $item[1];

      case self::TYPE_REFERENCE:
        return $this->_strval($item[1]) . ' 0 R';

      case self::TYPE_REFERENCE_F:
        if (!isset($this->ref[$item[1]][$item[2]])) {
          $this->error('reference vers un object non sauve');
        }
        return $this->_strval($this->ref[$item[1]][$item[2]]) . ' 0 R';

      case self::TYPE_NUMERIC:
        return $this->_strval($item[1]);

      case self::TYPE_HEX:
        return '<' . strval($item[1]) . '>';

      case self::TYPE_BOOL:
        return $item[1] ? 'true' : 'false';

      case self::TYPE_STRING:
        return '(' . str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], strval($item[1])) . ')';

      case self::TYPE_ARRAY:
        $r = [];
        foreach ($item[1] as $val) {
          $r[] = $this->_toStream($val);
        }
        return '[' . implode(' ', $r) . ']';

      case self::TYPE_DICTIONARY:
        $r = [];
        foreach ($item[1] as $key => $val) {
          $val = $this->_toStream($val);
          $r[] = $key . ' ' . $val;
        }
        return '<<' . implode("\n", $r) . '>>';

      break;
    }
    return '';
  }

  /**
   *
   */
  private function _newobj($n = NULL) {
    if (($n === NULL) || ($n === TRUE)) {
      $this->n++;
      $id = $this->n;
    }
    else {
      $id = $n;
    }
    if ($n !== TRUE) {
      $this->xref[$id] = strlen($this->buffer);
      $this->_out($id . ' 0 obj');
    }
    return $id;
  }

  /**
   *
   */
  private function _addObj($dico = NULL, $buf = NULL) {
    $ref = $this->_newobj();
    $buf = empty($buf) && ($buf !== 0) && ($buf !== '0') ? NULL : $buf;
    if (is_array($dico)) {
      if ($buf !== NULL) {
        if ($this->compress && !isset($dico['/Filter'])) {
          $buf = gzcompress($buf);
          $dico['/Filter'] = [self::TYPE_TOKEN, '/FlateDecode'];
        }
        $dico['/Length'] = [self::TYPE_NUMERIC, strlen($buf)];
      }
      $this->_out($this->_toStream([self::TYPE_DICTIONARY, $dico]));
    }
    if ($buf !== NULL) {
      $this->_out('stream');
      $this->_out($buf);
      $this->_out('endstream');
    }
    $this->_out('endobj');
    return $ref;
  }

  /**
   *
   */
  private function _getObjectType($object) {
    return isset($object['type']) && !empty($object['type']) ? $object['type'] : 'default';
  }

  /**
   *
   */
  private function _putObject($object) {
    $type = $this->_getObjectType($object);
    if (!isset($this->ref[$type])) {
      $this->ref[$type] = [];
    }
    $this->ref[$type][$object['index']] = $this->_addObj($object['item'][1][1], $object['item'][2]);
  }

  /**
   *
   */
  private function _putObjects() {
    foreach ($this->objects as $object) {
      if ($object['type']) {
        continue;
      }
      $this->_putObject($object);
    }
    foreach ($this->objects as $object) {
      if (!$object['type']) {
        continue;
      }
      $this->_putObject($object);
    }
    foreach ($this->fonts as $object) {
      $this->_putObject($object);
    }
  }

  /**
   *
   */
  private function _putResources() {
    $dico = [
    '/ProcSet' => [
            self::TYPE_ARRAY,
            [
                [self::TYPE_TOKEN, '/PDF'],
                [self::TYPE_TOKEN, '/Text'],
                [self::TYPE_TOKEN, '/ImageB'],
                [self::TYPE_TOKEN, '/ImageC'],
                [self::TYPE_TOKEN, '/ImageI']
            ]
        ]
  ];

    $xObjects = [];
    foreach ($this->objects as $index => $object) {
      if ($object['type'] === FALSE) {
        continue;
      }
      $value = [
      self::TYPE_TOKEN,
      $this->_toStream([self::TYPE_REFERENCE, $this->ref[$object['type']][$object['index']]])
];
      if ($object['type'] === '/XObject') {
        $xObjects[$object['name']] = $value;
      }
    }
    if (!empty($xObjects)) {
      $dico['/XObject'] = [self::TYPE_DICTIONARY, $xObjects];
    }

    $fonts = [];
    foreach ($this->fonts as $index => $object) {
      $value = [
      self::TYPE_TOKEN,
      $this->_toStream([self::TYPE_REFERENCE, $this->ref['/Font'][$object['index']]])
];
      $fonts[$object['name']] = $value;
    }
    if (!empty($fonts)) {
      $dico['/Font'] = [self::TYPE_DICTIONARY, $fonts];
    }
    return $this->_addObj($dico);
  }

  /**************************************************
   * /*                      PUBLIC.
   /**************************************************/
  public function add($filename) {
    $f = @fopen($filename, 'rb');
    if (!$f) {
      $this->error('impossible d\'ouvrir le fichier');
    }
    fseek($f, 0, SEEK_END);
    $fileLength = ftell($f);

    // Localisation de xref
    // -------------------------------------------------.

    fseek($f, -128, SEEK_END);
    $data = fread($f, 128);
    if ($data === FALSE) {
      return $this->error('erreur de lecture dans le fichier');
    }
    $p = strripos($data, 'startxref');
    if ($p === FALSE) {
      return $this->error('startxref absent');
    }
    $startxref = substr($data, $p + 10, strlen($data) - $p - 17);
    $posStartxref = $fileLength - 128 + $p;

    // Extraction de xref + trailer
    // -------------------------------------------------.

    fseek($f, $startxref);
    $data = fread($f, $posStartxref - $startxref);

    // Extraction du trailer
    // -------------------------------------------------.
    $p = stripos($data, 'trailer');
    if ($p === FALSE) {
      return $this->error('trailer absent');
    }
    $dataTrailer = substr($data, $p + 8);
    $len = strlen($dataTrailer);
    $off = 0;
    $trailer = $this->parse($dataTrailer, $len, $off);

    // Extraction du xref
    // -------------------------------------------------.

    $data = explode("\n", trim(substr($data, 0, $p)));
    // "xref".
    array_shift($data);

    $cnt = 0;
    $xref = [];

    foreach ($data as $line) {
      if (!$cnt) {
        if (preg_match('`^([0-9]+) ([0-9]+)$`', $line, $m)) {
          $index = intval($m[1]) - 1;
          $cnt = intval($m[2]);
        }
        else {
          $this->error('erreur dans xref');
        }
      }
      else {
        $index++;
        $cnt--;
        if (preg_match('`^([0-9]{10}) [0-9]{5} ([n|f])`', $line, $m)) {
          if ($m[2] === 'f') {
            continue;
          }
          $xref[$index] = $m[1];
        }
        else {
          $this->error('erreur dans xref : ' . $line);
        }
      }
    }

    // Lecture des pages
    // -------------------------------------------------.

    $root = $this->getObject($f, $xref, $trailer[1]['/Root'][1]);
    $root = $root[1][1];

    $pages = $this->getObject($f, $xref, $root['/Pages'][1]);
    $pages = $pages[1][1];

    foreach ($pages['/Kids'][1] as $kid) {
      $kid = $this->getObject($f, $xref, $kid[1]);
      $kid = $kid[1];

      $resources = $this->getResources($f, $xref, $kid);
      $resources = $resources[1][1];

      $content = $this->getContent($f, $xref, $kid);

      // Traitement des fonts
      // -------------------------------------------------.
      $newFonts = [];
      if (isset($resources['/Font']) && !empty($resources['/Font'])) {
        if (preg_match_all("`(/F[0-9]+)\s+-?[0-9\.]+\s+Tf`", $content, $matches, PREG_OFFSET_CAPTURE)) {
          $newContent = '';
          $offset     = 0;
          $cnt        = count($matches[0]);
          for ($i = 0; $i < $cnt; $i++) {
            $position = $matches[0][$i][1];
            $name     = $matches[1][$i][0];
            if (!isset($newFonts[$name])) {
              $object = $this->getObject($f, $xref, $resources['/Font'][1][$name][1], TRUE);
              $newFonts[$name] = $this->storeObject($object, '/Font');
            }
            if ($newFonts[$name] !== $name) {
              $newContent .= substr($content, $offset, $position - $offset);
              $newContent .= $newFonts[$name];
              $offset = $position + strlen($name);
            }
          }
          $content = $newContent . substr($content, $offset);
        }
      }

      // Traitement des XObjets
      // -------------------------------------------------.
      $newXObjects = [];
      if (isset($resources['/XObject']) && !empty($resources['/XObject'])) {
        if (preg_match_all("`(/[^%\[\]<>\(\)\r\n\t/]+) Do`", $content, $matches, PREG_OFFSET_CAPTURE)) {
          $newContent = '';
          $offset     = 0;
          foreach ($matches[1] as $m) {
            $name = $m[0];
            $position = $m[1];
            if (!isset($newXObjects[$name])) {
              $object = $this->getObject($f, $xref, $resources['/XObject'][1][$name][1], TRUE);
              $newXObjects[$name] = $this->storeObject($object, '/XObject');
            }
            if ($newXObjects[$name] !== $name) {
              $newContent .= substr($content, $offset, $position - $offset);
              $newContent .= $newXObjects[$name];
              $offset = $position + strlen($name);
            }
          }
          $content = $newContent . substr($content, $offset);
        }
      }

      $mediaBox = isset($kid[1]['/MediaBox']) ? $kid[1]['/MediaBox'] : (isset($pages['/MediaBox']) ? $pages['/MediaBox'] : NULL);

      if ($mediaBox[0] !== self::TYPE_ARRAY) {
        $this->error('MediaBox non definie');
      }

      $this->pages[] = [
      'content'   => $content,
      '/XObject'  => array_values($newXObjects),
      '/Font'     => array_values($newFonts),
      '/MediaBox' => $mediaBox
];
    }
    fclose($f);
  }

  /**
   *
   */
  public function output($filename = NULL) {
    $this->_out('%PDF-1.6');

    $this->_putObjects();

    $rsRef = $this->_putResources();

    $ptRef = $this->_newobj(TRUE);

    $kids = [];

    // Ajout des pages.
    $n = count($this->pages);
    for ($i = 0; $i < $n; $i++) {
      $ctRef = $this->_addObj([], $this->pages[$i]['content']);
      $dico = [
        '/Type'     => [self::TYPE_TOKEN, '/Page'],
        '/Parent'   => [self::TYPE_REFERENCE, $ptRef],
        '/MediaBox' => $this->pages[$i]['/MediaBox'],
        '/Resources' => [self::TYPE_REFERENCE, $rsRef],
        '/Contents' => [self::TYPE_REFERENCE, $ctRef],
      ];
      $kids[] = [self::TYPE_REFERENCE, $this->_addObj($dico)];
    }

    // Ajout du page tree.
    $ptDico = [
    self::TYPE_DICTIONARY,
    [
        '/Type'     => [self::TYPE_TOKEN, '/Pages'],
        '/Kids'     => [self::TYPE_ARRAY, $kids],
        '/Count'    => [self::TYPE_NUMERIC, count($kids)]
    ]
  ];

    $this->_newobj($ptRef);
    $this->_out($this->_toStream($ptDico));
    $this->_out('endobj');

    // Ajout du catalogue.
    $ctDico = [
    self::TYPE_DICTIONARY,
    [
        '/Type' => [self::TYPE_TOKEN, '/Calalog'],
        '/Pages' => [self::TYPE_REFERENCE, $ptRef]
        ]
  ];
    $ctRef = $this->_newobj();
    $this->_out($this->_toStream($ctDico));
    $this->_out('endobj');

    // Ajout du xref.
    $xrefOffset = strlen($this->buffer);
    $count = count($this->xref);
    $this->_out('xref');
    $this->_out('0 ' . ($count + 1));
    $this->_out('0000000000 65535 f ');
    for ($i = 0; $i < $count; $i++) {
      $this->_out(sprintf('%010d 00000 n ', $this->xref[$i + 1]));
    }

    // Ajout du trailer.
    $dico = [
    '/Size' => [self::TYPE_NUMERIC, 1 + count($this->xref)],
    '/Root' => [self::TYPE_REFERENCE, $ctRef]
  ];
    $this->_out('trailer');
    $this->_out($this->_toStream([self::TYPE_DICTIONARY, $dico]));

    // Ajout du startxref.
    $this->_out('startxref');
    $this->_out($xrefOffset);
    $this->_out('%%EOF');

    if ($filename === NULL) {
      header('Content-Type: application/pdf');
      header('Content-Length: ' . strlen($this->buffer));
      header('Cache-Control: private, max-age=0, must-revalidate');
      header('Pragma: public');
      ini_set('zlib.output_compression', '0');

      echo $this->buffer;
      die;
    }
    else {
      file_put_contents($filename, $this->buffer);
    }
  }

}
