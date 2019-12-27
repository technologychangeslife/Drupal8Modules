(function ($) {
    $("body").on( "click", '.view-dyamic-cards-with-filters .view-content .views-row .views-field-field-target a', function(e) {
        if ($(this).attr('href').indexOf('youtu') > -1) {
            e.preventDefault();
            $(this).videoPopup();
        }
    });
    $("body").on( "click", '#video-bhge', function(e) {
            e.preventDefault();
            $(this).videoPopup();
    });
})(jQuery);