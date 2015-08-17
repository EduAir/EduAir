
$(document).ready(function(){
	
	//Cette fonction fait marcher les infobulles
	$(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
	
	//On crée maintenant des évènementonclick sur chaque ty de recherche
	
	  //pour les talks
			$('.search_talk').click(function() { 
			
			        //On affiche la box pour patienter
                   
				   $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
				   
				    //On désactive tout les boutons actifs
                    $('.liste_click .active').attr('class','active_ceci');
					
				    //Et on active le bouton sur le quel on vien de cliquer	
                    $(this).parent().attr('class','active');
					
	         		$.ajax({  //On affiche tout dabord les onglets avec cette requete ajax
    
							url: $(this).attr('action'),
                                        
							type: 'POST',
                                        
							async : true,
					                    
							error: function(){alert("theres an error with AJAX");},
                                        
							success:function(page_aff) {									          												 
								 
								      $('.contenu').html(page_aff);
									  
									  
									                if($('#js_talk').attr('statu')=='no')//REgardons si le fichier js qui gère l'affichage des talk en instantané est déjà là
												    {
												      var jqXHRCommentaire_time = $.post($('#js_talk').attr('live'), '' );
					  
					                                    jqXHRCommentaire_time.done( function( data, textStatus, jqXHR ){
					   
					                                        response = $.trim(data);
																	                    
							                                $("#js_talk").html(response);
					                                    });												  
												      

                                                       $('#js_talk').attr('statu','yes');												  
                                                    }
									  
									  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
								   
									}
					});
			 return false;		            
            });
			
			
			
		//pour les fichiers
			$('.search_file').click(function() { 
			
			        //On affiche la box pour patienter
                   
				   $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
				   
				   //On désactive tout les boutons actifs
                    $('.liste_click .active').attr('class','active_ceci');
					
				    //Et on active le bouton sur le quel on vien de cliquer	
                    $(this).parent().attr('class','active');
					
	         		$.ajax({  //On affiche tout dabord les onglets avec cette requete ajax
    
							url: $(this).attr('action'),
                                        
							type: 'POST',
                                        
							async : true,
					                    
							error: function(){alert("theres an error with AJAX");},
                                        
							success:function(page_aff) {									          												 
								 
								      $('.contenu').html(page_aff);
									  
									  
									                if($('#js_file').attr('statu')=='no')//REgardons si le fichier js qui gère l'affichage des talk en instantané est déjà là
												    {
												      var jqXHRCommentaire_time = $.post($('#js_file').attr('live'), '' );
					  
					                                    jqXHRCommentaire_time.done( function( data, textStatus, jqXHR ){
					   
					                                        response = $.trim(data);
																	                    
							                                $("#js_file").html(response);
					                                    });												  
												      

                                                       $('#js_file').attr('statu','yes');												  
                                                    }
									  
									  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
								   
									}
					});
			  return false;		            
            });
			
			
					
			//Cette fonction affiche plus resultat de talk
			$('.plus_talk').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#plus_talk').attr('counter')};
				var ajout = parseInt($('#plus_talk').attr('counter'));
					
				 //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {					
								    $('#plus_talk').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#plus_talk').attr('counter',ajout+50);//Ceci est pour la pagination												  
                                }
				});
              return false;				
            });
			
			
			//Cette fonction affiche plus resultat de fichier
			$('.plus_file').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#plus_file').attr('counter')};
				var ajout = parseInt($('#plus_file').attr('counter'));
					
				 //On affiche la box pour patienter
                $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();	
													  
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {					
								    $('#plus_file').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#plus_file').attr('counter',ajout+50);//Ceci est pour la pagination												  
                                }
				});
              return false;				
            });
			
			
			
			
		
	   
			
});	