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
  //var url = window.location.href;
  // Display the div only on nongeform page
 // if( url.search('nongeform' ) > 0 ) {
  //$( ".content" ).prepend("<div class='formmaindiv'><div class='homepagelink'><a href='/'>Return to Home Page</a></div><h1 class='messagetext'>Request Access</h1><div><p class='thankyoutextspace'>Thank you for your interest in gaining access to engageRecip.com . Please provide below details to submit your request for access .</p></div></div>");
  //}
   $("label[for='edit-ssoid-nonge']").attr("id", "ssoidspace");
  $("label[for='edit-email-nonge']").attr("id", "emailidnongespace");
  $("label[for='edit-companyname-nonge']").attr("id", "companyname");
  $("label[for='edit-phonenumber-nonge']").attr("id", "phonenumbernonge");
   $("label[for='edit-city-nonge']").attr("id", "citynon");
   $("label[for='edit-state-nonge']").attr("id", "statenon");
   $("label[for='edit-zipcode-nonge']").attr("id", "zipnon");
   $("label[for='edit-geemployeecontant-nonge']").attr("id", "geemp");
   $("label[for='edit-display2']").attr("id", "geempcontact");
   $("label[for='edit-display3']").attr("id", "geempcontactemail");
  $("#edit-issuelogin-nonge").click(function(e){
  	e.preventDefault();
  alert("If you have an existing SSO and unable  to login , please send your SSO to engage.recip@ge.com");

  });
  $('#edit-geemployeecontant-nonge').change(function() {
       
        var x = $(this).val();
		if(x=='5'){
        $('#edit-display2').val("");
		}
    });
     $("#edit-submit-nonge").click(function(){
		 
		$('.content div#errormsgfirstname').remove();
     
	    var firstname=$("#edit-firstname-nonge").val();
		var lastname=$("#edit-lastname-nonge").val();
		var sso=$("#edit-ssoid-nonge").val();
		var emailid=$("#edit-email-nonge").val();
		var companyname=$("#edit-companyname-nonge").val();
		var jobtitle=$("#edit-jobtitle-nonge").val();
		var phonenumber=$("#edit-phonenumber-nonge").val();
		var address=$("#edit-address-nonge").val();
		var city=$("#edit-city-nonge").val();
		var state=$("#edit-state-nonge").val();
		var country=$("#edit-country-nonge").val();
		var zip=$("#edit-zipcode-nonge").val();
		var geemployeecontact=$("#edit-geemployeecontant-nonge").val();
		var alpha = /^[a-zA-Z\s-, ]+$/;
		var usphonetest = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
		var ziptest=/^[a-z0-9]+$/i;
        var reg = /^([\w-\.]+@(?!ge.com)(?!bhge.com)([\w-]+\.)+[\w-]{2,4})?$/
		var othercontactname=$("#edit-display2").val();
		var othercontactemailid=$("#edit-display3").val();
		var query = $('#edit-display2');
		var isVisible = query.is(':visible');
		console.log(isVisible);
			if(firstname =='')
		{
		$('#errormsgfirstnamenonge').html("please enter your Firstname ");
			$('#errormsgfirstnamenonge').addClass("error");
			$(".form-item-firstname-nonge").append("<div id='errormsgfirstname'>Please Enter Your First Name</div>");
		    return false;
		}
		else if(!firstname.match(alpha)) {
			$('#errormsgfirstnamenonge').html("please enter valid Firstname ");
			$('#errormsgfirstnamenonge').addClass("error");
			$(".form-item-firstname-nonge").append("<div id='errormsgfirstname'>Please Enter valid First Name</div>");
			 return false;
		}
		else if(lastname =='')
		{
		    $('#errormsglastnamenonge').html("please enter your Lastname ");
			$('#errormsglastnamenonge').addClass("error");
			$(".form-item-lastname-nonge").append("<div id='errormsgfirstname'>Please Enter Your Last Name</div>");
      	    return false;
		
		}else if(!lastname.match(alpha)) {
		    
			$('#errormsglastnamenonge').addClass("error");
			$(".form-item-lastname-nonge").append("<div id='errormsgfirstname'>please enter valid Lastname</div>");
			return false;
		}else if(companyname =='')
		{
		
			$('#errormsgcompanynamenonge').addClass("error");
			$(".form-item-companyname-nonge").append("<div id='errormsgfirstname'>please enter your companyname</div>");
      	return false;
		
		}
		else if(!companyname.match(alpha))
		{
		    $('#errormsgcompanynamenonge').addClass("error");
			$(".form-item-companyname-nonge").append("<div id='errormsgfirstname'>please enter valid companyname</div>");
      	    return false;

		}
		else if(jobtitle =='')
		{
			$('#errormsgjobtitlenonge').addClass("error");
			$(".form-item-jobtitle-nonge").append("<div id='errormsgfirstname'>please enter your Job title</div>");
      	    return false;
		
		}else if(!jobtitle.match(alpha))
		{
		    
			$('#errormsgjobtitlenonge').addClass("error");
			$(".form-item-jobtitle-nonge").append("<div id='errormsgfirstname'>please enter Valid Job Title</div>");
      	    return false;
		
		}
		else if(phonenumber =='')
		{
			$('#errormsgphonenumbernonge').addClass("error");
			$(".form-item-phonenumber-nonge").append("<div id='errormsgfirstname'>please enter your phonenumber</div>");
      	    return false;

		}else if(!phonenumber.match(usphonetest))
		{
	
			$('#errormsgphonenumbernonge').addClass("error");
			$(".form-item-phonenumber-nonge").append("<div id='errormsgfirstname'>please enter Valid phonenumber</div>");
      	    return false;
		
		}
		else if(address =='')
		{
			$('#errormsgaddressnonge').addClass("error");
			$(".form-item-address-nonge").append("<div id='errormsgfirstname'>please enter your address</div>");
      	return false;
		
		}
		else if(city =='')
		{
		
			$('#errormsgcitynonge').addClass("error");
			$(".form-item-city-nonge").append("<div id='errormsgfirstname'>please enter your city</div>");
      	return false;
				}
				else if(!city.match(alpha))
		{
			$('#errormsgcitynonge').addClass("error");
			$(".form-item-city-nonge").append("<div id='errormsgfirstname'>please enter Valid city</div>");
      	return false;
		
		}
		else if(state =='')
		{
			$('#errormsgstatenonge').addClass("error");
			$(".form-item-state-nonge").append("<div id='errormsgfirstname'>please enter your state</div>");
      	return false;
				}
				else if(!state.match(alpha))
		{
			$('#errormsgstatenonge').addClass("error");
			$(".form-item-state-nonge").append("<div id='errormsgfirstname'>please enter Valid state</div>");
      	return false;
		
		}
		else if(country =='')
		{
		$('#errormsgcountrynonge').addClass("error");
			$(".form-item-country-nonge").append("<div id='errormsgfirstname'>please enter your country</div>");
      	return false;
				}
				else if(!country.match(alpha))
		{
		
			$('#errormsgcountrynonge').addClass("error");
			$(".form-item-country-nonge").append("<div id='errormsgfirstname'>please enter Valid country</div>");
      	return false;
		
		}
		else if(zip =='')
		{
			$('#errormsgzipcodenonge').addClass("error");
			$(".form-item-zipcode-nonge").append("<div id='errormsgfirstname'>please enter your zip</div>");
      	return false;
				}
				else if(!zip.match(ziptest))
		{
			$('#errormsgzipcodenonge').addClass("error");
			$(".form-item-zipcode-nonge").append("<div id='errormsgfirstname'>please enter Valid zip</div>");
      	return false;
		
		}
		
		else if(geemployeecontact =='')
		{
		    $('#errormsggecontactnonge').addClass("error");
			$(".form-item-geemployeecontant-nonge").append("<div id='errormsgfirstname'>please select a GE Employee Contact</div>");
      	    return false;
					   		
		}
		else if(othercontactname =='' && isVisible == false ) 
		{
			
		 return true;		
		}
			else if(othercontactname =='' && isVisible == true ) 
		{
			$('#errormsgothergecontactname').addClass("error");
			$(".form-item-display2").append("<div id='errormsgfirstname'>please enter a GE contact Name</div>");
		 return false;
		}
		else if(!othercontactname.match(alpha)) {
			$('#errormsgothergecontactname').addClass("error");
			$(".form-item-display2").append("<div id='errormsgfirstname'>please enter a valid  GE contact Name</div>");
			 return false;
		}
		
		else if(othercontactemailid.match(reg) && (othercontactemailid !='')) {
    		$('#errormsgothergecontactemail').addClass("error");
			$(".form-item-display3").append("<div id='errormsgfirstname'>please enter  valid  GE Emailid</div>");
			 return false;
		}
		else{
			if((lastname.match(alpha)) &&(firstname.match(alpha)) && (companyname.match(alpha)) && (jobtitle.match(alpha)) &&(phonenumber.match(usphonetest)) && (city.match(alpha)) && (state.match(alpha)) && (country.match(alpha)) && (zip.match(ziptest))){
				alert("Thank you for the information . A member of our team will contact you shortly with more information regarding your engageRecip access .");
				$( "#my-form-formpage-form" ).submit();
			}
		
		}
   });
        }
    };
})(jQuery, Drupal);

