jQuery(document).ready(function($){

 var href = drupalSettings.path.baseUrl + '/get-the-form';
 	var html = $.ajax({
			type: "POST",
			url: href,
			data: {"ajaxCall":true},
			async: false
			}).responseText;
			
			if(html != "error"){
    
    //jQuery('#header').hide();
	
				$("#showformresults").html(html);
 
 
				return false; 
			} else {
					$("#showformresults").html('error');
				
				return false;
			}
});

