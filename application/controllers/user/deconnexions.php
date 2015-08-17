<?php
class Deconnexions extends CI_Controller 
{

    function __construct()
    {
       // Call the Controller constructor
      parent::__construct();
	  
	 //On charge les helpers qui vont aider lors de le redirection 
	 $this->load->helper('url'); 
	}

//Pas grand chose à faire ici: on détruit les sessions	 
function index()
    {  
	 
          $this->load->library('session');
		  
          //on détruit les sesssiosn et puis c'est tout
		  $this->session->sess_destroy();
		  
		  
		  //on affiche la page de confirmation  de l'action
		  redirect('','refresh');   
    }
	 
	 
}

?>