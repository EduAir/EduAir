<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller 
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


        $this->load->model('user/users');

        $this->load->library('form_validation');	

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');		 
    }


public function index()
    {
	   redirect('','refresh'); 
    }  	
	




//Here we add user
function add_friend()
    { 
      
        if($this->session->userdata('logged_in'))
	    { 
	       //on définit les règles de succès: 
		    $this->form_validation->set_rules('name',$this->lang->line('form_name'),'trim|xss_clean|min_length[2]|max_length[30]');
		    $this->form_validation->set_rules('phone', $this->lang->line('form_phone'),'trim|required|xss_clean|min_length[5]|numeric');
            
		    //si la validation du formulaire a échouée on redirige vers le formulaire d'inscription
            if(!$this->form_validation->run())
		    { 
			    $resultat = array(
		        'message'  => validation_errors(),
				'statu'    => 'fail',
			    );
		
                // reste juste à l'encoder en JSON et l'envoyer
                header('Content-Type: application/json');
                echo json_encode($resultat);
            } 
	        else 
		    {	
                if(!$this->users->add_friend($this->input->post('name'),$this->input->post('phone')))
			    {
			     
				  $resultat = array(
		                   'message'  => 'Fatal error',
						   'statu'    => 'fail',
						   );
		
                  // reste juste à l'encoder en JSON et l'envoyer
                  header('Content-Type: application/json');
                  echo json_encode($resultat);
			    }
			    else
			    {
			      $resultat = array(
		                   'message'  => 'none',
						   'statu'    => 'yep'
						   );
		
                  // reste juste à l'encoder en JSON et l'envoyer
                  header('Content-Type: application/json');
                  echo json_encode($resultat);
			    }
			}
		}
	}


	
	
//ICi on affiche la liste des amis de l'utilisateur	
public function List_friends()
    {
	    //on vérifie si l'user est connecté si oui,on le redirige vers une page qui lui dit qu'il est déja connecté
        if($this->session->userdata('logged_in'))
	    { 
	   	 //je prend la liste de tous ces amis
		 $reponse_friends = $this->users->all_contact($this->session->userdata('user_id')); //C'est un tableau qui est renvoyé
		 	
			if($reponse_friends!==false)// s'il a des amis on affiche la liste
			{ 
			  // on a notre objet $reponse (un array en fait)
             // reste juste à l'encoder en JSON et l'envoyer
				$resultat = array('statu' => 'connected','friend'=>$reponse_friends );
			}else{
				$resultat = array('statu' => 'no_friend','friend'=>'none');
			}
		}else{
			$resultat = array('statu' => 'disconnected','friend'=>'none' );
		}

		header('Content-Type: application/json');
        echo json_encode($resultat);
	}
	
	


	//ICi on enregistre les critiques
public function critik()
    {
	  //je regarde s'il est dans la liste de mes amis et je l'inscrit si cest pas le cas
	    if($this->session->userdata('logged_in'))
	    { 
          //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('text','','required|trim|xss_clean');
	  
	        if($this->form_validation->run())
		    {
			 $text = $this->input->post('text');
			 
		     $reponse = $this->users->critik($text);
		    }
		}   
	}


}


?>