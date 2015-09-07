$(document).ready(function(){
				
		//Appending HTML5 Audio Tag in HTML Body
		
        //$('<audio id="chatAudio"><source src="'+$('#song').attr('url_chat')+'.mp3" type="audio/mpeg"></audio>').appendTo('body');
		
		    //$('<audio id="bellAudio" loop><source src="'+$('#song').attr('url_bell')+'.mp3" type="audio/mpeg"></audio>').appendTo('body');
		
		var unread = 0;//le nombre de message non lus
		
		var time_waiting_call = 10000 //temps d'attente du lancement d'un appel

		var delay_search_connected = 3000;

    var delay_toast = 10000;
		
		var all_message = [];//ce tableau garde les conversations
		
		window.all_interloc = [];//ce tableau garde la liste des numéros avec qui je suis en conversation array numero(username,nbre_unread)
		
		window.all_interloc_tab = [];//ce tableau garde tous les interlocuteurs sous forme de taleau permettant une boucle
		
		
		var my_infos = my_info();
		
		var my_username,my_numero,my_user_id;
						
	//On enregistre les données de l'utilisateur en local(téléphone,username,userid,rtc_id)	
	function my_info()
	{
	    $.ajax({ 
    
		    url: $('.my_info').attr('url_info'),
                                        
		    type: 'POST',
							                            
			async : false,
							
			dataType:"json",
					                    
			error: function(data){
                                   my_username = $.jStorage.get('username');
                                   my_numero   = $.jStorage.get('numero');
                                   my_user_id  = $.jStorage.get('user_id');	
								   },
                                        
			success:function(data) {
			
			            if(data.statu =='connected')
						{					             
						
                            my_username = $.trim(data.username);
                            my_numero   = $.trim(data.numero);
                            my_user_id  = $.trim(data.user_id);			 
	                    }
					}
		});
	}

		//url de node js
		var socket  = io.connect($('#url_node').attr('url'));
		
		//On créee la room avec le numéro de téléphone 
		//socket.emit('welcome',{'my_id':$.trim($('.peer').text()),'path_upload':$('.message_ajax').attr('url_for_file_upload_dir')});
		
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

		
    if(!navigator.getUserMedia){

       $('#browser').openModal({
        dismissible: false
      });
    }
	
		



	/////////////////////////////////////////////Share file///////////////////////////////////////////////////////////	
    //Testons si le navigateur est comaptible avec le partage de fichier
    if (window.File && window.FileReader && window.FileList && window.Blob){

        window.filer = true;
    }else{

        window.filer = false;
        $('#browser').openModal({
           dismissible: false
        });
    }


    function send_file(caller,room) {
       
      if(window.my_pinooy){ //We close the popup if it is open

        window.my_pinooy.close()
      }
      window.my_pinooy = window.open($('.hoster').attr('pinooy')+room+'/'+caller+'/'+window.user_number+'/'+window.username+'/share_file',"mywindow","status=1,width=650,height=450");
    }


    socket.on('close_box',function () {
      close_box();
    })


    function close_box () {

      window.my_pinooy.close(); 
    }



    socket.on('can_you_take_this',function  (data) {
      window.data_sender = data;

      Materialize.toast(data.caller_name+' '+$('.get_input').attr('ask')+' <a class="accept" href="accept"> &nbsp;'+$('.get_input').attr('yes')+'</a>&nbsp;&nbsp;<a class="decline" href="decline"> &nbsp;'+$('.get_input').attr('no')+'</a>')

      //play a song: Todo
      $(document).ready(function(){

        $('.accept,.decline').unbind('click');

        $('.accept').click(function () { 

          socket.emit('yes_send_it',{'caller_number':window.data_sender.caller_number,'called_number':window.user_number,'room':window.data_sender.room});
          send_file(window.data_sender.caller_number,window.data_sender.room);

          $('#toast-container').html('');

          return false;
        });


        
        $('.decline').click(function () { 

          socket.emit('dont_send_it',{'caller_number':window.data_sender.caller_number,'called_number':window.user_number,'room':window.data_sender.room});
          
          $('#toast-container').html('');

          return false;
        });
      });
    })

    socket.on('dont_send_it',function () {
       
       $('#toast-container').html('');
       Materialize.toast($('.get_input').attr('rejected'),4000);
    })

    
    socket.on('yes_send_it',function  (data) { 
       
       $('#toast-container').html('');
       send_file('none',data.room);
    })




	/////////////////////////////////////////////Share file///////////////////////////////////////////////////////////	

		
        //cette fonction déclanche les notifications		
        window.notificate_it = function(text_msg,type,position) {
      
	    var text_msg = text_msg.substr(0,150);//100 est le nombre limite de caractère
		 
		    // Materialize.toast(message, displayLength, className, completeCallback);
           Materialize.toast(text_msg, 4000) // 4000 is the duration of the toast
		}

	

        
        function htmlspecialchars (string, quote_style, charset, double_encode) {
           // http://kevin.vanzonneveld.net
 
           var optTemp = 0,
           i = 0,
           noquotes = false;
            
			if (typeof quote_style === 'undefined' || quote_style === null) {
                      quote_style = 2;
            }
           string = string.toString();
  
            if (double_encode !== false) { // Put this first to avoid double-encoding
                   string = string.replace(/&/g, '&amp;');
            }
           string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');

           var OPTS = {
              'ENT_NOQUOTES': 0,
              'ENT_HTML_QUOTE_SINGLE': 1,
              'ENT_HTML_QUOTE_DOUBLE': 2,
              'ENT_COMPAT': 2,
              'ENT_QUOTES': 3,
              'ENT_IGNORE': 4
              };
            if (quote_style === 0) {
               noquotes = true;
            }
            if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
               quote_style = [].concat(quote_style);
                for (i = 0; i < quote_style.length; i++) {
                  // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
                    if (OPTS[quote_style[i]] === 0) {
                       noquotes = true;
                    }
                    else if (OPTS[quote_style[i]]) {
                       optTemp = optTemp | OPTS[quote_style[i]];
                    }
                }
              quote_style = optTemp;
            }
            if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
               // string = string.replace(/'/g, '&#039;');
            }
            if (!noquotes) {
              // string = string.replace(/"/g, '&quot;');
            }

           return string;
        }


        
        

         // Requesting to Database every 2 seconds
        var auto_refresher = setInterval(function ()
        { 
                  
				    $.ajax({

				            type: 'post',

				            url: $("#url_session").html(),

				            async : true,

				            error: function(){  //On dit kil est offline
											  $('.attente').html('<img style="width:50%;" class="bulle" title="'+$('#url_image_logo').attr('nothing')+'" data-placement="bottom" src="'+$('#url_image_logo').attr('url')+'begoo.png"> <br><br><br> <div class="alert alert-info">'+$('#url_image_logo').attr('mode_out')+' </div>');  
											   
											   ////////////////////////Manage the menu top///////////////////////////
											   $('.off_hider').fadeOut();//Hide all menu on top


											   ////////////////////////Manage the menu top///////////////////////////
											   window.kwiki_inline ='off';
											},
							
				            success: function(data){
							
									    window.kwiki_inline ='on';


									    ////////////////////////Manage the menu top///////////////////////////
											   $('.off_hider').fadeIn();//Hide all menu on top 
											   
									    ////////////////////////Manage the menu top///////////////////////////

									}
										 
			        });
        }, 5000);





        $('.hello').click(function  () {

          window.click = $(this).attr('reason');

          if(window.on_following_duo==true){
            
            open_hello (false,false,false);

          }else{

            if(window.key_follow!=false){

              open_type_of_communication();

            }else{

                $.ajax({

                  type: 'post',

                  url: $("#form_connection").attr('url')+'session',

                  async : true,

                  error: function(error){ 
              
                    window.notty_it($("#form_connection").attr('no_network'))
                        
                        console.log(error);
                  },
              
                  success: function(data){

                    data = $.trim(data);

                      if(data!='connected'){
                             
                        open_modal_connexion();

                      }else{ 

                        open_type_of_communication();
                      }
                  }
                });
            }          
          }

          return false;
        }) 



       //We manage connection
        $('.pinooy').click(function () {
        	//We close the hello modal
        	$('#hello').closeModal();

        	open_edit();

        	window.type_of_communication = $(this).attr('reason');

        	setTimeout(function  () {
        		//We verify if the user is connected
        	$.ajax({

				    type: 'post',

				    url: $("#form_connection").attr('url')+'session',

				    async : true,

				    error: function(error){ 
				    	
				    	window.notty_it($("#form_connection").attr('no_network'))
                        
                        console.log(error);
				    },
							
				    success: function(data){

              data = $.trim(data);

				    	if(data!='connected'){
                             
                open_modal_connexion();

				    	}else{

				    		//open_type_of_communication();
                //OUvrir la liste d'amis
                open_list_friend();
				    	}
				    }
		        });
         },250);
    })




        	



        function open_modal_connexion() {

           window.connection = true;
        	
        	$('#connection').openModal({

								dismissible: false,// Modal can be dismissed by clicking outside of the modal

                                ready: function() {                                 	
                                     close_edit();
                                     $('.hiden_form').fadeOut();
                                     $('.add_form').parent().fadeIn();
        	                           $('.title_statu').html($('.title_statu').attr('connexion'));
                                     $('.edit_pass').parent().fadeOut();
                                     window.editing = false;
                                }, // Callback for Modal open

                                complete: function() {open_edit();
                                                      wipe_form (); 
                                                      } // Callback for Modal close
			    });
        }



        function open_modal_edit_count () {

           window.connection  = false;
    
          $('#connection').openModal({

                dismissible: false,// Modal can be dismissed by clicking outside of the modal

                                ready: function() {                                   
                                     close_edit();
                                     $('.hiden_form').fadeIn();
                                     $('.add_form').parent().fadeOut();
                                     $('.title_statu').html($('.title_statu').attr('edit')+' <i class="mdi-image-edit"></i>');
                                     $('.form_phone').val(window.user_number);
                                     $('.form_pass').val('');
                                     $('.form_passconf').val('');
                                     $('.form_username').val(window.username);
                                     $('.form_filiere').val(window.user_level);
                                     $('.pass_edit').fadeOut();
                                     $('.edit_pass').parent().fadeIn();
                                     $('.hide_all').fadeOut();
                                     window.editing     = true;
                                    
                                }, // Callback for Modal open

                                complete: function() {open_edit();
                                                      wipe_form ();
                                                      $('.hide_all').fadeIn();
                                                      window.editing     = false;
                                                      } // Callback for Modal close
          });
        }


        $('.edit_pass').toggle(
          
          function(){
            $('.pass_edit').fadeIn();
            window.pass_edited  = true;//We put true to say that the password is not edited
          },
          function(){
            $('.pass_edit').fadeOut();
            window.pass_edited  = false;//We put false to say that the password is not edited
          }
        );


        $('.add_form').toggle(
          
          function(){
            $('.hiden_form').fadeIn();
            window.connection   =false;
            window.registration =true;
            $('.title_statu').html($('.title_statu').attr('inscription'));
          },
          function(){
            $('.hiden_form').fadeOut();
            window.connection   =true;
            window.registration =false;
            $('.title_statu').html($('.title_statu').attr('connexion'));
          }
        );


        //We scroll to the bottom of the box for inscruption in order to present the last input.
        $('.form_passconf').focus(function() {
          
          $('.scroll_content').animate({scrollTop : $('.scroll_content').width()},2000);
        })
       



        //Open the fixed button for communication
        function open_edit() {
        	
        	$('.com_pinooy').fadeIn();
        }

        //Close the fixed button for communication
        function close_edit() {
        	
        	$('.com_pinooy').fadeOut();
        }

        

        $('.add_friend').toggle(
          function  () {
            $('.hiden_form').fadeIn();
            $('.title_statu').html($('.title_statu').attr('inscription'));
            window.connection = false;
          },
          function  () {
            $('.hiden_form').fadeOut();
            $('.title_statu').html($('.title_statu').attr('connexion'));
            window.connection = true;
          }
        );

        
        window.connection = true;//We telling to this click that the submit is for sign in not for sign up
        
        $('.valid_sumit').click(function(){

        	if(window.connection==true){ 

                var form_data = {'number':$('.form_phone').val(),'pass':$('.form_pass').val()};
                var url       = 'connexion_trait';
        	}

          if(window.registration==true){ 
              var form_pass     = $('.form_pass').val();
              var form_passconf = $('.form_passconf').val();
              var form_data     = {'number':$('.form_phone').val(),'pass':form_pass,'passconf':form_passconf,'username':$('.form_username').val(),'filiere':$('.form_filiere').val(),'editing':window.editing,'pass_edited':window.pass_edited};
              var url = 'register_trait';
          }


          if(window.editing==true){

            var form_data = {'number':$('.form_phone').val(),'username':$('.form_username').val(),'filiere':$('.form_filiere').val()};
            var url = 'edit_account';
          }


          if(window.pass_edited==true){
              var form_data = {'pass':$('.form_pass').val(),'passconf':$('.form_passconf').val()};
              var url = 'edit_pass';
          }

        	$.ajax({

				    type: 'post',

				    url: $("#form_connection").attr('url')+url,

				    data:form_data,

				    async : true,

				    error: function(error){ console.log(error)},
							
				    success: function(data){ 

						  if(data.statu=='yep'){

                if(window.connection==true || window.registration==true){
                  
                  window.notty_it($('#statu_user').attr('connected'));
                  
                   open_type_of_communication();

                  window.connection   = false;
                  window.registration = false;
                }


                if(window.editing==true){
                  window.notty_it($('#statu_user').attr('edited'));
                  window.editing = false;
                  window.pass_edited = false;
                }


                $('#connection').closeModal();
                load_data_connection(data.result[0]);
                wipe_form ();
                open_edit();
						  }else{
							  window.notty_it(data.erreurs);
						  }
					  }
			    });
      });

      
    var on_load = get_data_connection();

    //We take all the variable session on the server
      function get_data_connection () {

         $.ajax({

            type: 'post',

            url: $("#form_connection").attr('url_get_data'),

            async : true,

            dataType:"json",

            error: function(error){ console.log(error)},
              
            success: function(data){ console.log(data)
                
                if(data.statu=='yes'){

                  user = data.result[0];
                  load_data_connection (user)
                } 
            }
          });
        }



      function load_data_connection (user) {
        
        window.user_id     = user.user_id;
        window.username    = user.username;
        window.user_number = user.user_number;
        window.user_level  = user.user_level;
        $.jStorage.set('my_user_id',window.user_id);
        window.all_my_data = {'user_id':window.user_id,'user_number':window.user_number,'user_level':window.user_level};
        socket.emit('welcome',window.all_my_data);
      }



       function wipe_form () {
       	//We wipe form
			    $('.form_phone').val('');
			    $('.form_pass').val('');
			    $('.form_passconf').val('');
			    $('.form_username').val('');
			    $('.form_filiere').val('');
       }




      function open_type_of_communication() { 
       	   
       	    switch(window.click){
       	   	    case 'friend': 
       	   	        open_list_friend();
       	   	        break;

       	   	    case 'follow_me':
       	   	        open_follower();
       	   	        break;

       	   	    case 'my_account':
                    setTimeout(function  () {
                      open_modal_edit_count();
                    },250)
       	   	        break;

       	   	    case 'notification':
       	   	        open_list_notification();
       	   	        break;

       	   	    case 'end_call':
       	   	        close_hello(true);
       	   	        break;
       	    }
        }




        window.key_follow = false;//If window.key_follow=false means that this user doesnt follow any group or person
        window.follow_initiator=false

        function open_follower () {

          if(window.key_follow==false){ //If we are not following anything
              
            //We hide the chat button

            setTimeout(function  () {
              $('.com_pinooy').hide('slide', {direction: 'left'}, 250)
            },500)
            

            //We display "stop"
            $('.follow_me').html($('#alert').attr('stop_follow'));
            $('.follow_me').parent().addClass('active');
            //notty_it($('#alert').attr('ask_follow'));

            //I ask people to follow me
             //I generate a key and I send it
              var second = new Date();
              window.key_follow = second.getTime()+'/'+window.user_id;
              socket.emit('big_follow',{'asker_name':window.username,'key_follow':window.key_follow,'asker_num':window.user_number});

              //We notice that It's me who initiate the follow
              window.follow_initiator='me';
          
          }else{
            $('.com_pinooy').show('slide', {direction: 'right'}, 250)

            //We display "Follow me"
            $('.follow_me').html($('#alert').attr('follow_me'));
            $('.follow_me').parent().removeClass('active');

            //I remove myself to the room
            socket.emit('leave_group',window.key_follow);

            window.key_follow=false;

            //We put false for iniatialisation of me
            window.follow_initiator=false;
          }
        }




        socket.on('big_follow',function (data){ 

          if(window.follow_initiator==false && window.key_follow==false){ //Si on est pas suivi et qu'on ne suit personne

            Materialize.toast('<a class="btn-floating btn-large waves-effect waves-light orange"><i class="mdi-social-people"></i></a>&nbsp;'+data.asker_name+'&nbsp;'+$('#alert').attr('new_share')+' <a class="take_share_all" href="accept"> &nbsp;'+$('#alert').attr('click_to_see')+'</a>', delay_toast)
          }
       
          $(document).ready(function(){

            $('.take_share_all').unbind('click');

            $('.take_share_all').click(function(){

              $('#toast-container').html('');

              //We hide the chat button
              $('.com_pinooy').hide('slide',{direction: 'left'},250)

              //We display "stop"
              $('.follow_me').html($('#alert').attr('stop_follow'));
              $('.follow_me').parent().addClass('active');
            
              //We record the key_follow
              window.key_follow = data.key_follow; 
              socket.emit('follow_accepted_anonym',{'asker_num':data.asker_num,'key_follow':data.key_follow,'follower_num':Math.floor(Math.random()*101)});

              return false;
            });
          });
        })



        socket.on('follow_accepted_anonym',function(data){
          
          //J'envoi mon écran à ce nouveau venant
          window.send_my_screen(data.follower_num,false,window.save_title,window.save_url,window.save_zim,window.save_zim_file,false);
        })


   
        function open_list_friend() {

          if(window.device=='mobile'){
            window.hide_page();
          }

        	
        	//We take the number of friend in localStorage
            var list_friend = $.jStorage.get('my_friends',false);

            //We verify if the owner on this list of friend is the user connected
            var user_id = $.jStorage.get('my_user_id',false);

            if(user_id==false || user_id!=window.user_id){
              
              $.jStorage.set('my_user_id',window.user_id);

              take_friend_list_from_database(true);
            }else{

              if(list_friend){

                if(list_friend.length==0){//if ther is no friend
                 //We send message to notice no friend list
                  no_friends();
                    
                }else{

                  $('.content_of_list').html('<div class="user_list collection"><a href="+" class="collection-item add_friend card-panel blue-text text-darken-2">'+$('.no_friend').attr('add_contact')+'<i class="small mdi-social-person-add"></i></a></div>');
                  $('.other_choice').fadeOut();
                  for (var i = 0; i < list_friend.length; i++) { 

                    if(list_friend[0]==false){

                      var name_displayed = list_friend[i][1];
                    }else{
                      var name_displayed = list_friend[i][0];
                    }

                    $('.user_list').append('<a class="waves-effect waves-blue user_click collection-item" href="'+list_friend[i][1]+'" user_name="'+name_displayed+'">'+name_displayed+'<i class="secondary-content mdi-content-send"></i></a>');

                    if(i==list_friend.length-1){

                     //We load new element to the DOM
                     add_friend();
                     ready_list_user_to_be_clicked();
                    }
                  }
                }
              }else{ 
            	//We send message to notice no friend list
            	take_friend_list_from_database(true);
            }
          }
        }

        //var cool = take_friend_list_from_database(true);

        function take_friend_list_from_database (request) {
          
          $.ajax({

                    type: 'post',

                    url: $(".no_friend").attr('get_friend_url'),

                    async : true,

                    error: function(error){ console.log(error)},
              
                    success: function(data){

                      if(request==true){

                        if(data.statu=='no_friend'){
                          no_friends()
                        }else{ 
                          //We record the friend list on local
                           $.jStorage.set('my_friends',[]);
                          for (var i = 0; i < data.friend.length; i++) { 
                            add_friend_on_storage_list (data.friend[i].friend_number,data.friend[i].friend_name,true);

                            if(i==data.friend.length-1){
                              open_list_friend ();
                            }
                          };
                        }
                      }
                    }
          });
        }


        function no_friends () {

        	$('.liste').html($('.no_friend').html());
          Materialize.showStaggeredList('#staggered-list');
        	  
            add_friend();//Load the button in order to add friend
        }

        

        function add_friend () {
        	
        	$(document).ready(function(){
               
                $('.add_friend,.ok_add').unbind('click');

        	    $('.add_friend').click(function () {
        	        $('#add_friend').openModal({
                        ready: function() { close_edit();$('.error_guest').html('');}, // Callback for Modal open
                        complete: function() {open_edit() } // Callback for Modal close
                    });
        	        return false;
                })

                $('.ok_add').click(function(){ 

                	var name  = $('.guest_name').val();
                	var phone = $('.guest_phone').val();

                	var form_data = {'name':name,'phone':phone};
                	
                	$.ajax({

				            type: 'post',

				            url: $(".no_friend").attr('adding_friend_url'),

				            data:form_data,

				            async : true,

				            error: function(error){ console.log(error)},
							
				            success: function(data){
                            
                            if(data.statu=='fail'){

                            	$('.error_guest').html(data.message);
                            }else{ 
                            	
                            	$('#add_friend').closeModal();
                            	window.notty_it($('.no_friend').attr('added'));
                            	add_friend_on_visual_list(phone,name);
                            	add_friend_on_storage_list(phone,name,false);
                	           $('.guest_name').val('');
                	           $('.guest_phone').val('');
                            }
                        }
                    });
                })
            });
        }



        function add_friend_on_visual_list (user_phone,user_name) {

          if(user_name==false){
              var name_displayed=user_phone;
            }else{
              var name_displayed=user_name;
            }

        	//We scroll on the button of the list before add
        	$('.liste').animate({scrollBottom : '0px'},1000);

        	if($('.user_list').html()==undefined){

        		$('.liste').html('<div class="user_list collection"><a href="+" class="collection-item add_friend card-panel blue-text text-darken-2">'+$('.no_friend').attr('add_contact')+'<i class="small mdi-social-person-add"></i></a></div>');
        	}
        	
        	$('.user_list').append('<a class="waves-effect waves-blue user_click collection-item" href="'+user_phone+'" user_name="'+name_displayed+'">'+name_displayed+'<i class="secondary-content mdi-content-send"></i></a>');
            
            ready_list_user_to_be_clicked();
            add_friend();
        }



        function add_friend_on_storage_list (user_phone,user_name,type) {

        	var friend = [user_name,user_phone];

          if(type==true){
            var list_friend = $.jStorage.get('my_friends');
            list_friend.push(friend); 
            $.jStorage.set('my_friends',list_friend);

          }else{ 

            var list_friend = $.jStorage.get('my_friends',[]);
            list_friend.push(friend); 
            $.jStorage.set('my_friends',list_friend);
          } 
        }



        function ready_list_user_to_be_clicked () {

        	$(document).ready(function(){

        		$('.user_click').unbind('click');

        		$('.user_click').click(function  () {
        			
        			$('.user_click').removeClass('active');
        			$(this).addClass('active');

              window.interloc_num      = $(this).attr('href');
              window.interloc_username = $(this).attr('user_name');

        			//we open the chat
				    open_chat_box(); //on affiche la fenêtre de chat

				    var name = $(this).text(); //mdi-editor-attach-file
				   
				    $('.name_box').html($(this).text());

            command_click_box ();

        			return false;
        		})
        	});
        }



        function command_click_box () { 

          $(document).ready(function(){

            $('.com').unbind('blink');

            $('.com').click(function(){   
   
                switch($(this).attr('reason')){

                  case 'camera':
                    open_hello('me',false);
                   break;

                  case 'file':
                   var room = window.user_number+'_'+window.interloc_num;
                    //Send permission
                    Materialize.toast($('.get_input').attr('asker'));
                    socket.emit('can_you_take_this',{'caller_number':window.user_number,'caller_name':window.username,'called_number':window.interloc_num,'room':room});
                   break;

                  case 'follow':
                    if(window.key_follow!=false){
                      stop_make_him_follow_me(false);
                    }else{
                      make_him_follow_me(false);
                    }
                   break;

                  case 'close_box':
                    close_chat_box();
                   break;
                }
               
              
              return false;
            })
          });
        }


        window.my_pinooy_opened = false;

       

        



        function open_hello(caller,room) {

          if(caller=='me'){

            room = window.user_number+'_'+window.interloc_num;//We create à room for the connection

            turn_on_camera(room,window.interloc_num);
          }else{
            turn_on_camera(room,'none')
          }

          close_edit();
        }




  

       /////////////////////////////////////////////DUO  chat/webrtc//////////////////////////////////////////////

    function turn_on_camera(room,called) {
       
      if(window.my_pinooy){ //We close the popup if it is open

        window.my_pinooy.close()
      }
      window.my_pinooy = window.open($('.hoster').attr('pinooy')+room+'/'+called+'/'+window.user_number+'/'+window.username+'/video_call',"mywindow","status=1,width=650,height=450");
    }

  


		socket.on('verif_if_called_busy',function(data){ 

			if(window.calling == true){ 

				socket.emit('called_busy',data.caller_number);
			}else{

        ring_the_phone(data);
			}
		})




    function ring_the_phone(data) {

     
      Materialize.toast($('#alert').attr('new_call')+' '+data.caller_name+' <a class="take_call" href="accept"> &nbsp;<i class="small mdi-communication-call"></i></a>&nbsp;&nbsp;<a class="reject_call" href="reject"> &nbsp;<i class="small mdi-communication-call-end"></i></a>')
      window.data_calling = data;
      play_bell();


      $(document).ready(function(){

        $('.take_call,.reject_call').unbind('click');

        $('.take_call').click(function () { 

          stop_bell();

          socket.emit('called_not_busy',{'caller_number':window.data_calling.caller_number,'called_number':window.user_number});
          open_hello('not_me',data_calling.room);

          $('#toast-container').html('');

          return false;
        });


        
        $('.reject_call').click(function () { 

          stop_bell();
          
          call_rejected (window.data_calling);
          
          $('#toast-container').html('');

          return false;
        });
      });
    }


    

    function call_rejected (data) {
      
        socket.emit('call_rejected',data.caller_number);
    }

    socket.on('call_rejected',function  () {

      end_call();
    })


   
        
    socket.on('zut',function() {
                          
		  // son interlocuteur n'arrive pas a se connecté
			window.notty_it($('#alert').attr('wat'));	

    });




    function abord_call()
		{
		    stop_bell();
		}

		function play_bell() 
		{
		   //we play song
        window.audioBell = new Audio($('#song').attr('url_bell')+'pizzicato.ogg');
        window.audioBell.play();
		}


		function stop_bell()
		{
      window.audioBell.pause();
      window.audioBell.currentTime = 0;
		}



    socket.on('call_ended',function(){
        
        end_call();
    });



    function end_call () {

      window.notty_it($('#alert').attr('end_call'));

        //Close the window
        
      window.my_pinooy.close();                    
    }



	/////////////////////////////////////////////DUO  chat/webrtc  FIN//////////////////////////////////////////////


   /////////pour la chat box///////////////////////////////
    
     function open_chat_box() {

      if(window.key_follow==false){
       var camera_chat = '<a href="camera" class="com com_camera" reason="camera"><i class="mdi-notification-voice-chat"></i></a>';
       var attach_file = '<a href="file"   class="com com_file" reason="file">   <i class="mdi-editor-attach-file"></i></a>';
      }else{
        var camera_chat = '';
        var attach_file = '';
      }

      
     // var follow      = '<a href="follow" class="com" reason="follow"> <i class="following mdi-social-share"></i></a>';
      var minimize    = '<a class="" style="color:#FFF;cursor:pointer;" >  <i class="mdi-content-remove"></i></a>';
      var close       = '<a href="close" class="com" reason="close_box">  <i class="mdi-navigation-close"></i></a>';

    
      $("#chat_div").chatbox({id : "chat_div",
                                  title : '<span class="name_box truncate"></span><span class="title_box_style"><span class="ui-chatbox-icon">'+camera_chat+attach_file+minimize+close+'</span></span>',
                                  user : "can be anything",
                                  hidden: false, // show or hide the chatbox
                                  offset:0,
                                  width: 230, // width of the chatbox
                                  messageSent: function(id,user,msg){ 
                                  
                                     $("#chat_div").chatbox("option", "boxManager").addMsg(window.username, msg);//on écrit son message en local

                                     socket.emit('send_message',{'sender_name':window.username,'sender_num':window.user_number,'sender_msg':msg,'interloc_num':window.interloc_num});
                                  }
      });
      
      window.open_chat = true;
    }


    function close_chat_box() {
       $('.ui-widget').fadeOut();
       $('#chat_div').html();
       window.open_chat = false;
    }



    window.open_chat = false;

    //We recive message 
    socket.on('new_message', function (data) {
      //We open the chat box if it is not open
      if(window.open_chat==false){
         
          open_chat_box();

          window.interloc_num      = data.sender_num;
          window.interloc_username = data.sender_name;
          $('.user_click').removeClass('active');//We not active if there is a user clicked before
          $('.name_box').html(data.sender_name);

          command_click_box();
      }
      
        $("#chat_div").chatbox("option", "boxManager").addMsg(data.sender_name,data.sender_msg);//Displaying message
    });
      
    
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    //////////////////////////////////////Follow me ///////////////////////////////////////////////////////////////
    window.on_following_duo = false;//This variable to false means that Im not in followong process
    
    function make_him_follow_me (type) { //type trying to know if we follow during a calling or not.true means that it is during calling

      if(window.on_following_duo==true){

        stop_make_him_follow_me(false);

        window.stop_following = true;

      }else{
        
        if(type==true){

          window.on_following_duo = true;
     
          window.send_my_screen(window.interloc_num,true,window.save_title,window.save_url,window.save_zim,window.save_zim_file,false); //true means that we send this article for the first time

          close_modal_during_calling()
          socket.emit('close_modal_during_calling',window.interloc_num);
          
          change_statu_camera();
          socket.emit('change_statu_camera',window.interloc_num);
        
          window.stop_following = false;
      
          //We change icon to the message stop following
          $('.following').removeClass('mdi-social-share').addClass('mdi-action-visibility-off'); 
        }else{
         //We ask permmission if my friend want to see my screen if it is not during calling
         socket.emit('follow_please',{'asker_name':window.username,'asker_num':window.user_number,'receiver':window.interloc_num});

         notty_it($('#alert').attr('ask_follow'));
        }
      }
    }


    socket.on('follow_please',function(data){ 

      var asker_num =  data.asker_num;
      var asker_name = data.asker_name;

      window.delay_accept_sharing = false;
       
      Materialize.toast(data.asker_name+'&nbsp;'+$('#alert').attr('new_share')+' <a class="take_share" href="accept"> &nbsp;'+$('#alert').attr('click_to_see')+'</a>', delay_toast,'',function(){refuse_to_see(data)})
      
      $(document).ready(function(data){

        $('.take_share').unbind('click');

        $('.take_share').click(function (data) { 

          socket.emit('follow_accepted',asker_num);

          window.interloc_num      = asker_num;
          window.interloc_username = asker_name;

          //We add this asker if the list of friend is opened
          if($('.user_list').html()!=undefined){

            add_friend_on_visual_list(data.asker_num,data.asker_name);

            $('.user_click').removeClass('active');
          }

          window.delay_accept_sharing = true;

          return false;
        });
      });
    })



    socket.on('follow_accepted',function() {

      window.on_following_duo = true;

      window.stop_following   = false; alert(window.user_number);

      socket.emit('send_message',{'sender_name':window.username,'sender_num':window.user_number,'sender_msg':'...','interloc_num':window.interloc_num});
     
      window.send_my_screen(window.interloc_num,true,window.save_title,window.save_url,window.save_zim,window.save_zim_file,false); //true means that we send this article for the first time
    
      $('.following').removeClass('mdi-social-share').addClass('mdi-action-visibility-off');//We sure that we display the button to end follow
    });



    function refuse_to_see(data) {

      if(window.delay_accept_sharing == false){

        socket.emit('follow_refuse',data.asker_num);
      }
    }



    socket.on('follow_refuse',function  () {
      
      Materialize.toast($('#alert').attr('follow_refuse'),delay_toast);
    })


    socket.on('close_modal_during_calling',function () {
       close_modal_during_calling(); 
    })


    function stop_make_him_follow_me(type) { //type is false if the order is not comming from socket

      window.on_following_duo = false;
      //change_statu_camera();
      //close_chat_box();
      
      if(type==false){

        socket.emit('stop_make_him_follow_me',window.interloc_num);
        $('.following').removeClass('mdi-action-visibility-off').addClass('mdi-social-share');
        //close_chat_box();  
      }

      if(window.key_follow!=false){
          
          socket.emit('leave_group',window.key_follow);
          window.key_follow = false;
          //close_chat_box();
      }
     
    }


    socket.on('stop_make_him_follow_me',function  () {
      
      stop_make_him_follow_me(true);
    })


    function close_modal_during_calling () {

      put_blink();//We change the look of the fixed button
      close_hello(false);
    }


    window.send_my_screen = function(receiver,first,save_title,save_url,save_zim,save_zim_file,group) {//if group is true it means that we send this article to a group
    
       socket.emit('send_my_screen',{'content':$('.wiki_content').html(),'title':$('.wiki_title').text(),'receiver':receiver,'first_time':first,'save_title':save_title,'save_url':save_url,'save_zim':save_zim,'save_zim_file':save_zim_file,'group':group});
    }

         

    //////////////////////////////////////Follow me //////////////////////////////////////////////////////////////
    });