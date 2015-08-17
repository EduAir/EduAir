
$(document).ready(function(){

         //Ici on fait des coucou aux users
		 
		 var messageDelay = 3000; //durée d'apprition du message en milliseconde  
		 
	    //Cette fonction fait marcher les infobulles
	   $(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle

         //Envoi un message coucou
		$('.coucou').click(function() {
		 
		    var coucou = $(this);
				
				$.ajax({

				    type: 'post',

				    url:  $(this).attr('action'),
					
					async : false,
										
					error: function(){alert("theres an error with AJAX");},

				    success: function(){	
										coucou.parent().html($('.confirm').html());
				             }

			    });
			
		});	
		
	   
			
});	