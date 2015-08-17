
$(document).ready(function(){

 //Ici on fait les mise à jour pour avoir les tous derniers commentaire en ajax       
		

		/*
 * Si boolCommentaire est true, une nouvelle transaction AJAX 
 * est déclenchée 3s après la fin de la précédente
 */
 var boolCommentaire = true;
 
		
		$(function commentaire_talker()
        {
		    var form_data_talk = {last_com_talk: $("#my_last_time").attr('time'),id_talk: $("#my_last_time").attr('talker')};//Celle ci stoque un temps comme reférentiel
			
           $.ajax({

				            type: 'post',

				            url: $("#my_last_time").attr('url_verif'),
							
							async : true,
							
							data: form_data_talk,

				            success: function(data_maj){
							
									            if($.trim(data_maj) =='yes') //s'il ya des mise a jours à faire
												{
												    form_data_talk = {id_talk: $("#my_last_time").attr('talker'),last_com_talk: $("#my_last_time").attr('time')};
	                                               
												   //On fait la requete ajax qui récupère les derniers commentaires 
                                                    $.ajax({
                                        
										                    url: $('#my_last_time').attr('url_maj'),
                                        
										                    type: 'POST',
                                        
										                    async : false,
												
												            data: form_data_talk,
                                        
										                    success:function(data_msg) {
															
												                     response = $.trim(data_msg);
																	 
                                                                     $('.append_talk').append($(response).fadeIn('slow'));
																	 
																	 $('#attend').html('');//on arrete de faire patienter
																	 
                                                                         //On récupère le dernier timestamp du serveur 
                                                                         $.ajax({
                                        
										                                         url: $('#my_last_time').attr('my_last_time'),
                                        
										                                         type: 'POST',
                                        
										                                         async : true,										
                                        
										                                         success:function(data_msg) {
															
												                                         response = $.trim(data_msg);
																	                    
																						 $("#my_last_time").attr('time',response);
                                                                                         
												                                        }
									                                    });	

                                                                        		
			                                                            if ( boolCommentaire ){
			                                                                setTimeout( commentaire_talker, 3000);
		                                                                }																		
												                    }
									                });													
                                                }
                                                else
                                                {												    		
			                                        if ( boolCommentaire ){
			                                            setTimeout( commentaire_talker, 3000);
		                                            }
												}												
				                    }

			        });										
        });
		
		
		
		//C'est ici qu'on soumet aussi lescommentaires appuyany sur "entré"
		$('.commentMark').focus(function() {

			$(document).keypress(function(evenement){  
			
			// Si evenement.which existe, codeTouche vaut celui-ci.
				 // Sinon codeTouche vaut evenement.keyCode.
                var codeTouche = evenement.which || evenement.keyCode;
               
			    if(codeTouche==13)//On envoi le message si on appui sur la touche Entré
				{
				 $('#SubmitComment').click();
				}
            });
			
         return false;
		 
        });	
	


	
			

	   
			
});	