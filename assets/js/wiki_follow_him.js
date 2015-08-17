
$(document).ready(function(){

//url de node js
		var socket  = io.connect($('#url_node').attr('url'));
	
         $('.follow_him').unbind('click');//on détache le click s'il était présent avant

          	 //ceci est pour suivre quelqu'un
		    $('.follow_him').click(function() {
			
			    //Si je suivais une autre personne
			    var wiki_follow = $('.wiki_follow').html();
				/* 
			    if(wiki_follow!=='me' || $('.wiki_title').html()=='')
				{												 
	              socket.emit('leave_it',$('.followed_user_id').attr('user_id'));//je sort de cette room
			    }
				else //Si on me suivait
				{
				   socket.emit('leave_it_follower',my_user_id);//je sort de ma room
				}*/
				
				if($(this).attr('user_id')!==$('.my_info').attr('user_id'))
				{ 
			        socket.emit('jointe',{'followed_id':$(this).attr('user_id'),'follower_id':$('.my_info').attr('user_id')});//on le fait joindre le groupe

                  // on change le statu du bouton et d'autre action
			            var user_id_followed = $(this).attr('user_id');
			
			            var user_id_name = $(this).attr('user_name');
			
						  $('.wiki_follow').html('follower');//on met à jour sonstatu sur wikipedia
						  $('.wiki_follow_user').html(user_id_followed);//on prend son id
						  $('.name_followed').html(user_id_name);//on prend son nom
						  $('.followed_user_id').attr('user_id',user_id_followed);//on prend son id
						  $('.info_followed').fadeIn();
                          $('.note_follow').fadeOut();						  
                          $('#link_add').click();//on affiche la fenêtre de chat
						  $('.look_wiki').attr('if_article','yes');//signale que le navigo est tout ouvert

                          // on change le statu du bouton et d'autre action
                          $('.statu_link').html($('.follow_me').attr('follow_me'));
							
							if($('.wiki_follow').html()=='me')
							{
							  $('.wiki_follow').html('');//s'il ne suivait personne avant,on initialise son statu
							}
													
							$('.followers_number').fadeOut();
                }							
					
				 return false;
		    });
   
			
});	