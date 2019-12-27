(function ($) {
    'use strict';
  
    Drupal.behaviors.bhLayoutsLivePreview = {
      attach: function (context, settings) {
        $('#layout-builder-content-preview', context).on('change', function (event) {
		  var isChecked = $(event.currentTarget).is(':checked');
		
		  if (isChecked) {
		    $('[data-layout-content-preview-placeholder-label]').each(function (i, element) {
			  $(element).children('style').hide();
		    });
		  }
		});
  
      }
    }
  
  }(jQuery));