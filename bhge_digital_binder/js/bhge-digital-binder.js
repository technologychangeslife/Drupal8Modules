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
      var brandFilterHeight = fieldsetBrand.outerHeight();
      var fileFilterHeight = fieldsetFile.outerHeight();
      var productTypeBrandHeight = productTypeBrand.outerHeight();
      var submitButtonHeight = submitButton.outerHeight();
      var searchResultHeight = searchResult.outerHeight();
      var searchResultInnerHeight = searchResult.innerHeight();
      var totalHeight = brandFilterHeight + fileFilterHeight + productTypeBrandHeight;
      var view_selector = $('.digital-binder .content-wrapper');
      var toggler = document.getElementsByClassName("box");
      var i;

      for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function () {
          this.parentElement.querySelector(".nested").classList.toggle("active");
          this.classList.toggle("check-box");
          // File Type.
          $('fieldset[data-drupal-selector="edit-file-types"]').css("margin-top", ((fieldsetBrand.outerHeight() + 30) + (productTypeBrand.outerHeight() + 30)) + 'px');
          setContentHeight(searchResult.outerHeight(), (fieldsetBrand.outerHeight() + fieldsetFile.outerHeight() + productTypeBrand.outerHeight()));
        });
      }
      $(window).on("load", function () {
        $(window).scrollTop(0);
        setContentHeight(searchResult.outerHeight(), totalHeight);
        $('fieldset[data-drupal-selector="edit-search-result"] .btn-add').css("display", "flex");
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
        $('.edit-review').css("margin-top", (contentHeight + (searchKeyword.outerHeight() + 90)) + 'px');
      }
      $(document).ready(function () {
        $('fieldset[data-drupal-selector="edit-search-result"]').css("margin-top", ((searchKeyword.outerHeight() + 30) + 40) + 'px');
        searchResult.show();
        setContentHeight(searchResult.outerHeight(), totalHeight);
        $(document).ajaxComplete(function () {
          $('fieldset[data-drupal-selector="edit-search-result"] div.search-results-btn').css("pointer-events", "auto");
          $('.ajax-through-js').hide();
        });
        $(document).ajaxStart(function () {
          $('.ajax-through-js').show();
        });
        $(".box").click(function () {
          var idValue = $(this).attr('id');
          var active = $(this).next('.nested');
          if (!active.hasClass('active')) {
            $('.' + idValue + ' input[type="checkbox"]').each(function () {
              if ($(this).is(':checked')) {
                $(this).trigger('click');
              }
            });
          }
          $('#edit-product-types-' + idValue).trigger('click');
        });
        searchResult.show();

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
        if ($('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper div').hasClass('form-type-checkbox')) {
          $('input[data-drupal-selector="edit-submit"]').css("display", "block");
          topSubmitButton.show();
        } else {
          $('.edit-review').css("margin-top", '0px');
          submitButton.hide();
          topSubmitButton.hide();
          $('fieldset[data-drupal-selector="edit-search-result"] .fieldset-wrapper div').html("<div class='no-results'>No results found</div>");
        }
      });
      $('#edit-document-title').keypress(function (event) {
        if (event.keyCode == '13') {
          event.preventDefault();
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