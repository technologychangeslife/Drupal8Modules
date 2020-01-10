<?php

namespace Drupal\gemc_webform\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Utility\WebformElementHelper;
use Drupal\webform\Utility\WebformYaml;

/**
 * Emails a webform submission.
 *
 * @WebformHandler(
 *   id = "rtb_email",
 *   label = @Translation("RTB email"),
 *   category = @Translation("Notification"),
 *   description = @Translation("Sends a webform submission to a different email address as per product selection."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class RTBEmailWebformHandler extends EmailWebformHandler {

  /**
   * Sends a webform submission to a different email address as per product selection.
   */
  public function sendMessage(WebformSubmissionInterface $webform_submission, array $message) {

    $data = $webform_submission->getData();

    WebformElementHelper::convertRenderMarkupToStrings($data);

    if (count($data) > 0 && (isset($data['return_part_info']) || isset($data['part_number_and_quantity_of_non_serialized_parts']))) {
      /* field array ( composite_field_name => required_sub_field ) */

      $fields_array = ['return_part_info' => 'product_brand', 'part_number_and_quantity_of_non_serialized_parts' => 'ns_product_brand'];

      /* find out email ids based on selection of products */

      $product_email_address = [];

      foreach ($fields_array as $composite_field => $sub_field) {
        if (count($data[$composite_field]) > 0) {
          foreach ($data[$composite_field] as $product) {
            if (isset($product[$sub_field]) && strlen($product[$sub_field]) > 0 && !in_array($product[$sub_field], $product_email_address)) {
              $product_email_address[] = $product[$sub_field];
            }
          }
        }
      }

      if (count($product_email_address) > 0) {
        $product_email_address[] = $message['to_mail'];
        $product_email_str = implode(',', $product_email_address);
        $message['to_mail'] = $product_email_str;
      }
    }
    \Drupal::logger('custom_webform_handler')->warning('Message to mail after: <pre><code>' . print_r($message['to_mail'], TRUE) . '</code></pre>');

    parent::sendMessage($webform_submission, $message);
  }

}
