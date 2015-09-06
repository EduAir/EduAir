
     
            
    
        
      
      
  
        <div class="modal-content">
            <center>
                <p class="red-text text-darken-2 ask_to_choose" ><?php echo $this->lang->line('form_file_transfert'); ?></p>
                <p class="red-text text-darken-2 file_name" ></p>
                <span class="load_button"><a class="btn-floating btn-large waves-effect waves-light red choose_file"><i class="mdi-content-add"></i></a></span><br>
                <div class="progress"><div class="determinate" style="width: 0%"></div></div><div class="number_progress"></div>
            </center>
        </div>

        <center><a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat close_filer"><?php echo $this->lang->line('form_close'); ?></a></center>
        
        
            <span class="data_calling" room={room} called={called} my_number={my_number} my_username={my_username}></span>
            <div id="url_node" url="<?php echo NODE; ?>"> </div>
            <div class="get_input" asker="<?php echo $this->lang->line('form_file_asker'); ?>" download="<?php echo $this->lang->line('form_file_download'); ?>"><input type="file" id="filer" name="filer"></div>


            <script type="text/javascript" src="<?php echo base_url();?>assets/node/node_modules/simplewebrtc/latest.js"></script>
            <script type="text/javascript" src="<?php echo base_url();?>assets/js/share_file.js"></script>

            
       
   
  