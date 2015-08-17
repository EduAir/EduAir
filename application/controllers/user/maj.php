<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Maj extends CI_Controller 
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

        //On appelle la fonction qui s'occuper des mise à jours des données
	    $this->load->model('user/majs');		
    }
	
	 	 
public function index()
    {
	    //on vérifie si l'user est connecté si oui,on le redirige vers une page qui lui dit qu'il est déja connecté
        if(!$this->session->userdata('logged_in'))
	    { 
          $reponse['statu'] = 'off';
		  $reponse['message'] = $this->lang->line('statu_not_connected');

          // on a notre objet $reponse (un array en fait)
          // reste juste à l'encoder en JSON et l'envoyer

           header('Content-Type: application/json');
          echo json_encode($reponse);
        }
        else
        { 
		 
		  //On prend son nombre de message et son nombre de notification
            foreach ($this->majs->notif_msg() as $row)
			{
			  $reponse['user_msg']     = $row->user_msg;
              $reponse['user_note']    = $row->user_note;
              $reponse['user_talk']    = $row->talk_recall;			  
			}
			
          // reste juste à l'encoder en JSON et l'envoyer
          header('Content-Type: application/json');
          echo json_encode($reponse);        
        }
	    //On met à jour la liste des membres connectés
	    $this->majs->user_connected();
	}
	
	
	
	//on prend en ajax le nom de compte de l'user
	public function my_name()
    {
	  echo $this->session->userdata('logged_in');
	}

	
}


?>