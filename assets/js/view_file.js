$(document).ready(function(){

     
			
			//Cette fonction affiche un fichier
			$('.look_at').click(function view() {

                    //On désactive tout les boutons actifs
                    $('#liste_file .active').attr('class','active_ceci');
					
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
			
			
			
			
			
			 //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
			
			
});	