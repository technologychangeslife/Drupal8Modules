<?php

namespace Drupal\gemc_components\Handlebars\Helpers;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

/**
 * Handlebars halper interface.
 *
 * @category Xamin
 * @package Handlebars
 * @author fzerorubigd <fzerorubigd@gmail.com>
 * @author Behrooz Shabani <everplays@gmail.com>
 * @author Dmitriy Simushev <simushevds@gmail.com>
 * @author Jeff Turcotte <jeff.turcotte@gmail.com>
 * @copyright 2014 Authors
 * @license MIT <http://opensource.org/licenses/MIT>
 * @version Release: @package_version@
 * @link http://xamin.ir
 */
class ArrayLookupHelper implements Helper {

  /**
   * Execute the helper.
   *
   * @param \Handlebars\Template $template
   *   The template instance.
   * @param \Handlebars\Context $context
   *   The current context.
   * @param \Handlebars\Arguments $args
   *   The arguments passed the the helper.
   * @param string $source
   *   The source.
   *
   * @return mixed
   *   Returns context parsed arguments.
   *
   * @throws \Exception
   */
  public function execute(Template $template, Context $context, $args, $source) {// phpcs:ignore
    $isIndexInCurrentContext = TRUE;
    $parsedArgs = $template->parseArguments($args);
    // @todo This is a hack to handle '@../index' in hbs templates.
    if ($parsedArgs[1] == '../index') {
      $vars = $context->popSpecialVariables();
      $parsedArgs[1] = '@index';
      $isIndexInCurrentContext = FALSE;
    }
    $index = $context->get($parsedArgs[1]);
    if (!$isIndexInCurrentContext) {
      $context->pushSpecialVariables($vars);
    }
    return $context->get($parsedArgs[0])[$index][(string) $parsedArgs[2]];
  }

}
