(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.elinks = {
        attach: function (context, settings) {
            $(document).ready(function(){
                $('.external-links').each(function(index){
                    $(this).addClass('elinks-'+index);
                    $(".elinks-"+index+" .tab:first" ).addClass("open");
                    //default
                    adjustContentHeight(index);
                    $(".elinks-"+index+" .tab-button").click(function(){
                        $(".elinks-"+index+" .tab" ).removeClass("open");
                        $(this).parent().addClass("open");
                        adjustContentHeight(index);
                    });

                });

                function adjustContentHeight(num){
                    var count =0;
                    var item_height = 0;
                    var scrollHeight= 0;
                    var tabHeight = 0;
                    count = $(".elinks-"+num+ " .open .tab-content-wrapper .link-item").length;
                    var contentHeight = $(".section-wrapper.elinks-"+num+ " .content-wrapper ");
                    var tabContentNew = $(".section-wrapper.elinks-"+num+ " .tabs .open .tab-content");
                    var scrollContentNew = $(".section-wrapper.elinks-"+num+ " .tabs .open .tab-content .scroll-wrapper .scroll-content");
                    var tabScrollStyle = $(".section-wrapper.elinks-"+num+ " .tabs .open .tab-content .scroll-wrapper");
                    if(count <= 10){
                        item_height = 450+(count*135); //initial height + 135px/item
                        contentHeight.css('height', item_height +'px');
                        tabHeight = count*160;	//35px extra/item
                        scrollHeight = count*155; //20px extra/item
                        tabContentNew.css('height' , tabHeight +'px');
                        tabScrollStyle.css('height', scrollHeight +'px');
                        scrollContentNew.css('height' , scrollHeight+'px');
                    }else{
                        item_height = 400+1350; //initial height + 135px/item(show 10 items)
                        contentHeight.css('height' , item_height+'px');
                        scrollHeight = 1485; //height for (10+1) items
                        tabContentNew.css('height', item_height+'px');
                        tabScrollStyle.css('height', scrollHeight +'px');
                        scrollContentNew.css('height', scrollHeight+'px');
                    }
                }
            });
        }

    }
})(jQuery, Drupal);