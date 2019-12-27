/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.digital_binder = {
    attach: function () {

      $('.reorder-btn-top').css("display", "block");
      var searchListHeight = $('.bhge-digital-binder-reorder-form').outerHeight() + 15;
      var submitButton = $('.bhge-digital-binder-reorder-form input.form-submit');
      $('.reorder-btn-top').css("bottom", searchListHeight + 'px');
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
      });
    }
  };
}(jQuery, Drupal));