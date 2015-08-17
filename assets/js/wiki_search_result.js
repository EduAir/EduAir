
$(document).ready(function(){
	
	//on détache les évènements précédents
	 $('.search_wiki,.plus_wiki_b').unbind('click');
	 
		//pour les articles de wikipedia
			$('.search_wiki').click(function() { 

				if(window.device=='mobile'){

					window.show_page();
				}
			
			    $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//on affiche le message pour patienter
			   		
			    var page_title = $(this).attr('page_title');
				
				//On désactive tout les boutons actifs
                $('.liste_click .active').attr('class','active_ceci');
			
				//Et on active le bouton sur le quel on vien de cliquer	
                $(this).parent().attr('class','active');
				
				//Penser à faire fonctionner les clicks des articles
			    return false;		            
            });
			
			
			
			//Cette fonction affiche plus resultat de wikipedia
			$('.plus_wiki_b').click(function(){       
		
	         	var form_data = {nbre_msg : $('.liste_click').attr('counter')};
				var ajout = parseInt($('.liste_click').attr('counter'));
					
				 //On affiche la box pour patienter
				if(window.device=='standart'){

					$('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
				}
                	
		
	         	$.ajax({
                                        
						url: $(this).attr('action'),
                                        
						type: 'POST',
                                        
						async : true,
                                        
						data: form_data,
						
						dataType:"json",
					                    
						error: function(){alert("theres an error with AJAX");},
                                        
						success:function(papi) {
                                                    if(papi.statu=='success')
													{
						                                $.each(papi.titres, function(entryIndex, entry) {
														
														    
															var start = '<li class="active_ceci" id="my_list'+entry['page_latest']+'" rel="'+entry['page_latest']+'">';
															
															var b = '<a href="#" page_title="'+entry['page_title']+'" page_id="'+entry['page_id']+'" class="search_wiki">';
															
															var c = '<i class="icon-chevron-right" style="float:right;"></i>';
															
															var d = '';//cette variable dit si l'article est nouveau
															
															    var if_new_page = entry['page_is_new'];
																
																if(if_new_page == 1)//Si c'est un nouvel article sur wikipédia on affiche l'étoile.
															    {
																    d = '<span class="bulle" title="'+$('.resultat').attr('new_article')+'" data-placement="right"><i class="icon-star"></i></span>';

																    if(window.device==mobile){

																    	d = '<span  title="'+$('.resultat').attr('new_article')+'" data-icon="star"></span>';
																    }
																}
																
															var list_title = entry['page_title'];															  
															
															var e = list_title.replace(papi.terme,'<b>'+papi.terme+'</b>').replace('_',' ');
															
															var f = '<span style="float:right;"><span  class="label label-info">&nbsp;&nbsp;&nbsp;</span></span>';//span du rien du tout
															
															var end = '</a></li>';
															   
															 //on affiche
                                                           $('.liste_click').append(start+b+c+e+f+end).fadeIn('slow');														 
															
                                                        });
														
					
														$.getScript($('#wiki_search_result').attr('url')); //et on charge le fichier js qui prend en charge les nouveaux éléments											  
                                                   
								                    }
								    if(window.device=='standart'){

								    	$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
								    }
									
                                    $('.liste_click').attr('counter',ajout+50);//Ceci est pour la pagination												  
                                }
				});
              return false;				
            });

			
			
			
		
	   
			
});	