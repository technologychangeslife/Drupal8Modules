(function ($) {
  'use strict';

  Drupal.behaviors.previewMarketInfo = {
    attach: function (context) {
      $('#jsPreviewMarketInfo', context).click(function () {
        var win = window.open(this.href + '?' + getMapPreviewURL(), '_blank');
        win.focus();

        return false;
      });

      function getMapPreviewURL() {
        var $marketingPreviewInfo = $('#edit-group-market-info');
        var $inputs = $marketingPreviewInfo.find('input, select');
        var urlQuery = [];

        $inputs.each(function () {
          var $this = $(this);
          urlQuery.push($this.attr('name') + '=' + $this.val());
        });

        return urlQuery.join('&');
      }
    }
  };

}(jQuery));