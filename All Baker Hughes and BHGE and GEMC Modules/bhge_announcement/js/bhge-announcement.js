/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    $('document').ready(function () {
       $('.wrapper').append("<style>@media(min-width:1024px){[data-component=n01-navigation] .navigation-wrapper.has-intranet-menu{top:100px}[data-component=n01-navigation] .navigation-wrapper.hide-header,[data-component=n01-navigation] .navigation-wrapper.scrolling-down,[data-component=n01-navigation] .navigation-wrapper.scrolling-up{top:-130px}}@media(max-width:1024px){[data-component=n01-navigation] .navigation-wrapper {top:56px}}</style>");
    });
}(jQuery, Drupal));