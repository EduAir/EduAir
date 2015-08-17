<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hi extends CI_Controller {

	function __construct()
	{
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
		
		
	  //le helper de texte pour limiter les chaines de caractère lors de certains l'affichages
	    $this->load->helper('text');
		
		//C'est cette ligne de code qui détecte la langue du navigateur et affiche le site dans la langue correspondante
		$this->lang->load('form', $this->config->item('language'));
		$this->lang->load('statu', $this->config->item('language'));
		$this->lang->load('bulle', $this->config->item('language'));
		
		//On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');
	}

	function index()
	{  
	   //On fait un array des données à transmettre aux entetes et corps de page
		$data = array(
            'title'    => 'Begoo',
		    'h1'       => 'Géronimo',
			'top'      => 'hi'
                           );
			 
		$this->parser->parse('header/header_hi',$data);
		$this->parser->parse('hi',$data);
		$this->parser->parse('footer/footer_hi',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */