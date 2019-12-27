(function ($, Drupal,drupalSettings) {
    "use strict";
    Drupal.behaviors.views_infinite_scroll = {
        attach: function (context, settings) {
            // Do this only for dynamic card filter.
            if ($('.view-dyamic-cards-with-filters').length > 0) {
                let view_filter = $('.view-filters');
                if ($('.no-filter').length > 0) {
                    view_filter.css("border", "none !important");
                }
                else {
                    view_filter.css("border", "1px solid #d0d0d0");
                }
                // Remove the load more button.
                if($('.cta-new-replace').length >0) {
                    $('.view-dyamic-cards-with-filters').find('ul.js-pager__items.pager').remove();
                    $('.view-dyamic-cards-with-filters').find('.view-footer').remove();
                }
            }
            $('#total-rows-loaded').text($('.views-row').length);
        }
    }
})(jQuery, Drupal,drupalSettings);