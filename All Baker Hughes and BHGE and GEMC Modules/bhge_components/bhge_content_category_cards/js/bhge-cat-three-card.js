/*globals Drupal:false ,jQuery:false */
(function ($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.product_category_three_card = {
    attach: function () {
      $(document).ready(function () {
        //total number of element
        var n_t = $('.pc-block').length;
        //full width of element with margin
        var w = $('.pc-block').outerWidth(true);
        //width of container without padding
        var w_c = $('.pc-content').width();
        //blocksInRow element per row
        var blocksInRow = Math.min(parseInt(w_c / w), n_t);
        if (blocksInRow == 0) {
          blocksInRow = 1;
        }
        //Description div for  Normal Page
        var row = blocksInRow - 1;
        var total_rows = Math.ceil(n_t / blocksInRow);
        var totalBlocks = $('.pc-block').length;
        var rowNumber;
        var newTotalBlocks = totalBlocks - 1;
        var a = 1;
        var blockWidthTitle = 0;
        var setTitleWidth = 0;
        var imageHeight = 0;
        var imageDivHeight = 0;
        var titleHeight = 0;
        var blockCalculatedHeight = 0;
        titleHeight = $(".pc-title").outerHeight();
        imageHeight = $(".pc-image").height();
        imageDivHeight = $(".pc-image-div").outerHeight();
        blockCalculatedHeight = (titleHeight + imageDivHeight) + "px";
        blockWidthTitle = $(".pc-block").width();
        setTitleWidth = blockWidthTitle + "px";
        // $(".pc-title").css("width",setTitleWidth);
        // $(".pc-title").css("margin-top",imageHeight);
        //$(".pc-block").css("height", blockCalculatedHeight);
        $("div.pc-desc-block").remove();
        $.each($(".pc-block"), function (num, val) {
          //Append div to last element in row
          if (blocksInRow > 1) {

            if (((num + 1) % blocksInRow == 0)) {
              $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" + a + "'></div>");
              a++;
            }
            if ((num == totalBlocks - 1) && (num % blocksInRow != 0)) {
              $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" + a + "'></div>");
              a++
            }
            if ((num == totalBlocks - 1) && (num % blocksInRow == 0)) {
              $(".pc-block:eq(" + num + ")").after("<div class='pc-desc-block pc-desc-block-" + a + "'></div>");
              a++
            }

          }
          if (blocksInRow == 1) {
            var row_num = num;
            var lastRowElm = num;
            if (!$(".pc-desc-block-" + row_num + "").length > 0) {
              $(".pc-block:eq(" + lastRowElm + ")").after("<div class='pc-desc-block pc-desc-block-" + row_num + "'></div>");
            }
          }
          $(".pc-desc-block").hide();
        });
        //Description div on Window resize
        window.addEventListener('resize', function (event) {
          var blockWidthTitle = 0;
          var setTitleWidth = 0;
          var imageHeight = 0;
          var titleHeight = 0;
          titleHeight = $(".pc-title").outerHeight();
          //imageHeight = $(".pc-image").height();

          imageHeight = $(".pc-image").outerHeight();
          blockCalculatedHeight = (titleHeight + imageHeight) + "px";
          //$(".pc-block").css("height", blockCalculatedHeight);
          blockWidthTitle = $(".pc-block").width();
          setTitleWidth = blockWidthTitle + "px";
          //$(".pc-title").css("width",setTitleWidth);
          //$(".pc-title").css("margin-top",imageHeight);
          $(".pc-desc-block").remove();
          var b = 1;
          //only the width of container will change
          var w_c1 = $('.pc-content').width();
          //total number of element
          var n_t1 = $('.pc-block').length;
          //full width of element with margin
          var w1 = $('.pc-block').outerWidth(true);
          var blocksInRow1 = Math.min(parseInt(w_c1 / w1), n_t1);
          if (blocksInRow1 == 0) {
            blocksInRow1 = 1;
          }
          if (blocksInRow1 < 3) {
            // reload page on resize
            //  location.reload();
          }
          //$("div.pc-desc-block").remove();
          $.each($(".pc-block"), function (num1, val) {
            //Append div to last element in row
            if (blocksInRow1 > 1) {

              if (((num1 + 1) % blocksInRow1 == 0)) {
                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" + b + "'></div>");
                b++;
              }
              if ((num1 == totalBlocks - 1) && (num1 % blocksInRow1 != 0)) {
                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" + b + "'></div>");
                b++;
              }
              if ((num1 == totalBlocks - 1) && (num1 % blocksInRow1 == 0)) {
                $(".pc-block:eq(" + num1 + ")").after("<div class='pc-desc-block pc-desc-block-" + b + "'></div>");
                b++;
              }

            }
            if (blocksInRow1 == 1) {
              var row_num1 = num1;
              var lastRowElm1 = num1;
              if (!$(".pc-desc-block-" + row_num1 + "").length > 0) {
                $(".pc-block:eq(" + lastRowElm1 + ")").after("<div class='pc-desc-block pc-desc-block-" + row_num1 + "'></div>");
              }
            }
            $(".pc-desc-block").hide();
          });
        });

        // Display body on click and show correct icon
        var rowNumber = "";
        $.each($(".pc-block"), function (index, val) {
          $(".chevron-down-" + index).click(function () {
            var blockWidth = 0;
            var blockMargin = 0;
            var blockSetWidth = 0;
            var blockImg = "";
            var divContentHtml = "";
            blockImg = $(this).closest(".pc-block").find(".pc-image-bg-div img").attr("src");
            $(".pc-product-body").hide();
            $(".chevron-down").show();
            $(".chevron-down-" + index).hide();
            $(".chevron-up").hide();
            //$(".pc-product-body-"+index).show();
            $(".pc-desc-block-" + rowNumber).empty();
            //reset height
            $(".pc-desc-block-" + rowNumber + "").css("height", "auto");
            $(".chevron-up-" + index).show();
            $(".chevron-up-" + index).css("color", "darkturquoise");
            var selectNewBlocks = blocksInRow - 1;
            if ((index < blocksInRow) && (index >= 0)) {

            }
            if (blocksInRow > 1) {
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
            if (blocksInRow == 1) {
              rowNumber = index;
              if (index == 0) {
                rowNumber = 0;
              }
            }

            var current_theme = drupalSettings.bhge_content_category_cards.bhgeCatThreeCard.current_theme;

            var divContentHtml = $(".pc-product-body-" + index).html();
            $(".pc-desc-block").hide();
            $(".pc-desc-block-" + rowNumber).append(divContentHtml);
            if (current_theme === 'bh_new') {
              $(".pc-desc-block-" + rowNumber + "").css({'background': ' linear-gradient(to top,rgba(9, 97, 77, 1), rgba(9, 97, 77, 0.75)), url(' + blockImg + ')'});
            } else {
              $(".pc-desc-block-" + rowNumber + "").css({'background': ' linear-gradient(to top,rgba(9, 36, 79, 1), rgba(9, 36, 79, 0.75)), url(' + blockImg + ')'});
            }

            $(".pc-desc-block-" + rowNumber).slideDown("slow");
            $('html,body').animate({scrollTop: $(".pc-title-" + index + "").offset().top - 120}, 'slow');
            $(".pc-title").css("padding-bottom", "15px");
            // $(".pc-title-"+index).css("padding-bottom","40px");
            //Adjust height
            var blockDescHeight = 0;
            var blockHeight = 0;
            var blockMainLinksCount = 0;
            blockMainLinksCount = $(".pc-desc-block-" + rowNumber + " .pc-main-link-title ").length;
            blockDescHeight = $(".pc-desc-block-" + rowNumber + " .pc-description ").outerHeight(false);
            if (blockDescHeight === undefined) {
              blockDescHeight = 0;
            }
            var blockMainLinksHeight = 0;
            var blockMainLinksHeight = $(".pc-desc-block-" + rowNumber + " .pc-main-link-title ").outerHeight(true);
            blockHeight = (blockDescHeight + (blockMainLinksHeight + 100) + 50);
            if (blockMainLinksCount > 2) {
              blockHeight = blockHeight + 150;
            } else {
              blockHeight = blockHeight + 75;
            }
            //$(".pc-desc-block-"+rowNumber+" .pc-description ").css("margin-bottom","50px");
            if (blocksInRow == 1) {
              if ($(".pc-desc-block-" + rowNumber + " .pc-description ")) {
                blockHeight = blockHeight + 100;
              } else {
                blockHeight = blockHeight + 50;
              }
            }
            var blockSetHeight = blockHeight + "px";
            $(".pc-desc-block-" + rowNumber + "").css("height", blockSetHeight);
            blockWidth = $(".pc-block").width();
            blockMargin = $(".pc-block:nth-child(2)").css("margin-left");
            if (blocksInRow >= 2) {
              blockSetWidth = ((blockWidth * blocksInRow) + (parseInt(blockMargin, 10) * (blocksInRow - 1))) + "px";
            }
            if (blocksInRow == 1) {
              blockSetWidth = blockWidth + "px";
            }


            $(".pc-desc-block-" + rowNumber + "").css("width", blockSetWidth);
            $(".pc-product-body").hide();
          });
          $(".chevron-up-" + index).click(function () {
            $(".chevron-up").hide();
            $(".pc-desc-block").slideUp("slow");
            $(".chevron-down").show();
            $(".pc-title").css("padding-bottom", "15px");
            $(".chevron-up-" + index).hide();
            $(".chevron-down-" + index).show();
            $(".pc-desc-block-" + rowNumber).empty();
            var mainBlockHeight = $(".pc-block").outerHeight();
            $('html,body').animate({scrollTop: $(".pc-title-" + index).offset().top - (mainBlockHeight * 1.75)}, 'slow');
            $(".pc-desc-block-" + rowNumber).slideUp("slow");
            $(".pc-product-block-" + index).hide();
            $(".pc-product-body").hide();
          });
        });
      });
    }
  };
}(jQuery, Drupal, drupalSettings));