    <!-- Navbar ================================================== -->
    <div class="navbar-fixed">
<nav class="search_bar"> 
        <div class="nav-wrapper"> 
           <a href="#!" class="brand-logo right">Kwiizi</a>
            <ul>
                <li class="hide-on-med-and-up"><a class="btn-floating btn-large waves-effect waves-light blue"><i class="mdi-hardware-keyboard-arrow-left"></i></a></li>
                <li>
                    <div class="input-field col s12">
                        <input id="searcher" type="text" class="input_search" />
                        <label for="searcher"><i class="mdi-action-search"></i></label>
                    </div>
                </li>
                <li><a class="hello follow_me" reason="follow_me" href="follow_me"><?php echo $this->lang->line('form_follow_me');?></a></li>
                <li><a href="components.html">Notification </a></li>
                <li><a href="javascript.html">Profil</i></a></li>
            </ul>  
                
        </div>
    </nav>

    <nav class="hide-on-med-and-up back_list" style="display:none;cursor:pointer;"> 
        <div class="nav-wrapper">  
            <i class="mdi-navigation-chevron-left left"></i>
            <span class="right"></span>   
        </div>
    </nav>

    </div>
    

    
    

    

    <div class="row" id="page">
       
        <div class="col s5 liste" id="horizontal-a " list_article="true" ref="yes"></div>

        <div id="horizontal-b" class="col s7 principal">

            <div class="menu_header">
                
                <div class="fixed-action-btn com_pinooy" style="bottom: 45px; right: 10%;display:none;">
                    <a class="btn-floating btn-large red hello blink_calling" reason="friend">
                        <i class="change_phone large mdi-communication-chat"></i>
                    </a>
                </div>
               
   
		        <div id="statuMessage" class="alert alert-info ">
		            <button type="button" class="close" data-dismiss="alert">×</button>
			        <div id="Message">
			        </div>		
		        </div>
		
		        <div id="yann" style="display:none">		
		        </div>
            </div>
                <a class="waves-effect waves-light btn blue tooltipped historic" href="<?php echo site_url().'/search/search_wiki/historic/'; ?>" data-placement="bottom"  data-tooltip="<?php echo $this->lang->line('form_historic');?> !"><i class="mdi-action-history right"></i><?php echo $this->lang->line('form_historic');?></a>
            
            <div class="second_menu">
            	<div class="witch_zim" style="display:none;">
                    <div class="desktop hide-on-med-and-down">
                        <a class="zim_click waves-effect waves-teal btn-flat zim_wikipedia green" title="<?php echo $this->lang->line('form_wikipedia');?>"><?php echo $this->lang->line('form_wikipedia');?></a>
                        <a class="zim_click waves-effect waves-teal btn-flat zim_gutenberg" title="<?php echo $this->lang->line('form_library');?>"><?php echo $this->lang->line('form_library');?></a>
                        <a class="zim_click waves-effect waves-teal btn-flat zim_TED" title="<?php echo $this->lang->line('form_videotek');?>"><?php echo $this->lang->line('form_videotek');?></a>
                        <!-- Dropdown Trigger -->
                        <a class='dropdown-button waves-effect waves-teal btn-flat' href='#' data-activates='plus_menu'><?php echo $this->lang->line('form_plus'); ?></a>
                            <!-- Dropdown Structure -->
                            <ul id='plus_menu' class='dropdown-content'>
                                <li><a class="zim_click waves-effect waves-teal  zim_medecine" title="<?php echo $this->lang->line('form_medecine');?>"><?php echo $this->lang->line('form_medecine');?></a></li>
                                <li><a class="zim_click waves-effect waves-teal  zim_linux" title="<?php echo $this->lang->line('form_linux');?>"><?php echo $this->lang->line('form_linux');?></a></li>
                            </ul>

                        </ul>
                    	
                    </div>

                    <div class="tablet hide-on-small-only hide-on-large-only">
                    	<a class="zim_click waves-effect waves-teal btn-flat zim_wikipedia green" title="<?php echo $this->lang->line('form_wikipedia');?>"><?php echo $this->lang->line('form_wikipedia');?></a>
                    	<a class="zim_click waves-effect waves-teal btn-flat zim_gutenberg" title="<?php echo $this->lang->line('form_library');?>"><?php echo $this->lang->line('form_library');?></a>
                    	<a class="zim_click waves-effect waves-teal btn-flat zim_TED" title="<?php echo $this->lang->line('form_videotek');?>"><?php echo $this->lang->line('form_videotek');?></a>
                    	
                        <!-- Dropdown Trigger -->
                        <a class='dropdown-button waves-effect waves-teal btn-flat' href='#' data-activates='dropdown1'><?php echo $this->lang->line('form_plus');?></a>

                        <!-- Dropdown Structure -->
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a class="zim_click waves-effect waves-teal zim_medecine" title="<?php echo $this->lang->line('form_medecine');?>"><?php echo $this->lang->line('form_medecine');?></a></li>
                            <li><a class="zim_click waves-effect waves-teal zim_linux" title="<?php echo $this->lang->line('form_linux');?>"><?php echo $this->lang->line('form_linux');?></a></li>
                        </ul>
                    </div>

                    <div class="phone hide-on-med-and-up">
                    	<!-- Dropdown Trigger -->
                        <a class='dropdown-button waves-effect waves-teal btn-flat' href='#' data-activates='dropdown2'><?php echo $this->lang->line('form_plus');?></a>

                        <!-- Dropdown Structure -->
                        <ul id='dropdown2' class='dropdown-content'>
                           <li><a class="zim_click waves-effect waves-teal  zim_wikipedia green" title="<?php echo $this->lang->line('form_wikipedia');?>"><?php echo $this->lang->line('form_wikipedia');?></a></li>
                    	   <li><a class="zim_click waves-effect waves-teal  zim_gutenberg" title="<?php echo $this->lang->line('form_library');?>"><?php echo $this->lang->line('form_library');?></a></li>
                    	   <li><a class="zim_click waves-effect waves-teal  zim_TED" title="<?php echo $this->lang->line('form_videotek');?>"><?php echo $this->lang->line('form_videotek');?></a></li>
                    	   <li><a class="zim_click waves-effect waves-teal  zim_medecine" title="<?php echo $this->lang->line('form_medecine');?>"><?php echo $this->lang->line('form_medecine');?></a></li>
                    	   <li><a class="zim_click waves-effect waves-teal  zim_linux" title="<?php echo $this->lang->line('form_linux');?>"><?php echo $this->lang->line('form_linux');?></a></li>
                        </ul>
                    </div>
            	</div>
                <div class="row">
                    <div class="col s3"> 
				
					    <!--<img src="<?php echo base_url().'assets/smileys/lap_cretin.jpg'; ?>" class="profil_chiz img-polaroid"> -->				
				    </div>
                
				    <div class="col s9">

				        
					    <div class="navigo">
					
					        <span class="look_wiki" page_title="" style="display:none;" if_article="no">0</span><!-- garde l'id de la page wikipedia -->
					
					        <span class="wiki_follow" style="display:none;"></span><!-- dit s'il suit,est suivi ou rien du tout -->
					
					        <span class="wiki_follow_user" url="<?php echo site_url().'/wikipedia/wiki/il_est_ou/'; ?>" style="display:none;"></span><!-- garde l'id de l'user suivi -->
                    
					        <span class="look_wiki_url"  page_consulte="<?php echo site_url().'/wikipedia/wiki/record_article'; ?>"  style="display:none;"></span><!-- garde le préfixe de l'url des articles wikipedia -->
					
				        </div>											
				    </div>
                </div> 
            </div>

            <div class="row main_container">
        	    <div class="contenu_wiki">
		
				    <div class="wiki_title"></div>
				
				    <div class="wiki_content">
					    <div class="parent_begoo" style="padding-top:100px;">
                            <img class="logo_kwiki" style="height:100px;" src="<?php echo base_url(); ?>assets/img/kwiki.png" /><br>

                            <div class="row">
                                
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="mdi-action-search prefix"></i>
                                            <input id="icon_prefix" type="text" class="first_search">
                                            <label for="icon_prefix"><?php echo $this->lang->line('form_search_video'); ?></label>
                                        </div>
                                    </div>
                               
                            </div>

                            <ul id="popopMessage">
                                <li style="opacity: 0;">
                                <div class="row">
                                    <div class="col s12 m7">
                                        <div class="card red-text">
                                            <div class="card-content">
                                                <p><strong><?php echo $this->lang->line('form_term_search'); ?></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </li>
                                
                            </ul>
                        
                            
					    </div>
				    </div>
	   
				    <a href="#do_you_nknow" class=" install special_nav critika" data-toggle="modal" data-target="#do_you_nknowLabel" style="display:none;"><span class="label label-info" > <?php echo $this->lang->line('form_do_you'); ?> </span></a>
	            </div>

	            <a href="#top" id="toTop"></a>
            </div>   
        </div>
    </div> 
   