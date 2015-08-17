
$(document).ready(function(){

         var socket  = io.connect($('#url_node').attr('url'));
		 
         var messageDelay = 4000; //durée d'apprition du message e milliseconde

         var delay_notif = 12000;

         var timeout_notty = 10000.  
	
			//Ici on s'occupe dela validation du formulaire
			function updateTips(t) {

			   $("#validateTips").text(t).effect("bounce",{},1500);
		    }

		   	
		
		    function checkMessage(o,n) {

			    if ( o.length <= 0 ) {

				    $("#inputSlogan_msg").addClass('ui-state-error');
				    
				    updateTips($('#erreur_message').text());
				   
				    return false;
			    } else {
				    return true;
			    }

		    }
			
			
			//on fait apparaitre le compteur de caractère
			$("#inputNumber_msg_contain").keyup(function()
            {
			    var max_char = 160;
			  
                var box=$(this).val();
                var main = box.length *100;
                var valeur= (main / max_char);
                var count= max_char - box.length;

                if(box.length <= max_char)
                {
				  //progressbar
				    $("#bar_note").progressbar({
					   value: valeur
				    }).width(100);//
				
				 //compteur incrémenté
                   $('#counter_msg').html(count);
                   
                }
                else
                {
                  alert('OoooooH! STOP! ');
                }
              return false;
            });
			
		//Cette fonction fait des requêtes ajax par click et affiche la réponse en json
			$('.submit_msg').click(function() {

			     var message   = $("#inputNumber_msg_contain").val(),
			
			                    allFields = $([]).add($("#inputNumber_msg_contain")),
					            bValid = true;
					            allFields.removeClass('ui-state-error');
					
                               bValid = bValid && checkMessage(message,"message");         				
						
					            if(bValid){ 
	  
					               var form_data = {message:message};
								   								   
								    $('#myModal_form_msg').modal('hide');
								   
								     //effacons les entrées du formulaire
									$("#inputNumber_msg_contain").val('');
									
								    socket.emit('to_all_family',form_data);//On envoit le message à tout le monde
	         			          
                                  return false;
					            }
            });


            socket.on('to_all_family',function(final_form){

            	send_to_familly(final_form);
            });


            socket.on('forbid',function(){

            	$('.messenger').html($('.not_allow_family').attr('message'));
	            $('#bad_msg').fadeIn().delay(messageDelay).fadeOut();
            })

            

            //Envoi une annonce à tout le monde
            function send_to_familly(form_data){

                $.ajax({
                                        
						url: $('#url_traitMsgForm').html(),
                                        
						type: 'POST',
                                        
						async : false,
                                        
						data: form_data,
										
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(data_msg) {
										          
									data = $.trim(data_msg); 
								                
								    $('.messenger').html('CoOl.Done !');//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
									
									switch (data) {
	                                                     
										                case 'succes':
														  socket.emit('news');//on le dit à tout le monde
														  $('.messenger').html('CoOl.Done !');//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
	                                                      $('#good_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
														 
	                                                     case 'fail':
														   $('.messenger').html('Houston! we have a problem...');//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
	                                                       $('#bad_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
	                                                }	
                        }
				});
            }

           
           //On recoit tous les messages publiques
            socket.on('famille',function(data){ //(titre,message,image)

            	//cette fonction déclanche l'apparition de la notification
            	 notty_it(data.message);

            	//On enregistre le message
            	var note_nbre = $.jStorage.get('note_nbre','nada');

            	if(note_nbre=='nada'){//S'il nya aucune notification

                    //On en crée
                    $.jStorage.set('note_nbre',1);
                    $.jStorage.set('note',[data.message,' ']);
            	}else{

            		$.jStorage.set('note_nbre',$.jStorage.set('note_nbre')*1+1);

            		var note =  $.jStorage.get('note');

            		var new_note = [data.message,' '];

                    note.push(new_note);

                    $.jStorage.set('note',note);

                    My_notty($.jStorage.get('note'),0);//On fait défiler
            	}   
            })

			


            function affiche_list_note(data){

				//affiche les chevrons de la liste
			    $('.liste').html('<ul class="nav nav-list bs-docs-sidenav liste_notes"><li class="nav-header"> notifications <span class="badge badge-info">'+data.counter+'</span></li></ul>');
											   
                $.each(data.notification, function(entryIndex, entry) {
				    //ne pas oublier de télécharger les pubs
											
						    var a ='<li class="active_ceci">';
											
							var b ='<a href="#">';
											
							var c ='<div class="talk_post">';
											
							var d ='<h5><i class="icon-bell"></i> <span class="label label-info">  </span> </h5>';
											
							var e = entry['message'];

							var f = '<ul class="nav nav-pills"><li><i class="icon-time"></i><span class="user_post">'+entry['timestamp']+'</span> </li></ul>';
											
							var g = '</div></a></li>';
											
							//on affiche
                            $('.liste_notes').append(a+b+c+d+e+f+g).fadeIn('slow');

						    $.getScript($('#url_js_note').attr('action')); //et on charge le fichier js qui prend en charge les nouveaux éléments				 											
                });

                $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	 		
            }



   
 
			
			

        

		
	    
		
		//cette fonction liste les pubs téléchargés
		function see_note(){
		  //je récupère cette fameuse liste en local
		    
			if($.jStorage.storageAvailable() && $.jStorage.get('note_nbre'))
			{
			   //affiche les chevrons de la liste
			   $('.liste').html('<ul class="nav nav-list bs-docs-sidenav liste_notes"><li class="nav-header"> notifications <span class="badge badge-info">'+$.jStorage.get('note_nbre')+'</span></li></ul>');
										
		        var all_note = $.jStorage.get('note');

		       var note_nbre = all_note.length;
		      
				for(i=0; i < note_nbre; i++){ 
	               
				        var note_indiv = all_note[i];
				  
					    var a ='<li class="active_ceci">';
											
						var b ='<a href="#" class="see_note" action="'+note_indiv[1]+'">';
											
					    var c ='<div class="talk_post">';
											
						var d ='<h5><i class="icon-bell"></i> <span class="label label-info">  </span> </h5>';
											
						var e = note_indiv[0];

						var f = '<ul class="nav nav-pills"><li><i class="icon-time"></i><span class="user_post">'+note_indiv[1]+'</span> </li></ul>';
											
						var g = '</div></a></li>';
											
						   //on affiche
                        $('.liste_notes').append(a+b+c+d+e+f+g).fadeIn('slow');
						
						if(i==note_nbre-1)
						{ 
						   //on détache les évènements précédents
	                        $('.see_note').unbind('click');
				
							$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	 
						}					
                }
			}
		}



			
});	