$(document).ready(function(){

            var dbName = $('#get_API').attr('local_db');
    
			var url_api = $('#get_API').attr('api');
										

			$('.wiki_content a:not(.new,.toc a,.internal)').unbind('click');
			
	var statu_requete = true;		
			
			//pour les clicks de téléchargement
			
			$('.wiki_content a:not(.new,.toc a,.internal)').click(function() { 
			
			statu_requete  = true;
			
				//on fait une confirmation
				var statu_loader = confirm($('.confirm_loader').attr('msg'));
				
				if(statu_loader){
				    
				    window.true_level = 0;
					progression(0);//initialisation de la barre de téléchargemnet

					var etat_level = 0;
					
					$('.num_level').html(0);
					
				    $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//on affiche le message pour patienter
				
				   $('#Modal_loader').modal({'backdrop':false,'keyboard':false,});

				   $.blockUI();
				   
				   //on vérifie le support d'indexeddb
				    if (!window.indexedDB){
					
					 $('.load_msg').html($('.confirm_loader').attr('close'));
					 
					 $('.body_loader').html($('.confirm_loader').html());
					
					 $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
					}
					else
					{
					  progression(0);//initialisation de la barre de téléchargemnet
					  
					  var etat_level = 0;
					  
					  $('.num_level').html(0);
					 
					 $('.load_msg').html($('.confirm_loader').attr('stop'));
					 
					 //fonction de chargement des urls des articles
					 var statu_continue = 'new_babi';
					 
					 $('#hide_progress').attr('style','display:none;');//on cache la barre de progression
					
					 load_all_urls($(this).attr('href'),$(this).text(),statu_continue);
				              	
			         $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
					}
				}
			  return false;		            
            });
			
			
	    //fonction pour charger toutes les ids des pages de la catégorie
		function load_all_urls(category_url,category_title,statu_continue)
		{
		    switch(statu_continue)
			{
			    case false:
				    //on enregistre en local
					
					//Cette fonction met a jour la liste des catégories
		             maj_list_cat(category_title);
					 
					var level_loader = 15;

					var adder = false;//ce booléen permet de gerer le niveau de la barre de progression et aussi vérifier l'existance de la catégorie en base de donnée local
					
					record_this(category_title,level_loader,adder);
				break;
				
				default:
				    
					$('.wait_load_msg').html($('#Please_wait').html()+' '+$('.loader_wait').attr('collect'));//message de collecte des articles
					
					    
						 var form_data = {'category':category_url.replace($('.hoster').attr('kiwix'),'')};
					 
				     //on sélectionne la catégorie et recrute toutes les pages
					    $.ajax({
                         
						 url: $('#get_API').attr('api_category_list'),
                         
						 type: 'POST',
                         
						 async : true,
                         
						 data: form_data,

						 error:function(e){console.log(e)},
						 
						 dataType:"json",
                         
						 success: function(data) {
						           
									//Je compte le nombre d'url qu'il ya dans la page envoyé et je les stocke
									    $('.stock_engine').html(data.page_text);
									    
									//je consoie le tableau qui garde les id des page
									var nbre_page = $('#mw-pages a').length;
									//Je construit le tableau qui stocke touts les url et les titres
									window.all_article_url =[];
									window.all_article_title =[];

									if(nbre_page!==0){
									    
										$('#hide_progress').attr('style','');
									 
									    for(i=0; i<nbre_page;i++)
										{ 
										  window.all_article_url.push($('#mw-pages a')[i].href);
										 
										  window.all_article_title.push($('#mw-pages a')[i].innerText);
										  
										    if(i==nbre_page-1){

										    	var list_suiv = false;
									
									            load_all_urls(category_url,category_title,list_suiv);    
											}
										}
									}
									else
									{
									   //Il nya pas d'articles
									   $('.wait_load_msg').html($('.loader_wait').attr('no_article'));//message de téléchargement des articles
									   $('.load_msg').html($('.confirm_loader').attr('close'));
									   $("#progressbar_loader").attr('level',0);
									   $('#hide_progress').attr('style','display:none;');
									   progression(0);//on met à jour la barre de progression
									}
									   
						          }
					    });
			    break;
			}
		  
		}
		
		
		
		//cette fonction enregistre les articles en local
		function  record_this(category_title,level_loader,adder)
		{ 
		 
		 $('.wait_load_msg').html($('#Please_wait').html()+' '+$('.loader_wait').attr('download'));//message de téléchargement des articles
		 
		   var nbre_art = window.all_article_url.length;
		   
            if(nbre_art > 0)//S'il ya quelque chose à enregistrer
            {			
		        //ici je gère la barre ed progression
				if(!adder)//si c'est la premiere fois qu'on augmente le niveau de la barre, je met à 15%
			    {
				   progression(15);//on met à jour la barre de progression
				   window.true_level = 15;
				   
				   //je fait le calcul de progression
				   level_loader = 75/nbre_art;	
				  
				   adder = true;
				}
			    else
				{
				   progression(level_loader);
				}
		 
			  //******************************on fait entrer mainetenant les articles//*********************************
		 
		     //on lit le premier terme du tableau
		     var first_page_url = window.all_article_url[0];
		     first_page_url = first_page_url.replace($('.hoster').attr('kiwix'),'');
			 
			 $('.wait_load_msg').html($('#Please_wait').html()+' '+$('.loader_wait').attr('download')+' <strong>'+window.all_article_title[0]+'</strong>');//message de téléchargement des articles
			
		     //on vérifie s'il est présent en local
		     var objectStore_article = $.indexedDB(dbName).objectStore("article");
		  
		     var promise_article = objectStore_article.get(first_page_url);
			
		        promise_article.done(function(result_article, event){
			
			        if(result_article==undefined)//si non on fait la requete vers le serveur pour la télécharger et on pointe(loader et catégorie)
				    {
				        var url_get_article = $('#get_API').attr('api');
				
				        //on récupère l'article
				        window.requete = $.ajax({ 
    
							url: url_get_article,
                                        
							type: 'POST',
							
							data: {'page_url':first_page_url},
                                        
							async : true,
							
							dataType:"json",
					                    
							error: function(){
							                  //On enlève le premier élément de chaquue tableau
                                              window.all_article_url.shift();
											  window.all_article_title.shift();

                                              //on renvoi à la fonction											  
							                  record_this(category_title,level_loader,adder);
											},
                                        
							success:function(livraison) { 
							
							           
							            //On enregistre en local l'article en local
		                                 window.recording_all_article(livraison,first_page_url,livraison.page_title);   
                                                    
											 //cette fonction met une catégorie à jour en y inscrivant l'id et le titre de l'article
		                                    maj_cat(category_title,first_page_url,window.all_article_title[0]);																		 
                                                
										//On enlève le premier élément de chaquue tableau
                                        window.all_article_url.shift();
										window.all_article_title.shift();
										
										if(statu_requete==true)
										{
                                          //on renvoi à la fonction											  
							              record_this(category_title,level_loader,adder);
										}
										else
										{
										  window.requete.abort();
										}
									}
					    });	
				    }
				    else //Si oui on pointe
				    {
				      //On enlève le premier élément de chaque tableau
                      window.all_article_url.shift();
					  window.all_article_title.shift();
					  
					  //cette fonction met une catégorie à jour en y inscrivant l'id et le titre de l'article
		              maj_cat(category_title,first_page_url,window.all_article_title[0]);	

                       //on renvoi à la fonction											  
					  record_this(category_title,level_loader,adder);
				    }
			    })
			}
			else
			{
			   progression(100);//on met à jour la barre de progression
			   
			   //on affiche le bouton fermer
			    $('.load_msg').html($('.confirm_loader').attr('close'));
					 
			    $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
			   
			    //on affiche le message de terminaison
			    $('.wait_load_msg').html($('.loader_wait').attr('over'));
			}
		}
				
		
		
		//Gezstion du pourcentage sur la barre et le boutton

		function progression(level_parcer)
		{ 
		    if(level_parcer==100)
			{
			  var etat_level=100;
			}
			else
			{ 
			  var etat_level = (window.true_level*1)+(level_parcer*1);
			  window.true_level = (window.true_level*1)+(level_parcer*1);
			}
		   

		    
			$("#progressbar_loader").progressbar({
			   value: etat_level
		    });
				
		    $('.num_level').html(Math.round(etat_level));
		}
		
		
		//cette fonction met une catégorie à jour en y inscrivant l'id et le titre de l'article
		function maj_cat(category_title,article_url,article_title)
		{ 
                                   			
		  var objectStore_cat = $.indexedDB(dbName).objectStore("category");
		  
		  var promise_cat_url = objectStore_cat.get("cat_art_"+category_title+"_url");
			
			promise_cat_url.done(function(result_cat_url, event_cat_url){
			
			    if(result_cat_url!==undefined)//s'il ya la catégorie
				{
				  //ON ajoute l'article mise en cache
					if(jQuery.inArray(article_url,result_cat_url) == -1)//On vérifie si l'id n'est pas présent dans le tableau des articles
					{
					   result_cat_url.push(article_url);// on ajoute l'ids
														   
					   var promise_cat_title = objectStore_cat.get("cat_art_"+category_title+"_title");
			
			            promise_cat_title.done(function(result_cat_title,event_cat_title){
			           
			                if(event_cat_title.type=="success")//s'il ya la catégorie dans les titre
				            {
							   result_cat_title.push(article_title);
							   objectStore_cat.put(result_cat_title,"cat_art_"+category_title+"_title");
							}
						});

					  //on enresgistre en local
					  objectStore_cat.put(result_cat_url,"cat_art_"+category_title+"_url");
					} 
				}
				else
				{ 
				 //On crée l'entré 
				 result_cat_url = new Array();//pour l'urls
				 result_cat_title = new Array(); // pour les titres
													 
				 //et on entre les nouvelles données
				 result_cat_url.push(article_url);// on ajoute l'ids
													  
				 result_cat_title.push(article_title);//On ajoute le titre
												 
				 //on enresgistre en local
				  objectStore_cat.put(result_cat_url,"cat_art_"+category_title+"_url");
				  objectStore_cat.put(result_cat_title,"cat_art_"+category_title+"_title");
				}
			});
		}
		
		
		//Cette fonction met a jour la liste des catégories
		function maj_list_cat(category_title)
		{
		  //ON créé la catégorie si elle n'est pas déjà présente
	      var objectStore_cat = $.indexedDB(dbName).objectStore("category");
		   
		  var promise_cat = objectStore_cat.get("cat_list");
			
			promise_cat.done(function(cat_list, event){
			
			    if(cat_list==undefined)//s'il ya pas la catégorie on la créé
				{
				 cat_list = new Array();
				  
				 objectStore_cat.add(cat_list,"cat_list");
				}
						
		      //on enregistre le titre de la catégorie
		      //on vérifie l'existance du  titre de la catégorie dans le tableau
		        if(jQuery.inArray(category_title,cat_list) == -1)//On vérifie si la catégory n'est pas présente dans le tableau des catégories
			    {
			     cat_list.unshift(category_title);
			     
				 objectStore_cat.put(cat_list,"cat_list");
			    }
			 //si le titre n'xiste pas on l'ajoute dans le tableau
		    });
		}
		
		
		 //on click on déblock le navigateur
			$('.unblock').click(function() { 
			    $.unblockUI();
				statu_requete  = false;	
				window.requete.abort();			
			});
	
	
});

	