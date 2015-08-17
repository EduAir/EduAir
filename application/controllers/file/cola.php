<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cola extends CI_Controller 
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
		
		 //On appelle le model qui s'occupe des fichiers
	    $this->load->model('file/colas');	

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');
		$this->load->model('user/users');

        $this->load->library('form_validation');

        $this->load->helper('inflector');//pour les underscores des nom de fichier		
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
		    
         	if($this->form_validation->run())
		    {
	   	     //J'envoi à l'usine à  gaz
		     $result = $this->talks->insert_file($this->input->post('talk'));
             
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
		       $reponse['messenger'] = form_error('talk');
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


	

	
//Ici traite les commentaires pour un fichier
public function comm_file()
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		    //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('file_com','','required|trim|xss_clean|min_length[1]|max_length[160]');
          $this->form_validation->set_rules('id_file','','required|trim|xss_clean|numeric');		 
		 
         	if($this->form_validation->run())
		    {
	   	     //J'envoi à l'usine à  gaz
		        if($this->colas->insert_file_com($this->input->post('id_file'),$this->input->post('file_com')))
			    {
				    //On affiche le commentaire
					?>
					<div class="commentBox_file" align="left">

						<span style="float:left;" alt="" >
					        <i class="icon-user"></i><br>
						    <span class="user_post"><?php echo $this->connects->username($this->session->userdata('numero'),1); ?></span><br>
									
					    </span>
											
		                <label class="postedComments">
							<?php  echo $this->input->post('file_com');?>
						</label>
											
					    <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',time()); ?></div>
											
                    </div>
					
					<?php
				}	
			}
		}
	
	}




