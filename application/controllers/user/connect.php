<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Connect extends CI_Controller 
{

function __construct()
	{	  
	  // Call the Controller constructor
     parent::__construct();
	  
	  $this->load->helper('url');
		
		//on charge les sessions
	    $this->load->library('session');
		
		//on charge la librairie de parceage pour templeter la vue
	    $this->load->library('parser');
	  
	  //le helper de formulaire
	    $this->load->helper('form');
	  
	  //on charge la validation de formulaires
        $this->load->library('form_validation');

        $this->load->library('encrypt');
		
	  //le helper de texte pour limiter les chaines de caractère lors de certains l'affichages
	    $this->load->helper('text');

	    $this->load->helper('string');
		
		//C'est cette ligne de code qui détecte la langue du navigateur et affiche le site dans la langue correspondante
		$this->lang->load('form', $this->config->item('language'));
		$this->lang->load('statu', $this->config->item('language'));
		$this->lang->load('bulle', $this->config->item('language'));

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');		
    }
	
	 	 
function index()
    {
	    //on vérifie si l'user est connecté si oui,on le redirige vers une page qui lui dit qu'il est déja connecté
        if(!$this->session->userdata('logged_in'))
	    { 
          echo connexion();
        } 
    }


public function get_my_connection_data()
{
	if($this->session->userdata('logged_in'))
	{ 
        $resultat = array(
		                   'result'  =>  $this->connects->get_my_connection_data(),
						   'statu'    => 'yes',
						   );	
    }else{
    	 $resultat = array(
		                   'result'  =>  'ras',
						   'statu'    => 'disconnected',
						   );	
    } 

    // reste juste à l'encoder en JSON et l'envoyer
    header('Content-Type: application/json');
    echo json_encode($resultat);
}
	 

//Here we generate the hash of the user.
function hash_user()
    { 
      echo  $this->connects->hash_user();	
	}



//Here save the name of the user.
function hash_name()
    {
        $this->form_validation->set_rules('hash','hash','trim|required|xss_clean');
        $this->form_validation->set_rules('username','username','trim|required|xss_clean|max_length[100]|min_length[3]'); 

        if($this->form_validation->run())
		{
			$this->connects->hash_name($this->input->post('hash'),$this->input->post('username'));

			$resultat = array(
		        'message'  => '',
			    'response' => 'done');
		}else{
			 $resultat = array(
		        'message'     => validation_errors(),
				'response'    => 'fail');
		}

		
        // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json');
        echo json_encode($resultat);
	}






//Ici on traite le  formulaire d'édition de compte
function edit_account()
    { 
    	if($this->session->userdata('logged_in'))
    	{
		   //on définit les règles de succès: 
          $this->form_validation->set_rules('number',$this->lang->line("form_phone"),'trim|required|xss_clean|numeric|min_length[5]');
          $this->form_validation->set_rules('filiere',$this->lang->line('form_filiere'),'trim|required|xss_clean|max_length[100]|min_length[2]');
          $this->form_validation->set_rules('username',$this->lang->line('form_username'),'trim|required|xss_clean|max_length[100]|min_length[4]');
		    
		  //si la validation du formulaire a échouée on redirige vers le formulaire d'inscription
            if(!$this->form_validation->run())
			{ 
			    $resultat = array(
		                   'erreurs'  => validation_errors(),
						   'statu'    => 'fail',
						   );
		
               // reste juste à l'encoder en JSON et l'envoyer
               header('Content-Type: application/json');
               echo json_encode($resultat);
            } 
			else 
			{ 
				$data_signup = $this->connects->edit_data($this->input->post('username'),$this->input->post('number'),$this->input->post('filiere'));
                
                if(!$data_signup)
			    {
			     
				  $resultat = array(
		                   'erreurs'  => $this->lang->line('form_error'),
						   'statu'    => 'fail');
		
                  // reste juste à l'encoder en JSON et l'envoyer
                  header('Content-Type: application/json');
                  echo json_encode($resultat);
			    }
			    else
			    {
			      $resultat = array(
		                   'result'   => $data_signup,
						   'statu'    => 'yep'
						   );
		
                  // reste juste à l'encoder en JSON et l'envoyer
                  header('Content-Type: application/json');
                  echo json_encode($resultat);
			    }
			}
		}
	}





//Ici on traite le  formulaire d'édition de compte
function connection()
    { 
    	if(!$this->session->userdata('logged_in'))
    	{
		   //on définit les règles de succès: 
          $this->form_validation->set_rules('username','username','trim|required|xss_clean');
		  $this->form_validation->set_rules('user_number', 'user_number','trim|required|xss_clean');
           
		  //si la validation du formulaire a échouée on redirige vers le formulaire d'inscription
            if(!$this->form_validation->run())
			{ 
			    $resultat = array(
		                   'erreurs'  => validation_errors(),
						   'statu'    => 'fail',
						   );
		
               // reste juste à l'encoder en JSON et l'envoyer
               header('Content-Type: application/json');
               echo json_encode($resultat);
            } 
			else 
			{ 
			  $this->connects->sign_up($this->input->post('username'),$this->input->post('user_number'));
			}
		}
	}



	
	//Ici on traite le  formulaire de traitement.
function session()
    {
        if($this->session->userdata('logged_in'))
		{
		 echo 'connected';
		}else{
			echo 'disconnected';
		}
	}


  function disconnect()
    {
        
		$this->session->sess_destroy();	
	}




//////////////////////////////////////////////////////THis is for SMS API/////////////////////////////////////////////////
public function sms_reg($number,$name,$pass,$level)//To register member
    {
       $this->connects->sign_up($name,$pass,$number,$level);
                
    }

//////////////////////////////////////////////////////THis is for SMS API/////////////////////////////////////////////////



}





?> 