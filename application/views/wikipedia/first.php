
		 
    <?php
	//On regarde d'abord si la session est ouverte
	
	//si c'est le cas on redirige dans wikipedia
	
	//si cest pas le cas on fait:
     //On fait dabord le control du support de la base de données local sule navigateur
	 
	 //si ce n'est pas bon on affiche les lien avec les logos des différents navigateurs à télécharher
	 
	 //si  c'est bon on affiche le formulaire de connexion

    ?>	
		
    <div class="contenu_wiki">

      <div class="row">
        <div class="span6">
           <div id="statuMessage_yann">
		   <div class=" connexion">
			<div id="erreurs" style="color:red;font-weight:bold;">
			    <small><?php echo $this->session->userdata('erreur'); ?></small>				
			</div>
		    <form class="cachot" action="<?php echo site_url().'/user/connect/connexion_trait'; ?>" name="connexion" method="POST" class="form-horizontal">
                <div class="control-group">
				    <label class="control-label" for="inputName"><?php echo $this->lang->line('form_username'); ?></label>
                    <div class="controls">
                        <input type="text" name="pseudo" class="pseudo_user" id="inputName" placeholder="<?php echo $this->lang->line('form_username'); ?>">
                    </div>
                </div>
                <div class="control-group">
				    <label class="control-label" for="inputNumber"><?php echo $this->lang->line('form_number'); ?>: (+237)</label>
                    <div class="controls">
                        <input type="text" name="number" class="number_user" id="inputNumber" placeholder="<?php echo $this->lang->line('form_number'); ?>">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary click_me"><i class="icon-user icon-white"></i> <?php echo $this->lang->line('form_connect'); ?> <i class="loader_connect icon-spinner icon-spin icon-large" style="display:none;"></i></button>
                    </div>

                     <p class="zoro"><div class="loaderdotconnect"></div></p>
                </div>
            </form>
			<img style="width:60%;"src="<?php echo base_url(); ?>assets/img/kwiki.png" />	
			<div id="namer" style="display:none;"><?php echo $this->lang->line('form_err_name'); ?></div>
			<div id="number" style="display:none;"><?php echo $this->lang->line('form_err_number'); ?></div>
			<div id="one" style="display:none;">yes</div>
		  </div>
		</div>

      </div>
           

      <div class="span6">

        <div class="metro panorama tiler">
          <div class="panorama-sections">
              <div class="panorama-section tile-span-4">
   
                        <h2>Featured apps</h2>
   
                        <a class="tile app bg-color-orange" href="#">
                           <div class="image-wrapper">
                              <span class="icon icon-mail"></span>
                           </div>
                           <div class="app-label">Mail</div>
                           <div class="app-count"><span class="random_message">12</span></div>
                        </a>
   
                        <a class="tile app bg-color-blue" href="#">
                           <div class="image-wrapper">
                              <span class="icon icon-graduation"></span>
                           </div>
                           <div class="app-label">Learn</div>
                        </a>
   
                        <a class="tile wide imagetext bg-color-greenDark" href="#">
                           <div class="image-wrapper">
                              <span class="icon icon-chat-2"></span>
                           </div>
                           <div class="column-text">
                              <div class="text4">Chat with your friends</div>
                           </div>
                           <div class="app-label">{nbre_user} users <span class="icon icon-smiley"></span></div>
                        </a>
   
                        <a class="tile app bg-color-purple" href="#">
                           <div class="image-wrapper">
                              <span class="icon icon-html5"></span>
                           </div>
                           <div class="app-label">Powered</div>
                        </a>
   
                        <a class="tile app bg-color-green" href="#">
                           <div class="image-wrapper">
                              <span class="icon icon-wink-2"></span>
                           </div>
                           <div class="app-label">By Begoo</div>
                        </a>


                         <a class="tile wide text" href="#">
                     <div class="text-header">YeP...!</div>
                     <div class="text4">Des millions d'articles, tout ce que tu veux savoir sur presque tout.
                                Rassemblés gratuitement pour une consultation gratuite...où vous êtes.</div>
                  </a>
   
              </div>
		        </div>
	        </div>

      </div>
    </div>
			
		

		
						   
						   
						
						   
	</div>
	
	<!-- navigo -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/wiki_navigo.js"></script>
	