//Ici on affiche tous les comentaires d'un fichier
public function affiche_tout($id_file)
    {	  
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
		  
		     $reponse = $this->colas->all_com($id_file); 
        
                if($reponse!==false)
                {
				    foreach($reponse as $file_com)
					{  ?>
					    <div class="commentBox_file" id="record-<?php  echo $file_com->id_com;?>" align="left">

						    <span style="float:left;" alt="" >
					                <i class="icon-user"></i><?php if($file_com->auteur!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$file_com->auteur; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?><br>
						            <span class="user_post"><?php echo $this->connects->username($file_com->auteur,1); ?></span><br>
									
					        </span>
											
		                    <label class="postedComments">
							        <?php  echo $file_com->commentaire;?>
						    </label>
											
					        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$file_com->timestamp); ?></div>
											
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
	



	
	
//Ici on met un fichier en favoris
public function favorite($id_file)
    {	   
	   //on vérifie si l'user est connecté 
        if($this->session->userdata('logged_in'))
	    { 
	   	  //J'envoi à l'usine à  gaz
		  echo $this->colas->favorite($id_file);
        }	
	}
	
	
	
//Cette fonction affiche la liste des fichiers
public function ListeFile()
    {
	    if($this->session->userdata('logged_in'))
	    { 
		 ?>		 		 
			<div class="tabbable"> <!-- Only required for left/right tabs -->
				
				<ul class="nav nav-tabs">
                            
					<li class="active"><a href="#tab_new_file" class="new_file bulle" title="<?php echo $this->lang->line('bulle_new_file'); ?>" data-placement="top" action="<?php echo site_url().'/file/cola/ListeFile_last'; ?>" data-toggle="tab"><i class="icon-time"></i> <?php echo $this->lang->line('bulle_new_file'); ?></a></li>
                            
					<li><a href="#tab_favorite" class="favorite_file bulle" title="<?php echo $this->lang->line('bulle_view_file'); ?>" data-placement="top"  action="<?php echo site_url().'/file/cola/ListeFile_fav'; ?>" data-toggle="tab"><i class="icon-heart"></i><i class="icon-plus"></i> <?php echo $this->lang->line('bulle_view_file'); ?></a></li>
							
					<li><a href="#tab_my"  class="my_file bulle" title="<?php echo $this->lang->line('bulle_my_file'); ?>" data-placement="top"  action="<?php echo site_url().'/file/cola/ListeFile_my'; ?>" data-toggle="tab"><i class="icon-user"></i> <?php echo $this->lang->line('statu_file_my'); ?></a></li>
                				    
                    <li><a class="upload_it bulle" title="<?php echo $this->lang->line('bulle_put_file'); ?>" data-placement="top"  href="#myModal_form_file" data-toggle="modal" action="<?php echo site_url().'/file/cola/my_upfiler'; ?>"><i class="icon-plus-sign"></i> <?php echo $this->lang->line('bulle_put_file'); ?></a></li>
                	
					<li><a href="<?php echo base_url();?>assets/bluging/flash.exe"  class=" bulle"  title="<?php echo $this->lang->line('bulle_flash'); ?>" data-placement="top"><img src="<?php echo base_url(); ?>assets/smileys/flash.png" > <?php echo $this->lang->line('bulle_flash_alert'); ?></a></li>
                	
				</ul>
                    
                <div class="tab-content">
                           
					<div class="tab-pane active" id="tab_new_file">
                                								
					</div>
							
					<div class="tab-pane" id="tab_favorite">
							  
					</div>
							
					<div class="tab-pane" id="tab_my">
							  
					</div>
                
				</div>
				
				<!-- Modal pour le l'uploader-->
                <div class="modal" id="myModal_form_file" tabindex="-1" style="display:none;" role="dialog" aria-labelledby="myModalLabel_talk" aria-hidden="true">
                    <!-- Modal -->
		            <div class="modal-header">
                        <button type="button" class="close submit_closer_file" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel_file"><?php echo $this->lang->line('form_file'); ?></h3>
                    </div>
		            
					<!-- Affichage des erreurs -->
                    <div id="validateTips_file" class="alert alert-error"></div>
					
		             <div id="erreur" style="display:none;"><?php echo $this->lang->line('statu_court'); ?></div>
					 
					<!-- le corps de la fenêtre modal -->
		            <form class="form-horizontal" action="<?php echo site_url().'/file/cola/my_upfiler'; ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
	                        <div class="alert alert-info">
                                 <i class="icon-plus"></i> <?php echo $this->lang->line('bulle_upload'); ?>  <img src="<?php echo base_url(); ?>assets/smileys/cafe.gif" >
                            </div>
							
	                         <h5><?php echo $this->lang->line('form_titre_file'); ?></h5>
							 
                            <input type="text" id="titre" name="titre" placeholder="<?php echo $this->lang->line('form_titre_file'); ?>…">
    
	                        <br><br> 
                            <input id="my_file" type="file" name="userfile" /> <br> 
                            
							<div id="progressbar_file"></div>
                            							
                        </div>
				
				        <!-- le footer de la fenêtre modal -->
                        <div class="modal-footer">
						
						   <button href="#" class="btn btn-primary submit_file" type="submit" ><i class="icon-hdd icon-white"></i> <?php echo $this->lang->line('form_sendIt'); ?></button>
                   
	                       <button class="btn submit_closer_file" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <?php echo $this->lang->line('form_cancel'); ?></button>  
                        </div> 
                    </form>
                </div>
				
				
					
			</div>
			  <script type="text/javascript" src="<?php echo base_url();?>assets/js/liste_file.js"></script>
         <?php   
		}
		else
        { ?>
            <div class="alert alert-info">
				<?php echo $this->lang->line('statu_not_connected'); ?>
		    </div><?php	
        }
	}	
	



//Cette fonction affiche la liste des derniers files
public function ListeFile_last()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('new',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_file" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-time icon-white"></i> <?php echo $this->lang->line('statu_talk_new'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
												
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
							
							<span class="new_file_aff"></span>
									
							<button class="btn btn-info new_file_plus" action="<?php echo site_url().'/file/cola/last_file_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
						
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_file.js"></script>
						
						<div id="nbre_file_new" style="display:none;">50</div>
						
					</div>
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_file'); ?>
		            </div><?php	
                }					
		    }
			else
			{
			   ?>
                    <div class="alert alert-info">
					    <?php echo form_error('nbre_msg'); ?>
		            </div><?php	
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

//Cette fonction affiche la liste des derniers fichiers lorqu'on a appuyer le bouttons plus
public function last_file_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('new',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_file" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_file.js"></script>
				    
					</div>
                 <?php
                }					
		    }
		}
	}	
	

	
	


//Cette fonction affiche la liste des fichiers les plus consultés
public function ListeFile_fav()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('favorite',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_file" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-heart icon-white"></i><i class="icon-plus icon-white"></i> </i> <?php echo $this->lang->line('statu_talk_fav'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
							
							<span class="new_file_aff"></span>
									
							<button class="btn btn-info new_file_plus" action="<?php echo site_url().'/file/cola/last_file_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
						
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_file.js"></script>
						
						<div id="nbre_file_favorite" style="display:none;">50</div>
						
					</div>
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_file'); ?>
		            </div><?php	
                }					
		    }
		}
	}


	
