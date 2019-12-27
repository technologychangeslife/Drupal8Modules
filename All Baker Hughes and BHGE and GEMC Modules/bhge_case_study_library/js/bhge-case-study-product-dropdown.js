/*globals Drupal:false ,jQuery:false */
(function ($) {
    "use strict";
    Drupal.behaviors.bhge_case_study_library_product_dropdown = {
        attach: function () {
            // bind change event to select
            $('.custom-select select').on('change', function () {
                var url = $(this).val();
                if (url) {
                    window.open(window.location.origin +url ,'_blank');
                }
                return false;
            });
        }
    };
})(jQuery, Drupal);