(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.marketo_form_handler = {
        attach: function () {
            MktoForms2.loadForm(drupalSettings.geMarketoForm.marketo.url, drupalSettings.geMarketoForm.marketo.id, drupalSettings.geMarketoForm.marketo.formId, function (form) {
                let btn = document.getElementsByClassName("mktoButton");
                btn[0].innerHTML = drupalSettings.geMarketoForm.marketo.buttonText;
                form.addHiddenFields(drupalSettings.geMarketoForm.marketo.dataAttributes);
                form.onSuccess(function (values, followUpUrl) {
                    document.cookie = "marketoFormTrackingCode="+ values._mkt_trk+"expires=Fri, 31 Dec 9999 23:59:59 GMT";
                    if (drupalSettings.geMarketoForm.marketo.isFile !== undefined && drupalSettings.geMarketoForm.marketo.isFile && drupalSettings.geMarketoForm.marketo.gated) {
                        $('form').hide();
                        $('.content-frame h2:first').hide();
                        $('.content-frame p:first').hide();
                        $('.thank-you-text').show();
                        $('html,body').animate({scrollTop: $(".marketo-form-wrapper").offset().top - 300}, 'slow');
                        document.cookie = 'marketoFormTrackingCode=';
                        setTimeout(downloadLink, 5000);
                        return false;
                    }
                });
                function downloadLink(){
                    window.location.href = drupalSettings.geMarketoForm.marketo.gatedUrl;
                }
            });
        }
    }
}(jQuery, Drupal));