//Cette fonction affiche la liste des derniers fichiers les plus consultés lorqu'on a appuyer le bouttons plus
public function favorite_file_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('favorite',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_file" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_file.js"></script>
				    
					</div>
                 <?php
                }					
		    }
		}
	}	
	
	
	
	
	
 //Cette fonction affiche la liste des mes fichiers
 public function ListeFile_my()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('mine',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{
			     ?>		 		                     
                    <div class="tabbable tabs-left">
								   
						<div class="bs-docs-example">
                                    
							<ul id="liste_file" class="nav nav-list bs-docs-sidenav">
                                        
								<li class="nav-header">&nbsp;&nbsp; <i class="icon-user icon-white"></i> <?php echo $this->lang->line('statu_file_my'); ?></li>									
			                        <?php 
									
									foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
							
							<span class="new_file_aff"></span>
									
							<button class="btn btn-info new_file_plus" action="<?php echo site_url().'/file/cola/last_file_plus'; ?>" type="button"><i class="icon-plus icon-white"></i></button>	
				
						</div>
						
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/last_file.js"></script>
						
						<div id="nbre_my_file" style="display:none;">50</div>
						
					</div>
                 <?php
                }
                else
                { ?>
                    <div class="alert alert-info">
					    <?php echo $this->lang->line('statu_no_file'); ?>
		            </div><?php	
                }					
		    }
		}
	}



	
//Cette fonction affiche la liste de mes fichiers lorqu'on a appuyer le bouttons plus
public function my_file_plus()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('nbre_msg','Nbre_message','trim|xss_clean|alpha_numeric');
	  
	        if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->colas->MaListe_File('mine',$this->input->post('nbre_msg')); 
			 
			    if($reponse!== false)
				{?>
                    <div class="bs-docs-example">
                                    
					    <ul id="liste_file" class="nav nav-list bs-docs-sidenav">
						<?php				
			                
							foreach($reponse as $my_file)
				                    {?>
										
								<li class="active_ceci" id="my_list<?php echo $my_file->id_file; ?>">
									
									<a href="" class="look_at" rel="<?php echo site_url().'/file/cola/look_file/'.$my_file->id_file; ?>">													
									
									 <div class="row">
                        
						                <div class="span2">
						                    
											
									    </div>
                        
						                <div class="span8">
						    
							                <div class="talk_post">
											   <i class="icon-user"></i><br>
							                   <span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							                    
												<?php echo '<h6>'.humanize($my_file->titre).' '.$this->colas->extension($my_file->extension).'</h6>'; ?>
								
								                <div> 
								                    
													<ul class="nav nav-pills">
                                            
											            <li class="favorite_file bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="top">
										       
											                
										                        <span class="label label-warning">
										                            
													                    <?php echo $my_file->favoris; ?>
													                										                
																	<i class="icon-heart icon-white"></i>
												                
																</span> 
										                   
										                </li>
										
										                <li><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)  <i class="icon-time"></i><span class="user_post"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span> </li>
										
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
					    
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/view_file.js"></script>
				    
					</div>
                 <?php
                }					
		    }
		}
	}
	
	
	
	
	
