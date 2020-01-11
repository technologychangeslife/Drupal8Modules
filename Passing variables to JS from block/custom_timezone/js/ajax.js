jQuery(document).ready(function($){
 var href = 'http://localhost/drupal8/get-time';
 	var html = $.ajax({
			type: "POST",
			url: href,
			data: {"ajaxCall":true},
			async: false
			}).responseText;
			
			if(html != "error"){
 
				//window.history.pushState("object or string", "Title", href);
	
				$("#showresults").html(html);
 
 
				return false; 
			} else {
					$("#showresults").html('error');
				
				return false;
			}
 
 //$('#my-div').load('get-time');
 //setInterval(function(){ $("#my-div").load('get-time'); }, 3000);
 
 //setInterval(function(){ $("#showresults").html('Load current time'); }, 3000);
	 $('#main').on( "click", '.callController',function(event) {
   //alert('JQuery working from twig file beating cache');
		event.preventDefault();
		var href = $(this).attr('href');
  
  function my_ajax_load() {
   alert('called');
  }
  
 
		var html = $.ajax({
			type: "POST",
			url: href,
			data: {"ajaxCall":true},
			async: false
			}).responseText;
			
			if(html != "error"){
 
				window.history.pushState("object or string", "Title", href);
	
				$("#showresults").html(html);
 
 
				return false; 
			} else {
					$("#showresults").html('error');
				
				return false;
			}
		return false;
  
 
	});
});

/*(function ($, Drupal, drupalSettings) {
Drupal.behaviors.LotusBehavior = {
  attach: function (context, settings) {
   
    // can access setting from 'drupalSettings';
    //getiing variable from php or your module from preprocess html function.
    var timezone = drupalSettings.custom_timezone.timezone;
    alert(timezone);
    $('#showresults').html(timezone);
  }
};
})(jQuery, Drupal, drupalSettings);*/

