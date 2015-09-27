
$(document).ready(function(){ 

	//Unable right click
	//$(document).bind('contextmenu', function (e) {e.preventDefault();});

	//Chat Button draggable
	$('.com_pinooy').draggable();

	var popupWindow;

	var notty_timeout = 120000; //Second for notification message

	var nbre_historic = 50;//Number of article recorded


	var hoster = $('.hoster').attr('url');
	var port_kiwix = $('.hoster').attr('port_kiwix');
	var zim = $('.hoster').attr('zim');

	var all_zim_file = [];
	all_zim_file = $('.hoster').attr('zim_list').split(',');
	window.all_zim_file = all_zim_file;

 
   $('.tooltipped').tooltip({delay: 50});

  $(function() {
		$("#toTop").scrollToTop(1000);
	});
    
    var largeur_mobile = 800;
    window.device = 'derre';
    window.list_open = 'no';
    var nombre_followed = 10;


    if($(window).width()<=largeur_mobile){

    	$('.liste').fadeOut();
    }


 
    var resise = tailleur();

    function tailleur(){ 

    	$('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrain_width: false, // Does not change width of dropdown to that of the activator
            hover: false, // Activate on click
            alignment: 'left', // Aligns dropdown to left or right edge (works with constrain_width)
            gutter: 0, // Spacing from edge
            belowOrigin: false // Displays dropdown below the button
        });

    	var largeur = $(window).width();

    	if(largeur<=1323){

		    $('.dance_for_me').fadeOut();//On retire l'historique de navigation
    	}else{
		    $('.dance_for_me').fadeIn();//On retire l'historique de navigation

    	}

        if(largeur<=largeur_mobile){

        	window.device ='mobile';

        	$('.logo_kwiki').attr('height','') ;//We resise the logo image
        	$('.logo_kwiki').css('width','70%') ;//We resise the logo image
        	$('.logo_kwiki').css('height','') ;//We resise the logo image


        	$('.principal').attr('id','');
        	$('.principal').removeClass('col');
        	$('.principal').removeClass('s7');
            $('.liste').attr('id','');

        	    $('#form_up').fadeOut();

        		$('.one').show();$('.two').hide();

		        $('.dance_for_me').fadeOut();//On retire l'historique de navigation

		        //$('.chizer').fadeOut();//On retire le moteur de recher des suivis

		        $('.cacher').fadeOut();

		        $('.view').parent().attr('style','');
		    
		        $('.favorite_wiki').parent().fadeOut();
		    
		        $('.print_it').parent().fadeOut();

		        $('.tiler').fadeOut();

		        $('.fileUpload').fadeIn();
		        
		        window.already_mobile = true;

        }else{

        	window.device ='standart';

        	$('.logo_kwiki').css('width','') ;//We resise the logo image
        	$('.logo_kwiki').css('height','100px') ;//We resise the logo image


        	$('.principal').attr('id','horizontal-b');
        	$('.principal').addClass('col');
        	$('.principal').addClass('s7');

            $('.liste').attr('id','horizontal-a');

		    $('.fileUpload').fadeOut();

        	$('.chizer').fadeIn();//On remet le moteur de recher des suivis

        	$('.cacher').fadeIn();

        	$('.tiler').fadeIn();

            $('.one').hide();$('.two').show();

        	$('.contenu_liste').fadeIn();

        	//$('.liste').attr('class','liste col s5').attr('ref','yes');
            //$('.contenu_liste').attr('class','contenu_liste span7');

            if($('.liste').attr('ref')=='yes' && window.device=='standart' && window.list_open=='no'){

    	        window.list_open = 'yes';
            }

            //Ici on affiche de nouveau la liste et le contenu au cas ou l'un des deux était caché
            if(window.page_open =='no' && window.both!==true){

            	$('.contenu_liste').show("slide", { direction: "left" }, 500)

            	window.page_open ='yes';

            	window.both = true;
            }

            $('.liste').fadeIn();        
        }
    }

    
   // var hauteur = $('.liste').attr('style','height:'+$(window).height()+'px;');
    
    $(window).resize(function() {

        tailleur();

        console.log(window.device);

       // $('#page').attr('style','height:'+$(window).height()+'px;');
    });

    window.page_open='yes';

     //Cete fonction affiche la page centrale
    window.show_page =  function(){

    	if(window.page_open=='no'){

    		$('.liste').hide("slide", { direction: "left" }, 500)

            $('.principal').show("slide", { direction: "left" }, 500)

            $('.liste').attr('class','liste span1');
            $('.principal').attr('class','principal col s12');

            $('.principal').attr('id','');
            $('.liste').attr('id','');

            $('.menu_back').fadeIn();

            $('.hide_list_mobil').parent().fadeIn();

            window.page_open ='yes';
    	}   
    }

     //Cette fonction cahche la page centrale
    window.hide_page = function(){

    	if(window.page_open=='yes'){

    		$('.principal').hide("slide", { direction: "left" }, 500)
        
            $('.liste').show("slide", { direction: "left" }, 500);

            $('.liste').attr('class','liste col s12');
            $('.principal').attr('class','principal');
            
            $('.principal').attr('id','');
            $('.liste').attr('id','');

            $('.menu_back').fadeOut();


            $('.hide_list_mobil').parent().fadeOut();

            window.page_open ='no';
    	}   
    }


    $('.menu_back').click(function  () {
    	
    	window.hide_page();
    })

  
 //$.jStorage.flush(); //décommenter pour supprimer les données téléchargés dans le cache
  
 
 var dbName = $('#get_API').attr('local_db');
  // $.indexedDB(dbName).deleteDatabase();
    if(window.indexedDB || window.openDatabase){
	
	 var dbOpenPromise = $.indexedDB(dbName, {
		"version":1,
		"upgrade" :function(transaction){
             transaction.createObjectStore("article", {
            }).createIndex("title" , /*Optional*/ {
                           "unique" : false, // Uniqueness of Index, defaults to false
                       
						}, /* Optional */ "title")
						
		     transaction.createObjectStore("all_article");
			 
			 transaction.createObjectStore("category");
        }
	 });
	}
	

 	//console.log(($.jStorage.storageSize()/1024)/1024+' mo de capacité utilisée');//j'affiche dans le log mes capacité en charge(debbugeur)

        var article_max = 100; //nombre maximal d'articles à télécharger
		
		var historique_complet = 100;
		
		var historique_simple = 4;//historique qui s'affiche au menu de navigation

        
        var page_content_js = $('#js_wiki_click').attr('page_content'); 
		
		var url_api = $('#get_API').attr('api');
		
		//var deletePromise = $.indexedDB("begoo_wiki_store").deleteDatabase(); 
		
		
        //On met une veilleuse pour socket.IO timer
		//url de node js 
		var socket  = io.connect($('#url_node').attr('url'));

		var lastTime = (new Date()).getTime();

        setInterval(function() {

            var currentTime = (new Date()).getTime();
            if (currentTime > (lastTime + 2000*2)) {  // ignore small delays
                // Probably just woke up!
                socket  = io.connect($('#url_node').attr('url'));
            }
         lastTime = currentTime;
        }, 2000);
		
	
	///////////////////////////////////////follow me//////////////////////////////////////////////////////	
        //Dès la connexion on obtient la liste les leader qui demandent à être suivis
        socket.emit('get_leader');


		//S'il ya quelque qui vien de cliquer sur le boutton follow me on le dit à tous le monde
		socket.on('follow_him', function (result){

			//On enregistre les followed en local
			$.jStorage.set('followed_list',result);
            
            last_leader(result);
        });
		
		
		//Si on recoit un changement d'article
		socket.on('change_article', function(article){
		
		    //On affiche la box pour patienter
            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
           
		    retrieve_article_text(article.full,article.page_url);
        })


    $('.listing').click(function(){

        window.hide_page();
    })

		
	
	function retrieve_article_text(full_article,page_url) { 
			
		  window.full_text = full_article;
			
            //On apprete le titre et le texte
		  var title_push = full_article.page_title;
											  
		  var text_push  = full_article.page_text;

		  $('.wiki_title').html('<h1>'+title_push+'</h1>');
							     						
		  $('.wiki_content').html(text_push);
		    						   
		  $('.look_wiki').html(page_url);//relève la page qu'il consulte
		  $('.look_wiki').attr('page_title',title_push);//relève la page qu'il consulte
										
										
		
                                    
	    article_ready();//On applique des actions sur le texte                               
	}


	


	
		
		//S'il on me demande qu'elle article je consulte actuellement
		socket.on('get_article', function (follower_id) {
		
           var wiki_follow = $('.wiki_follow').html();				
			//je répond en donnant l'id de l'article que je consulte actuellement	
			socket.emit('this_article',{'page_url':$('.look_wiki').html(),'follower_id':follower_id});
			
        });
		
		
		//je met +1 au nombre de personnes qui me suivent
		socket.on('more_person', function () {
		
           var wiki_follow = $('.wiki_follow').html();				
			//si je  suis suivi je met +1 sur le nombre de personne qui me suivent	
			if(wiki_follow=='me')
			{
			  $('.followers').html(1 + parseInt($('.followers').html()));//on affiche ce nombre
			}
        });
		
		
		//je met -1 au nombre de personnes qui me suivent
		socket.on('less_person', function () {
		
           var wiki_follow = $('.wiki_follow').html();				
			//si je  suis suivi je met +1 sur le nombre de personne qui me suivent	
			if(wiki_follow=='me' && $('.wiki_title').html()!=='')
			{
			    var nbre_followers = parseInt($('.followers').html());
				
				if(nbre_followers > 0)
				{
				 $('.followers').html(nbre_followers-1);//on affiche ce nombre
				}
			}
        });
		
		//Ici j'affiche l'article qui st suivi actuellement dans la room où je vient d'entrer
		socket.on('this_page', function (page_url) {
		  
		  //On affiche la box pour patienter
          $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
			
		  retrieve_article_url(page_url);
		
		});
		
		
		//Si on me dit que le followed est sorti de la room
		socket.on('end_follow', function (followed_id) { 

			if($('.wiki_follow_user').html()==followed_id && $('.followed_user_id').attr('user_id')==followed_id){
				
		      //j'efface et je cache son nom
			      $('.wiki_follow').html('');
				  $('.wiki_follow_user').html(''); //on efface son id							
				  $('.followed_user_id').attr('user_id','');
				  $('.info_followed').fadeOut();
				  $('.note_follow').fadeIn();

                  $("#chat_div").chatbox("option", "boxManager").addMsg($('.name_followed').html(),$('#chat_conv').attr('bye'));//on affiche le message	

				  $('.name_followed').html('');//on efface son nom

				  $('.stop_follow_him').click();

				  $('a').remove('#followed_'+followed_id);//On retire le lien de celui qui arrete d'etre suivi
				  $('span').remove('#divider_'+followed_id);//On retire le lien de celui qui arrete d'etre suivi
			}
		   
		  //je sort moi aussi
		  socket.emit('leave_it',my_user_id);
		});
		
		
		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////

			
			
			
			
		    //ceci mettre un article en favoris
		    $('.favorite_wiki').click(function() {
			
			   var fav = $(this);
			
		       var url_fav = fav.attr('url_fav');//url de l'action
			
			   var jqXHRFav = $.post(url_fav+$('.look_wiki').html(),'');
					  
			        jqXHRFav.done( function(data, textStatus, jqXHR ){				
				    
					  response = $.trim(data);
						
					  // on change le statu du bouton et d'autre action
					  $('.fav_count').html(response);//on met à jour le nombre de personne qui on kifé 					
				    });
					
			      return false;
		    })
			
			
			//ceci est pour afficher un article au hazard
			$('.random').unbind('click');
			
		    $('.random').click(function() {
			
			 //On affiche la box pour patienter                  
		     $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();

		        if(window.device=='mobile'){

		        	window.show_page();
		        }
			 
			 $.blockUI();//onblock le navigateur
			 
			 //on prend l'url pour générer l'article au hazard
			   $.ajax({

				    url: $('#get_API').attr('get_random_article'),
                                        
				    async : true,

				    type: 'GET',

				    dataType:"json",
	                    
			        error: function(e){

			                   console.log(e);

			                   $('#info_msg_wait').fadeOut();

			                   $.unblockUI();//on débloque le navigateur

			                },//On efface la box qui fait patienter
                                        
				    success: function(random_page){ 
				            //console.log(random_page); 
							 //c'est bon je récupère l'id de larticle généré
							//var page_id = random_page.query.random[0].id;
                            
							$('.wiki_title').html('<h1>'+random_page.page_title+'</h1>');
							$('.wiki_content').html(random_page.page_text);	
							article_ready();
							$.unblockUI();//on débloque le navigateur	

						}
				}); 
			
			 return false;			
			
			});



            $('.accueil').unbind('click');
			
		    $('.accueil').click(function() {

		    	//On affiche la box pour patienter                  
		       $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();

		       if(window.device=='mobile'){

		       	   window.show_page();
		       }
			
			   $.ajax({

				    url: $('#get_API').attr('api'),
                                        
				    async : true,

				    type: 'GET',

				    dataType:"json",

				    data: {},
					                    
			        error: function(e){console.log(e); $('#info_msg_wait').fadeOut();},//On efface la box qui fait patienter
                                        
				    success:function(wikipedia){ 
				    	    $('.wiki_title').html('<h1>'+wikipedia.page_title+'</h1>');
							$('.wiki_content').html(wikipedia.page_text);	
							article_ready();	
						}
				}); 
			
			 return false;			
			
			});
		
			

            
			//on affiche l'article en passanrt par la base de donnée 
            function retrieve_article_server(page_url,recorder) {

            	var type = false;
			
			       //on récupère l'article
			       if(type==false){
                        
                        type = 'none';
			       }
				    $.ajax({ 
    
							url: $('#get_API').attr('api'),
                                        
							type: 'POST',
							
							data: {'page_url':page_url,'type':type,'witch_zim':window.witch_zim},
                                        
							async : true,

							dataType:"json",
								            
							error: function(e){ 
							                  $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter
											},
                                        
							success:function(page_aff) { 

								       window.url_image = page_url;

	                                   retrieve_article_text(page_aff,page_url); 
									}
					}); 
            }

			
		    function retrieve_article_url(page_url){

		    	window.url_image = false;
	    
				if(window.indexedDB || window.openDatabase) //s'il le navigateur supporte indexeddb
			    {
					
					$.indexedDB(dbName).objectStore("article").get(page_url).done(function(result, event){
                       
						if(result==undefined)//sil ya pas l'article,on le prend  au serveur
						{ 
						    var recorder = 'yes';
					     
					        retrieve_article_server(page_url,recorder);//on prend l'article au serveur
						}
						else
						{ 
						  //On apprete le titre et le texte
						  var title_push = result.page_title;
											  
						  var text_push = result.page_text;
						  
						  window.full_text = result;

						  $('.wiki_title').html('<h1>'+title_push+'</h1>');		     		
						  $('.wiki_content').html(text_push);
									  
									   
						  $('.look_wiki').html(page_url);//relève la page qu'il consulte
						  $('.look_wiki').attr('page_title',title_push);//relève la page qu'il consulté
							
	                        $(document).ready(function(){ 
                                article_ready();

                                window.click_by_url();
	                        });


						}
                        event; // Success Event
                    });
					
					$.indexedDB(dbName).objectStore("article").get(page_url).fail(function(error, event){ 
					    var recorder = 'yes';
					    //console.log('deviation'); 
					    retrieve_article_server(page_url,recorder,type);//on prend l'article au serveur	
                    });
				}
				else
				{
					if($.jStorage.storageAvailable())//est ce que navigateur supporte le webstorage?
                    { 
					   //si oui...
					   //on regarde s'il a le texte
					    var article_title = $.jStorage.get('title_'+page_url);
					   
					    if(!article_title)
					    {
						 var recorder ='yes';
						 
						 retrieve_article_server(page_url,recorder,type);//on prend l'article au serveur	   
                        }
						else
						{
						  $('.wiki_title').html('<h1>'+$.jStorage.get('title_'+page_url)+'</h1>');									  
						
						      
                              $('.look_wiki').html(page_url);//relève la page qu'il consulte
						      $('.look_wiki').attr('page_title',$.jStorage.get('title_'+page_url));//relève la page qu'il consulte
   	   
                              //on affiche le texte
							   
							     $('.wiki_content').html($.jStorage.get('text_'+page_url));
								 
								 article_ready();//On applique des actions sur le texte	
						}
                    }
                    else
                    {
					   var recorder = 'yes';
					     
					   retrieve_article_server(page_url,recorder,type);//on prend l'article au serveur	
                    }
                }					
			}
			
			
			
			function put_article(page_url,title_push,text_push)
			{
			 //console.log('article chargé');								   									  
									  
			 //j'enregistre l'article
                                             
				 //le titre
              $.jStorage.set('title_'+page_url,title_push);
											 
			  //le texte
              $.jStorage.set('text_'+page_url,text_push);
                                             
			 //son ordre 
              //on récupère le tableau qui garde les articles
              var all_articles = $.jStorage.get('all_article'); //qui renvoie le tableau des articles												 
											    
				if(!all_articles)//si ce tableau n'existe pas
				{
				 //on créée le tableau
				 var all_articles = new Array();
													   
				 $.jStorage.set('all_article',all_articles);//et on enregistre ce tableau
				}
													
			 //console.log(all_articles.length);
			 //console.log(all_articles.join());											 
												
			 //on ajoute le dernier article dans la liste du tableau
			 all_articles.unshift(page_url);//tableau.unshift() Cette méthode permet dajouter un ou plusieurs élément au début du tableau
											  
			 $.jStorage.set('all_article', all_articles);//et on enregistre ce tableau
			}


       

            //actions à faire à l'affichage d'un article
            function article_ready(){

            	$('#horizontal-b').animate({scrollTop : '0px'},2000);
			
			    //faire un unbind des évènement dans la page 
                //$('.extiw,.new,.external,.toc a,.internal,mw-magiclink-isbn,.mw-redirect').unbind('click');//on détache le click s'il était présent avant				
			    
				$(document).ready(function(){

					//On load de javascript file of the video
		            $.getScript($('.ted_video').attr('js'));

		            
					//This is for ted conference
					$('.wiki_content').html($('.wiki_content').html().replace('../../',$('.hoster').attr('host_wiki')+'/'+window.ted_zim_file+'/' ));

                     //We modify the url of link of the article if it is for Medecine article
					if(window.witch_zim=='medecine'){

					   var lien = $('.wiki_content a:not(.external)').length;

					    for (var i = 1; i < lien; i++) {
					        
					        var href = $('.wiki_content a:not(.external)').eq(i).attr('href');

					        if( href.indexOf('/wikipedia_en_medicine_09_2014_2/A/')==-1){

					           $('.wiki_content a:not(.external)').eq(i).attr('href','/wikipedia_en_medicine_09_2014_2/A/'+$('.wiki_content a:not(.external)').eq(i).attr('href'));
					        }
					        $('.wiki_content a:not(.external)').eq(i).addClass('list_link');
					        $('.wiki_content a:not(.external)').eq(i).attr('title',$('.wiki_content a:not(.external)').eq(i).text());
					        $('.wiki_content a:not(.external)').eq(i).attr('zim','medecine');
					        $('.wiki_content a:not(.external)').eq(i).attr('zim_file','wikipedia_en_medicine_09_2014_2');

					        if(i==lien-1){ 

					        	$(document).ready(function(){

                                    window.click_by_url();

                                    record_historic(true);
                                });
					        }
					    };                         
					}

					
					//on cha,ge le titre de la page
					$('title').html($('.wiki_title').text());
			
		   
		            //On met une axtérisque sur la distinction des pages non rédigées
		   	        $('.new').append('<sup>(Page not found)</sup>');
		   	        //Et on met un signal sur les liens externes
		   	        $('.extiw').append('<i class="tiny mdi-action-lock-outline"></i><sup>*</sup>');
					
			
			        //On affiche l'url des pages externes
		   	        $('.external').html($('.external').html()+' <i class="tiny mdi-action-lock-outline"></i><sup>*</sup>');

                    
                    //We design the link in the model of wikipedia
                    if(window.save_zim=='wikipedia'){

                    	var links = $('.wiki_content a:not(.new,.toc a,.internal),area').length;

		   	                $('.wiki_content a:not(.new,.toc a,.external),area').addClass('list_link');
		   	                $('.wiki_content a:not(.new,.toc a,.external),area').attr('zim',window.save_zim);
                            $('.wiki_content a:not(.new,.toc a,.external),area').attr('zim_file',window.save_zim_file);

                        $(document).ready(function(){

                            window.click_by_url();

                            record_historic(true);
                         });
                     }

                    if(window.save_zim=='ubuntu'){
                    	//We put the attr zim="ubuntu" and zim_file="ubuntudoc_fr_01_2009" in any link on the article
                        $('.wiki_content .wikilink1').addClass('list_link');
                        $('.wiki_content .wikilink1').attr('zim','ubuntu');
                        $('.wiki_content .wikilink1').attr('zim_file','ubuntudoc_fr_01_2009');
                                	
                        $(document).ready(function(){

                            window.click_by_url();
                            record_historic(true);
                        });

                        var nbre_image = $('.wiki_content img').length;

                        if(nbre_image >0){

                        	for (var i = 0; i < nbre_image; i++) {
                        		//We activ the click on each image to enlarge the image. Pretty Cool!
                        		var lien = $('.wiki_content img').eq(i).attr('src');

                        		if(lien.indexOf($('.hoster').attr('host_wiki'))==-1){
                        			var src = $('.wiki_content img').eq(i).attr('src').replace('/ubuntudoc_fr_01_2009',$('.hoster').attr('host_wiki')+'/ubuntudoc_fr_01_2009');
                        		}
                                $('.wiki_content img').eq(i).attr('src',src);
                                $('.wiki_content img').eq(i).parent().removeAttr('href');
                                
                                /* //Agrandissement de photo
                                $('.wiki_content img').eq(i).addClass('materialboxed');
                                $('.wiki_content img').eq(i).attr('data-caption',$('.wiki_content img').attr('title'));

                                if(i==nbre_image-1){

                                	$(document).ready(function(){ 
	                                        
	                                   $('.materialboxed').materialbox();//To enlarge the piture                                         
                                    })
                                }*/
                        	}                       	
                        }    
                    }

                    
                    //We select the right zim_file to keep image
                    var image_zim;

                    if(window.witch_zim=='wikipedia'){
                        
                        image_zim = 'image.json';
                    }else{
                    	
                    	image_zim = 'image_medecine.json';
                    }


                    //We record te first image to illustrate the article
        	        $.getJSON($('#url_json').attr('url')+image_zim, function(data) {

        	        	var image = $('.thumbinner img').attr('src');
        	        	var image2 = $('.infobox_v2 img').attr('src');

        	        	if(window.witch_zim=='medecine'){
        	        		image = $('.infobox img').attr('src');
        	        	}

        	        	if(image!=undefined && image2!=undefined) {

        	        		var src_image = data.image.page_url.indexOf(window.article_title);
                        
        	        	    if(src_image==-1){ //If this image is not recorded
                            
                                var article_title = window.article_title

                                record_image(image2,article_title);
        	        	    }
        	        	} else{
                            
                            if(image2!=undefined){

        	        		    var src_image = data.image.page_url.indexOf(window.article_title);
                        
        	        	        if(src_image==-1){ //If this image is not recorded
                            
                                    var article_title = window.article_title

                                    record_image(image2,article_title);
        	        	        }
        	        	    }else{

        	        		    if(image!=undefined){

        	        			    var src_image = data.image.page_url.indexOf(window.article_title);
                        
        	        	            if(src_image==-1){ //If this image is not recorded
                            
                                       var article_title = window.article_title

                                        record_image(image,article_title);
        	        	            }
        	        		    }
        	        	    } 
        	        	}              	    
        	        })
                    
                    /* //Agrandissement de photo
                    $('.wiki_content img').parent().removeAttr('href') ;
                    
                    $('.wiki_content img').addClass('materialboxed');
                    $('.materialboxed').materialbox();//To enlarge the piture
                    */

                });
				
				

				$('.special_nav').fadeIn();//on affiche le boutons cachés du navigo
				   
				//$('.look_wiki').attr('if_article','yes');//signale que le navigo est tout ouvert

                $.unblockUI();//on débloque le navigateur				
		        
		        //We wipe the double Title
		        $('.firstHeading').html('');

		        $('#title').fadeOut();
				                      
				//$('body').animate({scrollTop : '0px'},1000);//on te scroll au debut de la page


				//We record the article on localdatabase
		        window.recording_all_article($('.wiki_content').html(),window.save_url,window.save_title);

				$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter

				//This is for the duo following
				if(window.on_following_duo==true && window.stop_following == false && window.article_sended==false){ 
					
					window.send_my_screen(window.interloc_num,false,window.save_title,window.save_url,window.save_zim,window.save_zim_file,false);
				}

				if(window.key_follow!=false && window.article_sended==false){ 
					                
					window.send_my_screen(window.key_follow,false,window.save_title,window.save_url,window.save_zim,window.save_zim_file,true);
				}

				window.article_sended=false;
			}

            


            window.article_sended=false;

			socket.on('send_my_screen',function(data){ 

				window.article_sended=true; //We put this variable to true in before calling article_ready()

                $('.following').removeClass('mdi-social-share').addClass('mdi-action-visibility-off');//We sure that we display the button to end follow
       
                if($.trim(data.save_zim)=='TED'){
                	
                	open_frame(data.save_url,data.save_zim_file,data.save_title);    
                }else{

                   $('.wiki_content').html(data.content);
                   $('.wiki_title').html('<h1>'+data.title+'</h1>');
                   $('title').html(data.title);
                }

                //window.on_following_duo = true;//This variable is false if the user dont follow any person

               
                if(data.first_time==true){
                	window.stop_following = false;
                }

                article_ready();

                window.click_by_url();
                $('#toTop').click();
            })


			function record_image(image,article_title){

				$.post($('#site_url').attr('url')+'/record_image' , {'image_src': image,'image_article':article_title,'json_zim':window.witch_zim});
			}



			function record_historic(save){ 

			    //We build data 
			    var historic_title = [window.save_title];
			    var historic_list  = [window.save_url,window.save_zim,window.save_zim_file,save];
			    var whole_list = [];
			    whole_list.push(historic_list);

				var get_historic_title = $.jStorage.get('historic_title',false);
				var get_historic_list  = $.jStorage.get('historic_list',false);

				//We create historic if it does not exist
				if(get_historic_title==false){ 
					
					$.jStorage.set('historic_title',historic_title);
					$.jStorage.set('historic_list',whole_list);
				}else{

                    //We dont record this article if it the last article of the array
					historic_title_nber = get_historic_title.length;
					if(get_historic_title[historic_title_nber-1]!==window.save_title){

					   //Now we record the article in the end of any list
				       get_historic_title.push(window.save_title);
				       get_historic_list.push(historic_list);

                       $.jStorage.set('historic_title',get_historic_title);
                       $.jStorage.set('historic_list',get_historic_list);
					}
				}
                //We ensure that there is only 50 articles recorded on the browser
                
                if(get_historic_title!=false){
                    
                    historic_title_nber = get_historic_title.length;

                    if(historic_title_nber>nbre_historic){

                       var url_to_delete = get_historic_list[0][0];
 
                       get_historic_title.shift();
                       get_historic_list.shift();

                	   $.jStorage.set('historic_title',get_historic_title);
                       $.jStorage.set('historic_list',get_historic_list);
                       delete_recorded(url_to_delete);
                    } 
                }                   
			}



			
		
			
			//cette fonction permet d'afficher l'historique
			$('.historic').click(function() {

			    $('.liste').animate({scrollTop : '0px'},1000);//on te scroll au debut de la liste 

				if(window.device=='mobile'){

					window.hide_page();
				}
                //On affiche la box pour patienter                  
		        $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();   
                
				if($.jStorage.storageAvailable()) //si le navigateur supporte le web storage, on récupère l'historique dans son pc
				{
                   var list_historic_title = $.jStorage.get('historic_title',false);
                   var list_historic_list  = $.jStorage.get('historic_list',false);

		            if(list_historic_title==false){
		        	
					    window.notificate_it($('.msg_historic').attr('no_historic'),'error','bottomRight');//ON affiche le msg d'échec

					    $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter       	 
		            }else{

                        //We reverse historic before listing
		            	list_historic_title = list_historic_title.reverse();
		            	list_historic_list  = list_historic_list.reverse();

                        
                        //We display historic
                        $('.liste').html('<div class="receive_list collection"></div>');

                        for (var i = 0; i < list_historic_list.length; i++){
                        	
                        	$('.receive_list').append('<a class="list_link hist_link_list collection-item waves-effect waves-teal" title="'+list_historic_title[i]+'" href="'+list_historic_list[i][0] +'" zim="'+list_historic_list[i][1]+'" zim_file="'+list_historic_list[i][2]+'">'+list_historic_title[i]+'</a>')
                        
                            if(i==list_historic_list.length-1){
                        
                                $(document).ready(function(){

                                    window.click_by_url();//Gestion des clicks des articles

                                     //We reverse historic after listing
		            	             list_historic_title = list_historic_title.reverse();
		            	             list_historic_list  = list_historic_list.reverse();

                                    $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter	
					            });
            	            }
                        }
		            }
                }

               return false;	      
            });
						
						
		

			
			function local_recorded(url) { 
				
				if(window.indexedDB || window.openDatabase) //s'il le navigateur supporte indexeddb
			    {
			    	if(window.witch_zim=='wikipedia'){

			    		url = $('.hoster').attr('host_wiki')+url;	
			    	}
					
					$.indexedDB(dbName).objectStore("article").get(url).done(function(result, event){
                       
						if(result==undefined)//sil ya pas l'article,on le prend  au serveur
						{ 
						    return false; 
						}
						else
						{  
						  //On apprete le titre et le texte
						  var title_push = result.page_title; 
											  
						  var text_push = result.page_text;

						  
						  $('.wiki_title').html('<h1>'+title_push+'</h1>');		     		
						  $('.wiki_content').html(text_push);	
                                      
	                        $(function(){article_ready()});//On applique des actions sur le texte
						}
                    });
					
					$.indexedDB(dbName).objectStore("article").get(url).fail(function(error, event){ 

					   return false;
                    });
			}else{

				return false;
			}
		}


		function delete_recorded(url) { 
				
				if(window.indexedDB || window.openDatabase) //s'il le navigateur supporte indexeddb
			    {					
					$.indexedDB(dbName).objectStore("article").delete(url).done(function(result, event){
                       

                    }).fail(function(error, event){ 

					   console.log(error);
                    });
			    }
		}



		window.recording_all_article = function(full_article,page_url,title_push){

		//on enregistre l'article en local
		if(window.indexedDB || window.openDatabase){ 

			//We convert to Json
            var jsonfy = {'page_title':title_push,'page_text':full_article,'page_url':page_url};
			  
		    //maintenant regardons si la page_url existe en local
			var objectStore = $.indexedDB(dbName).objectStore("article");
											
			var promise_article = objectStore.get(page_url);
                                          
            promise_article.done(function(result, event){
										 
                if(result == undefined)
				{
				  var promise_add = objectStore.add(jsonfy,page_url); // Adds data to the objectStore    
                                                    
				    promise_add.done(function(result, event){ 
												
                        var objectStore_all_art = $.indexedDB(dbName).objectStore("all_article");	
												 
				        var promise_all_page_url = objectStore_all_art.get('table_article_url');// tableau des pageurl
														 
                        //On soccupe des urls des pages "page_url"														 
				        promise_all_page_url.done(function(result_all_page_url, event){ 
													
				          var table_article_url = result_all_page_url;
										 
                            if(result_all_page_url==undefined)
					        {
				             //on créée le tableau
				             var table_article_url = new Array();
																  
					         objectStore_all_art.add(table_article_url,'table_article_url');//et on enregistre ce tableau
					        }
				           //on ajoute le dernier article dans la liste du tableau
														
			                table_article_url.unshift(page_url);//on ajoute le nouvel id
														 
				            objectStore_all_art.put(table_article_url,'table_article_url');//et on enregistre ce tableau d'id
														      
                        });
														
			            //ON s'occupe des titre
				        var promise_all_page_title = objectStore_all_art.get('table_article_title');// tableau des articles
													
					    promise_all_page_title.done(function(result_all_page_title, event){
													
						    var table_article_title = result_all_page_title;
										 
                            if(result_all_page_title==undefined)
						    {
				             //on créée le tableau
				                var table_article_title = new Array();
																  
							    objectStore_all_art.add(table_article_title,'table_article_title');//et on enregistre ce tableau
						    }
			           
					        //on ajoute le dernier article dans la liste du tableau
														
			                table_article_title.unshift(title_push);//on ajoute le nouveau titre
											   
						    objectStore_all_art.put(table_article_title,'table_article_title');//et on enregistre ce tableau de titre
			                                                  
                        });
				    });									
			    }
												
            });										  
		}
	    else
		{
			if($.jStorage.storageAvailable())//est ce que le navigateur supporte le webstorage?
            {
			 // on enregistre en local le texte
			 var total_storage = ($.jStorage.storageSize()/1024)/1024;//la taille est en méga
                                         
				if(total_storage < 4.5)//si la taille maxi est inférieur à 4,5 Mo
				{
	              //on met en local
				  put_article(page_url,title_push,text_push);
				}
				else //on supprime le dernier article
				{
				 //console.log('stokage plein.Suppression du dernier article');
											 
				 //on vérifie si le tableau des articles existe
				 var all_article = $.jStorage.get('all_article');
											  
					if(all_article)
					{
					 //prennons ce tableau
					 //comptons son nombre d'entré
					 nbre_artcles = all_article.length;
												  
					 //console.log(nbre_artcles+' articles au total dont le premier va être supprimé');
												  
					 //suppression du premier article
					 //sélection au préable du dernier élément du tableau
					 var last_article = nbre_artcles-1;
												   
					 var old_article = all_article[last_article];
													  
					   //console.log('Suppression de "'+$.jStorage.get('title_'+old_article)+'"');
													   
					   //et on supprime cette article
					   $.jStorage.deleteKey('title_'+old_article);
					   $.jStorage.deleteKey('text_'+old_article);
												       
					   all_article.pop();//.pop() supprime le dernier élément du tableau
													
					    //je met à jour dans le cache le tableau des derniers articles
						$.jStorage.set('all_article',all_article)
													 
						 //on met en local
						 put_article(page_url,title_push,text_push);
												  
				    }
				}
			}
		}
	}

	        function is_it_in_local_storage (title,url) {
	        	
	        	//est ce que le navigateur supporte le webstorage?
   
	        	if($.jStorage.storageAvailable()){

	        		var list = $.jStorage.get('historic_title',false);

	        		if(list!=false && list.indexOf(title)!=-1){
                        
                        local_recorded(url);

                        return true;
	        		}else{
	        			return false;
	        		} 
	        	}else{
	        		return false;
	        	}
	        }

			
			
			//cette fonction s'occupe de tous les articles dont on peut générer par l'appel de son url
			window.click_by_url =  function(){

				                    $('.urlextern,.mf_,.not_link,.wikilink2,.external,.iw_go').click(function  () {
				                    	return false;
				                    })
			                        
			                        //on détache les évènements précédents
	                                $('.list_link').unbind('click');//Ne pas enlever unbind sur .cat_wiki
	
		                            //pour les articles 
			                        $('.list_link').click(function() {
			  
			                            if(window.device=='mobile'){ 

			                          	    window.show_page();
			                            }

			                            $('#toTop').click();

			                            //We save information about this click
			                            window.save_title     = $(this).attr('title');

			                            if(window.save_title==undefined){
			                            	window.save_title = $(this).text();
			                            }
			                            window.save_url       = $(this).attr('href');
			                            window.save_zim       = $(this).attr('zim');
			                            window.save_zim_file  = $(this).attr('zim_file');

			                            
                                            
                                          //We manage the active class  
                                        if($(this).parent().parent().attr('list_article')=='true'){
                                                
                                                //We change the link to active
                                            $('.list_link').removeClass('active');
                                        }

                                            $(this).addClass('active');
                                           

                                            var type_zim = $(this).attr('zim');

                                            switch(type_zim){

                                        	    case 'wikipedia': 
                                        	        //On affiche la box pour patienter                  
		                                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
			                                        var page_url = $(this).attr('href').replace($('.hoster').attr('host_wiki'),'').replace($('.hoster').attr('host'),'');
								                
								                    window.article_title = $(this).attr('title');
								                    window.witch_zim = 'wikipedia';

								                    var this_title = $(this).attr('title');

								                    if(this_title==undefined){

								                    	this_title = $(this).text();
								                    }

								                    var on_local = is_it_in_local_storage(this_title,page_url);

								                    if(on_local==false){

								                    	retrieve_article_url(page_url);
								                    }
                                        	        break;

                                        	    case 'gutenberg':
                                        	        window.witch_zim = 'gutenberg';

                                                    var on_local = is_it_in_local_storage($(this).attr('title'),$(this).attr("href"));

								                    if(on_local==false){
								                    	//window.open($('.hoster').attr('host_wiki')+$(this).attr("href"), "_blank", "width=600,height=600,scrollbars=yes");
                                                        open_frame_gutenberg($(this).attr("href"),$(this).attr('title'),'gutenberg');
								                    }
                                        	        break;

                                        	    case 'TED':
                                    	            open_frame($(this).attr('href'),$(this).attr('zim_file'),$(this).attr('title'));

                                    	             window.id_ted = $(this).attr('href');
                                    	             window.witch_zim = 'ted';
                                    	            //window.notty_it($('.ted_video_message').attr('help_language'));
                                    	            return false;                                             
                                        	        break;

                                        	    case 'ubuntu':
                                        	        if($(this).attr('href')!=''){
                                                       
                                                        window.witch_zim = 'ubuntu';
                                                        var on_local = is_it_in_local_storage($(this).attr('title'),$(this).attr("href"));

								                        if(on_local==false){
								                    	    //window.open($('.hoster').attr('host_wiki')+$(this).attr("href"), "_blank", "width=600,height=600,scrollbars=yes");
                                                            open_frame_gutenberg($(this).attr("href"),$(this).attr('title'),false);
								                        }                  
                                        	        }                                       	   
                                        	        break;

                                        	    case 'medecine':
                                        	        //On affiche la box pour patienter                  
		                                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();
			                                        var page_url = $(this).attr('href').replace($('.hoster').attr('host_wiki'),'').replace($('.hoster').attr('host'),'');
								                
								                    window.article_title = $(this).attr('title');
								                    window.witch_zim = 'medecine';
								                    var on_local = is_it_in_local_storage($(this).attr('title'),page_url);

								                    if(on_local==false){
								                        retrieve_article_url(page_url);								                    	   
								                    }
                                        	        break;
                                            }
  
                                      return false;		            
                                    });


                                    $('.hist_wiki').click(function(){ 

                                    	$('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//on affiche le message pour patienter
			         
			                            var page_url = $(this).attr('href').replace($('.hoster').attr('kiwix'),'');


				  		                retrieve_article_url(page_url);
				
					                    return false;                   
                                    }); 

                                     //Gestion du bouton pour afficher plus d'articles
                                    $('.plus_wiki_b').unbind();
                                    $('.plus_wiki_b').click(function(){

                                        //On affiche la box pour patienter
                                        $('.this_progress').fadeIn();

                                        //We prepare results
                                        var form_data = {'zim_file':window.actual_zim,'page':window.actual_number_of_result,'string_search':window.chaine,'zim':window.actual_zim_type};
                                        console.log(form_data);
                                        $.ajax({ 

                                            url: $('#get_API').attr('api_search_plus'),

                                            type: 'POST',

                                            async : true,

			                                dataType:"json",

			                                error: function(e){

						 	                    $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter

						 	                    console.log(e);},

                                            data: form_data,

                                            success: function(papi) {
							                    
												    switch(window.actual_zim_type){
												   	  case'wikipedia':
												   	    window.list_wikipedia(papi,true);
												   	  break;

												   	  case'gutenberg':
												   	    window.list_gutenberg(papi,true);
												   	  break;

												   	  case'ubuntu':
												   	    window.list_ubuntu(papi,true);
												   	  break;

												   	  case'medecine':
												   	    window.list_medecine(papi,true);
												   	  break;
												    }
							                    	 
						                        
                                                $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter 		 
					                        }
                                        });

                                        return false;   
                                    });

               									
                                
			    $('#info_msg_wait').fadeOut();
			}


            //trucate string
			function truncate(str, limit) { 

				var bits, i; 

				if ("string" !== typeof str) {

				 return ''; 

				} bits = str.split(''); 

				if(bits.length > limit) {

				    for (i = bits.length - 1; i > -1; --i) {

				        if (i > limit) {

				           bits.length = i;

				        } else if (' ' === bits[i]) {

				         bits.length = i; break; 
				        } 
				    } 

				    bits.push('...'); 

				} return bits.join(''); 
			}

			function open_frame(id,zim,title){ 


				$('.wiki_content').html('<iframe frameBorder="0" src="'+$('.ted_video').attr('url')+id+'/'+zim+'" width="100%" style="height:100em;padding-top:5px" name="myFrame" id="myFrame"></iframe>');
			
                //On ouvre la page du iframe http://localhost/GitHub/kwizi/

                $(document).ready(function(){

                    window.click_by_url();//Gestion des clicks des articles

			        $('.wiki_title').html('<h1>'+title+'</h1>');
			        $('title').html(title);
                        
                        $(document).ready(function(){ 
                            $('#myFrame').load(function(){ 

                                $('#myFrame').contents().find('.kiwix').remove();
                                $('#myFrame').contents().find('body').css('background-color','white');
                                $('#myFrame').contents().find('body').css('margin-top','0px');
                                $('#myFrame').contents().find('div')[0].remove();
                                $('#myFrame').contents().find('#speaker').remove();
                                $('#myFrame').contents().find('#title').remove();
                                $('#myFrame').contents().find('#kiwixtoolbar').remove();
                                $('.framaTed').fadeIn();

                                record_historic (false);
                                
                                //This is for the duo following
				                if(window.on_following_duo==true && window.stop_following == false && window.article_sended==false){
				               
					                window.send_my_screen(window.interloc_num,false,window.save_title,window.save_url,window.save_zim,window.save_zim_file,false);
				                }

				                if(window.key_follow!=false && window.article_sended==false){ 
					                
					                window.send_my_screen(window.interloc_num,false,window.save_title,window.save_url,window.save_zim,window.save_zim_file,true);
				                } 
                            });
                        });     
                });
			}


			function open_frame_gutenberg(zim,title,type){
                
                $('.wiki_content').html($('#Please_wait').html()); 
			
				//On ouvre la page du iframe http://localhost/GitHub/kwizi/#
				$('.content_gutenberg').html('<iframe frameBorder="0" src="'+$('.hoster').attr('gutenberg_url')+'get_zim_gutenberg'+zim+'" width="100%" style="height:100em;padding-top:5px" name="myFrame" id="myFrame"></iframe>');
			
                $(document).ready(function(){

			        $('.wiki_title').html('<h1>'+title+'</h1>');
			        $('title').html(title);
                        
                        $(document).ready(function(){ 

                            $('#myFrame').load(function(){ 
                                $('#myFrame').contents().find('.kiwix').remove();
                                $('#myFrame').contents().find('div')[0].remove() ;
                                $('#myFrame').contents().find('form').remove() ;
                                $('#myFrame').contents().find('body').css('background-color','white');
                                $('#myFrame').contents().find('body').css('margin-top','0px');
                                $('#myFrame').contents().find('.toc').eq(0).addClass('lexique');
                                $('.content_gutenberg').html($('#myFrame').contents().find('body').html().replace(/="..\//g,'="'+$('.hoster').attr('host_wiki')+'/'+$('.hoster').attr('gutenberg')+'/'));
                                $('.xlink').remove();
                                $('.zim_info').remove();
                                $('.wiki_content').html($('.content_gutenberg').html()).fadeIn();
                                $('.content_gutenberg').html('');
                                $('.urlextern').append('<i class="tiny mdi-action-lock-outline"></i>');

                                article_ready();
                                
                                //We record on the historic if it is directly a gutenberg file
                                if(type=='gutenberg'){ 
                                	record_historic (false);
                                }
                                                               
                                
                            });
                        });     
                });
			}

			
		


    $('.critika').click(function(){

        $('.critik').val('');
    })


	$('.send_critik').click(function(){ 

		var critik_text = $.trim($('.critik').val());

        if(critik_text!==''){

        	var form_data = {'text':critik_text};

        	$.ajax({
                    url: $("#url_critik").attr('action'),
                    type: 'POST',
                    async : true,
				    error: function(){$('.critik').val('Pas de réseau');},
                    data: form_data,
                    success: function() {

                    	$('.critik').val('Envoyé!');
                    }
            }); 
        }
			    
				   

		return false;
	});





    //This make the historic button blinking when there is not connection to the server
    $(function(){

    	setInterval(function  () { 
    		
    		if(window.kwiki_inline=="off"){ 
    			
    			$('.historic').addClass('red')
    			$('.historic').removeClass('blue')

    			setTimeout(function  () {
    				$('.historic').removeClass('red');
    			    $('.historic').addClass('blue')
    				
    			},3000);
    		}else{
    			$('.historic').removeClass('red');		
    			$('.historic').addClass('blue');		
    		}
    	},5000);
    })


    /////////////////////////////////Manage Appcache////////////////////////////////////////////////////////
    // Activer le nouveau cache quand il est disponible et recharger la page
    window.applicationCache.addEventListener('updateready', function (){
  	    
  	    window.applicationCache.swapCache();
  	    console.log('updating cache');
  	    window.location.reload();
    }, false);

    // Notice some errors
    window.applicationCache.addEventListener('error', function (evt){
	
	    console.log('cache error : ' + evt);
    }, false);

    // check of manifest version
    window.applicationCache.addEventListener('checking', function (evt){
	
	    console.log('Cache checking : ' + evt);
    }, false);

   
    //Download the  new cache when the manifest changed
    window.applicationCache.addEventListener('obsolete', function (evt){
	    
	    console.log('The cache is outdated : ' + evt);
	    window.applicationCache.update();
    }, false);

    // The manifest has not changed
    window.applicationCache.addEventListener('noupdate', function (evt){
	    console.log('No cache updating : ' + evt);
    }, false);

    /////////////////////////////////Manage Appcache////////////////////////////////////////////////////////



    ////////////////////////////////////Manage top notification /////////////////////////////////////////////
    //cette fonction déclanche l'apparition de la notification
			window.notty_it = function(message)
			{
				// toast(message, displayLength, className, completeCallback);
                Materialize.toast(message, 4000) // 4000 is the duration of the toast    
            } 
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////



    /*

    var lastTime = (new Date()).getTime();

    setInterval(function() {
  var currentTime = (new Date()).getTime();
  if (currentTime > (lastTime + 2000*2)) {  // ignore small delays
    // Probably just woke up!
  }
  lastTime = currentTime;
}, 2000);

    */

    //Appear some stuff in the home page
    var popop = setTimeout(function  () {
    	Materialize.showStaggeredList('#popopMessage');
    },1000)

    var youpi = setTimeout(function () {
    	$('.com_pinooy').fadeIn();
    },1500);



    //////////////////////////////////////////////////////////myschool//////////////////////////////////////////////////
    $('.myschool').click(function  () {
    	
    	$('.file_up_click').unbind('click');

        $('.cathegory').fadeOut();

    	//We display the interface my school
    	var button_add   = '<a class="btn-floating btn-large waves-effect waves-light blue file_up_click" style="display:none;"><i class="mdi-content-add"></i></a>';
    	var autocomplete = '<div class="ui-widget cathegory" style="display:none;"><label for="tags">Catégories: </label><input id="tags"><a class="waves-effect waves-light btn blue record_file"><i class="mdi-action-done tiny"></i></a></div>';
    	var loader       = '<br><span class="file_name_up" style="display:none;"></span> <span class="percentage_num" style="display:none;"></span><br><div class="progress during_up" style="display:none;"><div class="determinate" style="width: 0%"></div></div>';
        
    	$('.wiki_content').html('<div class="myschool_display"><center>'+autocomplete+button_add+loader+'</center><div class="row play_list"></div><a class="waves-effect waves-light btn blue back_list" style="display:none;"><i class="mdi-hardware-keyboard-backspace"></i></a> <div class="list_folder row" style="display:none;"></div></div>');

        //Display category by folder
        setTimeout(function  () {
        	display_folder();
        },1000);

    	$.getJSON($('#url_json').attr('url')+'all_file_uploaded.json',{_: new Date().getTime()},function(data) {
            
            window.playlist = data;
            
            var limit = 15; //Limit of card
            
            $.each(data, function(entryIndex, entry) {

              if(entryIndex<limit){

                $('.play_list').append('<div class="col m4">'+create_card (entry['file_cat'],entry['file_title'],entry['file_hash'])+'</div>')

                manage_playlist();
              }
            });
        });


        function manage_playlist () {

        	$(document).ready(function(){

        		//We verify the session if the user an upload file
           		$.post($('.message_ajax').attr('url_verify') , function(response) {

           			$('.message_ajax').attr('allow',$.trim(response)) 
           		});

                	$('.change_category').unbind('click')
                	
                	$('.change_category').click(function  () { 

                		setTimeout(function  () {
                			get_json_of_file(); 
                		},500)

                		$('.change_category').fadeIn();

                        $('.this_mini').parent().remove();
                		
                		var change_category = $(this);

                		change_category.fadeOut();

                		var cat = change_category.parent().parent().attr('category');

                		var this_file_hash = change_category.parent().parent().attr('file_hash');
                       
                		var form_edit = '<div class="input-field"><input type="text" id="tag_input" file_hash="'+this_file_hash+'" old="'+cat+'" class="this_mini" value="'+cat+'"></div>';

                		change_category.parent().append(form_edit);

                		$(document).ready(function(){

                			$('.this_mini').unbind('focus','keyup','blur');

                			$('.this_mini').focus(function(){ 

                                $('.this_mini').keyup(function(evenement){ 

                                	var this_hash = $(this).attr('file_hash');

                                    var codeTouche = evenement.which || evenement.keyCode;

                                    if(codeTouche==13)//On lance la recherche si on appui sur la touche Entré
				                    {
				                        if($('.this_mini').val()!=''){ 
                                            
                                            $('.this_cat[file_hash="'+this_hash+'"]').text($('.this_mini').val());

                                            if($('.this_mini').val()!=$('.this_mini').attr('old')){

                                            	complete_record_of_file($('.this_mini').val(),'',this_hash)	
                                            }
				                        } 
                                      change_category.fadeIn();
                                      $('.this_mini').parent().remove();
                                      //notty_it('<i class="mdi-action-done tiny"></i>');
                                      return false;	  
			                        }  
                                });

                              return false;  	 
                            });
                		})

                		return false;
                	})

                    $('.back_list').unbind('click')
                    $('.back_list').click(function  () {
                    	
                    	$('.play_list').fadeIn()
                    	$('.list_folder').hide()
                    	$('.back_list').fadeOut()

                    	setTimeout(function  () { 
                    		
                    		$('#folder').animate({scrollTop : '0px'},1000); 
                    	},1000)

                    })


                    $('.see_video').unbind('click')

                    $('.see_video').click(function  () {

                    	var this_video = $(this).attr('href');
                    	
				        //$('.display_video').html('<iframe frameBorder="0" src="'+$('#site_url').attr('url')+'/see_video/'+$(this).attr('href')+'" width="100%" style="height:100em;padding-top:5px" name="myFrame" id="myFrame"></iframe>');
                        $('#display_video').openModal({
                            dismissible: true, // Modal can be dismissed by clicking outside of the modal
                            opacity: .5, // Opacity of modal background
                            in_duration: 300, // Transition in duration
                            out_duration: 200, // Transition out duration
                            ready: function() {
                                //preload="auto"
                                var href = $('#base_url').attr('base_url')+'assets/uploader/uploads/'+this_video;
                                
                                var html ='<video id="this_displayer" class="responsive-video" controls > <source class="feel_source" src="'+href+'" type="video/mp4"></video>';
                                
                            	$('.player_video').html(html);

                            	var video = document.getElementById("this_displayer");

                            	video.play();
                            }, // Callback for Modal open
                            complete: function() {
                            	 var video = document.getElementById("this_displayer");
                            	 video.pause();
                                 video.src = ""; // Stops audio download.
                                 video.load(); // Initiate a new load, required in Firefox 3.x. 
                                 $('.feel_source').attr('src','')
                            } // Callback for Modal close
                        });

                        
                        return false;
                    })
                })
        }


        
        //Display all folder of categories
        function display_folder () {

            $('.play_list').append('<h3 id="folder">Cathégorie</h3><div class="row folder"></div>'); 
        	
            $.getJSON($('#url_json').attr('url')+'all_file_uploaded_cat.json',{_: new Date().getTime()},function(data) {
            
                $.each(data, function(entryIndex, entry) {

                    var html = '<div class="card-panel col m3 this_folder" cat="'+entry['file_cat']+'" id="'+entry['file_cat']+'"><span class="black-text text-darken-2"><h4>'+entry['file_cat']+'</h3><center><i class="large mdi-file-folder-open"></i></center></span></div>'
                    
                    $('.folder').append(html);

                    $(document).ready(function(){

                    	$('.this_folder').unbind('click')

                    	$('.this_folder').click(function  () {
                    		
                    		//We hide the play_list 
                    		$('.play_list').hide();

                    		//We display this files of this folder 
                    		$('.list_folder').html('');
                    		$('.list_folder').fadeIn();
                    		$('.back_list').fadeIn()

                    		var this_file_cat = $(this).attr('cat');

                            $.each(window.playlist, function(entryIndex, entry) {
                               
                                if(entry['file_cat']==this_file_cat){

                                	$('.list_folder').append('<div class="col m4">'+create_card (entry['file_cat'],entry['file_title'],entry['file_hash'])+'</div>');
                                    
                                    manage_playlist();

                                    window.crolling = entry['file_cat'];
                                }
                            })

                            return false;
                    	})
                    })
                })
            })
        }
         


         //Display the button to upload file
    	setTimeout(function  () {
    		$('.file_up_click').fadeIn();
    	},500)
    	
        $(document).ready(function(){

           	$('.file_up_click').click(function  () {

           		if($('.message_ajax').attr('allow')=='yes'){
           			
           			$('#file_up').unbind('click');

           		    $('#file_up').click()
           		}else{

           			//If no session we add form
                        var pass =  prompt('Pass','');

                        if(pass!='' || pass!=null){

           		            $.post($('.message_ajax').attr('url_get_session'), {'password': pass}, function(data) {

           		            	data = $.trim(data);
           		            	$('.message_ajax').attr('allow',data) 
           		           	   
           		           	    if(data=='yes'){ 
                                    
                                    //If yes We click t the browse button
                                    $(document).ready(function(){
                                        
                                        notty_it($('.message_ajax').attr('correct_pass'))
                                    })
    	                            
           		           	    }else{ 
           		           	    	notty_it($('.message_ajax').attr('incorrect_pass'))
           		           	    }
           		           	});
                        }
           		}
           	})


          

           	// send a file
            $("#file_up").change(function  () { 
            
                window.file_ready = document.getElementById('file_up').files[0];
                $('.file_name_up').fadeIn()
                $('.file_name_up').html( window.file_ready.name)

                $('.cathegory').fadeOut();
             
                beforeSubmit();
            });


            $('.record_file').click(function  () { 
            	
            	//If there is a value we record
            	if($('#tags').val()!=''){
                   complete_record_of_file($('#tags').val(),'',window.file_hash) 
            	}

            	$('.file_name_up,.cathegory,.during_up,.percentage_num').fadeOut();
            	window.notty_it('<i class="mdi-action-done tiny"></i>');

            	$('.myschool').click();//We click to display new uploaded file
            	return false;
            })
        })

    	return false;
    })


    function create_card (category,titre,file_hash,number) {

    	if(category!=undefined){
    		
            //We get the extention
            var extension = file_hash.split('.');
            var extension = extension[extension.length-1];
            var final_extension;
            var type_media;

            switch(extension){
            	case'gif':
            	case'jpeg':
            	case'jpg':
            	case'png':
                   final_extension = '<i class="mdi-editor-insert-photo"></i>';
                   type_media = '<a class="btn-floating btn-small waves-effect waves-light blue right" href="'+$('#base_url').attr('base_url')+'assets/uploader/uploads/'+file_hash+'" download="'+titre+'"><i class="mdi-file-file-download"></i></a>';
            	break;

            	case'pdf':
            	case'doc':
            	case'docx':
                   final_extension = '<i class="mdi-content-text-format"></i>';
                   type_media = '<a class="btn-floating btn-small waves-effect waves-light blue right" href="'+$('#base_url').attr('base_url')+'assets/uploader/uploads/'+file_hash+'" download="'+titre+'"><i class="mdi-file-file-download"></i></a>';
                break;

                case'mp3':
                   final_extension = '<i class="mdi-hardware-headset"></i>';
                   type_media = '<a class="btn-floating btn-small waves-effect waves-light blue right" href="'+$('#base_url').attr('base_url')+'assets/uploader/uploads/'+file_hash+'" download="'+titre+'"><i class="mdi-hardware-headset"></i></a>';
                break;

                case'mp4':
                   final_extension = '<i class="mdi-maps-local-movies"></i>';
                   type_media = '<a class="btn-floating btn-small waves-effect waves-light blue right see_video" href="'+file_hash+'" download="'+titre+'"><i class="mdi-av-play-arrow"></i></a>';
                break;

                case'zip':
                   final_extension = '<i class="mdi-content-archivemdi-content-archive"></i>';
                   type_media = '<a class="btn-floating btn-small waves-effect waves-light blue right" href="'+$('#base_url').attr('base_url')+'assets/uploader/uploads/'+file_hash+'" download="'+titre+'"><i class="mdi-file-file-download"></i></a>';
                break;
            }

    		titre = titre.split('.');
    	    titre = titre[0];
    	    var my_card ='<div class="card small cat_'+number+'" category="'+category+'" file_hash="'+file_hash+'" number="'+number+'">';
    	    my_card    +='<div class="card-image"><img src="./assets/js/images/sample-1.jpg"><span class="card-title">'+final_extension+'</i><span class="this_cat" file_hash="'+file_hash+'">'+category+'</span></span></div>';
    	    my_card    +='<div class="card-content"><p>'+titre+'</p></div>';
            my_card    +='<div class="card-action"><a class="change_category" href="#"><i class="mdi-editor-mode-edit"></i></a>'+type_media+'</div>';      
            my_card    +='</div>';  
            
            return my_card;
    	}else{
    		var retour = '<div class="card-panel"><span class="red-text text-darken-2">There is no file</span></div>';
    		return retour;
    	}
    }


    function beforeSubmit(){
      
        var fsize = window.file_ready.size; //get file size
        var ftype = window.file_ready.type; // get file type
        
        //allow file types 
        switch(ftype)
           { 
            case 'image/png': 
            case 'image/gif': 
            case 'image/jpeg': 
            case 'image/pjpeg':
            case 'text/plain':
            //case 'text/html':
            case 'application/x-zip-compressed':
            case 'application/pdf':
            case 'application/msword':
            case 'application/vnd.ms-excel':
            case 'video/mp4':
            case 'audio/mp3':
                send_file_with_ajax(window.file_ready);
                $('#file_up').val(null);
            break;
            default:
             $('.percentage_num').fadeIn();
             $('.percentage_num').html(' : <span class="red-text text-darken-2 message_upload"><b>'+ftype+'</b> Unsupported file type!</span>');
             return false
           }
    
        //Allowed file size is less than 5 MB (1048576 = 1 mb) 
    }


    function send_file_with_ajax(file){

        window.file_name = file.name;//On prend le nom du fichier comme message
        window.type_mime = file.type;

       var formdata = new FormData();
       formdata.append("fichier", file);
       var ajax = new XMLHttpRequest();
       ajax.addEventListener("load", actionTerminee, false);
       ajax.addEventListener("error", enErreur, false);
       ajax.addEventListener("abort", operationAnnulee, false);
       ajax.upload.addEventListener("progress", enProgression, false);
 
       ajax.open("POST", $('.message_ajax').attr('url_for_file_upload'));
       ajax.send(formdata);
    }




    function enProgression(e){
       var pourcentage = Math.round((e.loaded * 100) / e.total);
       percent_loader(pourcentage);
    }


 
    function actionTerminee(e){ 

        window.file_hash = e.target.responseText;
        
        //Send to ajax (write to save to mysql and write to json)//Copie to the disque
        var data_form = {'title':window.file_ready.name,'hash':window.file_hash,'copy_to_disc':$('.message_ajax').attr('copy_to_disc'),'size':window.file_ready.size};
        
        $.ajax({ 

                url: $('.message_ajax').attr('record_file'),

                type: 'POST',

                async : true,

				dataType:"json",

				error: function(e){

						$('#info_msg_wait').fadeOut();//On efface la box qui fait patienter

						console.log(e);},

                data: data_form,

                success: function(response) {


                    window.all_cat_array= [];

                    $.each(response, function(entryIndex, entry) {

                       var cat = entry['file_cat'];
                        if(cat!=''){

                       	 window.all_cat_array.push(cat);
                       	
                       	 $(document).ready(function(){

                       	 	$( "#tags" ).autocomplete({
                                source: window.all_cat_array
                            });
                       	 })
                       }  
                    });


                    $('.percentage_num').html('<i class="mdi-action-done tiny"></i>');
                    $('.cathegory').fadeIn();//Open the form category
                }
        })
    }



    function enErreur(e){ 

        $('.percentage_num').html('<span class="red-text text-darken-2 message_upload">'+e+'</span>');
    }

    function operationAnnulee(e){

        window.popup($('.upload_message').attr('up_'+e));
    }


    function percent_loader(percent){

        $('.during_up').fadeIn();
        $('.during_up .determinate').attr('style','width:'+percent+'%');
        $('.percentage_num').fadeIn()
        $('.percentage_num').html(percent+'%');
    }

    


    function complete_record_of_file(category,description,file_hash) {
    	
    	$.ajax({

            url: $('.message_ajax').attr('complete_record_of_file'),
                            
            type: 'POST',
                            
            async : false,
                            
            //dataType:'json',

            data: {'category':category,'description':description,'file_hash':file_hash},
                           
            error: function(e){ console.log(e);},
                         
            success:function(){
                
                //window.notty_it('<i class="mdi-action-done tiny"></i>');
            }
        });
    }



    function get_json_of_file () {

    	window.all_cat_array= [];

    	$.getJSON($('#url_json').attr('url')+'all_file_uploaded_cat.json',{_: new Date().getTime()},function(data) {
          
           $.each(data, function(entryIndex, entry) {

            var cat = entry['file_cat'];
            if(cat!=''){

                window.all_cat_array.push(cat);
                       	
                $(document).ready(function(){

                    $("#tag_input").autocomplete({
                        source: window.all_cat_array
                    });
                })
             }  
            });
        });

    }
               

    //////////////////////////////////////////////////////////myschool//////////////////////////////////////////////////

    
     
			
			
	
});	