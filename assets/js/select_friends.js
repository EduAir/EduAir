$(document).ready(function(){			
						
		 //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
	   
	   
	   //Cette fonction sélectionne les numéros de téléphone pour mettre dans le des numbers
			$('.take_me').click(function() {       
													  
	         	//On prend le numéro de téléphone pour le mettre dans le champs des destinataires 
				$('#mes_potes').val($(this).attr('number')+','+$('#mes_potes').val());
				
				$('#inputNumber_msg').val($(this).attr('number')+','+$('#inputNumber_msg').val());
				
				//On retire maintenant le numéro dont on a cliqué dessus
				$(this).parent().fadeOut();
                 	            
            });
			
			
});	