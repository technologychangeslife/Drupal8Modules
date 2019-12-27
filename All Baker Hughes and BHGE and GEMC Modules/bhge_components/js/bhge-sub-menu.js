/*globals Drupal:false ,jQuery:false */
(function($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.sub_menu = {
    attach: function(context, settings) {
      var site_internal = drupalSettings.bhge_components.bhgeSubmenu.site_nature;
      var theme_path = drupalSettings.bhge_components.bhgeSubmenu.theme_path;
      var menuHtml = $(".sub-menu-nav").html();
      if ($("#toolbar-bar").length > 0) {
        $('.section-wrapper').addClass('submenu-toolbar-present');
        var lastScrollTop1 = 0;
        // add class on scroll
        $(window).scroll(function(event) {
          var st1 = $(this).scrollTop();
          if (st1 > 50) {
            if (st1 > lastScrollTop1) {
              $('.section-wrapper').addClass('scrolling-down-toolbar');
              $('.section-wrapper').removeClass('scrolling-up-toolbar');
              if ($("section.block-announcement").length > 0) {
                $(".sub-menu .section-wrapper.scrolling-down-toolbar").addClass("block-announcement-present-down");
                $(".sub-menu .section-wrapper.scrolling-up-toolbar").removeClass("block-announcement-present-up");
              }
            } else {
              $('.section-wrapper').addClass('scrolling-up-toolbar');
              if ($("section.block-announcement").length > 0) {
                $(".sub-menu .section-wrapper.scrolling-up-toolbar").addClass("block-announcement-present-up");
              }
              // special case - navigation menu height changes when scrolling up
              // add class for admin view - desktop
              if ($("section[data-component=n01-navigation] .navigation-wrapper").hasClass("scrolling-down")) {
                if ($(".submenu-toolbar-present").hasClass("scrolling-up-toolbar")) {
                  if (site_internal) {
                    $(".sub-menu .section-wrapper.scrolling-up-toolbar").addClass("interal-submenu-override-top-up");
                    if ($("section.block-announcement").length > 0) {
                      $(".sub-menu .section-wrapper.scrolling-up-toolbar").removeClass("interal-submenu-override-top-up");
                      $(".sub-menu .section-wrapper.scrolling-up-toolbar").addClass("block-announcement-present-up-internal");
                    }
                  } else {
                    $(".sub-menu .section-wrapper.scrolling-up-toolbar").addClass("submenu-override-top-up");
                    $(".sub-menu .section-wrapper.scrolling-up-toolbar").removeClass("block-announcement-present-up-internal");
                  }
                }
              }
              // special case - navigation menu height changes when scrolling up
              // add class for admin view - mobile
              if ($("section[data-component=n01-navigation] .navigation-wrapper").hasClass("scrolling-down")) {
                if ($(".submenu-toolbar-present-mobile").hasClass("scrolling-up-toolbar")) {
                  if (!site_internal) {
                    $(".section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar").addClass("mobile-submenu-override-top-up");
                    if ($("section.block-announcement").length > 0) {
                      $(".section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar").removeClass("mobile-submenu-override-top-up");
                    }
                  } else {
                    $(".section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar").addClass("mobile-submenu-override-top-up");
                  }
                }
              } else {
                $(".sub-menu .section-wrapper.scrolling-up-toolbar").removeClass("submenu-override-top-up");
                $(".sub-menu .section-wrapper.scrolling-up-toolbar").removeClass("interal-submenu-override-top-up");
                //$(".section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar").removeClass("mobile-submenu-override-top-up-internal");
                $(".section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar").removeClass("mobile-submenu-override-top-up");
              }
              $('.section-wrapper').removeClass('scrolling-down-toolbar');
              $(".sub-menu .section-wrapper").removeClass("block-announcement-present-down");
            }
            lastScrollTop1 = st1;
          } else {
            $('.section-wrapper').removeClass('scrolling-up-toolbar');
            $('.section-wrapper').removeClass('scrolling-down-toolbar');
            if ($("section.block-announcement").length > 0) {
              $(".sub-menu .section-wrapper").removeClass("block-announcement-present-up");
              $(".sub-menu .section-wrapper").removeClass("block-announcement-present-down");
            }
          }
        });
      } else {
        // modify color for navigation menu for anonymous users
        $('.section-wrapper').addClass('submenu-no-toolbar');
        $("section[data-component=n01-navigation] .navigation-wrapper .header-wrapper .logo-wrapper .component-icon").css("color", "#005eb8");
        $("section[data-component=n01-navigation] .navigation-wrapper .header-wrapper .logo-wrapper ").addClass("sub-menu-after");
        $("section[data-component=n01-navigation] .navigation-wrapper .header-wrapper .large-main-navigation .link ").css("color", "#005eb8");
        $("section[data-component=n01-navigation] .navigation-wrapper .header-wrapper .large-main-navigation .search ").css("color", "#005eb8");
        $("section[data-component=n01-navigation] .navigation-wrapper .header-wrapper .component-menu-button").css("color", "#005eb8");
        $("section[data-component=n01-navigation] .navigation-wrapper ").css("color", "#fffff");
        //breadcrumbs
        $(".breadcrumbs .content-wrapper .crumbs-trail .crumb").css("color", "#005eb8");
        $(".breadcrumbs .content-wrapper .crumbs-trail .crumb .link").css("color", "#005eb8");
        /*-- Scroll Up/Down add class --*/
        var lastScrollTop = 0;
        $(window).scroll(function(event) {
          let st = $(this).scrollTop();
          if (st > 50) {
            if (st > lastScrollTop) {
              $('.section-wrapper').addClass('scrolling-down');
              $('.section-wrapper').removeClass('scrolling-up');
              // add classes if announcement block is present
              if ($("section.block-announcement").length > 0) {
                $(".sub-menu .section-wrapper").removeClass("block-announcement-present-up-no-toolbar");
                $(".sub-menu .section-wrapper").addClass("block-announcement-present-down-no-toolbar");
              }
            } else {
              $('.section-wrapper').addClass('scrolling-up');
              if ($("section.block-announcement").length > 0) {
                $(".sub-menu .section-wrapper").addClass("block-announcement-present-up-no-toolbar");
              }
              // special case - navigation menu height changes when scrolling up
              // add class for anonymous view - desktop
              if ($("section[data-component=n01-navigation] .navigation-wrapper").hasClass("scrolling-down")) {
                if ($(".submenu-no-toolbar").hasClass("scrolling-up")) {
                  if (site_internal) {
                    $(".sub-menu .section-wrapper.scrolling-up").addClass("internal-submenu-override-top-up");
                  } else {
                    $(".sub-menu .section-wrapper.scrolling-up").addClass("menu-override-top-up");
                  }
                }
                // special case - navigation menu height changes when scrolling up
                // add class for anonymous view - mobile
                if ($(".submenu-no-toolbar-mobile").hasClass("scrolling-up")) {
                  if (!site_internal) {
                    $(".sub-menu .section-wrapper.submenu-no-toolbar-mobile.scrolling-up").addClass("menu-override-top-up");
                  } else {
                    if (!$("section").hasClass("block-announcement")) {
                      $(".sub-menu .section-wrapper.submenu-no-toolbar-mobile.scrolling-up").addClass("menu-override-top-up");
                    }
                  }
                }
              } else {
                $(".sub-menu .section-wrapper.scrolling-up").removeClass("menu-override-top-up");
                $(".sub-menu .section-wrapper.scrolling-up").removeClass("internal-submenu-override-top-up");
                $(".sub-menu .section-wrapper.submenu-no-toolbar-mobile.scrolling-up").removeClass("menu-override-top-up");
              }
              $('.section-wrapper').removeClass('scrolling-down');
              if ($("section.block-announcement").length > 0) {
                $(".sub-menu .section-wrapper").removeClass("block-announcement-present-down-no-toolbar");
              }
            }
            lastScrollTop = st;
          } else {
            $('.section-wrapper').removeClass('scrolling-up');
            $('.section-wrapper').removeClass('scrolling-down');
            if ($("section.block-announcement").length > 0) {
              $(".sub-menu .section-wrapper").removeClass("block-announcement-present-up-no-toolbar");
              $(".sub-menu .section-wrapper").removeClass("block-announcement-present-down-no-toolbar");
            }
          }
        });
      }
      $(document).ready(function() {
        $(".sub-menu").show();
        $(".sub-menu-nav").addClass("desktop-submenu");
        // style list items in menu
        var mobileMenu = "<div class='mobile-submenu'><img src='"+theme_path+"/image/submenu/submenu-down.png' alt='down' class='submenu-down'/><img src='"+theme_path+"/image/submenu/submenu-up.png' alt='up' class='submenu-up'/><div class='mobile-main-menu'>" + menuHtml + "</div></div>";
        $(mobileMenu).insertAfter(".sub-menu-nav");
        $(".sub-menu-nav li").hover(function() {
          var subMenuPresent = $(this).find("ul.menu").length;
          var itemWidth = $(this).width();
          var currentLeft = 90;
          var newLeft = 0;
          if ($(this).is(":last-child")) {
            if ($(window).width() < 1270) {
              $(this).closest("li").find("ul").css("left", "-20px");
            } else {
              if (itemWidth <= 100) {
                newLeft = currentLeft + 35;
                $(this).closest("li").find("ul").css("left", "-" + newLeft + "px");
              }
              if (itemWidth >= 200) {
                newLeft = currentLeft - 45;
                $(this).closest("li").find("ul").css("left", "-" + newLeft + "px");
              }
            }
          }
          if ($(this).is(":first-child")) {
            $(this).closest("li").find("ul").css("left", "-20px");
          }
          if (!$(this).is(":first-child") && !$(this).is(":last-child")) {
            if (itemWidth <= 100) {
              newLeft = currentLeft + 35;
              $(this).closest("li").find("ul").css("left", "-" + newLeft + "px");
            }
            if (itemWidth >= 180) {
              newLeft = currentLeft - 20;
              $(this).closest("li").find("ul").css("left", "-" + newLeft + "px");
            }
          }
          $(this).find("ul.menu").show();
        }, function() {
          $(this).find("ul.menu").hide();
        });
        var windowWidth = $(window).width();
        // mobile style
        if (windowWidth >= 1025) {
          $(".sub-menu-nav").show();
          $(".mobile-submenu").hide();
          if ($(".section-wrapper").hasClass('submenu-toolbar-present-mobile')) {
            $('.section-wrapper').removeClass('submenu-toolbar-present-mobile');
            $('.section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar').removeClass('mobile-override-top-val');
          }
        }
        if (windowWidth < 1025) {
          $(".mobile-submenu").show();
          if ($("#toolbar-bar").length > 0) {
            $('.section-wrapper').removeClass('submenu-toolbar-present');
            $('.section-wrapper').addClass('submenu-toolbar-present-mobile');
            $(window).scroll(function(event) {
              if (site_internal) {
                // special case - navigation menu height changes when scrolling up
                // add class for admin view - mobile
                if ($("section[data-component=n01-navigation] .navigation-wrapper").hasClass("scrolling-down")) {
                  if ($(".submenu-toolbar-present-mobile").hasClass("scrolling-up-toolbar")) {
                    $('.section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar').addClass('mobile-override-top-val');
                  }
                } else {
                  $('.section-wrapper.submenu-toolbar-present-mobile.scrolling-up-toolbar').removeClass('mobile-override-top-val');
                }
              }
            });
          } else {
            $('.section-wrapper').removeClass('submenu-no-toolbar');
            $('.section-wrapper').addClass('submenu-no-toolbar-mobile');
          }
          $(".mobile-main-menu").css("width", windowWidth + "px");
          $(".sub-menu-nav").hide();
        }
        // switch to mobile css on resize
        $(window).resize(function() {
          if ($(window).width() >= 1025) {
            $(".sub-menu-nav").show();
            $(".mobile-submenu").hide();
            if ($("#toolbar-bar").length > 0) {
              $('.section-wrapper').addClass('submenu-toolbar-present');
            } else {
              $('.section-wrapper').addClass('submenu-no-toolbar');
            }
            if ($(".section-wrapper").hasClass('submenu-toolbar-present-mobile')) {
              $('.section-wrapper').removeClass('submenu-toolbar-present-mobile');
            }
          }
          if ($(window).width() < 1025) {
            $(".mobile-submenu").show();
            if ($("#toolbar-bar").length > 0) {
              $('.section-wrapper').removeClass('submenu-toolbar-present');
              $('.section-wrapper').addClass('submenu-toolbar-present-mobile');
            } else {
              $('.section-wrapper').removeClass('submenu-no-toolbar');
              $('.section-wrapper').addClass('submenu-no-toolbar-mobile');
            }
            $(".mobile-main-menu").css("width", $(window).width() + "px");
            $(".sub-menu-nav").hide();
          }
        });
        $('.mobile-main-menu ul li:not(:first)').hide();
        $(".submenu-down").on("click", function() {
          $('.mobile-main-menu ul li:not(:first)').slideDown();
          $(this).hide();
          $(".submenu-up").show();
          $(".mobile-submenu .mobile-main-menu").css("overflow-y", "scroll");
          $(".mobile-submenu .mobile-main-menu").css("height", "80vh");
        });
        $(".submenu-up").on("click", function() {
          $(this).hide();
          $(".submenu-down").show();
          $('.mobile-main-menu ul li:not(:first)').slideUp();
          $(".mobile-submenu .mobile-main-menu").css("overflow-y", "unset");
          $(".mobile-submenu .mobile-main-menu").css("height", "unset");
        });
      });
    }
  };
}(jQuery, Drupal, drupalSettings));