/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.main_menu = {
        attach: function () {
            /* class for css of menu with icon */
            if($('li.navigation-item a[class*="menu-icon-"]').length > 0){
                $('li.navigation-item a[class*="menu-icon-"]').closest('li').addClass('menu-with-icon');
            }
        }
    }

}(jQuery, Drupal));