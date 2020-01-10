/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.digital_binder = {
    attach: function () {

      var submitButton = $('.bhge-digital-binder-reorder-form input.form-submit');
      // Code for icons.
      // Get file type.
      $('.reorder-binder').each(function () {
        var iconDiv = generateIcon('pdf');
        //add icon only once
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
      $("div.reorder-binder-drag").wrapAll("<div class='reorder-binder-sortable'></div>");
      $(".bhge-digital-binder-reorder-form .reorder-binder-sortable").sortable({
        items: '> .reorder-binder-drag',
        connectWith: '.reorder-binder-sortable',
        cursor: "move",
        placeholder: 'ui-state-drop',
      });
      $(".bhge-digital-binder-reorder-form .reorder-binder-sortable").disableSelection();
      $("form.bhge-digital-binder-reorder-form input[type='submit']").click(function () {
        var fileWeight = 1;
        $('.reorder-binder.reorder-binder-drag input[type="number"]').each(function () {
          $(this).val(fileWeight);
          fileWeight++;
        });
      });
      $(document).ready(function () {
        submitButton.click(function () {
          $('.ajax-through-js').show();
        });

        function modifyButtons(searchListHeight) {
          $('.reorder-btn-top').css("bottom", $('.bhge-digital-binder-reorder-form').outerHeight() + 15 + 'px');
          $('.reorder-btn-top').css("display", "block");
        }
        if ($(window).width() < 751) {
          $('.reorder-btn').hide();
          $(".mobile-filter-buttons").css("display", "block");
          if ($('input[data-drupal-selector="edit-submit"]').length == 0) {
            $('#dc-overlay-filter-merge').hide();
            $('.bhge-digital-binder-reorder-form').css("margin-bottom", "70px");
            $('#edit-no-files').css("margin-top", "110px");
          }
          $('#dc-overlay-filter-merge span.merge').click(function () {
            $('input[data-drupal-selector="edit-submit"]').trigger('click');
          });
          $('#dc-overlay-filter-go-back span.go-back').click(function () {
            $('input[data-drupal-selector="edit-go-back"]').trigger('click');
          });
          $('.reorder-btn-top').css("bottom", searchListHeight + 'px');
        } else {
          $('.reorder-btn').show();
          $(".mobile-filter-buttons").css("display", "none");
        }
        $(window).on('resize', function () {
          if ($(window).width() < 751) {
            $('.reorder-btn').hide();
            $(".mobile-filter-buttons").css("display", "block");
            if ($('input[data-drupal-selector="edit-submit"]').length == 0) {
              $('#dc-overlay-filter-merge').hide();
              $('.bhge-digital-binder-reorder-form').css("margin-bottom", "70px");
              $('#edit-no-files').css("margin-top", "110px");
            }
            $('#dc-overlay-filter-merge span.merge').click(function () {
              $('input[data-drupal-selector="edit-submit"]').trigger('click');
            });
            $('#dc-overlay-filter-go-back span.go-back').click(function () {
              $('input[data-drupal-selector="edit-go-back"]').trigger('click');
            });
          } else {
            $('.reorder-btn').show();
            $(".mobile-filter-buttons").css("display", "none");
            modifyButtons();
          }
        });
        modifyButtons();
      });
    }
  };
}(jQuery, Drupal));