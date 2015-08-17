<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Talk extends CI_Controller 
{

public function __construct()
	{	  
	  // Call the Controller constructor
     parent::__construct();
	  
	  $this->load->helper('url');
	  	
		//on charge les sessions
	    $this->load->library('session');
			
	  //le helper de texte pour limiter les chaines de caractère lors de certains l'affichages
	    $this->load->helper('text');
		
		//C'est cette ligne de code qui détecte la langue du navigateur et affiche le site dans la langue correspondante
		$this->lang->load('form', $this->config->item('language'));
		$this->lang->load('statu', $this->config->item('language'));
		$this->lang->load('bulle', $this->config->item('language'));
		$this->lang->load('note', $this->config->item('language'));

        //On appelle la fonction qui s'occuper de la messagerie
	    $this->load->model('msg/msgs');	
		
		 //On appelle la fonction qui s'occuper des talk
	    $this->load->model('msg/talks');	

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');
		$this->load->model('user/users');


        $this->load->library('form_validation');		
    }
	
	 	 
public function index()
    {
	   redirect('','refresh'); 
	}
	

//Ici on traite les données d'un nouveau message	
public function trait_talk()
    {
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('talk','','required|trim|xss_clean|min_length[1]|max_length[160]');
		  $this->form_validation->set_rules('friends_list','','trim|xss_clean');
		    
         	if($this->form_validation->run())
		    {
	   	     //J'envoi à l'usine à  gaz
		     $result = $this->talks->insert_talk($this->input->post('talk'),$this->input->post('friends_list'));
             
             $verdict = $result[0];//$verdict renvoi false ou true pour savoir s'il ya eu une erreur

                if($verdict)
                {
				   $reponse['statu']     = 'good';
		           $reponse['messenger'] = $result[1];
                }
                else
                {
				   $reponse['statu']     = 'bad';
		           $reponse['messenger'] = $result[1];
                }				
			}
			else
			{
			   $reponse['statu']     = 'bad';
		       $reponse['messenger'] = form_error('talk').$this->input->post('talk');
			}
		}
		else
		{
		  $reponse['statu']     = 'info';
		  $reponse['messenger'] = $this->lang->line('statu_not_connected');
		}
		
	 // on a notre objet $reponse (un array en fait)
     // reste juste à l'encoder en JSON et l'envoyer

     header('Content-Type: application/json');
     echo json_encode($reponse);
	}	


	
