$(document).ready(function(){

    $('.contact,.my_msg').unbind('click');

    $('.contact').click(function(){
		
		   //on regarde en local s'il ya la liste des contacts
		   var all_contact = $.jStorage.get('all_contact','nada');
		   
		    if(all_contact !=='nada')
			{
				if(window.device=='mobile'){

					window.hide_page();
				}
			  //affiche les chevrons de la liste
			  $('.liste').html('<ul class="nav nav-list bs-docs-sidenav list_unread"><li class="nav-header">'+$('.contact').html()+'</li></ul>');
				
			   var nbre_user = all_contact.length;console.log(all_contact); 
			   
			    for(i = 0;i < nbre_user;i++)
			    {	
				  var indiv_user = $.jStorage.get('contact_'+all_contact[i]); 
				  
				 window.all_contact_list(indiv_user[0],indiv_user[1]);
				  
				    if(i == (nbre_user-1))
					{
					   $.getScript($('#url_js_chat').attr('action')); //et on charge le fichier js qui prend en charge les nouveaux éléments
					}
				}
			}
			else
			{
			   notificate_it($('#alert').attr('no_contact'),'error','bottomRight');
			}
		
		});
		
		
		window.all_contact_list = function(numero,name)
		{
		  //j'affiche le tout
		    var a ='<li class="active_ceci">';
											
			var b ='<a href="#" class="see_msg" numero="'+numero+'">';
											
			var c ='<i class="icon-chevron-right" style="float:right;"></i>';
					  
			var d = '<span class="pencil_'+numero+'"></span>&nbsp;&nbsp;'
					  
			var e = '<span class="username_'+numero+'">'+name+'</span>';
					  
			var f = '</a></li>';
					 
				    
			$('.list_unread').append(a+b+c+d+e+f).fadeIn('slow');
		}



        //var all_my_friend = get_all_friend();

        function get_all_friend()
        {
		    $.ajax({

				    type: 'post',

				    url:  $('#friends_liste').attr('url'),
					
					async : true,
					
					dataType:"json",
					
					success: function(data){
					    
						    $.each(data, function(entryIndex, entry) {
						 
						       window.contact_list(entry['friend_name'],entry['friend_number']);//on enregistre les contacts en local
       
                            });
						}
			});
		  
        }


		//Cette fonction affiche les contacts et les messages
			$('.my_msg').click(function() {
			
			  //On affiche la box pour patienter
              $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();

                if(window.device=="mobile"){

					window.hide_page();
				}
				 
			  var numero_actuel ='';
			  
			  var sender_name ='';
			  
			  var sender_num = '';
			  
			  var nbre_unread ='';
			  
			  var contact_num =''
			  	
			  var  all_number = window.all_interloc_tab.length*1;
				
			    if(all_number!==0)//S'il ya des messages en mémoire
				{
                 //affiche les chevrons de la liste
			     $('.liste').html('<ul class="nav nav-list bs-docs-sidenav list_unread"><li class="nav-header"> '+$('.my_msg').html()+'</li></ul>');
								
				    for(i=0;i < all_number;i++)
					{
					  numero_actuel = window.all_interloc_tab[i];
					  
					  //je récupère son username
					  contact_num = numero_actuel;
					  sender_name = window.all_interloc[numero_actuel][0][0];
					  sender_num  = numero_actuel;
					  
					  //je récupère son nombre de message
					  nbre_unread = window.all_interloc[numero_actuel][0][1];
					  
					  //j'affiche le tout
					  var a ='<li class="active_ceci">';
											
					  var b ='<a href="#" class="see_msg" numero="'+sender_num+'">';
											
					  var c ='<i class="icon-chevron-right" style="float:right;"></i>';
					  
					  var d = '<span class="pencil_'+sender_num+'"></span><span class="label label-warning unread_user_'+sender_num+' ">'+nbre_unread+'</span>&nbsp;&nbsp;'
					  
					  var e = '<span class="username_'+sender_num+'">'+sender_name+'</span>';
					  
					  var f = '</a></li>';
					 
				     $('#texto').html('');//je vide le chat
				  
                     $('.list_unread').append(a+b+c+d+e+f).fadeIn('slow');
					 
					    if(i==(all_number-1))
					    {
					     $.getScript($('#url_js_chat').attr('action')); //et on charge le fichier js qui prend en charge les nouveaux éléments										
				        }                   
					}					
                }
                else
                {
				   window.notificate_it($('#alert').attr('no_active_conv'),'error','bottomRight');
				}

             $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter			  
			 	
			  return false;
            });	


            //Ici on ouvre le formulaire pour le numéro de téléphone
		$('.sender_message').click(function() { 
		 
		    $('.number_phone').val('');//on efface le formulaire
			$('#my_person').modal('show');//on fait appataitre le formulaire

			$('.incognito_name').fadeOut();//On ferme le formulaire de l'username de l'interlocuteur si cest ouvert
			
			$(document).ready(function(){

				$('.number_phone').focus();

                //C'est ici qu'on regerde le numéro le téléphone qu'il a tapé en appuyant sur la touche "entré" du clavier			
			
			    $('.number_phone').keypress(function(evenement){
			
                 // Si evenement.which existe, codeTouche vaut celui-ci.
				 // Sinon codeTouche vaut evenement.keyCode.
                    var codeTouche = evenement.which || evenement.keyCode;
               
			        if(codeTouche==13)//On lance la recherche si on appui sur la touche Entré
				    {
				       $('.call_number').click();

				       return false;
				    }
                });		
			});		
			
		});			
      
});