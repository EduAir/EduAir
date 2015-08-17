
$(document).ready(function(){

 //Ici on gère la liste des utilisateurs dans le chat
 
    //détachons dabord tout évènement click éventuel sur cette class
	$('a.see_msg,#all_conversation').unbind('click');
	
	    
		$('.see_msg').click(function() {

		    window.sender_num = $(this).attr('numero');//on récupère le numéro de la personne cliquée
		
		    window.interloc_user = $('.username_'+window.sender_num).html();

		    window.open_my_space();

		    $('#end_call').click();//on ferme tout appel éventuel
		  	  
		    $('.typing_chat').val('');
		  
		    //$('.typing_chat').focus();
		    $('#toTop').click();//On remonte au top de la page

		  	  

			window.interloc_user = $('.username_'+window.sender_num).html();
	
			  
			   $('#texto').html('');//On efface les message l'espace de texto s'il yen avait
			  
			  //on voit s'il est en ligne
			  window.online(window.sender_num);
		  
			
		  //on diminue le nombre de message non lus.
		   //récupérons son nombre de message nons lus
		   var unread_num = $.trim($('.unread_user_'+window.sender_num).text())*1;
		  
		    if(unread_num !==0)
			{

			  window.unread_msg('less',unread_num,window.sender_num,window.interloc_user);
			}
			
			
		 //effacons le nombre de message contenu dans la bulle
		 $(this).children('.unread_user_'+window.sender_num).html(0);
		 $(this).children('.unread_user_'+window.sender_num).fadeOut();
		 
		 //On désactive tout les boutons actifs
         $('.active').attr('class','active_ceci');
		 
		 //Et on active le bouton sur le quel on vien de cliquer	
          $(this).parent().attr('class','active');
		
          return false;  
	    });	

	//Todo ajouter la fonction d'ajout de nouveau message dans la lits en instantané-ajouter le nombre de nouveau message non lus par onglet
			
});	