//Ici on récupère le dernier talk fait par l'user connecté
public function my_last_talk()
    {
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    {   
		  $result = $this->talks->my_last_talk();
		  
		    if($result!== false)
			{
			    foreach($result as $my_talk)
				{?>
				 
				    
					<div class="friends_area" id="record-<?php  echo $my_talk->id_talk; ?>">
					
					  <div class="row bs-docs-chat">
                        
						<div class="span2">
						    <i class="icon-user"></i><br>
							<span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							<?php if($my_talk->auteur_talk!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$my_talk->auteur_talk; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?>
						</div>
                        
						<div class="span9">
						    
							<div class="talk_post">
							    <?php echo $my_talk->talk_talk; ?>
								
								<div> 
								     <ul class="nav nav-pills">
                                            
											<?php if($this->talks->if_follow($my_talk->id_talk)){ ?>										
                                        <li class="follow bulle" action="<?php echo site_url().'/msg/talk/follow/'.$my_talk->id_talk; ?>"  title="<?php echo $this->lang->line('bulle_follow'); ?>" data-placement="top" ><a href="#"><?php echo $this->lang->line('statu_follow'); ?> <i class="icon-headphones"></i></a></li>
										    <?php } ?>
										
										<li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										   <a href="#">
										        <span class="label label-warning">
										            <span class="all_favor"> 
													    <?php echo $my_talk->favoris_talk; ?>
													</span> 
													<i class="icon-heart icon-white"></i>
												</span> 
										   </a>
										</li>
										
										<li><a href="javascript: void(0)" id="post_id<?php echo $my_talk->id_talk; ?>" class="showCommentBox"><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)</a></li>
										
										     <?php if($this->talks->if_tag($my_talk->id_talk)){ ?>
										<li class="bulle tag_it"  title="<?php echo $this->lang->line('bulle_tag'); ?>" data-placement="top"><a href="#myModal_tag" data-toggle="modal"><i class="icon-tag"></i> <?php echo $this->lang->line('form_taguer'); ?></a></li> 
                                            <?php } ?>

								   </ul>
							    </div>
								<div class="info_sup"><span class="label label-info"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span></div>
					        </div>
							
				        </div>
						
						<div id="CommentPosted<?php echo $my_talk->id_talk;?>">
						
						<?php 
						     
						   $result_com = $this->talks->talk_comments($my_talk->id_talk);
							
							if($result_com!== false)//SI on a quelque chose
							{						 
							  $comment_num_row = count($result_com[1]);
							 
							    if($comment_num_row > 0)
								{
								    //Regardons s'il ya possibilité de cacher les autres commentaires du talk s'il sont supérieur à 4
								    $comment_num_row_reel = $result_com[0];//$result_com[0] porte le nombre réel decommentaire
									
									if($comment_num_row < $comment_num_row_reel)
									{
									  $com_affiche = $result_com[0] - $comment_num_row ; ?>
									
									  <span id="affiche_plus"><a href="#"><i class="icon-plus"></i> <?php echo $this->lang->line('form_all_talk'); ?> <?php echo $com_affiche; ?> <?php echo $this->lang->line('form_commentaire'); ?> </a></span>
									
									  <?php
									}
									
									foreach($result_com[1] as $talk_com)
									{?>
									    <div class="commentBox" id="record-<?php  echo $talk_com->id_com;?>" align="left">

						                    <span style="float:left;" alt="" >
					                            <i class="icon-user"></i><?php if($talk_com->user!== $this->session->userdata('numero')){?><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$talk_com->user; ?>" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a><?php } ?><br>
						                        <span class="user_post"><?php echo $this->connects->username($talk_com->user,1); ?></span><br>
									
					                        </span>
											
		                                    <label class="postedComments">
							                    <?php  echo $talk_com->commentaire;?>
						                    </label>
											
					                        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$talk_com->timestamp); ?></div>
											
                                        </div>
									  <?php  
									}
								}
							}
						?>
						</div>

					</div>				   
				 
				   </div>
				   
				   <div class="append_talk"></div>		
				   

                 <div class="commentBox" align="right" id="commentBox-<?php  echo $my_talk->id_talk;?>">
				       
					<span style="float:left;" alt="" >
					    <i class="icon-user"></i><br>
						<span class="user_post"><?php echo $this->connects->username($this->session->userdata('numero'),0); ?></span>
					</span>
				
				    <label id="record-<?php  echo $my_talk->id_talk; ?>">
					
					    <textarea  placeholder="<?php echo $this->lang->line('form_comment_talk'); ?>..." class="commentMark" id="commentMark-<?php  echo $my_talk->id_talk; ?>" name="commentMark" cols="60"></textarea>
				            
					</label>
				
				    <br clear="all" />
			        
					<div id="progressbar_talk_comment"></div>
					
					<div id="attend"></div>
					
				    <button id="SubmitComment" action="<?php echo site_url().'/msg/talk/comm_talk'; ?>" class="btn btn-mini btn-primary comment" type="button"><i class="icon-comment icon-white"></i> <?php echo $this->lang->line('form_comment'); ?> (- <span id="count_com">160</span>)</button>
			            
				 </div>				 
				
				     <!-- CE DIV EST POR LES INFORMATIONS SERVANT AU RAFRAICHISSEMENT AJAX DES NOUVEAUX COMMENTAIRES-->
				  <div id="my_last_time" time="<?php echo time(); ?>" talker="<?php echo $my_talk->id_talk; ?>" url_verif="<?php echo site_url().'/msg/talk/if_new_com'; ?>" url_maj="<?php echo site_url().'/msg/talk/new_com' ;?>" my_last_time="<?php echo site_url().'/msg/talk/my_last_time'; ?>"></div>
				 
				    <!-- Modal pour le talk-->
                    <div class="modal" id="myModal_tag" tabindex="-1" style="display:none;" role="dialog" aria-labelledby="myModalLabel_tag" aria-hidden="true">

                        <!-- le corps de la fenêtre modal -->
		                <form class="form-horizontal">
                            
							<div class="modal-body">
	                         
                                <div class="control-group">
                                    
									<div class="controls">
						                <span class="w">
										    <label class="control-label" for="my_tag"><?php echo $this->lang->line('form_tag'); ?></label>
										    <input id="my_tag" type="text" placeholder="<?php echo $this->lang->line('form_tag'); ?>...">		          
		                                </span>
                                    </div>
                                </div>  	
                            </div>
				
				            <!-- le footer de la fenêtre modal -->
                            <div class="modal-footer">						
						        <a href="#" class="btn btn-primary submit_tag" rel="<?php  echo $my_talk->id_talk; ?>" action="<?php echo site_url().'/msg/talk/tag_it'; ?>"><i class="icon-tag icon-white"></i> <?php echo $this->lang->line('form_sendIt'); ?></a>
                   
	                            <button class="btn submit_closer_tag" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <?php echo $this->lang->line('form_cancel'); ?></button>  
                            </div> 
                        </form>
			 
                    </div> 
				 
				 <?php
				}
				  ?>
				  
				    <div class="js_talk">			        
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.elastic.js"></script>
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/talk_view.js"></script>
					</div><?php 
			}
        }
	}	

		
	
