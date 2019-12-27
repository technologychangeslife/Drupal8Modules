jQuery(document).ready(function($){
 var href = drupalSettings.path.baseUrl + '/get-time';
 	var html = $.ajax({
			type: "POST",
			url: href,
			data: {"ajaxCall":true},
			async: false
			}).responseText;
			
			if(html != "error"){
	
				$("#showresults").html(html);
 
 
				return false; 
			} else {
					$("#showresults").html('error');
				
				return false;
			}
});

