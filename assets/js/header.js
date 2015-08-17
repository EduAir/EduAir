	

	$(document).ready(function(){	
	
	//Cette fonction fait marcher les infobulles
	if(window.is_mobile==true){

		$(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
	}
	
	
	
	
	//Cette fonction la liste des préférences de l'user
			$('.my_prefer').click(function() {
			
			    //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
					
	         			                $.ajax({
                                        
										url: $('.my_prefer_liste').attr('action'),
                                        
										type: 'POST',
                                        
										async : true,
                                        					                    
										error: function(){alert("theres an error with AJAX");},
                                        
										success:function(data_msg) {										          												 
								                  $('.contenu').html(data_msg);
												  
												  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
                                                }
									});

                                  return false;
					            
            });
			
			
			
    //Cette fonction met à jour la liste des préférences
			$('.submit_prefer').click(function() {
			
			    if($('#input_preference').val()!=='')
				{
					
					var form_data = {prefer: $('#input_preference').val()};
	         			                $.ajax({
                                        
										url: $('.my_prefer_liste').attr('rel'),
                                        
										type: 'POST',
                                        
										async : true,
										
										data: form_data,
                                        					                    
										error: function(){alert("theres an error with AJAX");},
                                        
										success:function(data_msg) {

                                                   $('#myModal_preference').modal('hide');
													
												   $('.messenger').html(data_msg);//On met le message dans le conteneur #messenger 
												   
									               $('#info_msg').fadeIn().delay(messageDelay).fadeOut();                         
                                                }
									});

                                  return false;
			    }
					            
            });
		
	});	

	