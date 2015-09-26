


            <!-- Block qui la box qui fait patienter pendans le chargement ajax -->
	        <div id="Please_wait"style="display:none;"> 
                 
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>

	        <!-- <?php echo $this->lang->line('statu_wait'); ?> -->

	        </div>	
              
			<div id="info_msg_wait" style="display:none;" >
               
            </div>
	
	

    <!-- url de MAJ ajax du statu du compte du membre connecté -->
	<div id="form_connection" url="<?php echo site_url();?>/user/connect/" no_network="<?php echo $this->lang->line('form_no_network'); ?>" url_get_data="<?php echo site_url();?>/user/connect/get_my_connection_data" ></div>
	
	
	<!-- url de MAJ ajax pour savoir si l'user à toujours une session ouverte -->
	<div id="url_session" style="display:none;"><?php echo site_url();?>/user/connect/session</div>
    
	<!-- Url du controlleur de connexion pour ajax -->
	<div id="url_connect" style="display:none;"><?php echo site_url();?>/user/connect/connexion</div>
    
	<!-- Block qui affiche le petit box de signalement de nouveau message et qui après,explose après -->
	<div id="new" style="display:none;">
	    <div class="new_messsage alert alert-info">
            <?php echo $this->lang->line('statu_newMsg'); ?>.
	    </div>
    </div>
	

	<!-- Block qui affiche les box de succes d'un action -->
    <div id="good_msg" style="display:none;" class="alert alert-success">
			<p><i class="icon-ok"></i> <span class="messenger"></span></p>           
    </div>

    <!-- Block qui affiche le box d'echec d'une action -->
    <div id="bad_msg" style="display:none;" class="alert alert-error">
            <p><i class="icon-minus-sign"></i> <span class="messenger"></span></p> 
    </div>
	
	<!-- Block qui affiche le box d'information action -->
    <div id="info_msg" style="display:none;" class="alert alert-info">
            <p><i class="icon-info-sign"></i> <span class="messenger"></span></p> 
    </div>
	
	
	
	<!-- Le savez-vous? -->
    <div class="modal" id="do_you_nknowLabel" tabindex="-1" style="display:none;" role="dialog" aria-labelledby="do_you_nknow" aria-hidden="true">
	    
        <!-- le corps de la fenêtre modal -->
		<div class="modal-body">
		
	        <p class="text-info"><?php echo $this->lang->line('form_do_you_text'); ?> </p>
	        <p><textarea class="critik" placeholder="...critiques"></textarea></p>
	        <a class="m-btn purple-stripe send_critik">Send (Envoyer) <i class="icon-envelope-alt"></i></a>       
        
		</div>
				
		<!-- le footer de la fenêtre modal -->
        <div class="modal-footer">
                   
	        <button class="btn submit_closer" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i>OK</button>  
        
		</div> 
           
    </div>


    
     
       
    <!-- Pour l'aspiration des caégories -->   
    <div id="Modal_loader" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Modal_loaderLabel" aria-hidden="true">
            
                <div class="modal-body body_loader">
				    <p class="text-info"><span class="wait_load_msg"></span></p>
					<div id="hide_progress" style="display:none;"><div id="progressbar_loader" style="width:50%;" level="0"></div></div>
                </div>
              
			    <div class="modal-footer">
                    <button class="btn btn-info unblock" data-dismiss="modal"><span class="load_msg"></span> (<span class="num_level">0</span>%)</button>
                </div>
    </div>
			
         
			
			
    <div class="confirm_loader" msg="<?php echo $this->lang->line('form_downloader'); ?>" close="<?php echo $this->lang->line('form_close'); ?>" stop="<?php echo $this->lang->line('form_stop'); ?>" style="display:none;"><?php echo $this->lang->line('form_sorry'); ?></div>
	<div class="loader_wait" local="<?php echo $this->lang->line('statu_load_local'); ?>" collect="<?php echo $this->lang->line('statu_load_collect'); ?>" download="<?php echo $this->lang->line('statu_load_down'); ?>"  over="<?php echo $this->lang->line('statu_load_over'); ?>" no_article ="<?php echo $this->lang->line('statu_load_no_article'); ?>"></div>



	
	

    <div class="form_u_msg" msg="<?php echo $this->lang->line('form_u_msg'); ?>" php="<?php echo site_url().'/msg/messenger/incomingMessage'; ?>" form_call="<?php echo $this->lang->line('form_call'); ?>" form_end_call="<?php echo $this->lang->line('form_end_call'); ?>"></div>
	
	
	
	
	<div id="statu_user" edited="<?php echo $this->lang->line('form_statu_edited'); ?>" connected="<?php echo $this->lang->line('form_statu_connect'); ?>" disconnected="<?php echo $this->lang->line('form_statu_disconnect'); ?>"></div>


    
    <div id="connection" class="modal modal-fixed-footer">
        <div class="modal-content scroll_content">
            <h4 class="title_statu" edit="<?php echo $this->lang->line('form_compte'); ?>" connexion="<?php echo $this->lang->line('form_conn'); ?>" inscription="<?php echo $this->lang->line('form_inscription'); ?>">Connexion</h4>
            <div class="row">
                <div class="input-field">
                    <i class="mdi-communication-phone prefix"></i>
                    <input id="icon_telephone" type="text" class="form_phone">
                    <label class="hide_all" for="icon_telephone"><?php echo $this->lang->line('form_phone'); ?></label>
                </div>

                <div class="input-field pass_edit">
                    <i class="mdi-communication-vpn-key prefix"></i>
                    <input id="icon_pass" type="password" class="form_pass">
                    <label for="icon_pass"><?php echo $this->lang->line('form_pass'); ?></label>
                </div>
                
                <p>
                    <a href="inscription" class="add_form"><?php echo $this->lang->line('form_inscription'); ?></a>
                </p>

                <p>
                    <a href="edit_pass" class="edit_pass"><?php echo $this->lang->line('form_edit_pass'); ?></a>
                </p>

                <div class="hiden_form" style="display:none;">
                    
                    <div class="input-field pass_edit">
                        <i class="mdi-communication-vpn-key prefix"></i>
                        <input id="icon_passconf" type="password" class="form_passconf">
                        <label for="icon_passconf"><?php echo $this->lang->line('form_passconf'); ?></label>
                    </div>

                    <div class="input-field">
                        <i class="mdi-action-perm-identity prefix"></i>
                        <input id="icon_username" type="text" class="form_username">
                        <label class="hide_all" for="icon_username"><?php echo $this->lang->line('form_username'); ?></label>
                    </div>
                    
                    <div class="input-field">
                        <?php echo $this->lang->line('form_filiere'); ?>
                        <input id="icon_filiere" type="text" class="form_filiere">
                        <label class="hide_all" for="icon_filiere"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="waves-effect waves-green  btn-flat modal-action valid_sumit"><?php echo $this->lang->line('form_connection'); ?><i class="mdi-action-done"></i></a>
            <a href="#" class="waves-effect waves-green  btn-flat modal-action modal-close "><?php echo $this->lang->line('form_cancel'); ?></a>          
        </div>
    </div>



    <!-- pour les navigateurs compatibles-->        
    <div class="modal" id="browser">
        
        <div class="modal-content">
            
               
                    <p class="red-text text-darken-2" ><?php echo $this->lang->line('form_browser'); ?></p>
                    <a class="waves-effect waves-light btn blue" href="<?php echo base_url(); ?>/assets/logi/com.android.chrome.apk"><i class="mdi-action-android"></i> Chrome (Android)</a><br>
                    <a href="<?php echo base_url(); ?>/assets/logi/firefox.exe"><span class="waves-effect waves-light btn-large blue"><i class="icon-windows icon-white"></i> Firefox (windows XP,7,8)</span></a><br>  
                    <a href="<?php echo base_url(); ?>/assets/logi/chrome.exe"><span class="waves-effect waves-light btn-large blue"><i class="icon-windows icon-white"></i> Chrome (windows XP,7,8)</span></a>  
                   
        </div>    
    </div>


     <!-- For the file tranfert-->        
    <div class="modal" id="file_transfert">
        
        <div class="modal-content">
            <center>
                <p class="red-text text-darken-2 choose_file" ><?php echo $this->lang->line('form_file_transfert'); ?></p>
                <p class="red-text text-darken-2 file_name" ></p>
                <span class="load_button"><a class="btn-floating btn-large waves-effect waves-light red choose_file"><i class="mdi-content-add"></i></a></span><br>
                <div class="progress"><div class="determinate" style="width: 0%"></div></div><div class="number_progress"></div>
            </center>
        </div>
        <div class="modal-footer">
           <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat close_filer">X</a>
        </div>    
    </div>





    <div id="hello" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light pinooy red" reason="friend"><i class="mdi-communication-contacts"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light pinooy blue" reason="follow_me"><i class="mdi-social-share"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light pinooy orange" reason="my_account"><i class="mdi-action-account-box"></i></a></center></div>
                <div class="col s3"><center><a class="btn-floating btn-large waves-effect waves-light pinooy red" reason="notification"><i class="mdi-social-notifications"></i></a></center></div>
            </div>
        </div>
    </div>


    <div class="no_friend" style="display:none;" added="<?php echo $this->lang->line('form_added'); ?>" no_contact="<?php echo $this->lang->line('form_no_friend'); ?>" add_contact="<?php echo $this->lang->line('form_add_friend'); ?>" adding_friend_url="<?php echo site_url().'/user/user/add_friend'; ?>" get_friend_url="<?php echo site_url().'/user/user/List_friends'; ?>">
        <ul id="staggered-list">
            <li class="opacity">
                <a href="add" class="add_friend collection-item">
                    <div class="card-panel">
                        <span >
                            <p class="red-text text-darken-2">
                                <span class="blue-text text-darken-2" style="float:left; padding-right:5px;"><i class="medium mdi-social-person-add"></i></span>
                                <?php echo $this->lang->line('form_no_friend'); ?><br>

                                <?php echo $this->lang->line('form_add_friend'); ?>
                            </p>
                        </span>
                    </div>
                </a>
            <li>
        </ul>
        
    </div>


    <div id="add_friend" class="modal">
        <div class="modal-content">
            <h3><?php echo $this->lang->line('form_add_friend'); ?></h3>
            <div class="row">
                <div class="input-field col s6">
                    <i class="mdi-communication-phone prefix"></i>
                    <input id="icon_guest_phone" type="text" class="guest_phone">
                    <label for="icon_guest_phone"><?php echo $this->lang->line('form_phone'); ?>*</label>
                </div>
                <div class="input-field col s6">
                    <i class="mdi-action-account-circle prefix"></i>
                    <input id="icon_guest_name" type="text" class="guest_name">
                    <label for="icon_guest_name"><?php echo $this->lang->line('form_name'); ?></label>
                </div>    
            </div>
            <p><span class="error_guest red-text text-darken-2"></span><p>
            <button class="btn waves-effect waves-light blue ok_add" type="submit" name="action"><?php echo $this->lang->line('form_connection'); ?><i class="mdi-content-send right"></i></button>
        </div>
    </div>




	

    <div id="alert" follow_me="<?php echo $this->lang->line('form_follow_me'); ?>" stop_follow="<?php echo $this->lang->line('form_stop_follow'); ?>" follow_user="<?php echo $this->lang->line('form_follow_user'); ?>" follow_refuse="<?php echo $this->lang->line('form_follow_refuse'); ?>" ask_follow="<?php echo $this->lang->line('form_ask_follow'); ?>" click_to_see="<?php echo $this->lang->line('form_click_to_see'); ?>" new_share="<?php echo $this->lang->line('form_new_share'); ?>" no_call="<?php echo $this->lang->line('form_no_call'); ?>" no_active_conv="<?php echo $this->lang->line('form_no_activ_speak'); ?>" form_u_number="<?php echo $this->lang->line('form_u_number'); ?>" form_none_number="<?php echo $this->lang->line('form_none_number'); ?>" error="<?php echo $this->lang->line('form_error'); ?>" nobody="<?php echo $this->lang->line('form_nobody'); ?>" new_call="<?php echo $this->lang->line('form_new_call'); ?>" reject_call="<?php echo $this->lang->line('form_reject_call'); ?>" end_call="<?php echo $this->lang->line('form_call_ended'); ?>"  wat="<?php echo $this->lang->line('form_wat'); ?>" no_contact="<?php echo $this->lang->line('form_no_contact'); ?>" incompatible="<?php echo $this->lang->line('form_incompatible'); ?>" busy="<?php echo $this->lang->line('form_busy'); ?>"></div>
		
    <div id="friends_liste" url="<?php echo site_url().'/user/user/List_friends'; ?>"></div>
	
	
    <div class="msg_historic" no_historic="<?php echo $this->lang->line('form_no_historic'); ?>" ></div>

    <div class="no_connected" no_connected="<?php echo $this->lang->line('form_no_connected'); ?>" ></div>



    <div class="upload_message" drag="<?php echo $this->lang->line('form_up_drag'); ?>" end_sending="<?php echo $this->lang->line('form_up_end_sending'); ?>" in_sending="<?php echo $this->lang->line('form_up_in_sending'); ?>" up_to="<?php echo $this->lang->line('form_up_to'); ?>" url_ajax_list="<?php echo site_url();?>/user/record_file/" up_error_unknow="<?php echo $this->lang->line('form_up_unknow'); ?>" up_error="<?php echo $this->lang->line('form_up_error'); ?>" up_not_supported="<?php echo $this->lang->line('form_up_not_supported'); ?>" up_too_big="<?php echo $this->lang->line('form_up_too_big'); ?>"  up_no_file="<?php echo $this->lang->line('form_up_no_file'); ?>"> </div>
    <div class="loader" style="display:none;"><div class="loaderbar">Loading...</div></span> <?php echo $this->lang->line('statu_wait'); ?>...</div>
    

    <div class="search_on_zim" search_zim="<?php echo $this->lang->line('form_search_video'); ?>" ></div>
    
	
	
	
	    <?php if($this->session->flashdata('statu_wonder'))
		    { ?>
	         <!-- la box de notification -->
	         <div id="statuMessage_yann" class="alert draggable">
		        <button type="button" class="close" data-dismiss="alert">×</button>
			   <?php echo $this->session->flashdata('statu_wonder'); ?>
		     </div>
			 
		     <?php 
		    }
		?>

     <!-- Modal Structure -->
    <div id="display_video" class="modal">
        <div class="modal-content">
            <center class="player_video">
            
            </center>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat orange">X</a>
        </div>
    </div>
	
    <div class="plus_menu" plus_menu="<?php echo $this->lang->line('form_plus'); ?>"></div>
	<div class="plus_result" plus_result="<?php echo $this->lang->line('form_plus_result'); ?>"></div>

	<!-- url pour les critiques -->
	<div id="url_critik" action="<?php echo site_url().'/user/user/critik'; ?>" ></div>
	
	<!-- url pour afficher la liste des préférences et traite la mise à jour-->
	<div class="my_prefer_liste" action="<?php echo site_url().'/user/user/list_prefer'; ?>" rel="<?php echo site_url().'/user/user/list_prefer_trait'; ?>"></div>
	
	
	<div class="confirm" style="display:none;"><i class="icon-ok"></i></div>
	
	<div id="fade_pencil" style="display:none;"><i class="icon-pencil"></i></div>
	
	<!-- url le javascript des notifications -->
	<div id="url_js_note" action="<?php echo base_url().'assets/js/last_notes.js'; ?>" ></div>
	
	
	<!-- url le javascript des messages chat -->
	<div id="url_js_chat" action="<?php echo base_url().'assets/js/chat_seeMessage.js'; ?>" ></div>

	
	
	<!-- ceci est pour la lecture des messages en bdd -->
	<div id="reader_msg" url="<?php echo site_url().'/msg/messenger/ListeMsg'; ?>"></div>
	
	
	<!-- url lpour le son du tchat -->
	<div id="song" url_chat="<?php echo base_url().'assets/song/beep'; ?>" url_bell="<?php echo base_url().'assets/song/'; ?>" ></div>


    <span class="upload_file" style="display:none"><input type="file" id="file_up" name="file_up"></span>
    <div class="message_ajax" complete_record_of_file="<?php echo site_url().'/wikipedia/wiki/complete_record_file'; ?>" record_file="<?php echo site_url().'/wikipedia/wiki/record_file'; ?>" copy_to_disc="<?php echo DISC_COPY ;?>" url_for_file_upload_dir="<?php echo base_url();?>assets/uploader/uploads/"  url_for_file_upload="<?php echo base_url();?>assets/uploader/processupload.php" url_for_send="<?php echo site_url();?>/user/record_message" no_receiver="<?php echo $this->lang->line('form_no_receiver'); ?>"></div>
	
	
	
	
	<!-- pour provoquer vérification si l'interlocuteur chat est en ligne -->
	<div id="online" numero=""></div>
		
	<!-- pour provoquer la lecture des conversations en local -->
	<div id="all_conversation" numero=""></div>
	
	<!-- pour provoquer la prise de photo -->
	<div id="capture"></div>


	<!-- pour le moteur de recherche -->
	<div class="stock_engine_wikipedia" style="display:none;"></div>
	<div class="stock_engine_gutenberg" style="display:none;"></div>
	<div class="stock_engine_ted" style="display:none;"></div>


	<div class="all_number_user" href="<?php echo site_url().'/user/user/all_number_user'; ?>"></div>
	
	
	<!-- ceci est pour le chat en instantané -->
	<div id="js_chat" statu="no" live="<?php echo site_url().'/msg/messenger/js_chat'; ?>"></div>
	<span style="display:none;" id="link_add"></span>
	
     <!-- ceci est pour le click d'affichage du wiki_text -->
	<div id="retriever"  page_content="<?php echo site_url().'/wikipedia/wiki/page_content'; ?>" url_explode="<?php echo site_url().'/wikipedia/wiki/explode_padeId/'; ?>"></div>
	
	
	<div id="url_search_cat" url="<?php echo base_url().'assets/js/wiki_category_search.js'; ?>"></div>
	<div id="wiki_cat_download" url="<?php echo base_url().'assets/js/wiki_category_download.js'; ?>"></div>
	<span action="<?php echo site_url().'/msg/notification/ListePub/'; ?>" class="my_msg_pub" ></span>
	<span action="<?php echo site_url().'/msg/notification/ListePub_out/'; ?>" class=" my_msg_pub_out" ></span>

    <div class="get_input" asker="<?php echo $this->lang->line('form_file_asker'); ?>" rejected="<?php echo $this->lang->line('form_file_declined'); ?>" ask="<?php echo $this->lang->line('form_file_ask'); ?>" yes="<?php echo $this->lang->line('form_file_yes'); ?>" no="<?php echo $this->lang->line('form_file_decline'); ?>" ></div>

	<div class="result_label" wikipedia="<?php echo $this->lang->line('form_wikipedia'); ?>" library="<?php echo $this->lang->line('form_library'); ?>" video="<?php echo $this->lang->line('form_videotek'); ?>"></div>

	
	<span class="article_cat" article="<?php echo $this->lang->line('form_cat_art'); ?>"> </span>  

	<span class="already_used" msg="<?php echo $this->lang->line('form_already_used'); ?>" redirect="<?php echo site_url(); ?>" connexion_trait ="<?php echo site_url().'/user/connect/connexion_trait'; ?>"> </span>
	 
	<span class="notif_search" short="<?php echo $this->lang->line('form_short'); ?>"></span>
	
    <span class="offline_app" message="<?php echo $this->lang->line('form_offline'); ?>"></span>

	<span class="no_result" message="<?php echo $this->lang->line('form_no_result'); ?>"></span>

	<span class="not_allow_family" message="<?php echo $this->lang->line('form_not_allow_family'); ?>"></span>

	<div class="ted_video" url="<?php echo site_url().'/wikipedia/wiki/get_zim/';?>"></div>

	<span class="ted_video_message" help_language="<?php echo $this->lang->line('form_help_ted'); ?>" go_back="<?php echo $this->lang->line('form_help_back'); ?>"></span>

	   
		<div id="get_API" get_random_article="<?php echo site_url().'/wikipedia/wiki/get_random_article'; ?>" ping="<?php echo site_url().'/wikipedia/wiki/ping' ; ?>" local_db="<?php echo WEB_STORAGE_NAME ; ?>" video_zim="<?php echo base_url().'assets/TED/' ; ?>" api_category="<?php echo site_url().'/wikipedia/wiki/get_category' ; ?>" api="<?php echo site_url().'/wikipedia/wiki/get_article' ; ?>" api_search="<?php echo site_url().'/wikipedia/wiki/search' ; ?>" api_search_plus="<?php echo site_url().'/wikipedia/wiki/search_plus' ; ?>"></div>

		<div class="hoster"  signal_server="<?php echo SIGNAL_SERVER ; ?>" pinooy="<?php echo site_url().'/wikipedia/wiki/pinooy/' ; ?>" gutenberg="<?php echo GUTENBERG ; ?>" gutenberg_url="<?php echo site_url().'/wikipedia/wiki/' ; ?>" url="<?php echo HOSTER ; ?>" port_kiwix="<?php echo KIWIX_PORT ; ?>" host="<?php echo HOST ; ?>" host_wiki="<?php echo HOST_WIKI ; ?>" zim="<?php echo ZIM ; ?>" kiwix="<?php echo KIWIX ; ?>" zim_list="<?php echo ZIM_LIST ; ?>" url_for_seek="http://<?php echo HOSTER ; ?>:<?php echo KIWIX_PORT ; ?>/search?content="></div>
		
	    <div id="site_url"  url="<?php echo site_url().'/wikipedia/wiki'; ?>"></div>
		
        <div id="site_url_base" url="<?php echo site_url(); ?>"> </div>
		
        <div id="url_json" url="<?php echo base_url().'assets/json/'; ?>"> </div>
		
		<div class="followed_user_id" user_id=""> </div>

        <div class="content_gutenberg" style="display:none;"></div>
		
		
		<div id="chat_conv" alone="<?php echo $this->lang->line('form_alone'); ?>" bye="<?php echo $this->lang->line('form_bye'); ?>" kwiki="Begoo"></div>
		
		
		<div id="url_image" url="<?php echo base_url().'assets/image_notif/' ; ?>"></div>
		<div id="url_image_logo" nothing="<?php echo $this->lang->line('bulle_begoo'); ?>" mode_out="<?php echo $this->lang->line('bulle_outline'); ?>" url="<?php echo base_url().'assets/img/' ; ?>"></div>
		
    <div id="base_url" base_url="<?php echo base_url(); ?>"> </div>
		
	<div id="url_node" url="<?php echo NODE; ?>"> </div>
	<div id="url_peer" host="<?php echo PEER_HOST; ?>" port="<?php echo PEER_PORT; ?>"> </div>


     

	
	

	
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/jquery.blockUI.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/jquery.indexeddb.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/IndexedDBShim.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/materialize.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/header.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/click.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/wiki_contact.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/wiki_search.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.scrollToTop.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.loadingdots.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/blocksit.min.js"></script>

	

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/wiki_navigo.js"></script>
	

	
</body>

</html>