//Ici traite les commentaires pour un talk
public function comm_talk()
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('talk_com','','required|trim|xss_clean|min_length[1]|max_length[160]');
          $this->form_validation->set_rules('id_talk','','required|trim|xss_clean|numeric');		 
		 
         	if($this->form_validation->run())
		    {
	   	     //J'envoi à l'usine à  gaz
		     $result = $this->talks->insert_talk_com($this->input->post('id_talk'),$this->input->post('talk_com'));
             
             $verdict = $result[0];//$verdict renvoi false ou true pour savoir s'il ya eu une erreur

                if($verdict)
                {
				   $reponse['statu']     = 'good';
		           $reponse['messenger'] = $result[1];
                }
                else
                {
				   $reponse['statu']     = 'bad';
		           $reponse['messenger'] = $result[1];
                }				
			}
			else
			{
			   $reponse['statu']     = 'bad';
		       $reponse['messenger'] = form_error('id_talk').form_error('talk_com');
			}
		}
		else
		{
		  $reponse['statu']     = 'info';
		  $reponse['messenger'] = $this->lang->line('statu_not_connected');
		}
		
	 // on a notre objet $reponse (un array en fait)
     // reste juste à l'encoder en JSON et l'envoyer

     header('Content-Type: application/json');
     echo json_encode($reponse);	
	}




//Ici un cherche à savoir si on a de nouveaux commentaire pour un talk précis
public function if_new_com()
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('last_com_talk','','required|trim|xss_clean|numeric');
          $this->form_validation->set_rules('id_talk','','required|trim|xss_clean|numeric');		 
		 
         	if($this->form_validation->run())
		    {
	   	     //on renvoi "1" s'il ya de nouveaux commentaires
		     echo $this->talks->if_new_com($this->input->post('id_talk'),$this->input->post('last_com_talk'));             			
			}
		}
	}



//Ici on récupère les nouveaux commentaires
public function new_com()
    {	  
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('last_com_talk','','required|trim|xss_clean|numeric');
          $this->form_validation->set_rules('id_talk','','required|trim|xss_clean|numeric');		 
		 
         	if($this->form_validation->run())
		    {
		     $reponse = $this->talks->new_com($this->input->post('id_talk'),$this->input->post('last_com_talk')); 
        
                if($reponse!==false)
                {
				    foreach($reponse as $talk_com)
					{  ?>
					    <div class="commentBox" id="record-<?php  echo $talk_com->id_com;?>" align="left">

						    <span style="float:left;" alt="" >
					                            <i class="icon-user"></i><?php if($talk_com->user!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$talk_com->user; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?><br>
						                        <span class="user_post"><?php echo $this->connects->username($talk_com->user,1); ?></span><br>
									
					        </span>
											
		                    <label class="postedComments">
							        <?php  echo $talk_com->commentaire;?>
						    </label>
											
					        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$talk_com->timestamp); ?></div>
											
                        </div>
						
					
					 <?php
					}
                      ?>
					  
                     <div class="js_talk_com">			        
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/talk_coucou.js"></script>
					 </div>
                     
					 <?php					  
                }				
			}
		}
	}




