/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.announcement = {
        attach: function () {
            var gallery_menu_selector = $('section.sidebar-filter .section-wrapper');
           if(gallery_menu_selector.length > 0){
                var field = 'type';
                var url = window.location.href;
                if(url.indexOf('?' + field + '=') != -1){
                    $('html, body').animate({
                        scrollTop: gallery_menu_selector.offset().top
                    }, 2000);
                }
            }
        }
    }
}(jQuery, Drupal));