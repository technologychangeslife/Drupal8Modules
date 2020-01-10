/**
 * @file
 * @fileGlobals Drupal:false ,jQuery:false */

(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.digital_binder = {
    attach: function () {
      var searchResult = $('fieldset[data-drupal-selector="edit-search-result"]');
      var searchKeyword = $('input[data-drupal-selector="edit-document-title"]');
      var searchKeywordIcon = $('input[data-drupal-selector="edit-actions-search"]');
      var fieldsetBrand = $('fieldset[data-drupal-selector="edit-brands"]');
      var fieldsetFile = $('fieldset[data-drupal-selector="edit-file-types"]');
      var productTypeBrand = $('fieldset[data-drupal-selector="edit-product-type-fieldset"]');
      var submitButton = $('input[data-drupal-selector="edit-submit"]');
      var topSubmitButton = $('input[data-drupal-selector="edit-submit-2"]');
      var resetButton = $('input[data-drupal-selector="edit-reset"]');
      var brandFilterHeight = fieldsetBrand.outerHeight();
      var fileFilterHeight = fieldsetFile.outerHeight();
      var productTypeBrandHeight = productTypeBrand.outerHeight();
      var submitButtonHeight = submitButton.outerHeight();
      var searchResultHeight = searchResult.outerHeight();
      var searchResultInnerHeight = searchResult.innerHeight();

      var fileHtml = fieldsetFile.html();
      var productHtml = productTypeBrand.html();
      var brandHtml = fieldsetBrand.html();

      var totalHeight = brandFilterHeight + fileFilterHeight + productTypeBrandHeight;
      var view_selector = $('.digital-binder .content-wrapper');

      $(window).on("load", function () {
        $(window).scrollTop(0);
        setContentHeight(searchResult.outerHeight(), totalHeight);
        $('fieldset[data-drupal-selector="edit-search-result"] div.search-results-btn').css("pointer-events", "auto");
      });

      function setContentHeight(contentHeight, totalHeight) {
        if (contentHeight >= 1300) {
          contentHeight = 1300;
          $('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper').css("height", 1300 + 'px');
        } else {
          $('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper').css("height", 'auto');
          contentHeight = $('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper').height();
        }
        if (contentHeight > totalHeight) {
          view_selector.css({
            'min-height': (contentHeight + 200) + 'px'
          });
        } else {
          view_selector.css({
            'min-height': (totalHeight + 400) + 'px'
          });
        }
        $('.edit-review').css("margin-top", (contentHeight + ((searchKeyword.outerHeight()) + ($('#edit-language-help-text').outerHeight() + 5) + 90)) + 'px');
      }
      $(document).ready(function () {
        $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight() + 30) + ($('#edit-language-help-text').outerHeight() + 5) + 30) + 'px');
        searchResult.show();
        setContentHeight(searchResult.outerHeight(), totalHeight);
        $(document).ajaxComplete(function () {
          $('fieldset[data-drupal-selector="edit-search-result"] div.search-results-btn').css("pointer-events", "auto");
          $('.ajax-through-js').hide();
        });
        $(document).ajaxStart(function () {
          $('.ajax-through-js').show();
        });
        $('input[data-drupal-selector="edit-reset"]').click(function () {
          $('fieldset[data-drupal-selector="edit-search-result"] input[type="checkbox"]').prop("checked", false);
        });

        $(".box").unbind().click(function () {
          $(this).next('.nested').toggleClass("active");
          $(this).toggleClass("check-box");
          $('fieldset[data-drupal-selector="edit-file-types"]').css("margin-top", ((fieldsetBrand.outerHeight() + 30) + (productTypeBrand.outerHeight() + 30)) + 'px');
          setContentHeight(searchResult.outerHeight(), (fieldsetBrand.outerHeight() + fieldsetFile.outerHeight() + productTypeBrand.outerHeight()));
          var idValue = $(this).attr('id');
          var active = $(this).next('.nested');
          if (!active.hasClass('active')) {
            $('.' + idValue + ' div#edit-product-brands-test-' + idValue + ' input[type="checkbox"]').each(function () {
              if ($(this).is(':checked')) {
                $(this).trigger('click');
              }
            });
          }
          $('#edit-product-types-' + idValue).trigger('click');
        });
        searchResult.show();
        // Dynamic Filtering of product types and brands
        $('fieldset[data-drupal-selector="edit-brands"] input[type="checkbox"]').each(function () {
          $(this).wrap("<span class='brands-dynamic'></span>");
        });
        $(".brands-dynamic").unbind().click(function () {
          var nothingChecked = true;
          $('li.model-number-li').addClass("product-brands-inactive");
          $('fieldset[data-drupal-selector="edit-brands"] input').each(function () {
            if ($(this).is(':checked')) {
              $('li.' + $(this).val()).removeClass("product-brands-inactive");
              nothingChecked = false;
            }
          });
          if (nothingChecked) {
            $('li.model-number-li').removeClass("product-brands-inactive");
          } else {
            $('.product-types-brand .product-brands-inactive').each(function () {
              if ($(this).children('span.box').hasClass('check-box')) {
                $(this).children('span.check-box').click();
              }
            });
          }
        });

        function modifyFilters(brandHeight, fileTypeHeight, productTypeBrand) {
          // Order Filters
          // Product.
          $('fieldset[data-drupal-selector="edit-product-type-fieldset"]').css("margin-top", (brandHeight.outerHeight() + 30) + 'px');
          // File Type.
          $('fieldset[data-drupal-selector="edit-file-types"]').css("margin-top", ((brandHeight.outerHeight() + 30) + (productTypeBrand.outerHeight() + 30)) + 'px');
        }
        $("label .btn-add").click(function () {
          var id = $(this).attr("data-id");
          $(".btn-add-" + id).css("display", "none");
          $(".btn-remove-" + id).css("display", "flex");
        });
        $(".btn-remove").click(function () {
          var id = $(this).attr("data-id");
          $(".btn-remove-" + id).css("display", "none");
          $(".btn-add-" + id).css("display", "flex");
        });
        topSubmitButton.click(function () {
          $('.ajax-through-js').show();
        });
        submitButton.click(function () {
          $('.ajax-through-js').show();
        });
        modifyFilters(fieldsetBrand, fieldsetFile, productTypeBrand);
        fieldsetBrand.show();
        productTypeBrand.show();
        fieldsetFile.show();
        searchKeyword.show();
        searchKeywordIcon.show();
        resetButton.show();
        $('#edit-language-help-text').show();
        if ($('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper div').hasClass('form-type-checkbox')) {
          $('input[data-drupal-selector="edit-submit"]').css("display", "block");
          topSubmitButton.show();
        } else {
          $('.edit-review').css("margin-top", '0px');
          submitButton.hide();
          //topSubmitButton.hide();
          $('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper div').html("<div class='no-results'>No results found</div>");
        }

        // Mobile Version

        //remove overlay if its already present
        $('.overlay').remove();
        $("section.digital-binder").append("<div id='overlay' class='overlay' style='display:none;'><div class='overlay-body'></div></div>");
        //append filter HTML
        $('.overlay .overlay-body').append("<div class='close-overlay'>x</div>");
        $('.overlay .overlay-body').append("<div class='overlay-brand-filter'><i class='right-arrow'></i><div class='brand-label'>Select Brand</div>" + brandHtml + "</div>");
        $('.overlay .overlay-body').append("<div class='overlay-product-filter'><i class='right-arrow'></i><div class='product-label'>Select Product</div><ul id='myUL'>" + productHtml + "</ul></div>");
        $('.overlay .overlay-body').append("<div class='overlay-file-filter'><i class='right-arrow'></i><div class='file-label'>File Type</div>" + fileHtml + "</div>");
        $('.overlay .overlay-body').append("<div class='overlay-back-container'><div class='overlay-back'><a href='#close' id='dc-overlay-filter-close' class='cta component-cta full wide light title-cta js-open-filter dc-overlay-filter-close'> <span class='copy'>Back to Results</span></div></div></div");
        //add slide effect

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
          $('.overlay').animate({
            width: 'toggle'
          });
        });
        $(".overlay-back").click(function (e) {
          e.preventDefault();
          $('.overlay').animate({
            width: 'toggle'
          });
        });
        $(".dc-overlay-filter-form").unbind("click").click(function (e) {
          e.preventDefault();
          $('.overlay').animate({
            width: 'toggle'
          });
        });
        //check if filter is checked and display filter on true
        var brand_checked = $("fieldset[data-drupal-selector='edit-brands'] .fieldset-wrapper div div");
        if (brand_checked) {
          if ((brand_checked).find('input').is(":checked")) {
            brand_wrapper.css('display', 'block');
          } else {
            brand_wrapper.css('display', 'none');
          }
          var file_checked = $("fieldset[data-drupal-selector='edit-file-types'] .fieldset-wrapper div div ");
          if ((file_checked).find('input').is(":checked")) {
            file_wrapper.css('display', 'block');
          } else {
            file_wrapper.css('display', 'none');
          }
          var product_checked = $(".overlay-product-filter .fieldset-wrapper li ");
          if ((product_checked).find('span').hasClass('check-box')) {
            product_wrapper.css('display', 'block');
          } else {
            product_wrapper.css('display', 'none');
          }

          //hide overlay on selction
          $(".overlay-file-filter .fieldset-wrapper div div").click(function () {
            $('.overlay').delay(8000).hide();
          });
          $(".overlay-product-filter .fieldset-wrapper div div").click(function () {
            $('.overlay').delay(1000).hide();
          });
          $(".overlay-brand-filter .fieldset-wrapper div div").click(function () {
            $('.overlay').delay(8000).hide();
          });
          $(".overlay-product-filter .fieldset-wrapper .box").click(function () {
            $('.overlay').delay(8000).hide();
          });
          // List View and Mobile Filter.
          if ($(window).width() < 751) {
            $('input[data-drupal-selector="edit-reset"]').css("margin-top", (searchKeyword.outerHeight() + 25) + 'px');
            $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight()) + ($('#edit-language-help-text').outerHeight() + 10) + 40) + 'px');
            $(".overlay .box").click(function () {
              var bboxId = $(this).attr('id');
              $('.model-number-li .' + bboxId).toggleClass("active");
              $(this).toggleClass("check-box");
              $('.digital-binder-first-form .model-number-li #' + bboxId).toggleClass("check-box");
              var active = $(this).next('.nested');
              if (!active.hasClass('active')) {
                $('.' + bboxId + ' input[type="checkbox"]').each(function () {
                  if ($(this).is(':checked')) {
                    $(this).trigger('click');
                  }
                });
              }
              $('#edit-product-types-' + bboxId).trigger('click');
            });

            $('input[type="checkbox"]').click(function () {
              var fieldSelector = $(this).attr('data-drupal-selector');
              if ($(this).is(":checked")) {
                $('input[data-drupal-selector="' + fieldSelector + '"]').attr("checked", "checked");
              } else {
                $('input[data-drupal-selector="' + fieldSelector + '"]').removeAttr("checked");
              }
            });

            $(".mobile-filter-buttons").css("display", "block");
            $(".edit-review-2 , .edit-review").css("display", "none");

            // hide filters
            fieldsetBrand.hide();
            fieldsetFile.hide();
            productTypeBrand.hide();

            $('#dc-overlay-filter-review span.review').click(function () {
              $('input[data-drupal-selector="edit-submit"]').trigger('click');
            });

          } else {
            $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight() + 30) + ($('#edit-language-help-text').outerHeight() + 5) + 30) + 'px');
            $(".mobile-filter-buttons").css("display", "none");
            $(".overlay").css("display", "none");
            $(".edit-review-2 , .edit-review").css("display", "block");
            fieldsetBrand.show();
            fieldsetFile.show();
            productTypeBrand.show();
          }
        }
        $(window).on('resize', function () {
          if ($(window).width() < 751) {
            $('input[data-drupal-selector="edit-reset"]').css("margin-top", (searchKeyword.outerHeight() + 25) + 'px');
            $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight()) + ($('#edit-language-help-text').outerHeight() + 10) + 40) + 'px');
            $(".overlay .box").click(function () {
              var bboxId = $(this).attr('id');
              $('.model-number-li .' + bboxId).toggleClass("active");
              $(this).toggleClass("check-box");
              $('.digital-binder-first-form .model-number-li #' + bboxId).toggleClass("check-box");
              var active = $(this).next('.nested');
              if (!active.hasClass('active')) {
                $('.' + bboxId + ' input[type="checkbox"]').each(function () {
                  if ($(this).is(':checked')) {
                    $(this).trigger('click');
                  }
                });
              }
              $('#edit-product-types-' + bboxId).trigger('click');
            });

            $('input[type="checkbox"]').click(function () {
              var fieldSelector = $(this).attr('data-drupal-selector');
              if ($(this).is(":checked")) {
                $('input[data-drupal-selector="' + fieldSelector + '"]').attr("checked", "checked");
              } else {
                $('input[data-drupal-selector="' + fieldSelector + '"]').removeAttr("checked");
              }
            });

            $(".mobile-filter-buttons").css("display", "block");
            $(".edit-review-2 , .edit-review").css("display", "none");
            fieldsetBrand.hide();
            fieldsetFile.hide();
            productTypeBrand.hide();

            $('#dc-overlay-filter-review span.review').click(function () {
              $('input[data-drupal-selector="edit-submit"]').trigger('click');
            });
          } else {
            $('input[data-drupal-selector="edit-reset"]').css("margin-top", '5px');
            $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight() + 30) + ($('#edit-language-help-text').outerHeight() + 5) + 30) + 'px');
            $(".mobile-filter-buttons").css("display", "none");
            $(".overlay").css("display", "none");
            $(".edit-review-2 , .edit-review").css("display", "block");
            modifyFilters(fieldsetBrand, fieldsetFile, productTypeBrand);
            fieldsetBrand.show();
            fieldsetFile.show();
            productTypeBrand.show();
          }
        });
      });
      $('#edit-document-title').keypress(function (event) {
        if (event.keyCode == '13') {
          event.preventDefault();
          $('input[data-drupal-selector="edit-actions-search"]').click();
        }
      });
      $('fieldset[data-drupal-selector="edit-search-result"] .form-checkboxes input:checkbox').each(function () {
        if ($(this).is(':checked')) {
          $('.btn-remove-' + $(this).val()).css("display", "flex");
          $('.btn-add-' + $(this).val()).hide();
        } else {
          $('.btn-remove-' + $(this).val()).hide();
          $('.btn-add-' + $(this).val()).css("display", "flex");
        }
      });
      // Code for icons.
      // Get file type.
      $('fieldset[data-drupal-selector="edit-search-result"] .form-type-checkbox').each(function () {
        var iconDiv = generateIcon('pdf');
        // Add icon only once.
        $(".download-type", this).show().html(iconDiv);
      });

      function generateIcon(type) {
        var str = "<div class=\"icon " + type + "\" data-bind=\"css: fileExtension, 'icon'\">\n" +
          "          <svg class=\"component-icon\">\n" +
          "            <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#asset\"></use>\n" +
          "          </svg>\n" +
          "          <span class=\"file-type\">.<span class=\"extension\" data-bind=\"text: fileExtension\">" + type + "</span></span>\n" +
          "        </div>\n" +
          "        <span class=\"type-text\" data-bind=\"text: fileType\"></span>\n";
        return str;
      }
    }
  };
}(jQuery, Drupal));