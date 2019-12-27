/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.social_feeds = {
        attach: function () {

            $(document).ready(function(){
                function changeFBPagePlugin(){
                    //getting parent box width
                    var container_width = (Number($('.facebook-feeds').width()) - Number($('.facebook-feeds').css('padding-left').replace("px", ""))).toFixed(0);
                    //getting parent box height
                    var container_height = (Number($('.facebook-feeds').height()) - Number($('.facebook-feeds').css('padding-top').replace("px", ""))).toFixed(0);
                    if (!isNaN(container_width) && !isNaN(container_height)) {
                        $(".fb-timeline .fb-page").attr("data-width", container_width).attr("data-height", container_height);
                    }
                    if (typeof FB !== 'undefined') {
                        FB.XFBML.parse();
                    }
                }
                $('ul.tabs li').click(function(){
                    var tab_id = $(this).attr('data-tab');

                    $('ul.tabs li').removeClass('current');
                    $('.all-feeds .tab-content').removeClass('current');

                    $(this).addClass('current');
                    $("#"+tab_id).addClass('current');
                });

                $(window).on('resize', function() {
                    if($(".social-feeds .all-feeds div").hasClass('facebook-feeds')) {
                        setTimeout(function () {
                            changeFBPagePlugin()
                        }, 500);
                    }
                });

                $(window).on('load', function() {
                    if($(".social-feeds .all-feeds div").hasClass('facebook-feeds')) {
                        setTimeout(function () {
                            changeFBPagePlugin()
                        }, 1500);
                    }
                });
            });
        }
    };
}(jQuery, Drupal));