//Ici on affiche tous les comentaires d'un talk
public function affiche_tout($id_talk)
    {	  
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		  
		     $reponse = $this->talks->all_com($id_talk); 
        
                if($reponse!==false)
                {
				    foreach($reponse as $talk_com)
					{  ?>
					    <div class="commentBox" id="record-<?php  echo $talk_com->id_com;?>" align="left">

						    <span style="float:left;" alt="" >
					                <i class="icon-user"></i><?php if($talk_com->user!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$talk_com->user; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?><br>
						            <span class="user_post"><?php echo $this->connects->username($talk_com->user,1); ?></span><br>
									
					        </span>
											
		                    <label class="postedComments">
							        <?php  echo $talk_com->commentaire;?>
						    </label>
											
					        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$talk_com->timestamp); ?></div>
											
                        </div>						
					
					 <?php
					}
                      ?>
					  
                     <div class="js_talk_com">			        
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/talk_coucou.js"></script>
					 </div>
                     
					 <?php					  
                }				
		}
		
	}	
	



//Ici on tague les talk
public function tag_it()
    {	  
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('tag','','required|trim|xss_clean|min_length[4]|max_length[30]');
          $this->form_validation->set_rules('id_talk','','required|trim|xss_clean|numeric');		 
		 
         	if($this->form_validation->run())
		    {
	   	     //on renvoi "1" s'il ya de nouveaux commentaires
		     $reponser = $this->talks->tag_it($this->input->post('id_talk'),$this->input->post('tag')); 
        	
				if($reponser)
                {
				 $reponse['statu']     = 'good';
			     $reponse['messenger'] = $this->lang->line('statu_talk_good');				
                }
                else
                {
				 $reponse['statu']     = 'bad';
			     $reponse['messenger'] = $this->lang->line('statu_error');	
                }				
			}
			else
			{
			  $reponse['statu'] = 'bad';
		      $reponse['messenger'] = form_error('tag').form_error('id_talk');
			}
		}
		else
		{
		  $reponse['statu'] = 'info';
		  $reponse['messenger'] = $this->lang->line('statu_not_connected');
		}
		
	 // on a notre objet $reponse (un array en fait)
     // reste juste à l'encoder en JSON et l'envoyer

     header('Content-Type: application/json');
     echo json_encode($reponse);
	}		
	
	

//Ici on renvoi juste le timestamp du serveur	
public function my_last_time()
    {	   
	   echo time();
	}


	
//Ici on suit un talk	
public function follow($id_talk)
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
	   	  //J'envoi à l'usine à  gaz
		  $this->talks->follow($id_talk,$this->session->userdata('numero'));
        }	
	}
	
	
	
	
//Ici on met un talk en favoris
public function favorite($id_talk)
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
	   	  //J'envoi à l'usine à  gaz
		  echo $this->talks->favorite($id_talk);
        }	
	}
	
	
	
