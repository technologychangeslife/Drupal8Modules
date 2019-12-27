/*globals Drupal:false ,jQuery:false */
(function ($) {
    "use strict";
    Drupal.behaviors.bhge_user_registration = {
        attach: function () {
            //Removing Footer
            $( "#footer" ).hide();
            //Removing SSO login links
            $( "#bhge-dialog" ).hide();
            //Removing Header section.
            $( ".header.navigation-wrapper" ).hide();
            $('.line').hide();
            $('.default-hero').hide();
            var url = window.location.href;
            // Display the div only on nongeform page
           // if( url.search('geform' ) > 0 ) {
             //   $( ".wrapper" ).prepend("<div class='formmaindiv'><div class='homepagelink'><a href='/'>Return to Home Page</a></div><h1 class='messagetext'>Request Access</h1><div><p class='thankyoutextspace'>Thank you for your interest in gaining access to engageRecip.com . Please provide below details to submit your request for access .</p></div></div>");
            //}
  $("label[for='edit-ssoid-ge']").attr("id", "ssoidspace");
  $("label[for='edit-email']").attr("id", "emailidspace");
   $("#edit-issueloggingin-ge").click(function(e){
       e.preventDefault();
    alert("If you have an existing SSO and unable  to login , please send your SSO to engage.recip@ge.com");

   });
  $("#edit-submit-ge").click(function(){
        $('.content div#errormsgfirstname').remove();
	    var firstname=$("#edit-firstname-ge").val();
		var lastname=$("#edit-lastname-ge").val();
		var sso=$("#edit-ssoid-ge").val();
		var emailid=$("#edit-email").val();
		var alpha = /^[a-zA-Z\s-, ]+$/;
			var validate=true;
		if(firstname =='')
		{
		$('#errormsgfirstname').html("Please Enter your First Name ");
			$('#errormsgfirstname').addClass("error");
			$(".form-item-firstname-ge").append("<div id='errormsgfirstname'>Please Enter Your First Name</div>");
		 return false;
		}
		else if(!firstname.match(alpha)) {
			$('#errormsgfirstname').html("please enter valid First Name ");
			$('#errormsgfirstname').addClass("error");
			$(".form-item-firstname-ge").append("<div id='errormsgfirstname'>Please Enter valid First Name</div>");
			 return false;
		}
		else if(lastname =='')
		{
		  $('#errormsglastname').html("please enter your Lastname ");
		  $('#errormsglastname').addClass("error");
          $(".form-item-lastname-ge").append("<div id='errormsgfirstname'>Please Enter Your Last Name</div>");
		  return false;
		
		}else if(!lastname.match(alpha)) {
		  $('#errormsglastname').html("please enter your Lastname ");
		  $('#errormsglastname').addClass("error");
		  $(".form-item-lastname-ge").append("<div id='errormsgfirstname'>Please Enter valid Last Name</div>");
		  return false;
		}
		else{
			$('#errormsgfirstname').html(" ");
			$('#errormsgfirstname').removeClass("error");
			$('#errormsglastname').html(" ");
            $('#errormsglastname').removeClass("error");
			if((lastname.match(alpha)) &&(firstname.match(alpha)) ) {
				alert("Thank you for the information . A member of our team will contact you shortly with more information regarding your engageRecip access .");
				$( "#my-form-formpage-form" ).submit();
			}

        }
  });
        }
    };
})(jQuery, Drupal);

