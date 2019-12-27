/*globals Drupal:false ,jQuery:false */
(function ($) {
    "use strict";
    Drupal.behaviors.bhge_case_study_library_clear_data = {
        attach: function () {
            var title ='input[data-drupal-selector="edit-title"]';
            var nid_selector = 'select[data-drupal-selector="edit-nid"]';
            var select_selectors = ["select[data-drupal-selector='edit-tid']", "select[data-drupal-selector='edit-tid-1']", "select[data-drupal-selector='edit-tid-3']"];
            var remove_div = ['section[data-component="c24-copy-block"]', 'section[data-component="results-and-challeges"]',
                'section[data-component="results-and-challeges-container"]','section[data-component="case-studies-product-section"]',
                'section[data-component="case-study-summary-cta"]','section[data-component="case-study-content"]',
                'section[data-component="block-spotlight-carousel"]','section[data-component="c55-card-gallery"]'];
            // Remove these components when results is shown.
            if ($('.view-id-dyamic_cards_with_filters_case_study .view-content').length) {
                $.each(remove_div, function (key, val) {
                    $(val).remove();
                });
            }
            $("#latest-case-study").click(function (event) {
                $(nid_selector +' option').prop("selected", "");
                event.preventDefault();
                if ($(title).val().length) {
                    $(title).val('');
                }
                change_select_val(select_selectors);
                $("input.search-button").trigger("click");
                $('html,body').animate({
                    scrollTop: $(".view-filters #latest-case-study").offset().top + 20
                }, 'slow');
            });

            $("#featured_case_study").click(function (event) {
                event.preventDefault();
                if ($(title).val().length) {
                    $(title).val('');
                }
                change_select_val(select_selectors);
                var cnt = ($(this).data('option-count'));
                if(cnt >1) {
                    var options = $(this).data('option').split(',');
                    $.each(options, function (index, value) {
                        $(nid_selector + ' option[value="' + value + '"]').attr("selected", "selected");
                    });
                }
                // If there is only one value.
                else {
                    $(nid_selector + ' option[value="' + $(this).data('option') + '"]').attr("selected", "selected");
                }

                $("input.search-button").trigger("click");
                $('html,body').animate({
                    scrollTop: $(".view-filters #featured_case_study").offset().top + 20
                }, 'slow');
            });
            $("input.clear-search").click(function () {
                location.reload();
            });
            $(title).keypress(function(){
                $(nid_selector +' option').prop("selected", "");
            });
            $(title).click(function() {
                $(nid_selector + ' option').prop("selected", "");
            });

            function change_select_val(select_selectors) {
                $.each(select_selectors, function (index, value) {
                    $(value).val('All');
                });
            }
        }
    };
})(jQuery, Drupal);