//Cette fonction affiche la liste des talks
public function ListeTalk()
    {
	    if($this->session->userdata('logged_in'))
	    { 
		 ?>		 		 
			<div class="tabbable"> <!-- Only required for left/right tabs -->
                        
				<ul class="nav nav-tabs">
                            
					<li class="active"><a href="#tab_new_talk" class="new_talk" action="<?php echo site_url().'/msg/talk/ListeTalk_last'; ?>" data-toggle="tab"><i class="icon-time"></i></a></li>
                            
					<li><a href="#tab_favorite" class="favorite_talk" action="<?php echo site_url().'/msg/talk/ListeTalk_fav'; ?>" data-toggle="tab"><i class="icon-heart"></i> <?php echo $this->lang->line('statu_talk_fav'); ?></a></li>
							
					<li><a href="#tab_private"  class="private_talk" action="<?php echo site_url().'/msg/talk/ListeTalk_private'; ?>" data-toggle="tab"><i class="icon-ban-circle"></i> <?php echo $this->lang->line('statu_talk_private'); ?></a></li>
					
					<li><a href="#tab_my"  class="my_talk" action="<?php echo site_url().'/msg/talk/ListeTalk_my'; ?>" data-toggle="tab"><i class="icon-user"></i> <?php echo $this->lang->line('statu_talk_my'); ?></a></li>
                
				    <li><a href="#myModal_form_talk" data-toggle="modal" class="bulle friends_liste" action="<?php echo site_url().'/user/user/List_friends'; ?>" title="<?php echo $this->lang->line('form_talk'); ?>" data-placement="bottom"><i class="icon-volume-up"></i> <?php echo $this->lang->line('form_talk_one'); ?></a></li>
				</ul>
                    
                <div class="tab-content">
                           
					<div class="tab-pane active" id="tab_new_talk">
                                								
					</div>
							
					<div class="tab-pane" id="tab_favorite">
							  
					</div>
					
					<div class="tab-pane" id="tab_private">
							  
					</div>
							
					<div class="tab-pane" id="tab_my">
							  
					</div>
                </div>
					
			</div>
			  <script type="text/javascript" src="<?php echo base_url();?>assets/js/liste_talk.js"></script>
         <?php   
		}
		else
        { ?>
            <div class="alert alert-info">
				<?php echo $this->lang->line('statu_not_connected'); ?>
		    </div><?php	
        }
	}	
	



//Cette fonction affiche la liste des derniers talks
public function ListeTalk_last()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('new',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-time icon-white"></i> <?php echo $this->lang->line('statu_talk_new'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
							</ul>
							
							<span class="talk_aff"></span>
									
							<button class="btn btn-info new_talk_plus" action="<?php echo site_url().'/msg/talk/last_talk_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
						
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_talk.js"></script>
						
						<div id="nbre_talk" style="display:none;">50</div>
						
					</div>
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_talk'); ?>
		            </div><?php	
                }					
		    }
		}
		else
        { ?>
            <div class="alert alert-info">
				<?php echo $this->lang->line('statu_not_connected'); ?>
		    </div><?php	
        }
	}	
	
	
///////////////////////////////////////////////

//Cette fonction affiche la liste des derniers talk lorqu'on a appuyer le bouttons plus
public function last_talk_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('new',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
					    </ul>
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_talk.js"></script>
				    
					</div>
                 <?php
                }					
		    }
		}
	}	
	

	
	


//Cette fonction affiche la liste des talk les plus favoris
public function ListeTalk_fav()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('favorite',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-heart icon-white"></i> <?php echo $this->lang->line('statu_talk_fav'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
							</ul>
									
							<span class="talk_aff"></span>
									
							<button class="btn btn-info favorite_talk_plus" action="<?php echo site_url().'/msg/talk/favorite_talk_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
										
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_talk.js"></script>
					  
					    <div id="nbre_talk" style="display:none;">50</div>
					
					</div>
	
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_talk'); ?>
		            </div><?php	
                }					
		    }
		}
	}


	
//Cette fonction affiche la liste des derniers talk favoris lorqu'on a appuyer le bouttons plus
public function favorite_talk_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('favorite',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
					    </ul>
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_talk.js"></script>
				    
					</div>
                 <?php
                }					
		    }
		}
	}	
	
	
	
	
	
 //Cette fonction affiche la liste des mes talks
 public function ListeTalk_my()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('mine',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-user icon-white"></i> <?php echo $this->lang->line('statu_talk_my'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
							</ul>
									
							<span class="talk_aff"></span>
									
							<button class="btn btn-info my_talk_plus" action="<?php echo site_url().'/msg/talk/my_talk_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
										
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_talk.js"></script>
					  
					    <div id="nbre_talk" style="display:none;">50</div>
					
					</div>
	
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_talk'); ?>
		            </div><?php	
                }					
		    }
		}
	}



	
