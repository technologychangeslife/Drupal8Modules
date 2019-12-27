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
class ConditionHelper implements Helper {

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
   *   Returns buffer.
   *
   * @throws \Exception
   */
  public function execute(Template $template, Context $context, $args, $source) {// phpcs:ignore
    $parsedArgs = $template->parseArguments($args);
    switch ($parsedArgs[1]) {
      case '===':
        $buffer = $context->get($parsedArgs[0]) === (string) $parsedArgs[2];
        break;

      case '==':
        $buffer = $context->get($parsedArgs[0]) == (string) $parsedArgs[2];
        break;

      case '>=':
        $buffer = $context->get($parsedArgs[0]) >= (string) $parsedArgs[2];
        break;

      case '>':
        $buffer = $context->get($parsedArgs[0]) > (string) $parsedArgs[2];
        break;

      case '!==':
        $buffer = $context->get($parsedArgs[0]) !== (string) $parsedArgs[2];
        break;

      default:
        throw new \Exception("Unknown operation: $parsedArgs[1] for condition helper");
    }

    return $buffer;
  }

}
