(function ($) {
    'use strict';
  
    Drupal.behaviors.bhLayoutsAutoSaveBlock = {
      attach: function (context, settings) {
  
        $('input.form-submit', "#layout-builder-add-block").once('autoSaveBlock').each(function () {
  
          $(document).ajaxComplete(function(event, xhr, settings) {
            if(typeof settings.extraData != 'undefined' && typeof settings.extraData._triggering_element_name != 'undefined') {
              var element_name = settings.extraData._triggering_element_name;
              if (element_name.indexOf("entity_browser][entity_ids") != -1) {
                $("#layout-builder-add-block > .form-submit").mousedown();
              }
            }
          });
    
        });
  
      }
    }
  
  }(jQuery));