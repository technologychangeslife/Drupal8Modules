/*globals Drupal:false ,jQuery:false */
(function ($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.bh_share_price = {
    attach: function () {
      var time = new Date().getTime();
      $.ajax({
        type: "GET",
        url: "/get-stock-price?_format=json&" + time
      }).done(function (data) {
        if ($('.bh-header__desktop').length > 0) {
          $('ul.bh-menu__main').find('.bh-shareprice').text(data.symbol + ' $' + data.lastTrade.toFixed(2));
        }
        if ($('.bh-header__mobile').length > 0) {
          $('ul.bh-menu__main').find('.bh-shareprice').text(data.symbol + ' $' + data.lastTrade.toFixed(2));
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);