//Cette fonction affiche la liste de mes talk lorqu'on a appuyer le bouttons plus
public function my_talk_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('mine',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_msg" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							 foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
					    </ul>
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_talk.js"></script>
				    </div>
                 <?php
                }					
		    }
		}
	}
	
	
	
	
	
	
	//Cette fonction affiche la liste des mes talks privés
 public function ListeTalk_private()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('private',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_talk" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-ban-circle icon-white"></i> <?php echo $this->lang->line('statu_talk_my'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
							</ul>
									
							<span class="talk_aff"></span>
									
							<button class="btn btn-info private_talk_plus" action="<?php echo site_url().'/msg/talk/private_talk_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
										
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_talk.js"></script>
					  
					    <div id="nbre_talk" style="display:none;">50</div>
					
					</div>
	
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_talk'); ?>
		            </div><?php	
                }					
		    }
		}
	}



	
//Cette fonction affiche la liste de mes talk privés lorqu'on a appuyer le bouttons plus
public function private_talk_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->talks->MaListe_Talk('private',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_msg" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							 foreach($reponse as $my_talk)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_talk->id_talk; ?>">
									
									<a href="" class="talking" rel="<?php echo site_url().'/msg/talk/talking/'.$my_talk->id_talk; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							                    
												<?php echo $my_talk->talk_talk; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_talk bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_talk->favoris_talk; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span> </li>
										
                                                    </ul>
							                    </div>
								
					                        </div>
							
				                        </div>
										
						             </div>
									
									</a>
                                  									
											
								</li>
								     <?php 
				                                
									}?>
					    </ul>
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_talk.js"></script>
				    </div>
                 <?php
                }					
		    }
		}
	}
	



	
	
