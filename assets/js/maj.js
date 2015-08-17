
	$(document).ready(function(){
		
        //Lan�ons le chargement des notifications

		if($.jStorage.storageAvailable()){loader();}//on t�l�charge les notifications		
		
		
		
		//mise � jour des notication en temps r�el
		var socket  = io.connect($('#url_node').attr('url'));
       
        socket.on('new_note',function (data) {
		
		    window.all_my_notification();
            
			window.loader();
        });
		
		
		
		


		//cette fonction t�l�charge les notifications
		function loader(){ 
		  //je fait la requete qui r�cup�re les dernieres notifications
		    $.ajax({  //On affiche tout dabord les onglets avec cette requete ajax
    
							url: $('.my_msg_pub_out').attr('action')+50,
                                        
							type: 'POST',
                                        
							async : true,
							
							dataType:"json",
					                    
							error: function(){console.log('Pas de connexion');},
                                        
							success:function(data) {

								if(data.statu =='succes')
			                    {
		                          //je fait l'enregistrement en local.Pour cela je 
			                      var all_note = new Array();
				  
				                  var i=0;
			 
			                      //je cr�� dabord le tableau qui va contenir les pubs
			                        $.each(data.notification, function(entryIndex, entry) {
                
                                     var entrer = new Array();//je cr�� un petit tableau pour chaque entr�
					
					                 entrer.push(entry['message']);
					                 entrer.push(entry['slogan']);
					                 entrer.push(entry['timestamp']);
					                 entrer.push(entry['image']);
					    
					                 all_note.push(entrer);//et je met ce tableau dans le grand tableau
					   
					                 i=i+1;
					   
					                    if(i==data.counter)
						                {
						                  $.jStorage.deleteKey('all_note');
						                  $.jStorage.deleteKey('counter_note');
						                  $.jStorage.set('all_note',all_note);//et on enregistre ce tableau
						                  $.jStorage.set('counter_note',data.counter);
						                  
					                    }

			                        	
			                        });			  
			                    }  
							}
			});
		}
		
	
	});	
	
	
	