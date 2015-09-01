
     
            
    
        
      
      
  


       
            
            <div class="ask_camera_enabled">
                <center>
                    <i class="large mdi-hardware-keyboard-arrow-up direction_up"></i>   
                    <p class="red-text text-darken-2" style="font-size:2em;"><?php echo $this->lang->line('form_allow_cam'); ?></p>
                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><?php echo $this->lang->line('form_close'); ?></a>
                </center>
            </div>
            
            <div class="row pilot controler" style="display:none;">
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light have_smile orange" reason="statu_cam" statu_cam="on"><i class="cam_state mdi-av-videocam-off"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light have_smile orange" reason="follow_me" statu_follow="off"><i class="mdi-action-group-work"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light have_smile orange" reason="send_file"><i class="mdi-editor-attach-file"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light have_smile red"    reason="end_call"><i class="mdi-notification-phone-missed"></i></a></center></div>
            </div>

            <center>
                <div class="small mirror pilot" style="display:none;">
                    <video id="localVideo"></video>
                    <div id="remotesVideos"><i class="mdi-av-videocam small"></i><br></div>    
                </div>
            </center>
            <span class="data_calling" room={room} called={called} my_number={my_number} my_username={my_username}></span>
            <div id="url_node" url="<?php echo NODE; ?>"> </div>

            <script type="text/javascript" src="<?php echo base_url();?>assets/node/node_modules/simplewebrtc/latest.js"></script>
            <script type="text/javascript" src="<?php echo base_url();?>assets/js/pinooy.js"></script>

            
       
   
  