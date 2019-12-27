/*globals Drupal:false ,jQuery:false */
(function ($) {
    "use strict"
    Drupal.behaviors.bhge_case_study_library = {
        attach: function () {
            $('[data-drupal-selector="edit-advanced-search"]').click(function (e) {
                e.preventDefault();
                advancedSearch();
            });
            function advancedSearch() {
                var main_selector = $('.case-study-advanced-search');
                    if ($(main_selector).length === 0) {
                        var select_selectors = ["[data-drupal-selector='edit-tid']", "[data-drupal-selector='edit-tid-1']", "[data-drupal-selector='edit-tid-3']"];
                        var case_study_hide = 'case-study-advanced-search--hide';
                        var search_button= $('.views-exposed-form input.search-button');
                        var advanced_serach_data = '<section class ="case-study-advanced-search" data-component="case-study-advanced-search">' +
                            '<div class="case-study-advanced-search__content"> <span class="case-study-advanced-search__close"></span>' + $('.details-wrapper').html() + '</div></section>';
                    $("body").append(advanced_serach_data).show('slow');
                    $('.case-study-advanced-search__content').css('margin-top', $(window).height() / 2);

                    if ($(main_selector).hasClass('case-study-advanced-search--animation')) {
                        setTimeout(function () {
                            $(main_selector).removeClass("case-study-advanced-search--animation");
                        }, 200);
                    }

                    $(".case-study-advanced-search__close").click(function () {
                        $('.case-study-advanced-search').addClass('case-study-advanced-search--hide').delay(515).queue(function () {
                            $(this).remove();
                        });
                    });
                    $('[data-drupal-selector="edit-submit-advanced"]').click(function () {
                        $('select[data-drupal-selector="edit-nid"] option').prop("selected", "");
                        $(search_button).trigger("click");
                        $('.case-study-advanced-search').addClass('case-study-advanced-search--hide').delay(515).queue(function () {
                            $(this).remove();
                        });
                    });

                    $('.case-study-advanced-search [data-drupal-selector="edit-submit-advanced-reset"]').click(function () {
                        $(main_selector).addClass(case_study_hide).delay(515).queue(function () {
                            $(this).remove();
                        });
                        location.reload();
                    });
                    copy_filter_val(select_selectors);
                }
            }
            function copy_filter_val(selector) {
                $.each(selector, function (key, value) {
                    $(".case-study-advanced-search " + value).change(function () {
                        if ($(this).val() !== 'All') {
                            $(".views-exposed-form " + value).val($(this).val());
                        }
                    });
                });

            }
        }
    };
})(jQuery);
