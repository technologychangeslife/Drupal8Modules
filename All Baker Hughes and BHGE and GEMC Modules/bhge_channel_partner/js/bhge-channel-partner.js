(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.channel_partner = {
        attach: function () {
            var form_popup = $('.form-popup');
            var formElement= '';
            $(document).ready(function() {
                $('.dark-overlay').addClass('channel-partner-hide-dark-overlay');
                $('.form-popup').addClass('channel-partner-hide-form-popup');
                MktoForms2.whenReady(function(form) {
                    formElement = form.getFormElem()[0];
                    form.onSubmit(function(){
                        $('section[data-component="m01-marketo-form"]').find("h2").css('display','none');
                        $('#thank_you_text').css({'margin-top': '-50px'});
                    });
                });
            });
                $(".cta-marketo").click(function(e){
                    e.preventDefault();

                    $('.dark-overlay').addClass('channel-partner-open-dark-overlay');
                    $(form_popup).addClass('channel-partner-open-form-popup');
                    $(form_popup).prepend($(this).closest(".views-row").find('.channel_partner_popup'));

                    // To prevent duplicates.
                    if($('.form-popup .channel_partner_popup').length >1) {
                           $('.form-popup .channel_partner_popup').eq(1).remove();
                    }
                    //Hide thank_you_text
                    if($("section-wrapper .marketo-form").is(':visible') || $(".section-wrapper .marketo-form").css('display') ==='flex' ){
                        $("#thank_you_text").hide();
                        $('section[data-component="m01-marketo-form"]').find("h2").css('display','block');
                    }else{
                        $("#thank_you_text").show();
                    }
                });
                $(".dark-overlay, .close-button, .js-close-button").click(function(){
                    $('.dark-overlay').removeClass('channel-partner-open-dark-overlay');
                    $('.form-popup').removeClass('channel-partner-open-form-popup');
                    //Reset form
                    formElement.reset();
                    $('.marketo-form').css('display','flex');
                    $(".mktoButtonRow .mktoButton").html('Contact Us');
                    $(".mktoButtonRow .mktoButton").prop('disabled',false);
                });

                // Scroll to the results section.
            $('input[type=submit]').click(function () {
                $('html,body').animate({
                    scrollTop: $("input[type=submit]").offset().top + 20
                }, 'slow');
            });
            // Fix map counter
            $('.map-counter').css('display','none');
            $('.attachment').css('display','none');
            var rowCount = $('.view-channel-partner-finder').find('.views-row').length;
            if(rowCount >0) {
                $('.attachment').css('display','block');
                $('.attachment-before').css('display','block');
                // add min height too
                $('.view-content').css('min-height','600px');
                var counter = 1;
                $('.views-row').each(function(i) {
                       $(this).attr('counter',counter);
                    counter++;
                });
                $('.map-counter').each(function(i) {
                    var map_counter=$(this).closest('.views-row').attr('counter');
                    $(this).html(map_counter).css('display','block');
                });
            }
        }
    };
})(jQuery, Drupal);