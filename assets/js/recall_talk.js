
$(document).ready(function(){


 //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
	  		
		//Cette fonction efface le talk en recall et ensuite affiche le talk en question
			$('.go_here').click(function() {
			
				var leap = $(this);
					
	            if(leap.attr('afficheur')=='1')
				{
				  //On récupère le talk et on affiche									

                    $.ajax({  //On affiche tout dabord les onglets avec cette requete ajax
                                        
							url: leap.attr('affiche'),
                                        
							type: 'POST',
                                        
							async : true,
                                        
							error: function(){alert("theres an error with AJAX");},	
                  
                            success:function(data_talker)  {  

                                                               $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();													
								                  
                                                               $('.contenu').html(data_talker);
												  
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
															  
															  leap.parent().fadeOut();//On efface la box parente
																						 
                                                            }				  
                    });
		        }
			    else
				{
				 leap.parent().fadeOut();//On efface la box parente
				}

              return false;
					            
            });	
			
});	