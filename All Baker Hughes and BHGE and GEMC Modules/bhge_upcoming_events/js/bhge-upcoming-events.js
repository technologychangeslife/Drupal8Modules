/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.upcoming_events = {
        attach: function () {
            // List and Grid events.
            var view_selector = $('.upcoming-events .content-wrapper');
            var search1 = $('.view-display-id-block_1 input[data-drupal-selector="edit-title"]').val();
            var fieldsetType = $('fieldset[data-drupal-selector="edit-field-event-type-target-id"]');
            var fieldsetRegion = $('fieldset[data-drupal-selector="edit-field-event-region-target-id"]');
            var fieldsetPast = $('fieldset[data-drupal-selector="edit-field-eventdate-value"]');
            var eventTypeHtml = $('fieldset[data-drupal-selector="edit-field-event-type-target-id"]').html();
            var eventRegionHtml = $('fieldset[data-drupal-selector="edit-field-event-region-target-id"]').html();
            var pastEventHtml = $('fieldset[data-drupal-selector="edit-field-eventdate-value"]').html();

            if (view_selector.hasClass('dc-list-view')) {
                $('.toggle-view-menu .list-view-button .list-icon').addClass('selected', 'selected');
            }
            //row display
            $('.list-view-button').click(function () {
                if (view_selector.hasClass('dc-grid-view')) {
                    view_selector.removeClass('dc-grid-view');
                }
                if ($('.toggle-view-menu .grid-view-button .grid-icon').hasClass('selected')) {
                    $('.toggle-view-menu .grid-view-button .grid-icon').removeClass('selected');
                }
                view_selector.addClass('dc-list-view');
                $('.toggle-view-menu .list-view-button .list-icon').addClass('selected');
            });
            //grid view
            $('.grid-view-button').click(function () {
                if (view_selector.hasClass('dc-list-view')) {
                    view_selector.removeClass('dc-list-view');
                }
                if ($('.toggle-view-menu .list-view-button .list-icon').hasClass('selected')) {
                    $('.toggle-view-menu .list-view-button .list-icon').removeClass('selected');
                }
                view_selector.addClass('dc-grid-view');
                $('.toggle-view-menu .grid-view-button .grid-icon').addClass('selected');
            });

            //Search Filters
            $('.filters-selected').remove();
            $('.view-display-id-block_1 .view-filters').append("<div class='filters-selected' style='display:none;'> </div>");
            // Code to create filter for search title and clear it.

            //BLOCK -1

            if (search1.length > 0) {
                $('.view-display-id-block_1 .filters-selected').append("<div id=\"clear-button-search\" class=\"search-filter-items\">\n" +
                    " <div class=\"filter-item\">\n" + search1 +
                    " <a href=\"/#\" class=\"close-search\" data-option= " + search + "></a>\n" +
                    "</div>\n" +
                    "</div>");

                $('.view-display-id-block_1 [id="clear-button-search"]:gt(0)').remove();
                $('.view-display-id-block_1 .filters-selected').show();
            }
            // Reset search title filter.
            $(".view-display-id-block_1 .filter-item a.close-search").click(function (event) {
                event.preventDefault();
                $('.view-display-id-block_1 input[data-drupal-selector="edit-title"]').val('');

                // Trigger the submit.
                $(".view-display-id-block_1 input.search-button").trigger("click");
            });

            // Code to create filter for selected checkbox filters and clear it.
            // Add div under search.
            $(".view-display-id-block_1 input:checkbox:checked").each(function () {
                var checkbox_val = $(this).val();
                var checkbox_label = $(this).next("label").text().split('(')[0];
                // For resetting the filters.
                if (checkbox_label.length > 0) {
                    $('.view-display-id-block_1 .filters-selected').append("<div id=\"clear-button-" + checkbox_val + "\" class=\"search-filter-items\">\n" +
                        " <div class=\"filter-item\">\n" + checkbox_label +
                        " <a href=\"/#\" class=\"close\" data-option= " + checkbox_val + "></a>\n" +
                        "</div>\n" +
                        "</div>");
                    $('.view-display-id-block_1 .filters-selected').show();
                }
                $('.view-display-id-block_1 .search-filter-items[id]').each(function () {
                    $('[id="' + this.id + '"]:gt(0)').remove();
                });
            });
            var filtersSelectedHeight = $('.filters-selected').outerHeight();

            // Reset the filter.
            $(".view-display-id-block_1 .filter-item a.close").click(function (event) {
                event.preventDefault();
                var option = $(this).data('option');
                $('.view-display-id-block_1 input[type=checkbox][value=' + option + ']').prop('checked', false);

                // Trigger the submit.
                $(".view-display-id-block_1 input.search-button").trigger("click");
            });

            // Clearing filters when un checking checkboxes.
            $(".view-display-id-block_1 input:checkbox").change(function() {
                var ischecked= $(this).is(':checked');
                if(!ischecked)
                    $('#clear-button-'+ $(this).val() + ' a.close').trigger("click");
            });

            // End of Code to create filter for selected checkbox filters and clear it.

            // Code for filter overlay - mobile.
            var mobileFilter = "<div class=\"mobile-filter-buttons\" style=\"\n" +
                "    padding: 20px;\n" +
                "    background: #fff;\n" +
                "    box-shadow: 0 0 40px rgba(0,0,0,.1);\n" +
                "    position: fixed;\n" +
                "    bottom: 0;\n" +
                "    left: 0;\n" +
                "    width: 100%;\n" +
                "    display: block;\n" +
                "    z-index: 10;\n" +
                "    text-align: center;\n" +
                "\">\n" +
                "                <a href='#overlay' id='overlay-filter' class=\"cta component-cta full wide light title-cta js-open-filter overlay-filter\">\n" +
                "                <span class=\"copy\">Filter</span>\n" +
                "                </a>\n" +
                "                \n" +
                "                </div>";


            $(document).ready(function(){
                //pager text
                $('.view-display-id-block_1 .item-count').text($('.view-display-id-block_1 .view-content .views-row').length);
                $('.view-display-id-block_2 .item-count').text($('.view-display-id-block_2 .view-content .views-row').length);
                var filtersTotalHeight = ((fieldsetType.outerHeight() + 40) + (fieldsetRegion.outerHeight()+50) + (fieldsetPast.outerHeight() + 60 ) + 240) + 'px';
                $(".upcoming-events .content-wrapper").css("min-height",filtersTotalHeight);

                //$(".form-item-sort-order label").append("<div class='events-down-arrow'><img src='/themes/custom/bhge/image/upcoming-events/events-down-arrow.png' alt='down-arrow' class='down-arrow-icon'/> </div>");

                $('.overlay').remove();
                $(".upcoming-events").append("<div id='overlay' class='overlay' style='display:none;'><div class='overlay-body'></div>");

                //append filter HTML
                $('.overlay .overlay-body').append("<div class='close-overlay'>x</div>");
                $('.overlay .overlay-body').append("<div class = 'overlay-buttons'><span class='overlay-upcoming-btn overlay-btn selected'>Upcoming Events</span><span class='overlay-past-btn overlay-btn'>Past Events</span></div>");
                $('.overlay .overlay-body').append("<div class='overlay-upcoming'></div>");
                $('.overlay .overlay-body').append("<div class='overlay-past'></div>");
                $('.overlay .overlay-body .overlay-upcoming').append("<div class='overlay-event-type-filter overlay-upcoming-filter'><i class='right-arrow'></i><div class='event-type-label'>Event Type</div>" + eventTypeHtml + "</div>");
                $('.overlay .overlay-body .overlay-upcoming').append("<div class='overlay-event-region-filter overlay-upcoming-filter'><i class='right-arrow'></i><div class='event-region-label'>Event Region</div>" + eventRegionHtml + "</div>");
                $('.overlay .overlay-body .overlay-past').append("<div class='overlay-past-events-filter overlay-past-filter'><i class='right-arrow'></i><div class='past-event-type-label'>Past Events</div>" + pastEventHtml + "</div>");
                $('.overlay .overlay-body').append("<div class='overlay-back-container'><div class='overlay-back'><a href='#close' id='overlay-filter-close' class='cta component-cta full wide light title-cta js-open-filter overlay-filter-close'> <span class='copy'>Back to Results</span></div></div></div");

                if($(".overlay-upcoming-btn").hasClass("selected")){
                    $(".overlay-upcoming-btn").css("background", "#005eb8");
                    $(".overlay-upcoming").show();
                }
                $(".overlay-upcoming-btn").click(function(){
                    $(".overlay-upcoming-btn").css("background", "#005eb8");
                    $(".overlay-upcoming").show();
                    $(".overlay-upcoming-btn").addClass("selected");
                });
                //Past Filter for Overlay
                var overlayPast = $(".overlay-past-events-filter .form-radios .js-form-item:nth-child(3) input").is(':checked');
                if(!overlayPast){
                    $(".overlay-past-events-filter .form-radios .js-form-item:nth-child(2)").hide();
                    $(".overlay-past-events-filter .form-radios .js-form-item:nth-child(3)").show();
                    $(".past-event-type-label").text("Past Events");
                }else{
                    $(".overlay-past-events-filter .form-radios .js-form-item:nth-child(3)").hide();
                    $(".overlay-past-events-filter .form-radios .js-form-item:nth-child(2)").show();
                    $(".past-event-type-label").text("Upcoming Events");
                }
                //hide overlay on selecting a filter
                $(".overlay-event-type-filter div[data-drupal-selector='edit-field-event-type-target-id'] div").click(function () {
                    $('.overlay').delay(1000).hide();
                    $(".upcoming-events-btn").trigger("click");
                });
                $(".overlay-event-region-filter div[data-drupal-selector='edit-field-event-region-target-id'] div").click(function () {
                    $('.overlay').delay(1000).hide();
                    $(".upcoming-events-btn").trigger("click");
                });
                $(".overlay-past-events-filter div.form-type-radio").click(function () {
                    $('.overlay').delay(1000).hide();
                });
                //Disable checkbox
                $(".overlay-event-type-filter div[data-drupal-selector='edit-field-event-type-target-id'] input").each(function () {
                    $(this).attr("disabled","disabled");
                });
                $(".overlay-event-region-filter div[data-drupal-selector='edit-field-event-region-target-id'] input").each(function () {
                    $(this).attr("disabled","disabled");
                });
                //close
                $(".close-overlay").click(function () {
                    $('.overlay').animate({width: 'toggle'});
                });
                $(".overlay-back").click(function (e) {
                    e.preventDefault();
                    $('.overlay').animate({width: 'toggle'});
                });

                var contentHeight = parseInt($(".upcoming-events .content-wrapper").outerHeight());

                $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(2)').on("click",function(){
                    $('.form-item-sort-order select>option:eq(0)').attr('selected','selected').trigger("click");
                });
                $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(3)').on("click",function(){
                    $('.form-item-sort-order select>option:eq(1)').attr('selected','selected').trigger("click");
                });
                //Past Filter
                var checkPastFilter = $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(3) input').is(':checked');
                if(!checkPastFilter){
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(2)').hide();
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .fieldset-legend').text("Past Events");
                    $('.events-options .upcoming-text').text("Upcoming Events");
                }else{
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(2)').show();
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .js-form-item:nth-child(3)').hide();
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"] .fieldset-legend').text("Upcoming Events");
                    $('.events-options .upcoming-text').text("Past Events");

                }

                function modifyFilters(typeHeight,regionHeight,pastHeight){
                    // Order Filters
                    // Type
                    $('fieldset[data-drupal-selector="edit-field-event-type-target-id"]').css("margin-top", "0px");
                    // Region
                    $('fieldset[data-drupal-selector="edit-field-event-region-target-id"]').css("margin-top", ((typeHeight.outerHeight() + 40)) + 'px');
                    // Region
                    $('fieldset[data-drupal-selector="edit-field-eventdate-value"]').css("margin-top", ((typeHeight.outerHeight() + 40) + regionHeight.outerHeight()+50) + 'px');

                }
                modifyFilters(fieldsetType,fieldsetRegion,fieldsetPast);

                // List View and Mobile Filter.
                if ($(window).width() < 1040) {
                    if ($(view_selector).hasClass('dc-list-view')) {
                        $(view_selector).removeClass('dc-list-view');
                        $(view_selector).addClass('dc-grid-view');
                    }
                    $(".toggle-view-menu").hide();
                    if ($('.mobile-filter-buttons').length == 0) {
                        $(".upcoming-events").append(mobileFilter);
                        $(".overlay-filter").click(function (e) {
                            e.preventDefault();
                            $('.overlay').animate({width: 'toggle'});
                        });
                    }
                    else {
                        $(".mobile-filter-buttons").css("display", "block");
                    }
                    // hide filters
                    fieldsetType.hide();
                    fieldsetRegion.hide();
                    fieldsetPast.hide();
                }
                else {
                    $(".mobile-filter-buttons").css("display", "none");
                    $(".overlay").css("display", "none");
                    modifyFilters(fieldsetType,fieldsetRegion,fieldsetPast);
                    fieldsetType.show();
                    fieldsetRegion.show();
                    fieldsetPast.show();
                    $(".toggle-view-menu").show();
                }
                $(window).on('resize', function () {
                    if ($(window).width() < 1040) {
                        if ($(view_selector).hasClass('dc-list-view')) {
                            $(view_selector).removeClass('dc-list-view');
                            $(view_selector).addClass('dc-grid-view');
                        }
                        $(".toggle-view-menu").hide();
                        if ($('.mobile-filter-buttons').length == 0) {
                            $(".upcoming-events").append(mobileFilter);
                            $(".overlay-filter").click(function (e) {
                                e.preventDefault();
                                $('.overlay').animate({width: 'toggle'});
                            });
                        }
                        else {
                            $(".mobile-filter-buttons").css("display", "block");
                        }
                        fieldsetType.hide();
                        fieldsetRegion.hide();
                        fieldsetPast.hide();
                    }

                    else {
                        $(".mobile-filter-buttons").css("display", "none");
                        $(".overlay").css("display", "none");
                        modifyFilters(fieldsetType,fieldsetRegion,fieldsetPast);
                        fieldsetType.show();
                        fieldsetRegion.show();
                        fieldsetPast.show();
                        $(".toggle-view-menu").show();
                    }
                });
            });
        }
    }
}(jQuery, Drupal));