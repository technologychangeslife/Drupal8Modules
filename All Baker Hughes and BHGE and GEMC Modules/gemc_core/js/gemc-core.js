(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.gemc_download = {
        attach: function () {

            // Get c129 div
            var c129div = document.getElementById("edit-field-marketo-form-embed-wrapper");

            $(document).ready(function() {
                var chkBox = $('#edit-field-gated-content-value');
                function toggleCheckbox(val){
                    if (val){
                        c129div.style.display = "block";
                    } else {
                        c129div.style.display = "none";
                    }
                }

                toggleCheckbox(chkBox.prop('checked'));

                chkBox.on('change',function(){
                    toggleCheckbox($(this).prop('checked'));
                });


            });


        }
    };

})(jQuery, Drupal);