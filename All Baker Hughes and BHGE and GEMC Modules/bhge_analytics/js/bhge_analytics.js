/**
 * @file
 * BHGE Analytics libraries
 *
 */
(function ($, Drupal, settings) {
  "use strict";
  Drupal.behaviors.BhgeAnalytics = { //the name of our behavior
    attach: function (context, settings) {
      if (typeof drupalSettings.localanalytics.custid.dimension3 != "undefined") {
      ga('create','UA-138892245-1','auto');
      ga('set','dimension3',drupalSettings.localanalytics.custid.dimension3);
      ga('set','dimension2',drupalSettings.localanalytics.custid.dimension2);
      ga('set','dimension1',drupalSettings.localanalytics.custid.dimension1);
      ga('send', 'pageview');
       }
       else {
         // var myVar = setInterval(callgoogle, 5000);
       }

    function callgoogle() {
      if(drupalSettings.localanayltics.custid.sso > 0) {
      ga('set','dimension3',drupalSettings.localanalytics.custid.dimension3);
      ga('set','dimension2',drupalSettings.localanalytics.custid.dimension2);
      ga('set','dimension1',drupalSettings.localanalytics.custid.dimension1);
      }
    }
  }
  };
})(jQuery, Drupal, drupalSettings);

function myTimer() {
  var d = new Date();
  document.getElementById("demo").innerHTML = d.toLocaleTimeString();
}