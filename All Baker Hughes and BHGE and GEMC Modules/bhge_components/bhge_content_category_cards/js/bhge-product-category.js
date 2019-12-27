/*globals Drupal:false ,jQuery:false */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.product_category = {
        attach: function () {
            $(document).ready(function(){
                //total number of element
                var n_t = $('.pc-block').length;
                //full width of element with margin
                var w = $('.pc-block').outerWidth(true);
                //width of container without padding
                var w_c = $('.pc-content').width();
                //blocksInRow element per row
                var blocksInRow = Math.min(parseInt(w_c / w),n_t);
                if(blocksInRow == 0){
                    blocksInRow = 1;
                }
                //Description div for  Normal Page
                var row = blocksInRow-1;
                var total_rows = Math.ceil(n_t/blocksInRow);
                var totalBlocks = $('.pc-block').length;
                var rowNumber;
                var newTotalBlocks = totalBlocks -1;
                var a=1;
                $("div.pc-desc-block").remove();
                $.each($(".pc-block"),function(num,val){
                    //Append div to last element in row
                    if(blocksInRow>1){

                        if(((num+1)%blocksInRow == 0)){
                            $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" +a+ "'></div>");
                            a++;
                        }
                        if( (num == totalBlocks-1) && (num%blocksInRow != 0) ){
                            $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" +a+ "'></div>");
                            a++
                        }
                        if( (num == totalBlocks-1) && (num%blocksInRow == 0) ){
                            $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" +a+ "'></div>");
                            a++
                        }

                    }
                    if(blocksInRow == 1){
                        var row_num = num;
                        var lastRowElm = num;
                        if(!$(".pc-desc-block-"+row_num+"").length >0){
                            $(".pc-block:eq("+lastRowElm+")").after("<div class='pc-desc-block pc-desc-block-"+row_num+"'></div>");
                        }
                    }
                    $(".pc-desc-block").hide();
                });
                //Description div on Window resize
                window.addEventListener('resize', function(event){
                    $("div.pc-desc-block").remove();
                    var b=1;
                    //only the width of container will change
                    var w_c1 = $('.pc-content').width();
                    //total number of element
                    var n_t1 = $('.pc-block').length;
                    //full width of element with margin
                    var w1 = $('.pc-block').outerWidth(true);
                    var blocksInRow1 = Math.min(parseInt(w_c1 / w1),n_t1);
                    if(blocksInRow1 == 0){
                        blocksInRow1 = 1;
                    }
                    if(blocksInRow1 < 3){
                        // reload page on resize
                        //  location.reload();
                    }
                    //$("div.pc-desc-block").remove();
                    $.each($(".pc-block"),function(num1,val){
                        //Append div to last element in row
                        if(blocksInRow1>1){

                            if(((num1+1)%blocksInRow1 == 0)){
                                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" +b+ "'></div>");
                                b++;
                            }
                            if( (num1 == totalBlocks-1) && (num1%blocksInRow1 != 0) ){
                                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" +b+ "'></div>");
                                b++;
                            }
                            if( (num1 == totalBlocks-1) && (num1%blocksInRow1 == 0) ){
                                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" +b+ "'></div>");
                                b++;
                            }

                        }
                        if(blocksInRow1 == 1){
                            var row_num1 = num1;
                            var lastRowElm1 = num1;
                            if(!$(".pc-desc-block-"+row_num1+"").length >0){
                                $(".pc-block:eq("+lastRowElm1+")").after("<div class='pc-desc-block pc-desc-block-"+row_num1+"'></div>");
                            }
                        }
                        $(".pc-desc-block").hide();
                    });
                });

                //var j=0;
                // Remove Icon if there is no description or links
                $.each($(".pc-product-body"),function(index2,val2){
                    var descCount = $(this).find("p.pc-description").length;
                    var linkCount = $(this).find("ul.pc-main-link-title").length;
                    if(descCount == 0 && linkCount == 0){
                        var classes = $(this).attr("class").split(" ");
                        var searchString = "pc-product-body-";
                        for(var i =0; i<classes.length;i++){
                            if(classes[i].indexOf(searchString)!= -1 ){
                                var removeIconClass = classes[i].split("-");
                                var removeIconNum = removeIconClass[3];
                                $(".pc-block:eq("+removeIconNum+")").find(".component-icon").addClass("disabled");
                                $(".pc-block:eq("+removeIconNum+")").find(".component-icon").css({"display":"none"});
                            }
                        }
                    }
                });
                $.each($(".pc-title"),function(index,val){
                    if($(this).text().length > 225){
                        //     $(this).css("font-size","14px");
                    }
                });

                // Display body on click and show correct icon
                var rowNumber="";
                $.each($(".pc-block"),function(index,val){
                    $(".chevron-down-"+index).click(function(){
                        var blockImg = "";
                        var divContentHtml = "";
                        blockImg = $(this).closest(".pc-block").find(".pc-image-bg-div img").attr("src");
                        $(".pc-product-body").hide();
                        $(".chevron-down").show();
                        $(".chevron-down-"+index).hide();
                        $(".chevron-up").hide();
                        //$(".pc-product-body-"+index).show();
                        $(".pc-desc-block-"+rowNumber).empty();
                        //reset height
                        $(".pc-desc-block-"+rowNumber+"").css("height","auto");
                        $(".chevron-up-"+index).show();
                        $(".chevron-up-"+index).css("color","darkturquoise");
                        var selectNewBlocks = blocksInRow-1;
                        if((index < blocksInRow) && (index>=0) ){

                        }
                        if(blocksInRow >1) {
                            if (index == 0) {
                                rowNumber = 1;
                            }
                            if (index < (blocksInRow) && index != 0) {
                                rowNumber = Math.ceil(index / blocksInRow);
                            }
                            if ((index % blocksInRow) == 0) {
                                //console.log(index/blocksInRow);
                                var value = (index / blocksInRow);
                                rowNumber = (value + 1);
                            }
                            if (index > blocksInRow && (index % blocksInRow) != 0) {
                                rowNumber = Math.ceil(index / blocksInRow);
                            }
                        }
                        if(blocksInRow == 1){
                            rowNumber = index;
                            if(index == 0){
                                rowNumber = 0;
                            }
                        }
                        var divContentHtml = $(".pc-product-body-"+index).html();
                        $(".pc-desc-block").hide();

                        $(".pc-desc-block-"+rowNumber).append(divContentHtml);
                        $(".pc-desc-block-"+rowNumber+"").css({'background':' linear-gradient(to top,rgba(9, 36, 79, 1), rgba(9, 36, 79, 0.45)), url('+blockImg+')'});
                        $(".pc-desc-block-"+rowNumber).slideDown("slow");
                        $(".pc-title").css("padding-bottom","15px");
                        $(".pc-title-"+index).css("padding-bottom","40px");
                        //Adjust height
                        var blockDescHeight = 0;
                        var blockHeight = 0;
                        var blockMainLinksCount = 0;
                        blockMainLinksCount = $(".pc-desc-block-"+rowNumber+" .pc-main-link-title ").length;
                        blockDescHeight = $(".pc-desc-block-"+rowNumber+" .pc-description ").outerHeight(false);
                        if(blockDescHeight === undefined){
                            blockDescHeight = 0;
                        }
                        var blockMainLinksHeight = 0;
                        var blockMainLinksHeight = $(".pc-desc-block-"+rowNumber+" .pc-main-link-title ").outerHeight(true);
                        blockHeight = (blockDescHeight+ (blockMainLinksHeight+ 100) + 50);
                        if(blockMainLinksCount > 2){
                            blockHeight = blockHeight + 150;
                        }else{
                            blockHeight = blockHeight + 75;
                        }
                        $(".pc-desc-block-"+rowNumber+" .pc-description ").css("margin-bottom","50px");
                        if(blocksInRow == 1){
                            if($(".pc-desc-block-"+rowNumber+" .pc-description ")){
                                blockHeight= blockHeight + 100;
                            }else{
                                blockHeight= blockHeight + 50;
                            }
                        }
                        var blockSetHeight = blockHeight+"px";
                        $(".pc-desc-block-"+rowNumber+"").css("height",blockSetHeight);

                        var blockSubLinksHeight = 0;
                        var blockSubLinksHeight = $(".pc-desc-block-"+rowNumber+" .pc-sub-link:last").outerHeight(true);
                        if(blockSubLinksHeight == undefined){
                            blockSubLinksHeight = 0;
                        }
                        var blockTotalSublinks = blockHeight+ blockSubLinksHeight + 'px';


                        $.each($(".pc-desc-block-"+rowNumber+" .pc-main-link-title"),function(number,val){
                            //hide chevron-right if there's no sublink
                            if($(this).find(".pc-sub-link").length == 0){
                                $(this).find("img").hide();
                            }
                        });
                        $(".pc-desc-block-"+rowNumber+"").css("height",blockTotalSublinks);
                        // Display sub categories on hover

                        $(".pc-desc-block-"+rowNumber+" .pc-main-link-title").mouseover(function(){
                            if($(this).find(".pc-sub-link").length > 0){
                                $("a",this).css("color","#005db7");
                                $("a",this).css("font-weight","bold");
                                $(".pc-desc-block-"+rowNumber+" .pc-sub-link").hide();
                                $(this).find(".pc-sub-link").show();
                                $(".pc-desc-block-"+rowNumber+" .pc-sub-link a").css("color","#333333");

                                $(".pc-desc-block-"+rowNumber+" .pc-sub-link").delay(5000).fadeOut('slow',function(){
                                    $(".pc-desc-block-"+rowNumber+"").css("height",blockSetHeight);
                                    $(".pc-desc-block-"+rowNumber+" .pc-sub-link a").css("font-weight","bold");
                                    $(".pc-desc-block-"+rowNumber+"").css({'background':' linear-gradient( to top,rgba(9, 36, 79, 1), rgba(9, 36, 79, 0.45)), url('+blockImg+')'});
                                });
                                $(".pc-desc-block-"+rowNumber+"").css("height",blockTotalSublinks);
                                $(".pc-desc-block-"+rowNumber+"").css({'background':' linear-gradient( to top,rgba(9, 36, 79, 1), rgba(9, 36, 79, 0.9)), url('+blockImg+')'});
                            }
                        });
                        $(".pc-desc-block-"+rowNumber+" .pc-sub-link").mouseover(function(){
                            $(this).closest(".pc-main-link-title a").css("color","#333333");
                        });
                        $(".pc-desc-block-"+rowNumber+" .pc-main-link-title").mouseout(function(){
                            $("a",this).css("color","#333333");
                        });
                        $(".pc-main-link-title").mouseover(function(){
                            $("a",this).css("color","#005db7");
                        });

                        $(".pc-product-body").hide();
                    });
                    $(".chevron-up-"+index).click(function(){
                        $(".chevron-up").hide();
                        $(".pc-desc-block").slideUp("slow");
                        $(".chevron-down").show();
                        $(".pc-title").css("padding-bottom","15px");
                        $(".chevron-up-"+index).hide();
                        $(".chevron-down-"+index).show();
                        $(".pc-desc-block-"+rowNumber).empty();
                        $(".pc-desc-block-"+rowNumber).slideUp("slow");
                        $(".pc-product-block-"+index).hide();
                        $(".pc-product-body").hide();
                    });
                    // Trim description text to 850 characters
                    var txt= $('.pc-description-'+index).text();
                    if(txt.length > 900){
                        $('.pc-description-'+index).text(txt.substring(0,700) + '...');
                    }
                });
            });
        }
    };
}(jQuery, Drupal));