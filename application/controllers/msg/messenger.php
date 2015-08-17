<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Messenger extends CI_Controller 
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

        //On appelle la fonction qui s'occuper de la messagerie
	    $this->load->model('msg/msgs');	
		
		 //On appelle la fonction qui s'occuper du statu des users
	    $this->load->model('user/majs');	

        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');
		$this->load->model('user/users');

        $this->load->library('form_validation');		
    }
	
	 	 
public function index()
    {
		  redirect('','refresh'); 
	}
	


//Cette fonction envoi tous les nouveaux messages dans la bdd
public function incomingMessage()
    {
	    if($this->session->userdata('logged_in'))
	    { 
		  //on définit les règles de succès: 	      
	      $this->form_validation->set_rules('interloc_num','','required|trim|xss_clean|min_length[3]|max_length[12]|integer');
	  	  $this->form_validation->set_rules('sender_name','','required|trim|xss_clean|min_length[1]|max_length[20]');
	  	  $this->form_validation->set_rules('sender_num','','required|trim|xss_clean|min_length[3]|max_length[12]|integer');
	  	  $this->form_validation->set_rules('message','','required|trim|xss_clean|min_length[1]|max_length[500]');
	  	      
	        if($this->form_validation->run())
		    {
			   $reponse = $this->msgs->incomingMessager($this->input->post('interloc_num'),$this->input->post('sender_name'),$this->input->post('sender_num'),$this->input->post('message'));
			
			    if($reponse)
				{
				  echo'done';
				}
			}
			else
			{
			   echo validation_errors();
			}
        }
	}	
	
//Cette fonction récupère tous les nouveau messages dans la bdd
public function ListeMsg()
    {
	    if($this->session->userdata('logged_in'))
	    { 
		   $reponse = $this->msgs->MaListe_Msg();
		   
		    if($reponse)
			{
			 //on envoi la réponse json_decode
			 header('Content-Type: application/json');
             echo json_encode($reponse);
			 
			 //on supprime les messages		 
			 $this->msgs->MaListe_Msg_del();
		    }
        }
	}
	
	
}


?>