//Cette fonction affiche un talk
public function talking($id_talk)
    {
	    if($this->session->userdata('logged_in'))
	    { 
		    $result = $this->talks->talking($id_talk);
			
		    if($result!== false)
			{
			    foreach($result as $my_talk)
				{?>				    
					<div class="friends_area" id="record-<?php  echo $my_talk->id_talk; ?>">
					
					  <div class="row bs-docs-chat">
                        
						<div class="span2">
						    <i class="icon-user"></i><br>
							<span class="user_post"><?php echo $this->connects->username($my_talk->auteur_talk,1); ?></span><br>
							<?php if($my_talk->auteur_talk!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$my_talk->auteur_talk; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?>
						</div>
                        
						<div class="span9">
						    
							<div class="talk_post">
							    <?php echo $my_talk->talk_talk; ?>
								
								<div> 
								     <ul class="nav nav-pills">
                                            
											<?php if($this->talks->if_follow($my_talk->id_talk)){ ?>										
                                        <li class="bulle" title="<?php echo $this->lang->line('bulle_follow'); ?>" data-placement="top"><a href="#" class="follow" action="<?php echo site_url().'/msg/talk/follow/'.$my_talk->id_talk; ?>"><?php echo $this->lang->line('statu_follow'); ?> <i class="icon-headphones"></i></a></li>
										    <?php } ?>
										
										<li class="favorite_talker bulle" action="<?php echo site_url().'/msg/talk/favorite/'.$my_talk->id_talk; ?>" title="<?php echo $this->lang->line('bulle_favorite'); ?>" data-placement="top">
										   <a href="#">
										        <span class="label label-warning">
										            <span class="all_favor"> 
													    <?php echo $my_talk->favoris_talk; ?>
													</span> 
													<i class="icon-heart icon-white"></i>
												</span> 
										   </a>
										</li>
										
										<li><a href="javascript: void(0)" id="post_id<?php echo $my_talk->id_talk; ?>" class="showCommentBox"><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_talk->comment_talk; ?>)</a></li>
										
										     <?php if($this->talks->if_tag($my_talk->id_talk)){ ?>
										<li class="bulle tag_it"  title="<?php echo $this->lang->line('bulle_tag'); ?>" data-placement="top"><a href="#myModal_tag" data-toggle="modal"><i class="icon-tag"></i> <?php echo $this->lang->line('form_taguer'); ?></a></li> 
                                            <?php } ?>
											
										<?php if($this->talks->permit_talk_special($id_talk)){ ?>										
                                        <li class="bulle" title="<?php echo $this->lang->line('bulle_rappel_talk'); ?>" data-placement="top"><a href="#" class="recall" action="<?php echo site_url().'/msg/talk/recall/'.$my_talk->id_talk; ?>"><?php echo $this->lang->line('statu_recall'); ?> <i class="icon-bell"></i></a></li>
										    <?php } ?>

								   </ul>
							    </div>
								<div class="info_sup"><span class="label label-info"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_talk->timestamp_talk); ?></span></div>
					        </div>
							
				        </div>
						
					</div>				   
				 
				   </div>
				   
				   <div class="all_comment" id="CommentPosted<?php echo $my_talk->id_talk;?>">
						
						<?php 
						     
						   $result_com = $this->talks->talk_comments($my_talk->id_talk);
							
							if($result_com!== false)//SI on a quelque chose
							{						 
							  $comment_num_row = count($result_com[1]);
							 
							    if($comment_num_row > 0)
								{
								    //Regardons s'il ya possibilité de cacher les autres commentaires du talk s'il sont supérieur à 4
									
								    $comment_num_row_reel = $result_com[0];//$result_com[0] porte le nombre réel decommentaire
									
									if($comment_num_row < $comment_num_row_reel)
									{
									  ?>
									
									  <span class="affiche_plus"  action="<?php echo site_url().'/msg/talk/affiche_tout/'.$id_talk; ?>"><a href="#"><i class="icon-plus"></i> <?php echo $this->lang->line('form_all_talk'); ?> <?php echo $comment_num_row_reel; ?> <?php echo $this->lang->line('form_commentaire'); ?> </a></span>
									
									  <?php
									}
									
									foreach($result_com[1] as $talk_com)
									{?>
									    <div class="commentBox" id="record-<?php  echo $talk_com->id_com;?>" align="left">

						                    <span style="float:left;" alt="" >
					                            <i class="icon-user"></i><?php if($talk_com->user!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$talk_com->user; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?><br>
						                        <span class="user_post"><?php echo $this->connects->username($talk_com->user,1); ?></span><br>
									
					                        </span>
											
		                                    <label class="postedComments">
							                    <?php  echo $talk_com->commentaire;?>
						                    </label>
											
					                        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$talk_com->timestamp); ?></div>
											
                                        </div>
									  <?php  
									}
								}
							}
						?>
						</div>
				   
				   <div class="append_talk"></div>		
				   

                 <div class="commentBox" align="right" id="commentBox-<?php  echo $my_talk->id_talk;?>">
				       
					<span style="float:left;" alt="" >
					    <i class="icon-user"></i><br>
						<span class="user_post"><?php echo $this->connects->username($this->session->userdata('numero'),0); ?></span>
					</span>
				
				    <label id="record-<?php  echo $my_talk->id_talk; ?>">
					
					    <textarea  placeholder="<?php echo $this->lang->line('form_comment_talk'); ?>..." class="commentMark" id="commentMark-<?php  echo $my_talk->id_talk; ?>" name="commentMark" cols="60"></textarea>
				            
					</label>
				
				    <br clear="all" />
			        
					<div id="progressbar_talk_comment"></div>
					
					<div id="attend"></div>
				    <button id="SubmitComment" action="<?php echo site_url().'/msg/talk/comm_talk'; ?>" class="btn btn-mini btn-primary comment" type="button"><i class="icon-comment icon-white"></i> <?php echo $this->lang->line('form_comment'); ?> (- <span id="count_com">160</span>)</button>
			            
				 </div>				 
				
				     <!-- CE DIV EST POR LES INFORMATIONS SERVANT AU RAFRAICHISSEMENT AJAX DES NOUVEAUX COMMENTAIRES-->
				  <div id="my_last_time" time="<?php echo time(); ?>" talker="<?php echo $my_talk->id_talk; ?>" url_verif="<?php echo site_url().'/msg/talk/if_new_com'; ?>" url_maj="<?php echo site_url().'/msg/talk/new_com' ;?>" my_last_time="<?php echo site_url().'/msg/talk/my_last_time'; ?>"></div>
				 
				    <!-- Modal pour le talk-->
                    <div class="modal" id="myModal_tag" tabindex="-1" style="display:none;" role="dialog" aria-labelledby="myModalLabel_tag" aria-hidden="true">

                        <!-- le corps de la fenêtre modal -->
		                <form class="form-horizontal">
                            
							<div class="modal-body">
	                         
                                <div class="control-group">
                                    
									<div class="controls">
						                <span class="w">
										    <label class="control-label" for="my_tag"><?php echo $this->lang->line('form_tag'); ?></label>
										    <input id="my_tag" type="text" placeholder="<?php echo $this->lang->line('form_tag'); ?>...">		          
		                                </span>
                                    </div>
                                </div>  	
                            </div>
				
				            <!-- le footer de la fenêtre modal -->
                            <div class="modal-footer">						
						        <a href="#" class="btn btn-primary submit_tag" rel="<?php  echo $my_talk->id_talk; ?>" action="<?php echo site_url().'/msg/talk/tag_it'; ?>"><i class="icon-tag icon-white"></i> <?php echo $this->lang->line('form_sendIt'); ?></a>
                   
	                            <button class="btn submit_closer_tag" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <?php echo $this->lang->line('form_cancel'); ?></button>  
                            </div> 
                        </form>
			 
                    </div> 
				 
				 <?php
				}
				  ?>
				  
				  
				    <div class="js_talk">			        
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.elastic.js"></script>
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/talk_view.js"></script>
					</div><?php 
			}
            else
            { ?>
			        <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_allowed'); ?>
		            </div>
              <?php			 
			}			
		}
		
	}	
	

	
	
