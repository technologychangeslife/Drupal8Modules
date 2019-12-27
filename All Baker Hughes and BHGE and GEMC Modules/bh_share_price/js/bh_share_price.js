/*globals Drupal:false ,jQuery:false */
(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.bh_share_price = {
        attach: function () {
         if($('.bh-header__desktop').length > 0) {
                var shareVal = $('ul.bh-menu__main').find('.bh-shareprice').text();
                if (shareVal.length > 0) {
                    var num = shareVal.match(/[\d\.]+/g);
                    var ticker_text = shareVal.match(/[A-Za-z]+/g);
                    if (num != null) {
                        var number = num.toString();
                        if (number !== drupalSettings.bh_share_price.share_val) {
                            if (ticker_text[0].length > 0) {
                                $('ul.bh-menu__main').find('.bh-shareprice').text(ticker_text[0] + ' $' + drupalSettings.bh_share_price.share_val);
                            }
                        }
                    }
                }
            }
            if ($('.bh-header__mobile').length > 0) {
                var shareVal = $('ul.bh-menu__external').find('.bh-shareprice').text();
                if (shareVal.length > 0) {
                    var num = shareVal.match(/[\d\.]+/g);
                    var ticker_text = shareVal.match(/[A-Za-z]+/g);
                    if (num != null) {
                        var number = num.toString();
                        if (number !== drupalSettings.bh_share_price.share_val) {
                            if (ticker_text[0].length > 0) {
                                $('ul.bh-menu__main').find('.bh-shareprice').text(ticker_text[0] + ' $' + drupalSettings.bh_share_price.share_val);
                             }
                        }
                    }
                }
            }
         }
    };
})(jQuery, Drupal,drupalSettings);
