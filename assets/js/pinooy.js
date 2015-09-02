$(document).ready(function(){

	var socket  = io.connect($('#url_node').attr('url'));

	//On créee la room avec le numéro de téléphone 
	socket.emit('welcome_pinooy',$('.data_calling').attr('my_number'));

	$('.direction_up').effect('bounce');

	var call_ready = turn_on_camera($('.data_calling').attr('room'),$('.data_calling').attr('called'));

	 
	function turn_on_camera (room,called){	
       
     
        window.webrtc = new SimpleWebRTC({
            // the id/element dom element that will hold "our" video
            localVideoEl: 'localVideo',
            // the id/element dom element that will hold remote videos
            remoteVideosEl: 'remotesVideos',
            // immediately ask for camera access
            autoRequestMedia: true,

            url:$('.hoster').attr('signal_server')
        });



         
        window.webrtc.on('readyToCall', function () { 
         // you can name it anything
           window.webrtc.joinRoom($('.data_calling').attr('room')); 
           $('.ask_camera_enabled').fadeOut();
           $('.pilot').fadeIn();
        
           //Belling
           //We ring the called if im not the called
            if(called!='none'){

                start_local_bell();

                var room =$('.data_calling').attr('room') ;//We create à room for the connection
      
                socket.emit('verif_if_called_busy',{'caller_number':$('.data_calling').attr('my_number'),'caller_name':$('.data_calling').attr('my_username'),'called_number':$('.data_calling').attr('called'),'room':$('.data_calling').attr('room')});
            }
        });



      // listen for mute and unmute events
    window.webrtc.on('mute', function (data) { // show muted symbol
        
        window.webrtc.getPeers(data.id).forEach(function (peer) {
            if(data.name == 'video') {
                show_mute_video();
            } 
        });
    });


      window.webrtc.on('unmute', function (data) { // hide muted symbol
    
        window.webrtc.getPeers(data.id).forEach(function (peer) {
          if (data.name == 'video') {
            show_unmute_video();
          }
        });
      });


      window.webrtc.on('videoOn', function () { 
      // local video just turned on
        show_unmute_video();
      });

      window.webrtc.on('videoOff', function () {
        // local video just turned off
        show_mute_video();
      });

	}




	function change_statu_camera () { 
      
      if(window.camera==true){ 

         mute_video();
         show_mute_video()
         window.camera = false;
      }else{ 
        unmute_video();
        show_unmute_video();
        window.camera = true;
      }
    }



    socket.on('change_statu_camera',function  () {
      change_statu_camera();
    })


    //This function is to mute video to allow only video call 
    function mute_video () {
      window.webrtc.pauseVideo();
    }

    function unmute_video () {
      window.webrtc.resumeVideo();
    }

    function show_mute_video () {
         $('.cam_state').removeClass('mdi-av-videocam-off');
         $('.cam_state').addClass('mdi-av-videocam');
         //$('.mirror').append('<i class="valign phone large mdi-notification-phone-in-talk"></i>');
         $('#my_caller').fadeOut();
    }


    function show_unmute_video () {
        $('.cam_state').addClass('mdi-av-videocam-off');
        $('.cam_state').removeClass('mdi-av-videocam');
        $('.phone').remove();
        $('#my_caller').fadeIn();
    }






    


    $('.have_smile').click(function() {
          
            switch($(this).attr('reason')){

              case 'end_call':
                 socket.emit('end_call',window.interloc_num);
                 break;
              
              case 'statu_cam':
                 change_statu_camera();
                 socket.emit('change_statu_camera',window.interloc_num);
                 break;

              case 'follow_me':
                 make_him_follow_me(true);
                 break;

            }

          return false;
    })

   



    socket.on('call_rejected',function () {
      
      notty_it($('#alert').attr('reject_call'));

      stop_local_bell();

     //Close the window
    })



	socket.on('called_busy',function(){

	   window.notty_it($('#alert').attr('busy'));

       $('.mirror').html('<i class="valign large mdi-notification-phone-missed"></i>');
       
       stop_local_bell();

       //Close the window
	});


	
	socket.on('called_not_busy',function(){

		stop_local_bell();
	}); 




    socket.on('stop_belling',function(){

      abord_call()
    })






	function stop_local_bell () {
		//we play song
        window.audioBell.pause();
        window.audioBell.currentTime = 0;
	}


	function start_local_bell () {
		window.audioBell = new Audio($('#song').attr('url_bell')+'pizzicato.ogg');
        window.audioBell.play();
	}



});