	

	$(document).ready(function(){	
	
	
	
	 //Cette fonction la liste des préférences de l'user chaque fois qu'il clique dessus
			
			$('.my_choice').click(function() {
			
			    //je récupère l'id du choix de la préférence dont il vient de cliquer dessus
				$('#all_my_choice').html($('#all_my_choice').html()+$(this).attr('value')+',');
					            
            });
			
		
		
		
		
	//Cette fonction met à jour la liste des préférences
			$('.submit_prefer').click(function() {
			
			    if($('#all_my_choice').html()!=='')
				{
					
					var form_data = {prefer: $('#all_my_choice').html()};
	         			                $.ajax({
                                        
										url: $('.my_prefer_liste').attr('rel'),
                                        
										type: 'POST',
                                        
										async : true,
										
										data: form_data,
                                        					                    
										error: function(){alert("theres an error with AJAX");},
                                        
										success:function(data_msg) {

                                                   $('#myModal_preference').modal('hide');
													
												   $('.messenger').html(data_msg);//On met le message dans le conteneur #messenger 
												   
									               $('#info_msg').fadeIn().delay('5000').fadeOut(); 
                    
                                                   $('.contenu').html('');//En on vide le contenu					
                                                }
									});

                                  return false;
			    }
					            
            });	
			
			
			
			//Cette fonction annule le formulaire de mise a jour des infos
			$('.submit_cancel').click(function() {
			
			   	$('#all_my_choice').html('')//ON vide tous les choix
				
				$('.contenu').html('');//En on vide le contenu					
                
				return false;
		            
            });	
			
			
		
	});	

	