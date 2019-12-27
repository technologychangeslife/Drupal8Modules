(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.events_summary = {
    attach: function (context, settings) {
      var theme_path = drupalSettings.bhge_upcoming_events.theme_path;

      $(document).ready(function () {
        if ($(".atcb-link").length > 0) {
          $(".atcb-link").append("<img src='" + theme_path + "/image/event-summary/calendar.png' alt='calendar' class='component-icon social-icon-calendar'>");
          $(".atcb-link").show();
        }
      });

    }
  };
})(jQuery, Drupal);