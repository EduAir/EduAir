
		 
            
		
		    
			
			
	

		
	<!-- ceci est pour l'affichage du contenu d'un article -->
	<div id="js_wiki_content"></div>
	
	<!-- ceci est pour l'affichage de la chat-box -->
	<div id="chat_div"></div>
	
	
	<!-- ceci est pour l'url d'envoi des msg en instantané -->
	<div id="chat_box" statu="no" live="<?php echo site_url().'/wikipedia/wiki/comm_chat'; ?>"></div>
	
	<!-- ceci est pour la lecture des msg instantanés -->
	<div id="chat_statu" statu="no" url_last_time="<?php echo site_url().'/wikipedia/wiki/my_last_time'; ?>" url_if_new_com="<?php echo site_url().'/wikipedia/wiki/if_new_com'; ?>" url_new_com="<?php echo site_url().'/wikipedia/wiki/new_com'; ?>" last_time=""></div>
	
	<!-- url pour voir les favoris d'un wiki -->
	<div id="wiki_kiff"  url_wiki_kiff="<?php echo site_url().'/wikipedia/wiki/wiki_kiff/'; ?>"></div>
		
	<!-- url pour pour télélcharger les pdf générés -->
	<div id="my_pdf" url_my_pdf="<?php echo site_url().'/wikipedia/wiki/printer/'; ?>"></div>	
	
	<!-- url pour garder l'historique des articles -->
	<div id="recorder" url_recorder="<?php echo site_url().'/wikipedia/wiki/recorder'; ?>"></div>
	
	<!-- url pour obtenir l'id d'un article -->
	<div id="get_id" url_get_id="<?php echo site_url().'/wikipedia/wiki/explodeIt_and_FeelPAgeId'; ?>"></div>
	
	<!-- url pour obtenir les articles les plus lus -->
	<div class="more_see" action="<?php echo site_url().'/search/search_wiki/more_see/'; ?>" see="<?php echo $this->lang->line('statu_more_see');?>"></div>
	
	<!-- url récupérer le JS qui se charge des résultats -->
	<div id="wiki_search_result" url="<?php echo base_url();?>assets/js/wiki_search_result.js"></div>
	
	<!-- le garde mangé -->
	<div id="save_me" value="yo"></div>
	
	<!-- le garde mangé pour la recherche-->
	<div class="va_chercher_chien_chien" page_title=""></div>
	
	<!-- message des resultats de recherche-->
	<div class="resultat" message="<?php echo $this->lang->line('bulle_result');?>" no_article="<?php echo $this->lang->line('statu_no_result');?>" new_article="<?php echo $this->lang->line('bulle_nouvel_article'); ?>"></div>
	
	<!-- url pour le boutton plus -->
	<div class="plus_button" url="<?php echo site_url().'/search/search_wiki/plus_wiki/'; ?>"></div>
	
	<!-- navigo -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.ui.chatbox.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/chatboxManager.js"></script>
	