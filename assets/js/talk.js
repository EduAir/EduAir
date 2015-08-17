
$(document).ready(function(){

         //Ici on gère le module de talk
		 
		 var messageDelay = 4000; //durée d'apprition du message en milliseconde  			
		
    //Ici on compte le nobre de carectère pendant que l'user écrit
		
		$("#input_talk_contain").keyup(function()
        {
          var box=$(this).val();
          var main = box.length *100;
          var value= (main / 160);
          var count= 160 - box.length;

            if(box.length <= 160)
            {
             $('#count').html(count);
             $('#bar').animate(
                {
                 "width": value+'%',
                }, 1);
            }
            else
            {
               alert('OoooH! STOP!!');
            }
             return false;
        });

		
			//Ici on s'occupe dela validation du formulaire
			function updateTips(t) {
			   $("#validateTips_talk").text(t).effect("bounce",{},1500);
		    }

		    function checkLength(o,n,min,max) {

			    if ( o.val().length > max || o.val().length < min ) {
				    o.addClass('ui-state-error');
				    updateTips($('#erreur_talk').text());
				    return false;
			    } else {
				    return true;
			    }

		    }		
		
		    function checkMessage(o,n) {

			    if ( o.val().length <= 0 ) {
				    o.addClass('ui-state-error');
				    updateTips($('#erreur_message').text());
				    return false;
			    } else {
				    return true;
			    }

		    }
			
			
		//Cette fonction soumet un nouveau talk en ajax affiche la réponse en json
			$('.submit_talk').click(function() {
			    
			    var message_talk = $("#input_talk_contain"),
					talk_url     = $('#url_trait_talk').html(),
			
			                    allFields = $([]).add(message_talk),
					            bValid = true;
					            allFields.removeClass('ui-state-error');
					
                                bValid = bValid && checkLength(message_talk,"talk",1,160);
					          
					            if(bValid){
	  
					               var form_data = {talk : $("#input_talk_contain").val(),friends_list:$('#mes_potes').val()};
								   
								    $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//On affiche la box pour patienter
								   								   
								   $('#myModal_form_talk').modal('hide');
								   
								     //effacons les entrées du formulaire
									$("#input_talk_contain").val('');
									
	         			                $.ajax({
                                        
										url: talk_url,
                                        
										type: 'POST',
                                        
										async : true,
                                        
										data: form_data,
										
										dataType:"json",
					                    
										error: function(){ $('#info_msg_wait').fadeOut();},//On efface la box qui fait patienter,
                                        
										success:function(data_msg) {
										          var statu_messenger = data_msg.statu;												 
								 
								                 /*  $('.messenger').html(data_msg.messenger);//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
									
									               switch (statu_messenger) {
	                                                     case 'good':
	                                                      $('#good_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
	                                                     case 'bad':
	                                                      $('#bad_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
	                                                     case 'info':
	                                                       $('#info_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;	
                                                    }*/	
													 
													$('#count').html('160'); //Je rénitailise le compteur de caractère
													$("#validateTips_talk").html('');//j'éfface d'évantuels message d'erreur
													$("#input_talk_contain").val('');
													
													  //je fait la requette ajax qui affiche le dernier talk de l'user
                                                        $.ajax({
                                        
										                        url: $('#url_last_talk').html(),
                                        
										                        type: 'POST',
                                        
										                        async : true,
                                        
										                        error: function(){  $('#info_msg_wait').fadeOut();},//On efface la box qui fait patienter,
                                        
										                        success:function(data_msg) {
                                                
												                    $('.contenu').html(data_msg);
																	 															
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
												
												}
									});

                                  return false;
					            }
            });
			
	

            //Cette fonction annule le message qu'on voulait envoyer
			$('.submit_closer_talk').click(function() {
			
			   $('#count').html('160'); //Je rénitailise le compteur de caractère
			   
			   $("#validateTips_talk").html('');//j'éfface d'évantuels message d'erreur
			   
			   $("#input_talk_contain").val('');
            });	
			
			
});	