//Cette fonction affiche un fichier
public function look_file($id_file)
    {
	    if($this->session->userdata('logged_in'))
	    { 
		    $result = $this->colas->look_file($id_file);
			
		    if($result!== false)
			{
			    foreach($result as $my_file)
				{?>				    
					<div class="friends_area" id="record-<?php  echo $my_file->id_file; ?>">
					
					  <div class="row bs-docs-chat">
                        
						<div class="span2">
						    <i class="icon-user"></i><br>
							<span class="user_post"><?php echo $this->connects->username($my_file->auteur,1); ?></span><br>
							<?php if($my_file->auteur!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$my_file->auteur; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?>
						</div>
                        
						<div class="span9">
						    
							<div class="talk_post_file">
							     
								 <h5><?php echo humanize($my_file->titre); ?></h5>
							
							<?php
							    switch($my_file->extension)
								{
								   //SI c'est une video
								   case '.flv':
								        ?>			  
										<object type="application/x-shockwave-flash" data="<?php echo base_url();?>assets/player/dewtube.swf" height="250">
                                            <param name="movie" value="<?php echo base_url();?>assets/player/dewtube.swf" />
                                            <param name="flashvars" value="movie=<?php echo base_url();?>assets/alibaba/<?php echo $my_file->url; ?>" />
                                        </object>
										
										<div class="alert alert-info">
										    <button type="button" class="close" data-dismiss="alert">×</button>
                                                <a href="<?php echo base_url();?>assets/bluging/flash.exe"  class=" bulle"  title="<?php echo $this->lang->line('bulle_flash'); ?>" data-placement="top">
												    <?php echo $this->lang->line('bulle_flasher'); ?>  <img src="<?php echo base_url(); ?>assets/smileys/flash.png" >
												</a>
                                        </div>
										
										
								        <a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <i class="icon-film"></i> <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								   //Si c'est un audio
								   case '.mp3':
								       ?>
                                        <object type="application/x-shockwave-flash" data="<?php echo base_url();?>assets/player/dewplayer-vol.swf" width="100%" height="20" id="dewplayer-vol" name="dewplayer-vol">
                                            <param name="movie" value="<?php echo base_url();?>assets/player/dewplayer-vol.swf" />
                                            <param name="flashvars" value="mp3=<?php echo base_url();?>assets/alibaba/<?php echo $my_file->url; ?>" />
                                            <param name="wmode" value="transparent" />
                                        </object>
										
										<div class="alert alert-info">
										    <button type="button" class="close" data-dismiss="alert">×</button>
                                                <a href="<?php echo base_url();?>assets/bluging/flash.exe"  class=" bulle"  title="<?php echo $this->lang->line('bulle_flash'); ?>" data-placement="top">
												    <?php echo $this->lang->line('bulle_flasher'); ?>  <img src="<?php echo base_url(); ?>assets/smileys/flash.png" >
												</a>
                                        </div>
										
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <i class="icon-headphones"></i> <i class="icon-download-alt"></i></a>
									   <?php
								   break;
								   
								   //Si c'est une image on affiche un popup pour l'afficher
								   case '.jpg':
								        ?>
										<a href="#myModal_tof" class="bulle grossir" title="<?php echo $this->lang->line('bulle_grossir'); ?>"  data-placement="left" action="<?php echo site_url(); ?>/file/cola/tof/<?php echo  $my_file->url; ?>" data-toggle="modal"><img  src="<?php echo base_url(); ?>assets/alibaba/thumbs/<?php echo  $my_file->url; ?>" ></a>
										  
										<?php
								   break;
								   case '.jpeg':
								        ?>
										<a href="#myModal_tof" class="bulle grossir" title="<?php echo $this->lang->line('bulle_grossir'); ?>"  data-placement="left" action="<?php echo site_url(); ?>/file/cola/tof/<?php echo  $my_file->url; ?>" data-toggle="modal"><img  src="<?php echo base_url(); ?>assets/alibaba/thumbs/<?php echo  $my_file->url; ?>" ></a>
										  
										<?php
								   break;
								   case '.png':
								        ?>
										<a href="#myModal_tof" class="bulle grossir" title="<?php echo $this->lang->line('bulle_grossir'); ?>"  data-placement="left" action="<?php echo site_url(); ?>/file/cola/tof/<?php echo  $my_file->url; ?>" data-toggle="modal"><img  src="<?php echo base_url(); ?>assets/alibaba/thumbs/<?php echo  $my_file->url; ?>" ></a>
										  
										<?php
								   break;
								   case '.gif':
								        ?>
										<a href="#myModal_tof" class="bulle grossir" title="<?php echo $this->lang->line('bulle_grossir'); ?>"  data-placement="left" action="<?php echo site_url(); ?>/file/cola/tof/<?php echo  $my_file->url; ?>" data-toggle="modal"><img  src="<?php echo base_url(); ?>assets/alibaba/thumbs/<?php echo  $my_file->url; ?>" ></a>
										  
										<?php
								   break;
								   case '.bipmap':
								        ?>
										<a href="#myModal_tof" class="bulle grossir" title="<?php echo $this->lang->line('bulle_grossir'); ?>"  data-placement="left" action="<?php echo site_url(); ?>/file/cola/tof/<?php echo  $my_file->url; ?>" data-toggle="modal"><img  src="<?php echo base_url(); ?>assets/alibaba/thumbs/<?php echo  $my_file->url; ?>" ></a>
										  
										<?php
								   break;
								   
								   
								   //si cest un fichier pdf
								   case '.pdf':
								        ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" target="_blank" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <img  src="<?php echo base_url(); ?>assets/smileys/pdf.png" > <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								    //si cest un fichier zip
								   case '.zip':
								        ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" target="_blank" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <img  src="<?php echo base_url(); ?>assets/smileys/zip.png" > <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								    //si cest un fichier rar
								   case '.rar':
								        ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" target="_blank" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <img  src="<?php echo base_url(); ?>assets/smileys/zip.png" > <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								    //si cest un fichier doc
								   case '.doc':
								        ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" target="_blank" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <img  src="<?php echo base_url(); ?>assets/smileys/doc.png" > <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								    //si cest un fichier docx
								   case '.docx':
								        ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" target="_blank" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <img  src="<?php echo base_url(); ?>assets/smileys/doc.png" > <i class="icon-download-alt"></i></a>
										<?php
								   break;
								   
								   //Dans tout autre cas
								   default:
								         ?>
										<a class="bulle" href="<?php echo base_url(); ?>assets/alibaba/<?php echo $my_file->url ; ?>" title="<?php echo $this->lang->line('bulle_download'); ?>" data-placement="left">[ <?php echo  humanize($my_file->titre) ; ?> ] <i class="icon-download-alt"></i></a>
										<?php
								   break;
								}
							?>								
								<div> 
								     <ul class="nav nav-pills">
										
										<li class="favorite_filer bulle" action="<?php echo site_url().'/file/cola/favorite/'.$my_file->id_file; ?>" title="<?php echo $this->lang->line('bulle_favorite_file'); ?>" data-placement="bottom">
										   <a href="#">
										        <span class="label label-warning">
										            <span class="all_favor"> 
													    <?php echo $my_file->favoris; ?>
													</span> 
													<i class="icon-heart icon-white"></i>
												</span> 
										   </a>
										</li>
										
										<li><a href="javascript: void(0)" id="post_id<?php echo $my_file->id_file; ?>" class="showcommentBox_file"><i class="icon-comment"></i> <?php echo $this->lang->line('form_comment'); ?>(<?php echo $my_file->nbre_commentaire; ?>)</a></li>
                                        
										<li class="bulle" title="<?php echo $this->lang->line('bulle_view_file'); ?>" data-placement="bottom">
										    <a href="#">
											    <span class="label label-info">                                                											
													<?php echo $my_file->view; ?>													 
													<i class="icon-eye-open icon-white"></i>												
											    </span> 
											</a>										  
										</li>
										
								      </ul>
							    </div>
								<div class="info_sup"><span class="label label-info"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$my_file->timestamp); ?></span></div>
					        </div>
							
				        </div>
						
					</div>				   
				 
				   </div>
				   
				   <div class="all_comment" id="CommentPosted<?php echo $my_file->id_file;?>">
						
						<?php 
						     
						   $result_com = $this->colas->file_comments($my_file->id_file);
							
							if($result_com!== false)//SI on a quelque chose
							{						 
							  $comment_num_row = count($result_com[1]);
							 
							    if($comment_num_row > 0)
								{
								    //Regardons s'il ya possibilité de cacher les autres commentaires du fichier s'il sont supérieur à 4
								    $comment_num_row_reel = $result_com[0];//$result_com[0] porte le nombre réel decommentaire
									
									if($comment_num_row < $comment_num_row_reel)
									{
									  ?>
									
									  <span class="affiche_plus_file"  action="<?php echo site_url().'/file/cola/affiche_tout/'.$id_file; ?>"><a href="#"><i class="icon-plus"></i> <?php echo $this->lang->line('form_all_talk'); ?> <?php echo $comment_num_row_reel; ?> <?php echo $this->lang->line('form_commentaire'); ?> </a></span>
									
									  <?php
									}
									
									foreach($result_com[1] as $file_com)
									{?>
									    <div class="commentBox_file" id="record-<?php  echo $file_com->id_com;?>" align="left">

						                    <span style="float:left;" alt="" >
					                            <i class="icon-user"></i><?php if($file_com->auteur!== $this->session->userdata('numero')){?><span class="bulle" title="<?php echo $this->lang->line('bulle_coucou'); ?>" data-placement="left"><a class="coucou bulle" href="javascript: void(0)" action="<?php echo site_url().'/msg/messenger/coucou/'.$file_com->auteur; ?>" ><img src="<?php echo base_url().'assets/img/tic.gif'; ?>"></a></span><?php } ?><br>
						                        <span class="user_post"><?php echo $this->connects->username($file_com->auteur,1); ?></span><br>
									
					                        </span>
											
		                                    <label class="postedComments">
							                    <?php  echo $file_com->commentaire;?>
						                    </label>
											
					                        <div class="time"><?php echo date('d-m-Y '.$this->lang->line('statu_a').' H:i',$file_com->timestamp); ?></div>
											
                                        </div>
									  <?php  
									}
								}
							}
						?>
						</div>
				   
				   <div class="append_file"></div>		
				   

                 <div class="commentBox_file" align="right" id="commentBox_file-<?php  echo $my_file->id_file;?>">
				       
					<span style="float:left;" alt="" >
					    <i class="icon-user"></i><br>
						<span class="user_post"><?php echo $this->connects->username($this->session->userdata('numero'),0); ?></span>
					</span>
				
				    <label id="record-<?php  echo $my_file->id_file; ?>">
					
					    <textarea  placeholder="<?php echo $this->lang->line('form_comment_talk'); ?>..." class="commentMark_file" id="commentMark_file-<?php  echo $my_file->id_file; ?>" name="commentMark_file" cols="60"></textarea>
				            
					</label>
				
				    <br clear="all" />
			        
					<div id="progressbar_file_comment"></div>
					
					<div id="attend"></div>
					
				    <button id="SubmitComment_file" action="<?php echo site_url().'/file/cola/comm_file'; ?>" class="btn btn-mini btn-primary comment" type="button"><i class="icon-comment icon-white"></i> <?php echo $this->lang->line('form_comment'); ?> (- <span id="count_com">160</span>)</button>
			            
				 </div>

                
                 <!-- Modal pour afficher les tof-->
				
				<?php if($my_file->extension =='.jpg' or $my_file->extension =='.jpeg' or $my_file->extension =='.png' or $my_file->extension =='.gif' or $my_file->extension =='.bipmap') { ?>
                
				<div class="modal" id="myModal_tof" tabindex="-1" style="display:none;" role="dialog" aria-labelledby="myModalLabel_tof" aria-hidden="true">
                    <!-- Modal -->
		            <div class="modal-header">
                        <button type="button" class="close submit_closer_file" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel_tof"><?php  echo humanize($my_file->titre); ?></h3>
                    </div>
		            
                    <!-- le corps de la fenêtre modal -->
		               <div class="modal-body corps_tof">						
                        </div>
				
				        <!-- le footer de la fenêtre modal -->
                        <div class="modal-footer">
	                       <button class="btn btn-info" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> </button>  
                        </div>                   
                </div>
				
                <?php } ?>
               
			    <!-- Modal pour afficher les tof-->				
				 
				 <?php
				}
				  ?>
				  
				  
				    <div class="js_file">			        
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.elastic.js"></script>
						<script type="text/javascript" src="<?php echo base_url();?>assets/js/file_view.js"></script>
					</div><?php 
			}				 
		}
		
	}




