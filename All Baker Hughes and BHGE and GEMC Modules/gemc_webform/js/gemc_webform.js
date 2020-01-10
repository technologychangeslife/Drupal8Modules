/**
 * @file
 * JS for RMA webforms related to Reuter stokes .
 */

(function ($, Drupal) {

    'use strict';

    Drupal.behaviors.webformsettings = {
        attach: function () {

            function resizeInput() {
                $(this).attr('size', (($(this).val().length + 1) * 5));
            }

            function resizeSelect(){
                $(this).width($(this).find("option:selected").text().length * 10);
            }

            /* Reuter Stokes Measurement Solutions Product Repair Form */
            /* Reuter Stokes Measurement Solutions Credit Form */

            var addmore2 = $('[data-drupal-selector="edit-return-part-info-add-more-items"]').val();

            for (var j = 0; j <= addmore2; j++) {
                if($('[data-drupal-selector="edit-return-part-info-items-' + j + '-product-brand"]').length > 0){
                 $('[data-drupal-selector="edit-return-part-info-items-' + j + '-product-brand"]')

                    .change(resizeSelect)

                    .each(resizeSelect);
                }

                $('[data-drupal-selector="edit-return-part-info-items-' + j + '-part-number"]')

                    .keyup(resizeInput)

                    .each(resizeInput);

                $('[data-drupal-selector="edit-return-part-info-items-' + j + '-serial-number"]')

                    .keyup(resizeInput)

                    .each(resizeInput);

                $('[data-drupal-selector="edit-return-part-info-items-' + j + '-custmer-part-number"]')

                    .keyup(resizeInput)

                    .each(resizeInput);

                $('[data-drupal-selector="edit-return-part-info-items-' + j + '-hours-of-operation"]')

                    .keyup(resizeInput)

                    .each(resizeInput);

                if($('[data-drupal-selector="edit-return-part-info-items-'+ j +'-quantity"]').length > 0){
                    $('[data-drupal-selector="edit-return-part-info-items-' + j + '-quantity"]')

                    .keyup(resizeInput)

                    .each(resizeInput);
                }    
                if($('[data-drupal-selector="edit-return-part-info-items-'+ j +'-weight"]').length > 0){
                    $('[data-drupal-selector="edit-return-part-info-items-' + j + '-weight"]')

                    .keyup(resizeInput)

                    .each(resizeInput);
                }    
            }

            var nonserializeaddmore2 = $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-add-more-items"]').val();
            for (var l = 0; l <= nonserializeaddmore2; l++) {

                if($('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-product-brand"]').length > 0){
                    $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-product-brand"]')
   
                       .change(resizeSelect)
   
                       .each(resizeSelect);
                }
                $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-part-number"]')

                    .keyup(resizeInput)

                    .each(resizeInput);
                $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-qty"]')

                    .keyup(resizeInput)

                    .each(resizeInput);

                if($('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-weight"]').length > 0){
                    $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items-' + l + '-ns-weight"]')

                    .keyup(resizeInput)

                    .each(resizeInput);
                }    
            }
            // Find date picker.
            $('[data-drupal-selector="edit-return-part-info-items-0-dateof-first-use"]').attr("placeholder", "Never used");
            $('[data-drupal-selector="edit-return-part-info-items"]').find('tr').mouseover(function (e) {
                var id_row = $(this).attr('data-drupal-selector');
                if (typeof id_row != 'undefined') {
                    var id_val = id_row.split('-');
                    $('[name="return_part_info[items][' + id_val[5] + '][dateof_first_use]"]').click(function (e) {
                        $(this).datepicker().datepicker('show');
                        e.preventDefault();
                    });
                    var datevalue =$('[name="return_part_info[items][' + id_val[5] + '][dateof_first_use]"]').val();
                    if(datevalue ==''){
                        $('[name="return_part_info[items][' + id_val[5] + '][dateof_first_use]"]').val("Never Used");
                    }
                }
            });
            // return part click event
            $('[data-drupal-selector="edit-customer-return-information"] input[type=image]').mousedown(function (e) {
                var cnt = 0;
                var opadd = (this.id).split('-');
                var submit_plus_ret=1;
                $(document).ajaxComplete(function (event, request, settings) {
                    if(submit_plus_ret === 1) {
                        if (cnt === 0) {
                            var rowid = (parseInt(opadd[5]) + 1);
                            var partnumber = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][part_number]"]').val();
                            var customerpartnumber = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][custmer_part_number]"]').val();
                            var hoursofop = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][hours_of_operation]"]').val();
                            var additionalcomments = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][comments]"]').val();
                            var returnreason = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][return_reason]"]').val();
                            var dateoffirstuse = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][dateof_first_use]"]').val();
                            $('[name="return_part_info[items][' + rowid + '][dateof_first_use]"]').attr("placeholder", "Never used");

                            // Return Part info.
                            $('[name="return_part_info[items][' + rowid + '][part_number]"]').val(partnumber);
                            $('[name="return_part_info[items][' + rowid + '][custmer_part_number]"]').val(customerpartnumber);
                            $('[name="return_part_info[items][' + rowid + '][hours_of_operation]"]').val(hoursofop);
                            $('[name="return_part_info[items][' + rowid + '][comments]"]').val(additionalcomments);
                            $('[name="return_part_info[items][' + rowid + '][return_reason]"]').val(returnreason);
                            $('[name="return_part_info[items][' + rowid + '][dateof_first_use]"]').val(dateoffirstuse);

                            if($('[name="return_part_info[items][' + parseInt(rowid - 1) + '][product_brand]"]').length > 0)
                            {
                                var productbrand = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][product_brand]"]').val();
                                $('[name="return_part_info[items][' + rowid + '][product_brand]"]').val(productbrand);
                                $('[name="return_part_info[items][' + rowid + '][product_brand]"]').trigger('change');

                            }
                            if($('[name="return_part_info[items][' + parseInt(rowid - 1) + '][quantity]"]').length > 0)
                            {
                                var quantity = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][quantity]"]').val();
                                $('[name="return_part_info[items][' + rowid + '][quantity]"]').val(quantity);

                            }
                            if($('[name="return_part_info[items][' + parseInt(rowid - 1) + '][weight]"]').length > 0)
                            {
                                var weight = $('[name="return_part_info[items][' + parseInt(rowid - 1) + '][weight]"]').val();
                                $('[name="return_part_info[items][' + rowid + '][weight]"]').val(weight);

                            }
                            cnt = 1;
                            submit_plus_ret=0;
                            e.preventDefault();
                        }
                    }
                });
            });

            $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items"] input[type=image]').mousedown(function () {
                var cnt_1 = 0;
                var opadd = (this.id).split('-');
                var nonserializerowid = (parseInt(opadd[10]) + 1);
                var submit_plus=1;

                $(document).ajaxComplete(function (event, request, settings) {
                    if(submit_plus === 1) {
                        if (cnt_1 === 0) {
                            var nonserializepartnumber = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) + '][ns_part_number]"]').val();
                            var nonserializequantity = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) + '][ns_qty]"]').val();
                            var nonserializereturnreason = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) + '][ns_return_reason]"]').val();
                            var nonserializecomments = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) + '][ns_comments]"]').val();
                            
                            // Non serialized data.
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid + '][ns_qty]"]').val(nonserializequantity);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid + '][ns_part_number]"]').val(nonserializepartnumber);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid + '][ns_return_reason]"]').val(nonserializereturnreason);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid + '][ns_comments]"]').val(nonserializecomments);

                            if($('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) +  '][ns_product_brand]"]').length > 0)
                            {
                                var nonserializeproductbrand = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) +  '][ns_product_brand]"]').val();
                                $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid +  '][ns_product_brand]"]').val(nonserializeproductbrand);
                                $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid +  '][ns_product_brand]"]').trigger('change');
                            
                            }

                            if($('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) +  '][ns_weight]"]').length > 0)
                            {
                                var nonserializeweight = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + parseInt(nonserializerowid - 1) +  '][ns_weight]"]').val();
                                $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + nonserializerowid +  '][ns_weight]"]').val(nonserializeweight);

                            }

                            cnt_1=1;
                            submit_plus=0;
                        }
                    }
                });
            });

            $('[data-drupal-selector="edit-return-part-info-add-submit"]').mousedown(function () {
                var rowidreturn = $('table[data-drupal-selector="edit-return-part-info-items"]').find('tr').last().attr('data-drupal-selector').split('-');
                var partnumber = $('[name="return_part_info[items][' + rowidreturn[5] + '][part_number]"]').val();
                var customerpartnumber = $('[name="return_part_info[items][' + rowidreturn[5] + '][custmer_part_number]"]').val();
                var hoursofop = $('[name="return_part_info[items][' + rowidreturn[5] + '][hours_of_operation]"]').val();
                var additionalcomments = $('[name="return_part_info[items][' + rowidreturn[5] + '][comments]"]').val();
                var returnreason = $('[name="return_part_info[items][' + rowidreturn[5] + '][return_reason]"]').val();
                var dateoffirstuse = $('[name="return_part_info[items][' + rowidreturn[5] + '][dateof_first_use]"]').val();
                var productbrand = '';
                var quantity ='';
                var weight ='';
                if($('[name="return_part_info[items][' + rowidreturn[5] + '][product_brand]"]').length > 0)
                {
                    productbrand = $('[name="return_part_info[items][' + rowidreturn[5] + '][product_brand]"]').val();
                }
                if($('[name="return_part_info[items][' + rowidreturn[5] + '][quantity]"]').length > 0)
                {
                    quantity = $('[name="return_part_info[items][' + rowidreturn[5] + '][quantity]"]').val();
                }
                if($('[name="return_part_info[items][' + rowidreturn[5] + '][weight]"]').length > 0)
                {
                    weight = $('[name="return_part_info[items][' + rowidreturn[5] + '][weight]"]').val();
                }
                var submit = 1;
                $(document).ajaxComplete(function (event, request, settings) {
                    if(submit === 1) {
                        var addmore = $('[data-drupal-selector="edit-return-part-info-add-more-items"]').val();
                        var startrow = parseInt(addmore) + parseInt(rowidreturn[5]);
                        var startfrom = (parseInt(rowidreturn[5]));
                        for (var i = startfrom; i <= startrow; i++) {
                            $('[name="return_part_info[items][' + i + '][part_number]"]').val(partnumber);
                            $('[name="return_part_info[items][' + i + '][custmer_part_number]"]').val(customerpartnumber);
                            $('[name="return_part_info[items][' + i + '][hours_of_operation]"]').val(hoursofop);
                            $('[name="return_part_info[items][' + i + '][comments]"]').val(additionalcomments);
                            $('[name="return_part_info[items][' + i + '][return_reason]"]').val(returnreason);
                            $('[name="return_part_info[items][' + i + '][dateof_first_use]"]').val(dateoffirstuse);
                            $('[name="return_part_info[items][' + i + '][dateof_first_use]"]').attr("placeholder", "Never used");
                            $('[name="return_part_info[items][' + i + '][product_brand]"]').val(productbrand);
                            $('[name="return_part_info[items][' + i + '][quantity]"]').val(quantity);
                            $('[name="return_part_info[items][' + i + '][weight]"]').val(weight);
                        }
                        submit = 0;
                    }
                });
            });

            // For non serialized parts, when clicking on the add more buttons.
            $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-add-submit"]').mousedown(function () {
                var rowidreturn = $('table[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-items"]').find('tr').last().attr('data-drupal-selector').split('-');

                var nonserializepartnumber = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_part_number]"]').val();
                var nonserializequantity = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_qty]"]').val();
                var nonserializereturnreason = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_return_reason]"]').val();
                var nonserializecomments = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_comments]"]').val();
                var submit_part_number=1;
                var nonserializeproductbrand = '';
                var nonserializeweight ='';
                if($('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_product_brand]"]').length > 0)
                {
                    nonserializeproductbrand = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_product_brand]"]').val();
                }
                if($('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_weight]"]').length > 0)
                {
                    nonserializeweight = $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + rowidreturn[10] + '][ns_weight]"]').val();
                }
                $(document).ajaxComplete(function (event, request, settings) {
                    if(submit_part_number=== 1) {
                        var nonserializeaddmore = $('[data-drupal-selector="edit-part-number-and-quantity-of-non-serialized-parts-add-more-items"]').val();
                        var startrow = parseInt(nonserializeaddmore) + parseInt(rowidreturn[10]);
                        var startfrom = (parseInt(rowidreturn[10]));
                        for (var i = startfrom; i <= startrow; i++) {
                            // Non serialized data.
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_qty]"]').val(nonserializequantity);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_part_number]"]').val(nonserializepartnumber);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_return_reason]"]').val(nonserializereturnreason);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_comments]"]').val(nonserializecomments);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_product_brand]"]').val(nonserializeproductbrand);
                            $('[name="part_number_and_quantity_of_non_serialized_parts[items][' + i + '][ns_weight]"]').val(nonserializeweight);
                        }
                        submit_part_number= 0;
                    }
                });
            });

            //adjust width for error messages
            let formWidth = $("form.webform-submission-form").width();
            if ($("div.messages--error").length > 0) {
                $("form.webform-submission-form div[data-drupal-messages] div.messages--error").css("width", formWidth + "px");
            }
            $(window).on('resize', function () {
                let formWidthResize = $("form.webform-submission-form").width();
                if ($("div.messages--error").length > 0) {
                    $("form.webform-submission-form div[data-drupal-messages] div.messages--error").css("width", formWidthResize + "px");
                }
            });
            $('.webform-button--submit').click(function (e) {
                var check = setInterval(function () {
                    if ($('.ui-dialog-titlebar-close').length > 0) {
                        clearInterval(check);
                        $('.ui-dialog-titlebar-close').click(function (e) {
                            location.reload();
                            e.preventDefault();
                        });
                    }
                }, 100);
            });
            $('input[type=file]').click(function (e) {

                $('.webform-button--preview').attr("disabled", true);
                $('.webform-button--previous').attr("disabled", true);
                $('.webform-button--next').attr("disabled", true);
                $('input[type=submit]').css("cursor", 'not-allowed');

                $(document).ajaxComplete(function () {
                    $('.webform-button--preview').attr("disabled", false);
                    $('.webform-button--previous').attr("disabled", false);
                    $('.webform-button--next').attr("disabled", false);
                    $('input[type=submit]').css("cursor", 'pointer');

                });
            });

            if ($('[data-webform-key="customer_return_information"]').length > 0) {
                $('.webform-button--next,.webform-button--preview').click(function (e) {
                    $(document).ajaxComplete(function (event, request, settings) {
                        var rowid = $('[data-drupal-selector="edit-return-part-info-items"]').find('tr');
                        $.each(rowid, function (index, row) {
                            var rowName = $(row).attr('data-drupal-selector');
                            if (typeof rowName != 'undefined') {
                                var id_val_row = rowName.split('-');
                                if ($('[name="return_part_info[items][' + id_val_row[5] + '][part_number]"]').val().length !== 0
                                    && $('[name="return_part_info[items][' + id_val_row[5] + '][serial_number]"]').val().length === 0) {
                                    $('[name="return_part_info[items][' + id_val_row[5] + '][serial_number]"]').addClass("error");
                                }
                                else {
                                    if ($('[name="return_part_info[items][' + id_val_row[5] + '][serial_number]"]').hasClass("error")) {
                                        $('[name="return_part_info[items][' + id_val_row[5] + '][serial_number]"]').removeClass("error");
                                    }
                                }
                            }
                        });

                    });
                });
            }

            if ($('.webform-progress-tracker li:first-child').hasClass('is-active')){
                $('.field--name-body.field--type-text-with-summary').show();
            }
            else {
                $('.field--name-body.field--type-text-with-summary').hide();
            }

            /* hide previous button for customer information tab in RTB form */
            if($('form.webform-submission-request-for-take-back-form-form').length > 0)
            {
                if($('div[data-webform-key="customer_information"]').length > 0)
                {
                    var action_element = $('div[data-webform-key="customer_information"]').siblings('div[data-drupal-selector="edit-actions"]');
                    if(action_element.length > 0){
                        action_element.find('input.webform-button--previous.js-form-submit').css('visibility','hidden');
                    }
                }
            }
            
        }
    };

})(jQuery, Drupal);