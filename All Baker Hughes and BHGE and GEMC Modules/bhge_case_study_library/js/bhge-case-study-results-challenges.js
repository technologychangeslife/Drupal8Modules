/*globals Drupal:false ,jQuery:false */
(function ($) {
    "use strict";
    Drupal.behaviors.bhge_case_study_library_results_challenges = {
        attach: function () {
            var open = false;
            $("#tabTitle" ).click(function() {
                if (open) {
                    closeNav1();
                    open = false;
                }
                else {
                    $("#resultsTab").css('width', '80%');
                    $("#tabTitle").css('right', '80%');
                    $('[data-component=results-and-challeges]').css({'width':'100%', 'background': 'transparent'});
                    open = true;
                }
            });

            $(".closebtn" ).click(function() {
                closeNav1();
            });
            function closeNav1() {
                $("#tabTitle").css('right', '0');
                $("#resultsTab").css('width' ,'3px');
            }
        }
    };
})(jQuery, Drupal);