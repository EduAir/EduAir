$(document).ready(function(){

			
			//Cette fonction affiche un talk
			$('.talking').click(function view() {

                    //On désactive tout les boutons actifs
                    $('#liste_talk .active').attr('class','active_ceci');
					
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
								                  $('#js').remove();//supprime le fichier js qe j'ai mis dans ce div
								                  $('.contenu').html(data_msg);
												  
												  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
                                                }
									});

                                  return false;
					            
            });
			
			
			//Cette fonction affiche plus de talk favoris
			$('.favorite_talk_plus').click(function() {       
													  
	         	var form_data = {nbre_msg : $('#nbre_talk_favorite').html()};
				var ajout = parseInt($('#nbre_talk_favorite').html());
					
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
								    $('.favorite_talk_aff').append(data_msg).fadeIn('slow');
									$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
                                    $('#nbre_msg_locked').html(ajout+50);//Ceci est pour la pagination												  
                                }
				});		            
            });
			
			
			 //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
			
			
});	