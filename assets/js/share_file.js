$(document).ready(function(){

	var socket  = io.connect($('#url_node').attr('url'));

	//On créee la room avec le numéro de téléphone 
	socket.emit('welcome_pinooy',$('.data_calling').attr('my_number'));


	var sharing_ready = turn_on_sharing($('.data_calling').attr('room'),$('.data_calling').attr('called'));

	 
	
     //We send file (Take one by one)
    function turn_on_sharing(room,caller){

      //We close the calling popup if it is open
      if(window.my_pinooy){

        window.my_pinooy.close();
      }


      //How to display the right menu
      if(caller=='none'){
          config_loader_as_sender();
      }else{
         config_loader_as_receiver();
      }


      var webrtc = new SimpleWebRTC({
      // we don't do video
        localVideoEl: '',
        remoteVideosEl: '',
        // dont ask for camera access
        autoRequestMedia: false,
          // dont negotiate media
        receiveMedia: {
          mandatory: {
            OfferToReceiveAudio: false,
            OfferToReceiveVideo: false
          }
        },

        url:$('.hoster').attr('signal_server')
      });


      webrtc.joinRoom(room);

      // called when a peer is created
      webrtc.on('createdPeer', function (peer) {
           
          // send a file
          $("input:file").change(function  () {
            
            $(this).disabled = true;
            var file = document.getElementById('filer').files[0];
            var sender = peer.sendFile(file);
            window.file_size = file.size;

            sender.on('progress', function (bytesSent) {
              $('.load_button').html('<a class="btn-floating btn-large waves-effect waves-light blue"><i class="mdi-device-access-time"></i></a>');
              $('.close_filer').fadeOut();//We hide the dismiss button closing the modal
              $('.ask_to_choose').fadeOut();

              var percentage = Math.floor((bytesSent/window.file_size)*100);
              progress_loaeder(percentage);
            });

            sender.on('complete', function () {
              // safe to disconnect now
              $('.load_button').html('<a class="btn-floating btn-large waves-effect waves-light red"><i class="mdi-action-done"></i></a>');
              $('.close_filer').fadeIn();//We hide the dismiss button closing the modal
            });  
          });


          // receiving an incoming filetransfer
        peer.on('fileTransfer', function (metadata, receiver) {
          console.log('incoming filetransfer', metadata.name, metadata);
          config_loader_as_receiver(metadata.name);
          receiver.on('progress', function (bytesReceived) {
            console.log('receive progress', bytesReceived, 'out of', metadata.size);
            $('.close_filer').fadeOut();//We hide the dismiss button closing the modal
            var percentage = Math.floor((bytesReceived/metadata.size)*100);
            progress_loaeder(percentage);
          });
        
          // get notified when file is done
          receiver.on('receivedFile', function (file, metadata) {
            console.log('received file', metadata.name, metadata.size);
            $('.load_button').html('<a class="btn-floating btn-large waves-effect waves-light red" download="'+metadata.name+'" href="'+ URL.createObjectURL(file)+'"><i class="mdi-file-file-download"></i></a>'+$('.get_input').attr('download'));
            $('.ask_to_choose').html('')
            $('.close_filer').fadeIn();

            // close the channel
            receiver.channel.close();
          });
        });
      });  
    }



    function config_loader_as_sender () {
      
      $('.file_name').hide();
      $('.choose_file').show();
      $('.determinate').attr('style','width:0%');
      $('.number_progress').html('');
    }


    function config_loader_as_receiver (filename) {
      $('.file_name').show();
      $('.file_name').html(filename);
      $('.choose_file').hide();
      $('.determinate').attr('style','width:0%');
      $('.number_progress').html('');
      $('.ask_to_choose').html('<a class="btn-floating btn-large waves-effect waves-light blue"><i class="mdi-device-access-time"></i></a> ...'+$('.get_input').attr('asker'));
    }

    function progress_loaeder (percentage) {
      $('.number_progress').html(percentage+'%');
      $('.determinate').attr('style','width:'+percentage+'%');
    }


    $('.choose_file').click(function() { 

        $('.get_input input').click();
    })


    $('.close_filer').click(function  () {
      
      window.close();
    })


  /*
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
    */

});