//traitement du formulaire d'upload de fichier
function my_upfiler()
    {
	    if(!$this->session->userdata('logged_in'))//sil n'est pas connecté
        {
         $this->session->set_flashdata('statu_wonder',$this->lang->line('statu_not_connected'));
		 
		  redirect('','refresh');
        }
	    else
	    {	
	       //on définit les règles de succès: 
	      $this->form_validation->set_rules('titre',$this->lang->line('form_titre_file'),'trim|required|xss_clean|min_length[1]|max_length[30]'); 
				  
		  //maintenant entrons les données du champ d\'uploads
	            
				 //les conditions maintenant sur le fichier à uploader
		          $config['upload_path'] = './assets/alibaba/';
		          $config['allowed_types'] = 'zip|rar|doc|docx|pdf|mp3|flv|gif|jpg|jpeg|png|bipmap';
		          $config['max_size'] = '300000';
		          $config['file_name'] = underscore($this->input->post('titre'));
				   
	             //si la validation a échouée on redirige vers le formulaire d'inscription
                    if(!$this->form_validation->run())
		            {
				     $this->session->set_flashdata('statu_wonder',form_error('titre'));
		             
		             redirect('','refresh');
	                } 
		            else 
		            {
					  //on officialise les conditions citées plus haut dans la librairie upload
			          $this->load->library('upload',$config);
			      
				      $this->upload->initialize($config);//cette ligne de code ma cassé la tête à cause de son oublie.grrrrh
				  
				        if(!$this->upload->do_upload())
		                { 
						 $this->session->set_flashdata('statu_wonder','<a href="#" action="'.site_url().'/file/cola/ListeFile" class="all_files" data-dismiss="alert" rel="'.site_url().'/file/cola/ListeFile_last">'.$this->upload->display_errors().'</a>');												 
		
	                     redirect('','refresh');// la redirection à la page d'accueil
		                }
						else
						{
					     $titre = underscore($this->input->post('titre'));
                         $fichier = $this->upload->data();//on créé le array fichier qui a les infos et on commence à puiser ses infos
				         $chemin_crypt = $fichier['file_name'];
				         $capacite = $fichier['file_size'];
				         $extension = strtolower($fichier['file_ext']);
										 
		                  ///////////maintenant on envoi tout ca à la bdd tout ça/////////////		                            
						 $introduit = $this->colas->insert_file($titre,$capacite,$extension,$chemin_crypt);
						  
						    if(!$introduit)//Si l'introduction a échoué
			                {
							  // On affiche le message d'echec  statu_error
			                 $this->session->set_flashdata('statu_wonder','<a href="#" action="'.site_url().'/file/cola/ListeFile" class="all_files" data-dismiss="alert" rel="'.site_url().'/file/cola/ListeFile_last">'.$this->lang->line('statu_error').'</a>');												 
		
	                         redirect('','refresh');// la redirection à la page d'accueil
			                }
						
						 //Puis je crée la miniature du fichier si c'est une photo
						 if($extension=='.png' or $extension =='.jpg' or $extension=='.jpeg' or $extension==='.gif' or $extension=='.bipmap')
						 {
						    /////redimenssionnons l'image
					       $this->load->library('image_lib');	//libraire de manipulation des images  
					  
					       //configuration
					       $config['image_library']  = 'gd2';
						   $config['maintain_ratio'] = TRUE;
                           $config['source_image']	 = './assets/alibaba/'.$chemin_crypt;//on prend l'image dans ce dossier
					       $config['new_image']	     = './assets/alibaba/thumbs/'.$chemin_crypt;//on le redimenssionne et le met dans ce dossier
                           $config['maintain_ratio'] = TRUE;
                           $config['width']	 = 250;
                           $config['height']	= 300;
					  
					       $this->image_lib->initialize($config);

                           $this->load->library('image_lib', $config); 

						   $this->image_lib->resize();
						 }
                        										
						   $this->session->set_flashdata('statu_wonder','<a href="#" action="'.site_url().'/file/cola/look_file/'.$introduit.'" class="my_click" data-dismiss="alert">'.$this->lang->line('statu_go_file').'</a>');												 
								  
						   redirect('','refresh');
						}
					}			
		}
    }// et cest terminé...!		
	

	
	
	
//Ici on affiche une photo
public function tof($url_tof)
    {	   
	   echo '<img src="'.base_url().'assets/alibaba/'.$url_tof.'" >';
	}
	


//Ici on affiche le fichier js qui gère les commentaires en instantané	
public function js_file()
    {
	   echo '<script type="text/javascript" src="'.base_url().'assets/js/file_chat.js"></script>';
	}

	
	
}


?>