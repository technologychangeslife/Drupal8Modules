AOS.init();
(function($) {
  $(document).ready(function() {
    $(".fixed-btn-cta").click(function() {
      $(".fixed-content-container").css("display", "block");
      $(".fixed-content-container").addClass("upwardAnimate");
      $(".fixed-content-container").removeClass("downwardAnimate");
      // if ($(".fixed-content-container").hasClass("downwardAnimate")) {
      //   $(".fixed-content-container").addClass("upwardAnimate");
      //   $(".fixed-content-container").removeClass("downwardAnimate");
      // } else {
      //   $(".fixed-content-container").removeClass("upwardAnimate");
      //   $(".fixed-content-container").addClass("downwardAnimate");
      // }
    });
    $("#close-menu").click(function() {
      $(".fixed-content-container").removeClass("upwardAnimate");
      $(".fixed-content-container").addClass("downwardAnimate");
      setTimeout(function() {
        $(".fixed-content-container").css("display", "none");
      }, 250);
    });
  });
})(jQuery);
