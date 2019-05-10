// VidalFramework




// Right swipe Menu
/*
 var el = document.getElementById('content-wrapper')
	swipedetect(el, function(swipedir){
  	//  swipedir contains either "none", "left", "right", "top", or "down"
    if (swipedir =='right'){
        alert('You just swiped Right');
	}
})

*/

function systemAlert(message, type){
	 
	
	$("#systemMessages").stop(true, true);
	
	$("#systemMessagesText").html(message);
	$("#systemMessages").removeClass("alert-danger alert-info alert-success alert-warning systemMessages");
	$("#systemMessagesIcon").removeClass('fa-check fa-ban fa-exclamation fa-times-circle fa-exclamation-triangle');
	$("#iconTitle").html();
	
	var return_val = 'error';
	
	switch(type){
		case "success":
		  $("#systemMessages").addClass("alert-success");
		  $("#systemMessagesIcon").addClass('fa-check');
		  $("#iconTitle").html('Success');
		  return_val = true;
		  break;
		case "error":
		  $("#systemMessages").addClass("alert-danger");
		  $("#systemMessagesIcon").addClass('fa-times-circle');
		  $("#iconTitle").html('Error');
		  return_val = false;
		  break;
		case "information":
		  $("#systemMessages").addClass("alert-info");
		  $("#systemMessagesIcon").addClass('fa-exclamation');
		  $("#iconTitle").html('Information');
		  return_val = false;
		  break;
		case "info":
		  $("#systemMessages").addClass("alert-info");
		  $("#systemMessagesIcon").addClass('fa-exclamation');
		  $("#iconTitle").html('Information');
		  return_val = false;
		  break;
		case "warning":
		  $("#systemMessages").addClass("alert-warning");
		  $("#systemMessagesIcon").addClass('fa-exclamation-triangle');
		  $("#iconTitle").html('Warning');
		  return_val = false;
		  break;
		  
		default:
		  $("#systemMessages").addClass("alert-success");
		  $("#systemMessagesIcon").addClass('fa-check');
		  $("#iconTitle").html('Success');
		  return_val = true;
	}
 
	$("#systemMessages").slideDown(500).focus().delay(5000).slideUp(500);
	 
	return return_val; 
}



$('#systemMessages .close').click(function(){
		
		$(this).parent().stop(false, true);
		$(this).parent().slideUp();
		
});


function submitForm(url, form , redirect  ){
	var return_value;
	
	if(url == '' && redirect !='' ){
		
		location.href = redirect ;
		return true;
	}
	
	if(redirect ==''){
		redirect = false;
	}
	
	 
	$.ajax({
		url:url,
		type:'POST',
		async: true,
		data: $('#' + form ).serialize() ,
		dataType:'xml',
		
		success: function(xml) {
			$(xml).find('messages').each(function(){
				var type = $(this).find('type').text();
				var message = $(this).find('msg').text();
				
				 if(redirect != false  && type != 'error' ){ 
				 	location.href = redirect ;
				 }else{
				 	// show message 
					systemAlert(message ,type);	
				 	
				 }
				 
				 
				
			});
		}
		
	});
	
	if(return_value == true){
	 
		return true;
	}else{
		
		return false;
	}
}

function submitFile(url, form , redirect  ){
	var return_value;
	var forma =document.getElementById( form ) ;
	var formData = new FormData(  forma );
	
	if(url == '' && redirect !='' ){
		
		location.href = redirect ;
		return true;
	}
	
	if(redirect ==''){
		redirect = false;
	}
	
	 
	 
	$.ajax({
		url:url,
		type:'POST',
		async: true,
		cache:false,
		data: formData ,
		dataType:'xml',
		processData:false,
		contentType:false,
		
		success: function(xml) {
			$(xml).find('messages').each(function(){
				var type = $(this).find('type').text();
				var message = $(this).find('msg').text();
				return_value = systemAlert(message ,type);	
				
				 if(redirect != false && return_value == true ){  
				 	location.href = redirect ;
					return true;
				 }
				
			});
		}
		
	});
	
	if(return_value == true){
	 
		return true;
	}else{
		
		return false;
	}
}

function submitData(url, dataArray    ){
	 var return_value = 'none'; 
	$.ajax({
		url:url,
		type:'POST',
		async: true,
		data: dataArray ,
		dataType:'xml',
		
		success: function(xml) {
			$(xml).find('messages').each(function(){
				var type = $(this).find('type').text();
				var message = $(this).find('msg').text();
				 
				 return_value = systemAlert(message , type) ;
				
			});
		}
		
	});
	
	
	return return_value;
	
	
}