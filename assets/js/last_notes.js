$(document).ready(function(){

     
			
			//Cette fonction fait voir l'url d'une notification
			$('.see_note').click(function view() {
			
                    //j'affiche le slogan
                    $('.messenger').html($(this).attr('action'));//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
	                $('#info_msg').fadeIn().delay(2000).fadeOut();													  

                return false;
					            
            });
			
});	