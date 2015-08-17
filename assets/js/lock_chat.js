$(document).ready(function(){

     //ce fichier s'occupe de l'affichahe lors du chat
		
		//cette ligne est pour rendre le formulaire textarea élastique
		$('#watermark').elastic();
				
			
		//Cette fonction block les messages des utilisateurs
			$('a.block_him').click(function() {
			 			 
			 var link = $(this);
                    
			form_data = { id_conversation : $("#watermark").attr('rel')};
			
			 $('#myModal_block').modal('hide')	//Et on ferme la fenêtre modale	
              
			    $.ajax({
                    
					url: link.attr('action'),
                    
					type: 'POST',
                    
					async : false,
					
					data: form_data,
                    
					success: function(data_msg) {
								 $('.contenu').html(data_msg);	//On affiche le message de succes                                						 
								}
                });
                
                return false;
            });
			
			
			
			//Cette fonction déblock les messages des utilisateurs
			$('a.deblock_him').click(function() {
			 			 
			 var link = $(this);
                    
			form_data = { id_conversation : $("#watermark").attr('rel')};
			
			 $('#myModal_deblock').modal('hide')	//Et on ferme la fenêtre modale	
              
			    $.ajax({
                    
					url: link.attr('action'),
                    
					type: 'POST',
                    
					async : false,
					
					data: form_data,
                    
					success: function(data_msg) {
								 $('.contenu').html(data_msg);	//On affiche le message de succes                                						 
								}
                });
                
                return false;
            });
		
		
		
	
});

	