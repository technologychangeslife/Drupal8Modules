/*globals Drupal:false ,jQuery:false */
(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.download_center = {
        attach: function (context, settings) {
            var theme_path = drupalSettings.bhge_document_center.theme_path;
            var fieldsetLanguage = $('fieldset[data-drupal-selector="edit-field-language-target-id"]');
            var fieldsetFile = $('fieldset[data-drupal-selector="edit-tid"]');
            var fieldsetProduct = $('fieldset[data-drupal-selector="edit-nid2"]');
            var fieldsetBrand = $('fieldset[data-drupal-selector="edit-select-brand-target-id-1"]');
            // Filter height.
            var languageFilterHeight = fieldsetLanguage.outerHeight();
            var fileFilterHeight = fieldsetFile.outerHeight();
            var productFilterHeight = fieldsetProduct.outerHeight();
            var brandFilterHeight = fieldsetBrand.outerHeight();
            // Overlay related variables.
            var view_selector = $('.download-center .content-wrapper');
            var totalHeight = languageFilterHeight + fileFilterHeight + productFilterHeight + brandFilterHeight;
            // Filter HTML
            var languageHtml = fieldsetLanguage.html();
            var fileHtml = fieldsetFile.html();
            var productHtml = fieldsetProduct.html();
            var brandHtml = fieldsetBrand.html();
            var search = $('input[data-drupal-selector="edit-title"]').val();
            var contentMinHeightConstant = parseInt($(".download-center .content-wrapper").css('min-height'));
            /*window.onbeforeunload = function (e) {
                window.localStorage.removeItem('visited');
                window.localStorage.removeItem('brand');
            };
            var yetVisited = localStorage.visited;
            if (!yetVisited) {
                localStorage.visited = "yes";
                $("[data-drupal-selector='edit-field-language-target-id']").find('input[type="checkbox"]').each(function () {
                    if ($(this).next('label').text().indexOf("English") >= 0) {
                        $(this).prop('checked', true);
                        event.preventDefault();
                        $("input.search-button").trigger("click");
                    }
                });
            }*/
            // For the brand filter.
            /*var brandsArray=[];
            $("[data-drupal-selector='edit-select-brand-target-id-1']").find('input[type="checkbox"]').change(function() {
                window.localStorage.removeItem('brand');
                var brand_value= $(this).attr('value');
                $("[data-drupal-selector='edit-select-brand-target-id-1']").find('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        brandsArray.push($(this).attr('value'));
                    }
                    if (this.unchecked) {
                        brandsArray.splice($.inArray(brand_value, brandsArray), 1);
                    }
                    localStorage.setItem('brand', JSON.stringify(brandsArray));
                });
            });
            if(typeof localStorage.brand !=='undefined') {
                var brand_list =$.parseJSON(localStorage.brand);
                $.each(brand_list,function(index,val){
                    $("[data-drupal-selector='edit-nid2']").find('input[type="checkbox"]').each(function () {
                        $(this).parent('.form-item').css('display', 'none');
                        $("input[data-brand*=" + val + "]" ).parent('.form-item').css('display', 'block');
                    });
                });
            }*/

            // Add div for selected filters.
            $('.filters-selected').remove();
            $('.view-filters').append("<div class='filters-selected' style='display:none;'> </div>");

            // Code to create filter for search title and clear it.
            if (search.length > 0) {
                $('.filters-selected').append("<div id=\"clear-button-search\" class=\"search-filter-items\">\n" +
                    " <div class=\"filter-item\">\n" + search +
                    " <a href=\"/#\" class=\"close-search\" data-option= " + search + "></a>\n" +
                    "</div>\n" +
                    "</div>");

                $('[id="clear-button-search"]:gt(0)').remove();
                $('.filters-selected').show();
            }
            // Reset search title filter.
            $(".filter-item a.close-search").click(function (event) {
                event.preventDefault();
                $('input[data-drupal-selector="edit-title"]').val('');

                // Trigger the submit.
                $("input.search-button").trigger("click");
            });

            // Code to create filter for selected checkbox filters and clear it.
            // Add div under search.
            $("input:checkbox:checked").each(function () {
                var checkbox_val = $(this).val();
                var checkbox_label = $(this).next("label").text().split('(')[0];
                // For resetting the filters.
                if (checkbox_label.length > 0) {
                    $('.filters-selected').append("<div id=\"clear-button-" + checkbox_val + "\" class=\"search-filter-items\">\n" +
                        " <div class=\"filter-item\">\n" + checkbox_label +
                        " <a href=\"/#\" class=\"close\" data-option= " + checkbox_val + "></a>\n" +
                        "</div>\n" +
                        "</div>");
                    $('.filters-selected').show();
                }
                $('.search-filter-items[id]').each(function () {
                    $('[id="' + this.id + '"]:gt(0)').remove();
                });
            });
            var filtersSelectedHeight = $('.filters-selected').outerHeight();

            // Reset the filter.
            $(".filter-item a.close").click(function (event) {
                event.preventDefault();
                var option = $(this).data('option');
                $('input[type=checkbox][value=' + option + ']').prop('checked', false);

                // Trigger the submit.
                $("input.search-button").trigger("click");
            });
            // End of Code to create filter for selected checkbox filters and clear it.

            // Adding margin-bottom for lengthy selected search filters.
            // Get default height.
            var maxHeight = $(".filters-selected .search-filter-items").outerHeight();
            $(".filters-selected .search-filter-items").each(function () {
                var filterHeight = $(".filter-item", this).outerHeight();
                if (filterHeight > 80) {
                    $(".filter-item", this).css({
                        'font-size': '12px',
                        'overflow': 'none',
                        'width' : '100%',
                    });
                    $(this).css({'margin-bottom': '20px', "width": "300px"});
                }
            });
            view_selector.css({
                'padding-top': '25px',
                'min-height': (totalHeight + filtersSelectedHeight + 100) + 'px',
                'margin-bottom': filtersSelectedHeight + 'px'
            });
            // Clearing filters when un checking checkboxes.
            $("input:checkbox").change(function() {
                var ischecked= $(this).is(':checked');
                if(!ischecked)
                    $('#clear-button-'+ $(this).val() + ' a.close').trigger("click");
            });

            // Code for icons.
            // Get file type.
            $('.views-row').each(function () {
                var hrefVal = $(".field-content a", this).attr("data-url");
                if(hrefVal !=" "){
                    var fileType = hrefVal.split("?")[0].split('.').pop();
                    var iconDiv = generateIcon(fileType);
                    //add icon only once
                    $(".views-field-field-topic-1", this).show().html(iconDiv);
                }
            });

            function generateIcon(type) {
                var str = "<div class=\"download-type\">\n" +
                    "        <div class=\"icon " + type + "\" data-bind=\"css: fileExtension, 'icon'\">\n" +
                    "          <svg class=\"component-icon\">\n" +
                    "            <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#asset\"></use>\n" +
                    "          </svg>\n" +
                    "          <span class=\"file-type\">.<span class=\"extension\" data-bind=\"text: fileExtension\">" + type + "</span></span>\n" +
                    "        </div>\n" +
                    "        <span class=\"type-text\" data-bind=\"text: fileType\"></span>\n" +
                    "      </div>";
                return str;
            }
            // List and Grid events.
            if (view_selector.hasClass('dc-list-view')) {
                $('.toggle-view-menu .list-view-button').attr('selected', 'selected');
            }
            //row display
            $('.view-download-center .list-view-button').click(function () {
                if (view_selector.hasClass('dc-grid-view')) {
                    view_selector.removeClass('dc-grid-view');
                }
                if ($('.toggle-view-menu .grid-view-button').hasClass('selected')) {
                    $('.toggle-view-menu .grid-view-button').removeClass('selected');
                }
                view_selector.addClass('dc-list-view');
                $('.toggle-view-menu .list-view-button').addClass('selected');
                $('.views-row .views-field-title .field-content').each(function () {
                    $(this).removeAttr('style');
                });
            });
            //grid view
            $('.view-download-center .grid-view-button').click(function () {
                if (view_selector.hasClass('dc-list-view')) {
                    view_selector.removeClass('dc-list-view');
                }
                if ($('.toggle-view-menu .list-view-button').hasClass('selected')) {
                    $('.toggle-view-menu .list-view-button').removeClass('selected');
                }
                view_selector.addClass('dc-grid-view');
                $('.toggle-view-menu .grid-view-button').addClass('selected');
                $('.views-row .views-field-title .field-content').each(function () {
                    var titleText = '';
                    titleText = $(this).text();
                    var titleTextLength = titleText.length;
                    if (titleTextLength > 55) {
                        $(this).css({
                            'overflow': 'hidden',
                            'text-overflow': 'ellipsis',
                            'display': '-webkit-box',
                            '-webkit-line-clamp': '3',
                            '-webkit-box-orient': 'vertical'
                        });
                    }
                });
            });
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
                "                <a href='#overlay' id='dc-overlay-filter' class=\"cta component-cta full wide light title-cta js-open-filter dc-overlay-filter\">\n" +
                "                <span class=\"copy\">Filter</span>\n" +
                "                </a>\n" +
                "                \n" +
                "                </div>";

            $(document).ready(function(){
                // Add dropdown for languages
                $(".others").remove();
                var lang ="<div class='others'><img src='"+theme_path+"/image/document-center/plus-gray.png' alt='plus' class='plus-sign'><img src='"+theme_path+"/image/document-center/minus-gray.png' alt='minus' class='minus-sign'><span class='other-text'>Other Languages</span> <div class='language-options'></div></div>";
                $(lang).appendTo($(fieldsetLanguage,'.fieldset-wrapper #edit-field-language-target-id'));
                $('.minus-sign').hide();
                //Get all languages
                $("div[data-drupal-selector='edit-field-language-target-id'] div").each(function(i, el){
                    var label_val =$('label',el).text();
                    if(label_val.indexOf('English') <=-1){
                        $(el).remove();
                        var classList = $(this).prop('className').split(' ')[5];
                        if($('.language-options div.'+classList).length == 0){
                            $('.language-options').append($(el));
                        }
                    }
                });
                function modifyFilters(languageHeight,brandHeight,productHeight){
                    // Order Filters
                    //Brand
                    $('fieldset[data-drupal-selector="edit-select-brand-target-id-1"]').css("margin-top", (languageHeight.outerHeight() + 30) + 'px');
                    // Product
                    $('fieldset[data-drupal-selector="edit-nid2"]').css("margin-top", ((brandHeight.outerHeight() + 30)+(languageHeight.outerHeight() + 40)) + 'px');
                    // File Type
                    $('fieldset[data-drupal-selector="edit-tid"]').css("margin-top", ((brandHeight.outerHeight() + 30) + (productHeight.outerHeight() + 30) + (languageHeight.outerHeight() + 40)) + 'px');

                }
                // Set initial height for filters
                modifyFilters(fieldsetLanguage,fieldsetBrand,fieldsetProduct);

                var languageOptions=0;
                $('.language-options').each(
                    function() {
                        languageOptions  = ($(this).find('div')).length;
                    }
                );
                var contentMinHeight = parseInt($(".download-center .content-wrapper").css('min-height'));
                var languageOptionsHeight = ((languageOptions * 37));
                var initialContentHeight = (contentMinHeight - languageOptionsHeight) ;
                if(initialContentHeight < contentMinHeightConstant && totalHeight!=0){
                    initialContentHeight = (totalHeight) + 100;
                }
                $(".download-center .content-wrapper").css('min-height',initialContentHeight+'px');
                var newContentMinHeight = parseInt($(".download-center .content-wrapper").css('min-height'),10);

                function modifyContentHeight(val){
                    if(val == 'expand'){
                        var languageFilterHeightExpand = $("fieldset[data-drupal-selector='edit-field-language-target-id']");
                        // modify height for filters
                        modifyFilters(languageFilterHeightExpand,fieldsetBrand,fieldsetProduct);
                        var  totalContentHeightPlus = (totalHeight) +100;

                        $(".download-center .content-wrapper").css('min-height',totalContentHeightPlus+'px');
                    }
                    if(val =='collapse'){
                        var languageFilterHeightCollapse = $("fieldset[data-drupal-selector='edit-field-language-target-id']");
                        // modify height for filters
                        modifyFilters(languageFilterHeightCollapse,fieldsetBrand,fieldsetProduct);
                        var totalContentHeightMinus = (totalHeight - languageOptionsHeight +100);
                        $(".download-center .content-wrapper").css('min-height',totalContentHeightMinus+'px');
                    }
                }
                $('.plus-sign').click(function(){
                    $(".language-options").toggle(300,function(){
                        if($(".language-options").css('display') == 'block'){
                            $('.plus-sign').hide();
                            $('.minus-sign').show();
                            modifyContentHeight('expand');
                        }
                    });
                });
                $('.minus-sign').click(function(){
                    $(".language-options").toggle(300,function(){
                        if($(".language-options").css('display') == 'none'){
                            $('.minus-sign').hide();
                            $('.plus-sign').show();
                            modifyContentHeight('collapse');
                        }
                    });
                });
                $('.other-text').click(function(){
                    $(".language-options").toggle(300,function(){
                        if($(".language-options").css('display') == 'block'){
                            $('.minus-sign').show();
                            $('.plus-sign').hide();
                            modifyContentHeight('expand');
                        }else{
                            $('.minus-sign').hide();
                            $('.plus-sign').show();
                            modifyContentHeight('collapse');
                        }
                    });
                });

                // remove search result
                $(".language-options input:checkbox").change(function() {
                    var ischecked= $(this).is(':checked');
                    if(!ischecked)
                        $('#clear-button-'+ $(this).val() + ' a.close').trigger("click");
                });
                // Scroll to top
                $("input:checkbox").on('click',function(){
                    $('html,body').animate({scrollTop: $(".view-download-center").offset().top - 300}, 'slow');
                });
                if($(".language-options div input:checkbox:checked").length>0){
                    $(".language-options").show();
                    $('.minus-sign').show();
                    $('.plus-sign').hide();
                    modifyContentHeight('expand');
                }else{
                    $(".language-options").hide();
                    $('.minus-sign').hide();
                    $('.plus-sign').show();
                    modifyContentHeight('collapse');
                }

                //set list button as default
                $('.toggle-view-menu .grid-view-button').removeClass('selected');
                $('.toggle-view-menu .list-view-button').addClass('selected');
                //remove overlay if its already present
                $('.overlay').remove();
                $(".download-center").append("<div id='overlay' class='overlay' style='display:none;'><div class='overlay-body'></div></div>");

                //append filter HTML
                $('.overlay .overlay-body').append("<div class='close-overlay'>x</div>");
                $('.overlay .overlay-body').append("<div class='overlay-language-filter'><i class='right-arrow'></i><div class='language-label'>File Language</div>" + languageHtml + "</div>");
                $('.overlay .overlay-body').append("<div class='overlay-brand-filter'><i class='right-arrow'></i><div class='brand-label'>Select Brand</div>" + brandHtml + "</div>");
                $('.overlay .overlay-body').append("<div class='overlay-product-filter'><i class='right-arrow'></i><div class='product-label'>Select Product</div>" + productHtml + "</div>");
                $('.overlay .overlay-body').append("<div class='overlay-file-filter'><i class='right-arrow'></i><div class='file-label'>File Type</div>" + fileHtml + "</div>");
                $('.overlay .overlay-body').append("<div class='overlay-back-container'><div class='overlay-back'><a href='#close' id='dc-overlay-filter-close' class='cta component-cta full wide light title-cta js-open-filter dc-overlay-filter-close'> <span class='copy'>Back to Results</span></div></div></div");
                //add slide effect
                var language_wrapper = $('.overlay-language-filter .fieldset-wrapper');
                $('.overlay-language-filter .language-label').click(function () {
                    language_wrapper.slideToggle();
                });

                var file_wrapper = $('.overlay-file-filter .fieldset-wrapper');
                $('.overlay-file-filter .file-label').click(function () {
                    file_wrapper.slideToggle();
                });

                var product_wrapper = $('.overlay-product-filter .fieldset-wrapper');
                $('.overlay-product-filter .product-label').click(function () {
                    product_wrapper.slideToggle();
                });
                var brand_wrapper = $('.overlay-brand-filter .fieldset-wrapper');
                $('.overlay-brand-filter .brand-label').click(function () {
                    brand_wrapper.slideToggle();
                });
                //close
                $(".close-overlay").click(function () {
                    $('.overlay').animate({width: 'toggle'});
                });
                $(".overlay-back").click(function (e) {
                    e.preventDefault();
                    $('.overlay').animate({width: 'toggle'});
                });
                //check if filter is checked and display filter on true
                var language_checked = $(".overlay-language-filter .fieldset-wrapper div[data-drupal-selector='edit-field-file-language-value'] div");
                if (language_checked) {
                    if ((language_checked).find('input').is(":checked")) {
                        language_wrapper.css('display', 'block');
                    }
                    else {
                        language_wrapper.css('display', 'none');
                    }
                    var file_checked = $(".overlay-file-filter .fieldset-wrapper div[data-drupal-selector='edit-tid'] div ");
                    if ((file_checked).find('input').is(":checked")) {
                        file_wrapper.css('display', 'block');
                    }
                    else {
                        file_wrapper.css('display', 'none');
                    }
                    var product_checked = $(".overlay-product-filter .fieldset-wrapper div div ");
                    if ((product_checked).find('input').is(":checked")) {
                        product_wrapper.css('display', 'block');
                    }
                    else {
                        product_wrapper.css('display', 'none');
                    }
                    var brand_checked = $(".overlay-brand-filter .fieldset-wrapper div div ");
                    if ((brand_checked).find('input').is(":checked")) {
                        brand_wrapper.css('display', 'block');
                    }
                    else {
                        brand_wrapper.css('display', 'none');
                    }
                    //hide overlay on selction
                    $(".overlay-language-filter div[data-drupal-selector='edit-field-language-target-id'] div").click(function () {
                        $('.overlay').delay(1000).hide();
                    });
                    $(".overlay-file-filter div[data-drupal-selector='edit-tid'] div").click(function () {
                        $('.overlay').delay(1000).hide();
                    });
                    $(".overlay-product-filter .fieldset-wrapper  div div").click(function () {
                        $('.overlay').delay(1000).hide();
                    });
                    $(".overlay-brand-filter .fieldset-wrapper  div div").click(function () {
                        $('.overlay').delay(1000).hide();
                    });
                    // List View and Mobile Filter.
                    if ($(window).width() < 768) {
                        if ($(view_selector).hasClass('dc-grid-view')) {
                            $(view_selector).removeClass('dc-grid-view');
                            $(view_selector).addClass('dc-list-view');
                        }
                        if ($('.mobile-filter-buttons').length == 0) {
                            $(".download-center").append(mobileFilter);
                            $(".dc-overlay-filter").click(function (e) {
                                e.preventDefault();
                                $('.overlay').animate({width: 'toggle'});
                            });
                        }
                        else {
                            $(".mobile-filter-buttons").css("display", "block");
                        }
                        // hide filters
                        fieldsetLanguage.hide();
                        fieldsetBrand.hide();
                        fieldsetFile.hide();
                        fieldsetProduct.hide();

                    }
                    else {
                        $(".mobile-filter-buttons").css("display", "none");
                        $(".overlay").css("display", "none");
                        fieldsetLanguage.show();
                        fieldsetBrand.show();
                        fieldsetFile.show();
                        fieldsetProduct.show();
                    }
                    if ($(window).width() >= 768 && $(window).width() <= 823) {
                        if ($(view_selector).hasClass('dc-grid-view')) {
                            $(view_selector).removeClass('dc-grid-view');
                            $(view_selector).addClass('dc-list-view');
                        }
                    }
                }
                $(window).on('resize', function () {
                    if ($(window).width() < 768) {
                        if ($(view_selector).hasClass('dc-grid-view')) {
                            $(view_selector).removeClass('dc-grid-view');
                            $(view_selector).addClass('dc-list-view');
                        }
                        if ($('.mobile-filter-buttons').length == 0) {
                            $(".download-center").append(mobileFilter);
                            $(".dc-overlay-filter").click(function (e) {
                                e.preventDefault();
                                $('.overlay').animate({width: 'toggle'});
                            });
                        }
                        else {
                            $(".mobile-filter-buttons").css("display", "block");
                        }
                        fieldsetLanguage.hide();
                        fieldsetBrand.hide();
                        fieldsetFile.hide();
                        fieldsetProduct.hide();
                    }
                    else {
                        $(".mobile-filter-buttons").css("display", "none");
                        $(".overlay").css("display", "none");
                        modifyFilters(fieldsetLanguage,fieldsetBrand,fieldsetProduct);
                        fieldsetLanguage.show();
                        fieldsetBrand.show();
                        fieldsetFile.show();
                        fieldsetProduct.show();
                    }
                    if ($(window).width() >= 768 && $(window).width() <= 823) {
                        if ($(view_selector).hasClass('dc-grid-view')) {
                            $(view_selector).removeClass('dc-grid-view');
                            $(view_selector).addClass('dc-list-view');
                        }
                    }
                });
            });
        }
    };
}(jQuery, Drupal, drupalSettings));