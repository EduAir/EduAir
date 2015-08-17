<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller 
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
		
		$this->load->model('user/majs');
		
		//C'est cette ligne de code qui détecte la langue du navigateur et affiche le site dans la langue correspondante
		$this->lang->load('form', $this->config->item('language'));
		$this->lang->load('statu', $this->config->item('language'));
		$this->lang->load('bulle', $this->config->item('language'));
		$this->lang->load('note', $this->config->item('language'));

        
		 //On appelle la fonction qui s'occuper du statu des users
	    $this->load->model('msg/notifications');	

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');

         $this->load->library('form_validation');			
    }
	
	 	 
public function index()
    {
	   redirect('','refresh'); 
	}
	
	
//Cette fonction affiche les notifications
public function My_note()
    {
	    if($this->session->userdata('logged_in'))
	    { 
		 ?>		 		 
			<div class="tabbable"> <!-- Only required for left/right tabs -->
                        
				<ul class="nav nav-tabs">
                            
					<li class="active"><a href="#tab_note" data-toggle="tab"><i class="icon-comment"></i></a></li>
                            
				</ul>
                    
                <div class="tab-content">
                           
					<div class="tab-pane active" id="tab_note">
                                								
					</div>
							
                </div>
					
			</div>
         <?php   
		}
		else
        { ?>
            <div class="alert alert-info">
				<?php echo $this->lang->line('statu_not_connected'); ?>
		    </div><?php	
        }
	}	
	





////////////////////////////////////////////////////////////////////////////////////////////////////

//Cette fonction enreggistre une nouvelle pub
public function new_pub()
    {
	    if($this->session->userdata('logged_in'))
	    { 
	      //on définit les règles de succès: 
	      $this->form_validation->set_rules('message','Message','trim|xss_clean|required');
		  
		    if($this->form_validation->run())
		    {
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->notifications->new_pub($this->input->post('message')); 
			 
			    if($reponse!==false)
				{
				   echo 'succes';
				}
				else
				{
				  echo 'fail';
				}
			}
            else
            {
			  echo form_error('message');
            }			
		    
		}
	}




    //Cette fonction affiche les pubs de notification
    public function ListePub($nbre_msg)
    {
	      
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->notifications->ListePub($nbre_msg); 
			 
			    if($reponse!==false)
				{
				    //on emballe tout
		            $resultat = array(
		                              'notification'   => $reponse,
						              'statu'          => 'succes',
									  'counter'        => count($reponse)
									  );
				}
				else
				{			  
		          $resultat = array('statu'    => 'fail');
				}	
	   
        		
		

		//et on expédie
      // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json');
        echo json_encode($resultat);
	}
	
	
	
	
	
	//Cette fonction affiche les pubs de notification qui sont en hors ligne
    public function ListePub_out($nbre_msg)
    {
	      
	   	      //J'envoi à l'usine à  gaz
			 $reponse = $this->notifications->ListePub_out($nbre_msg); 
			 
			    if($reponse!==false)
				{
				    //on emballe tout
		            $resultat = array(
		                              'notification'   => $reponse,
						              'statu'          => 'succes',
									  'counter'        => count($reponse)
									  );
				}
				else
				{
				  
		          $resultat = array('statu'    => 'fail');
				}	
	   
        		
		

		//et on expédie
      // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json');
        echo json_encode($resultat);
	}



   //Cette fonction compte le nombre de notification rescent
    public function CountPub()
    {
      echo $this->notifications->CountPub();
	 
	}		
	

    //Cette fonction compte le nombre de notification rescent en offline
    public function CountPub_out()
    {
      echo $this->notifications->CountPub_out();	 
	}		
	
}


?>