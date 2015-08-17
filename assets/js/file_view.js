
$(document).ready(function(){

         //Ici on gère le module de talk
		 
		 var messageDelay = 3000; //durée d'apprition du message en milliseconde  
		 
		 
		 //Cette fonction fait marcher les infobulles
	$(function(){$('.bulle').tooltip();});//ca c'est la class des infobulle
			
			
			//Cette fonction met un fichier en favoris
			$('.favorite_filer').click(function() {
			                
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
		$('.showCommentBox_file').click(function() {
			                
                var getpID =  $(this).attr('id').replace('post_id','');	
			
			$("#commentBox_file-"+getpID).css('display','');
			$("#commentMark_file-"+getpID).focus();
			$("#commentBox_file-"+getpID).children("CommentImg").show();			
			$("#commentBox_file-"+getpID).children("a#SubmitComment_file").show();                  
        });
		
		$('textarea.commentMark_file').elastic();//On rend élastique le textaerea
		
		
		$(".commentMark_file").keyup(function()
        {
          var box_com =$(this).val();
          var main_com = box_com.length *100;
          var value_com = (main_com / 160);
          var count_com = 160 - box_com.length;

            if(box_com.length <= 160)
            {
			  $("#progressbar_file_comment").progressbar({value: value_com});
              $('#count_com').html(count_com);
            }
            else
            {
               alert('OoooH! STOP!!');
            }
             return false;
        });

		
			
		//SubmitComment
		$('#SubmitComment_file').click(function() {
			
			var getpID =  $(this).parent().attr('id').replace('commentBox_file-','');	
			var comment_text = $("#commentMark_file-"+getpID).val();
			
			if(comment_text.length > 0 && comment_text!=='' )//Rien à expliquer là :)
			{ 				
				var form_data = {id_file : getpID,file_com : comment_text};
				
				 //On affiche la box pour patienter
                 $('#attend').html($('#Please_wait').html()).fadeIn();
				
				
				$.ajax({

				    type: 'post',

				    url:  $(this).attr('action'),
					
					async : true,

				    data: form_data,
					
				    success: function(data_msg){
		
					            $("#commentMark_file-"+getpID).val('');//on efface le champ de commentaire
								
								$('#count_com').html('160');//On remet le compteur à 160 caractères
								
								$("#progressbar_file_comment").progressbar({value: 0});//On réinitialise le progress bar
								
								$('.append_file').append(data_msg).fadeIn('slow');//On affiche le nouveau commentaire
								
								$('#attend').html('');//on arrete de faire patienter
				            }

			    });
			}
			
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
		 				
			$('.affiche_plus_file').click(function() {
			
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
			
			
			
			
			//cette fonction agrandi les photos			
			$('.grossir').click(function() {
			
			                 $('.corps_tof').html($('#Please_wait').html());//on affiche le message pour patienter
			            
                                        $.ajax({
                                        
										        url: $(this).attr('action'),
                                        
										        type: 'POST',
                                        
										        async : true,
                                        
										        error: function(){alert("theres an error with AJAX");},
                                        
										        success:function(response) {
														   
														   $('.corps_tof').html($(response).fadeIn('slow'));	
												          }
									    });

               
            });

	   
			
});	