/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.world_map = {
        attach: function () {

            /* $(document).ready(function(){
                var rightBoxHeight = $(".image-map-right-box").outerHeight();
                if ($(window).width() >= 1024 ) {
                    if (rightBoxHeight > 530) {
                        var newHieght = rightBoxHeight + 100;
                        $(".image-map .section-wrapper").css("height", newHieght + 'px');
                    }
                }
                $(window).on('resize', function () {
                    if ($(window).width() >= 1024 ) {
                        if (rightBoxHeight > 530) {
                            var newHieght = rightBoxHeight + 100;
                            $(".image-map .section-wrapper").css("height", newHieght + 'px');
                        }
                    }
                });
            }); */
        }
    };
}(jQuery, Drupal));