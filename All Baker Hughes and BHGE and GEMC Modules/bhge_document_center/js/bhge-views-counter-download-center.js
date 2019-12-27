(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.views_infinite_scroll_download_center = {
        attach: function (context, settings) {
            $('#total-rows-loaded').text($('.views-row').length);
            var form_popup = $('.form-popup');
            var formElement= '';
            var document_url='';
            $(document).ready(function() {
                $("a.js-close-button").html('Access Download');
                $("a.js-close-button").css('cursor','pointer');
                $('.dark-overlay').addClass('download-center-hide-dark-overlay');
                $('.form-popup').addClass('download-center-hide-form-popup');
                if(typeof MktoForms2 != 'undefined') {
                    MktoForms2.whenReady(function (form) {
                        formElement = form.getFormElem()[0];
                        $(".mktoButtonRow .mktoButton").html('Submit');
                        form.onSubmit(function () {
                            $("a.js-close-button").html("Access Download");
                            $('section[data-component="m01-marketo-form"]').find("h2").css('display', 'none');
                            $('#thank_you_text').css({'margin-top': '-50px'});
                            $('#thank_you_text .title').text("Click on the button below to download the file.")
                        });
                    });
                }
            });

            function resetMarketoForm(){
                formElement.reset();
                $(".mktoButtonRow .mktoButton").html('Submit');
                $('.marketo-form').css('display','flex');
                $(".mktoButtonRow .mktoButton").prop('disabled',false);
            }
            function setDocumentLink(link){
                document_url=link;
            };
            function getDocumentLink(){
                return document_url;
            };

            $(".gatedlink").click(function(e){
                var document_link='';
                e.preventDefault();
                $('.dark-overlay').addClass('download-center-open-dark-overlay');
                $(form_popup).addClass('download-center-open-form-popup');

                //Hide thank_you_text
                if($("section-wrapper .marketo-form").is(':visible') || $(".section-wrapper .marketo-form").css('display') ==='flex' ){
                    $("#thank_you_text").hide();
                    $('section[data-component="m01-marketo-form"]').find("h2").css('display','block');
                }else{
                    $("#thank_you_text").show();
                }
                document_link = $(this).attr('data-url');
                setDocumentLink(document_link);

            });

            $(".dark-overlay ,.close-button ").click(function(){
                $('.dark-overlay').removeClass('download-center-open-dark-overlay');
                $('.form-popup').removeClass('download-center-open-form-popup');
                //Reset form
                resetMarketoForm();
            });

            $("a.js-close-button").click(function(){
                var document_path = getDocumentLink();
                if(document_path!=""){
                    //open in a new window
                    window.open(document_path,"","width=500,height=500");
                }
                setTimeout(function(){
                    $('.dark-overlay').removeClass('download-center-open-dark-overlay');
                    $('.form-popup').removeClass('download-center-open-form-popup');
                    resetMarketoForm();
                },5000);

            });

        }
    };
})(jQuery, Drupal);