/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    $('document').ready(function () {
        $('[data-component=block-announcement] ').css("z-index", '2');
        $('.wrapper').append("<style>@media(min-width:1024px){[data-component=n01-navigation] .navigation-wrapper{top:50px}[data-component=n01-navigation] .navigation-wrapper.hide-header,[data-component=n01-navigation] .navigation-wrapper.scrolling-down,[data-component=n01-navigation] .navigation-wrapper.scrolling-up{top:-130px}}@media(max-width:1024px){[data-component=n01-navigation] .navigation-wrapper {top:56px}}</style>");
    });
}(jQuery, Drupal));