//Ici on affiche le fichier js qui gère la messagerie instantané des talks	
public function js_talk()
    {
	   echo '<script type="text/javascript" src="'.base_url().'assets/js/talk_tchat.js"></script>';
	}



//Ici rappelle tous les membres d'un talk	
public function recall($id_talk)
    {
	    if($this->session->userdata('logged_in'))//SI LE GAR EST CONNECTé
	    {
		    if($this->talks->permit_talk($id_talk))//s'il a les droit de ce talk
			{ 
			  //alors il peut rappeller les autres
			  $this->talks->recall($id_talk);
			}
		}
	}


//Ici récupère l'appel du talk en ajx pour afficher en ajax dans une popup qui explosera après	
public function recall_talk($id_talk)
    {
	    if($this->session->userdata('logged_in'))//SI LE GAR EST CONNECTé
	    {
		    $reponse = $this->talks->recall_talk($id_talk);
			
			if($reponse!==false)//s'il a le talk.Le premier champ contient le talk et le dexième,son auteur
			{?>
			       
				<div class="new_call_for_me alert alert-info">
	        
			          <div id="body_recall">
                        <h6><?php echo $this->lang->line('statu_new_recall'); ?></h6>
			            <p>
			                <?php echo $reponse[0]; ?><br> 
			                <?php echo $reponse[1]; ?>
			            </p>   
			        </div>		
			    
                    <a href="#"  class="go_here" affiche="<?php echo site_url();?>/msg/talk/talking/<?php echo $id_talk;?>" afficheur="1" data-dismiss="alert"><i class="icon-eye-open"></i> <?php echo $this->lang->line('statu_see'); ?></a>
				         &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#"  class="go_here" data-dismiss="alert" afficheur="0"  ><i class="icon-eye-close"></i> <?php echo $this->lang->line('statu_leave'); ?></a>
				   
	            </div>
    
	
	         <script type="text/javascript" src="<?php echo base_url();?>assets/js/recall_talk.js"></script>
			 <?php			
			}			
		}
	}
	
	
}


?>