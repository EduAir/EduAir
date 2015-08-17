$(document).ready(function(){

     
			
			//Cette fonction affiche un message
			$('.my_msg_perso').click(function() {

                    //On désactive tout les boutons actifs
                    $('#liste_msg .active').attr('class','active_ceci');
					
				    //Et on active le bouton sur le quel on vien de cliquer	
                    $(this).parent().attr('class','active');

					//On affiche la box pour patienter
                    $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();				
													  
	         			                $.ajax({
                                        
										url: $(this).attr('rel'),
                                        
										type: 'POST',
                                        
										async : true,
                                        					                    
										error: function(){alert("theres an error with AJAX");},
                                        
										success:function(data_msg) {										          												 
								                  
												  $('.contenu').html(data_msg);
												  
												  
												    if($('#js_chat').attr('statu')=='no')
												    {
												      var jqXHRCommentaire_time = $.post($('#js_chat').attr('live'), '' );
					  
					                                    jqXHRCommentaire_time.done( function( data, textStatus, jqXHR ){
					   
					                                        response = $.trim(data);
																	                    
							                                $("#js_chat").html(response);
					                                    });												  
												      

                                                       $('#js_chat').attr('statu','yes');												  
                                                    }
												  
												  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
                                                }
									});

                                  return false;
					            
            });
			
			
			
			//Cette fonction affiche les messages non lus
			$('.my_unread').click(function() {		            
													  
	         	var form_data = {nbre_msg : 0};
										
				//On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
													  
	         	$.ajax({
                        
						url: $('.my_unread').attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) { 									          												 
								    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								    $('#tab_unread').html(data_msg);
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});					            
            });
			
			
			
			
			
			//Cette fonction affiche plus de messages non lus
			$('.my_unread_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_msg_unread').html()};
				var ajout = parseInt($('#nbre_msg_unread').html());
					
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
								    $('.my_unread_msg_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_msg_unread').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			
			//Cette fonction affiche les messages fermés
			$('.my_locked').click(function() {	            
													  
	         	var form_data = {nbre_msg : 0};
					
				//On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
													  
	         	$.ajax({
                                        
						url: $('.my_locked').attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {								          												 
								    $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								    $('#tab_locked').html(data_msg);
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});
            });
			
			
			
			//Cette fonction affiche plus de messages fermés
			$('.my_locked_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_msg_locked').html()};
				var ajout = parseInt($('#nbre_msg_locked').html());
					
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
								    $('.my_locked_msg_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_msg_locked').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			
			
			
			
			//Cette fonction affiche les nouveaux messages 
			$('.my_new_msg').click(function() {    
													  
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
								    
									$('#tab_new').html(data_msg);
 
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			               	
                                }
				});
         
            });
			
	
	
			
			//Cette fonction affiche plus les nouveaux messages 
			$('.my_new_msg_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_msg_new').html()};
				var ajout = parseInt($('#nbre_msg_new').html());
					
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
								    $('.my_new_msg_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_msg_new').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
});	