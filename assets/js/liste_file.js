$(document).ready(function(){

			
			
			//Cette fonction affiche les nouveau fichiers
			$('.new_file').click(function() {		            
													  
	         	var form_data = {nbre_msg : 0};
										
				//On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
													  
	         	$.ajax({
                        
						url: $('.new_file').attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) { 									          												 
								    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								    $('#tab_new_file').html(data_msg);
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});					            
            });
			
			
			
			
			
			//Cette fonction affiche plus de nouveau fichier
			$('.new_file_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_file_new').html()};
				var ajout = parseInt($('#nbre_file_new').html());
					
				 //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {
                                    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div						
								    $('.new_file_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_file_new').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			
			//Cette fonction affiche les fichiers favoris
			$('.favorite_file').click(function() {         
													  
	         	var form_data = {nbre_msg : 0};
					
				//On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
													  
	         	$.ajax({
                                        
						url: $('.favorite_file').attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {								          												 
								    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								    $('#tab_favorite').html(data_msg);
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});
            });
			
			
			
			//Cette fonction affiche plus de fichiers favoris
			$('.favorite_file_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_file_favorite').html()};
				var ajout = parseInt($('#nbre_file_favorite').html());
					
				 //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {
                                    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div						
								    $('.favorite_file_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_file_favorite').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			
			
			
			
			//Cette fonction affiche mes fichiers
			$('.my_file').click(function() {    
													  
	         	var form_data = {nbre_msg : 0};
					
				//On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({  
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) { 							          												 
								    
									$('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								    
									$('#tab_my').html(data_msg);
 
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});
         
            });
			
	
	
			
			//Cette fonction affiche plus de mes mes fichiers
			$('.my_file_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_my_file').html()};
				var ajout = parseInt($('#nbre_my_file').html());
					
				 //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {
                                    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div						
								    $('.my_file_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_my_file').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			
			
			//Cette fonction affiche l'état d'upload de fichier
			$('.submit_file').click(function() {       
				
				var progression = 0;  								
                
				//On fait une vérification sur le titre
               			
	         	var titre = $('#titre').val();
				    
                    if(titre.length >=5 && titre!=='')//Sion a le titre c'est bon
                    { 
					    //////////////////on affiche la progress bar
						
						setInterval(function (){
                            
							if(progression < 75)
                            { 							
			                   progression = progression + 1;
                            }
                            						
			                 $("#progressbar_file").progressbar({value: progression});
                        
					   }, 3000); 												
						          					 
					}
                    else
                    {
				       $('#validateTips_file').html($('#erreur').html()).effect("highlight",{},1500);   
                      return false;
                    }				
            });
			
			
			
			 //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
			
			
});	