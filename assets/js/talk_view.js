
$(document).ready(function(){

         //Ici on gère le module de talk
		 
		 var messageDelay = 3000; //durée d'apprition du message en milliseconde  
		 
		 
		 //Cette fonction fait marcher les infobulles
	$(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
	
	
	
		 
		//Cette fonction fait suivre un talk
			$('.follow').click(function() {
			
			    var follower = $(this);
			                
                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//On affiche la box pour patienter
	
	         			                $.ajax({
                                        
										        url: $(this).attr('action'),
                                        
										        type: 'POST',
                                        
										        async : true,
                                        
										        error: function(){alert("theres an error with AJAX");},
                                        
										        success:function() {
														   
														   follower.parent().html($('.confirm').html());
										         
												           $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter		
												          }
									    });

                                  return false;
            });
			
			
			//Cette fonction met un talk en favoris
			$('.favorite_talker').click(function() {
			                
                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//On affiche la box pour patienter
	
	         			                $.ajax({
                                        
										        url: $(this).attr('action'),
                                        
										        type: 'POST',
                                        
										        async : true,
                                        
										        error: function(){alert("theres an error with AJAX");},
                                        
										        success:function(data_msg) {
												           $('.all_favor').html(data_msg);
										         
												           $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter		
												          }
									    });

                                  return false;
            });
			
	

       
	   
	    //showCommentBox
		$('.showCommentBox').click(function() {
			                
                var getpID =  $(this).attr('id').replace('post_id','');	
			
			$("#commentBox-"+getpID).css('display','');
			$("#commentMark-"+getpID).focus();
			$("#commentBox-"+getpID).children("CommentImg").show();			
			$("#commentBox-"+getpID).children("a#SubmitComment").show();                  
        });
		
		$('textarea.commentMark').elastic();//On rend élastique le textaerea
		
		
		$(".commentMark").keyup(function()
        {
          var box_com =$(this).val();
          var main_com = box_com.length *100;
          var value_com = (main_com / 160);
          var count_com = 160 - box_com.length;

            if(box_com.length <= 160)
            {
			  $("#progressbar_talk_comment").progressbar({value: value_com});
              $('#count_com').html(count_com);
            }
            else
            {
               alert('OoooH! STOP!!');
            }
             return false;
        });

		
		
		
		
		//SubmitTag
		$('.submit_tag').click(function() {
			
			var my_id_talk =  $(this).attr('rel');	
			var tag_text   = $('#my_tag').val();
			
			if(tag_text.length > 0 && tag_text!=='' )//Rien à expliquer là :)
			{ 				
				var form_data = {id_talk : my_id_talk,tag : tag_text};
				
				$.ajax({

				    type: 'post',

				    url:  $(this).attr('action'),
					
					async : true,

				    data: form_data,
					
					dataType:"json",
					
					error: function(){alert("theres an error with AJAX");},

				    success: function(data_msg){
					
					            var statu_messenger = data_msg.statu;

					            $('#my_tag').val('');//on efface le champ de tag
								
								$('li').remove('.tag_it'); //Je supprime l'élément
								
								$('#myModal_tag').modal('hide');//on ferme la fenêtre modal
								
								$('.messenger').html(data_msg.messenger);//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
									
									                switch (statu_messenger) {
													     case 'good':
	                                                      $('#good_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
	                                                     case 'bad':
	                                                      $('#bad_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;
	                                                     case 'info':
	                                                       $('#info_msg').fadeIn().delay(messageDelay).fadeOut();
	                                                     break;	
                                                    }
				            }

			    });
			}
			
		});


         //Cette fonction annule le message qu'on voulait taguer
			$('.submit_closer_tag').click(function() {
			
			   $('#my_tag').val('');//on efface le champ de tag
            });			
		
	

         //Envoi un message coucou
		$('.coucou').click(function() {
		 
		    var coucou = $(this);
				
				$.ajax({

				    type: 'post',

				    url:  $(this).attr('action'),
					
					async : true,
										
					error: function(){alert("theres an error with AJAX");},

				    success: function(){	
										coucou.parent().html($('.confirm').html());
				             }

			    });
			
		});	
		
		
	
	//Cette fonction fait marcher les infobulles
	$(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
		 		
		
		//Cette fonction fait suivre un talk
			$('.affiche_plus').click(function() {
			
			    var follower = $(this);
			                
                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//On affiche la box pour patienter
	
	         			                $.ajax({
                                        
										        url: $(this).attr('action'),
                                        
										        type: 'POST',
                                        
										        async : true,
                                        
										        error: function(){alert("theres an error with AJAX");},
                                        
										        success:function(response) {
														   
														   $('.all_comment').html($(response).fadeIn('slow'));
										         
												           $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter		
												          }
									    });

                return false;
            });
			
			
			
			$('.recall').click(function() {
			
			    var follower = $(this);
			                
                            $('#info_msg_wait').html($('#Please_wait').html()).fadeIn();//On affiche la box pour patienter
	
	         			                $.ajax({
                                        
										        url: $(this).attr('action'),
                                        
										        type: 'POST',
                                        
										        async : true,
                                        
										        error: function(){alert("theres an error with AJAX");},
                                        
										        success:function() {
										         
												           $('#info_msg_wait').fadeOut();//On efface la box qui fait patienter		
												          }
									    });

                                  return false;
            });

	   
	   
	   
	   //SubmitComment
	   $('#SubmitComment').unbind('click');//on détache le click s'il était présent avant
	   
		$('#SubmitComment').click(function() {
			
			var getpID =  $(this).parent().attr('id').replace('commentBox-','');	
			var comment_text = $("#commentMark-"+getpID).val();
			
			if(comment_text.length > 0 && comment_text!=='' )//Rien à expliquer là :)
			{ 				
				var form_data = {id_talk : getpID,talk_com : comment_text};
				
				//On affiche la box pour patienter
                $('#attend').html($('#Please_wait').html()).fadeIn();	
				
				$.ajax({

				    type: 'post',

				    url:  $(this).attr('action'),
					
					async : true,

				    data: form_data,
					
					dataType:"json",

				    success: function(data_msg){
					
					            var statu_messenger = data_msg.statu;

					            $("#commentMark-"+getpID).val('');//on efface le champ de commentaire
								
								$('#count_com').html('160');//On remet le compteur à 160 caractères
								
								$("#progressbar_talk_comment").progressbar({value: 0});//On réinitialise le progress bar
								
								$('.messenger').html(data_msg.messenger);//On met le message dans le conteneur #messenger et on l'affiche dans le switch suivant
									
									                switch (statu_messenger) {
	                                                     case 'bad':
	                                                      //$('#bad_msg').fadeIn().delay(messageDelay).fadeOut();
														   $('#attend').html('');//on arrete de faire patienter
	                                                     break;
	                                                     case 'info':
	                                                      // $('#info_msg').fadeIn().delay(messageDelay).fadeOut();
														   $('#attend').html('');//on arrete de faire patienter
	                                                     break;	
                                                    }
				            }

			    });
			}
			
		});	
			
});	