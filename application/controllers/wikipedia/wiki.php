<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends CI_Controller 
{

public function __construct()
	{	  
	  // Call the Controller constructor
     parent::__construct();
	  
	  $this->load->helper('url');
	  	
		//on charge les sessions
	    $this->load->library('session');
			
		//on charge la librairie de parceage pour templeter la vue
	    $this->load->library('parser');
		
		$this->load->library('form_validation');
			
	  //le helper de texte pour limiter les chaines de caractère lors de certains l'affichages
	    $this->load->helper('text');

	    $this->load->helper('string');

      $this->load->helper('file');
		
		//C'est cette ligne de code qui détecte la langue du navigateur et affiche le site dans la langue correspondante
		$this->lang->load('form', $this->config->item('language'));
		$this->lang->load('statu', $this->config->item('language'));
		$this->lang->load('bulle', $this->config->item('language'));
		$this->lang->load('note', $this->config->item('language'));

        //On appelle la fonction qui s'occuper de la messagerie
	    $this->load->model('msg/msgs');	
		
        //On appelle la fonction qui s'occuper des membres connectés
	    $this->load->model('user/connects');
		$this->load->model('user/users');
		
		//On appelle la fonction qui de la recherche
	    $this->load->model('wikis/wikis');
		$this->load->model('search/searchs');
	}

	
	 	 
public function hi()
	{ 
		
        if($this->session->userdata('logged_in'))//si on est connecté on va direct à la page d'accueil
	    {
	        redirect('','refresh');//et la redirection
		}
		else // si c'est pas le cas,on affiche le formulaire de connexion où on vérifie aussi la compatibilité du navigateur
		{ 
		 //On fait un array des données à transmettre aux entetes et corps de page
		 $data = array(
        'title'    => 'Kwiizi',
		    'h1'       => 'Kwiizi',
			'top'      => 'wikipedia',
			'nbre_user'=> $this->users->nbre_user()
                           );
			 
		 $this->parser->parse('page/all_pages_first',$data);
		 $this->parser->parse('wikipedia/first',$data);
		 $this->parser->parse('footer/footer_wiki',$data);
		}
	}
	
	
public function index()
	{ 	
	    //On fait un array des données à transmettre aux entetes et corps de page
		$data = array(
            'title'    => 'Kwiizi',
		    'h1'       => 'Kwiizi',
			'top'      => 'wikipedia',
        );
			 
		 $this->parser->parse('page/all_pages',$data);
		 $this->parser->parse('wikipedia/wikipedia',$data);
		 $this->parser->parse('footer/footer_wiki',$data);
	}



  public function pinooy($room,$called,$my_number,$my_username)
  {
    if($this->session->userdata('logged_in')){
       //On fait un array des données à transmettre aux entetes et corps de page
      $data = array(
      'title'       => 'Kwiizi',
      'h1'          => 'Kwiizi',
      'top'         => 'wikipedia',
      'room'        => $room,
      'called'      => $called,
      'my_number'   => $my_number,
      'my_username' => $my_username
        );
       
     $this->parser->parse('header/header',$data);
     $this->parser->parse('pinooy/pinooy',$data);
     $this->parser->parse('footer/footer_wiki',$data); 
    }  
     
  }



	
	
//Ici on affiche le fichier js qui gère l'affichage du wiki
public function page_content()
    {
	   echo '<script type="text/javascript" src="'.base_url().'assets/js/wiki_content.js"></script>';
	}
	
	
	
	
//Ici on casse url pour ressortir le titre de la page qui va servir à consulter la page sur le wiki
public function explodeIt_and_FeelPAgeId()
    {
	   //on définit les règles de succès: 	      
	  $this->form_validation->set_rules('url_article','','required|trim|xss_clean');
		 	
	    if($this->form_validation->run())
		{				
		 echo $this->wikis->explodeIt_and_FeelPAgeId($this->input->post('url_article'));//terminé!
		}                     
	}
	


	
	
//Feel the id of a Follow Me session
	public function get_id_follow_me()
	{
		echo random_string('alpha',8);
	}
   
   
   //CEtte fonction sert de ping pour voir si la webapp est connecté au serveur
    public function ping_it()
    {
     echo 'ok'; //c'est tout!
    }



    public function get_zim($id,$zim){ 

    	echo str_replace('../../',HOST_WIKI.'/'.$zim.'/',file_get_contents('http://'.HOSTER.':'.KIWIX_PORT.'/'.$zim.'/A/'.$id.'/index.html'));
    }

    public function get_zim_gutenberg($zim,$letter,$title){ //http://localhost:8100/gutenberg_fr_all_10_2014/A/Formules%20pour%20l'esprit.20013.html

    	echo file_get_contents(HOST_WIKI.'/'.$zim.'/'.$letter.'/'.$title);
    }

    
    
    public function list_a_zim($zim){
  
        $response = file_get_contents('http://'.HOSTER.':'.KIWIX_PORT.'/'.$zim.'/');

        $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");

    	$document = new DOMDocument();
    	$document->preserveWhiteSpace = false;
        $document->formatOutput       = true;
       
        if($response)
        {
            libxml_use_internal_errors(true);
            $document->loadHTML($response);

            //On obtient le contenu de l'article
            $tags           = $document->getElementById('grid-container');
         
            $full_text      = $this->DOMinnerHTML($tags);
            
            libxml_clear_errors();

            $reponses['page_text'] = $full_text;
  
	        // on a notre objet $reponse (un array en fait)
            // reste juste à l'encoder en JSON et l'envoyer
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($reponses); 
        } 
    } 


    //Cette fonction exploite le moteur derecherche de Kiwix
    public function record_image(){

       //on définit les règles de succès:         
      $this->form_validation->set_rules('image_src','image_src','required|trim');
      $this->form_validation->set_rules('image_article','image_article','required|trim');
      $this->form_validation->set_rules('json_zim','json_zim','required|trim');

      if($this->form_validation->run()) 
      { 
        if($this->input->post("json_zim")=='wikipedia'){
          $json_zim = 'image.json';
        }else{
           $json_zim = 'image_medecine.json';
        }
        
        $str_data = file_get_contents(base_url().'assets/json/'.$json_zim);
        
        $data = json_decode($str_data,true);
 
        array_push($data["image"]["page_url"],$this->input->post("image_article"));
        array_push($data["image"]["src"],$this->input->post("image_src"));
      
        $data["image"]["number_image"] = $data["image"]["number_image"] +1;

        write_file('./assets/json/'.$json_zim,json_encode($data));
      }
    }



    //Cette fonction va chercher les articles de wikipedia sur Kiwix
    public function get_article(){

    	 //on définit les règles de succès: 	      
	    $this->form_validation->set_rules('page_url','page_url','required|trim');
      $this->form_validation->set_rules('type','type','required|trim');
      $this->form_validation->set_rules('witch_zim','witch_zim','required|trim');

	    if($this->form_validation->run()) 
		  { 	            
          $response = file_get_contents(HOST_WIKI.str_replace(" ","%20",str_replace("'","%27",$this->input->post("page_url"))));

          $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");

    	    $document = new DOMDocument();

    	    $document->preserveWhiteSpace   = false;
          $document->formatOutput         = true;
       
            if($response)
            {
              libxml_use_internal_errors(true);
              $document->loadHTML($response);

              //On obtient le titre de la page
                $list = $document->getElementsByTagName("title");
                if ($list->length > 0) {

                    $title_text = $list->item(0)->textContent;
                }

                //On obtient le contenu de l'article
              $tags           = $document->getElementById('content');
         
              $full_text      = $this->DOMinnerHTML($tags,$this->input->post("witch_zim"));
            
              libxml_clear_errors();
            }		  
		}else{

			$response = file_get_contents(KIWIX.'/A/html/W/i/k/i/Wikipédia.html');
			$response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");

    	    $document = new DOMDocument();
    	    $document->preserveWhiteSpace = false;
            $document->formatOutput       = true;
       
            if($response)
            {
            	
              libxml_use_internal_errors(true);
              $document->loadHTML($response);

                //On obtient le titre de la page
                $list = $document->getElementsByTagName("title");
                
                if($list->length > 0) {
                     $title_text = $list->item(0)->textContent;
                }

                //On obtient le contenu de l'article
              $tags        = $document->getElementById('bodyContent');
         
              $full_text   = $this->DOMinnerHTML($tags,'wikipedia');
              
              libxml_clear_errors();
            }
		}

		$reponses['page_title']              = $title_text;
		
		if($this->input->post("type")!='none'){
		    
		    $reponses['page_text']               = str_replace('../../',HOST_WIKI.'/'.$this->input->post("type").'/',$full_text);
		}else{

		    $reponses['page_text']               = $full_text;
		}

  
	    // on a notre objet $reponse (un array en fait)
        // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($reponses);  
    }


   


      //On extrait uniquement le contenu
    function DOMinnerHTML($element,$witch_zim) 
    { 
       $innerHTML = "";

       $children = $element->childNodes; 
       
        foreach ($children as $child) 
        { 
           $tmp_dom = new DOMDocument(); 
           $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
           $innerHTML.=trim($tmp_dom->saveHTML()); 
        }

        if ($witch_zim=='wikipedia') {
          return str_replace('/'.ZIM,KIWIX,$innerHTML);
        } else {              
          return str_replace('../',HOST_WIKI.'/wikipedia_en_medicine_09_2014_2/',$innerHTML);
        }

    } 




    //Cette fonction exploite le moteur derecherche de Kiwix
    public function search(){

    	 //on définit les règles de succès: 	      
      $this->form_validation->set_rules('string','string','required|trim');
      $this->form_validation->set_rules('zim','zim','required|trim');
	    $this->form_validation->set_rules('type_zim','type_zim','required|trim');
  	
	    if($this->form_validation->run()) 
		  { 
        //$this->record_search($this->input->post('string')); //Store search on a json file

		    $string = str_replace(' ','+',$this->input->post('string'));//On remplace les espaces par des +

		    $string = str_replace("'","%27",$string);//Des apostrophes par des %27

		    $this->go_and_search($string,$this->input->post('zim'),$this->input->post('type_zim'));  
		  }else{

			  $header = false;

			  $footer = false;

            $result = validation_errors();

            $statu  = 'fail';

            $reponses['header']              = $header;
		        $reponses['footer']              = $footer;
		        $reponses['result']              = $result;
		        $reponses['statu']               = $statu;
  
	        // on a notre objet $reponse (un array en fait)
            // reste juste à l'encoder en JSON et l'envoyer
            header('Content-Type: application/json');
            echo json_encode($reponses); 
		  } 
    }


    
    //Store search on a json file
    function record_search($string){ 

      $str_data = file_get_contents(base_url().'assets/json/search.json');

      $data = json_decode($str_data,true);
 
      array_push($data["search"]["search"],$string);
      array_push($data["search"]["user"],$this->session->userdata('user_id'));
      
      $data["search"]["number_request"] = $data["search"]["number_request"] +1;

      write_file('./assets/json/search.json',json_encode($data));
    }


     

    function go_and_search($string,$zim,$type){

      //We get the zim file now
      $str_data = file_get_contents(base_url().'assets/json/zim.json');

      if($zim=='TED'){ //If it this TED article, we get the json file

        $data = json_decode($str_data,true);

        $reponses['result']            = $data['zim_list']['TED'];
        $reponses['zim']               = $zim;
        $reponses['type_zim']          = $type;
  
        header('Content-Type: application/json');
        echo json_encode($reponses); 

      }else{//If it this order Zim file

          $data = json_decode($str_data,true);

          $response = file_get_contents('http://'.HOSTER.':'.KIWIX_PORT.'/search?content='.$data['zim_list']["$zim"].'&pattern='.$string.'+');

          $header   = $this->get_class_by_name($response,'header');

          $footer   = $this->get_class_by_name($response,'footer');

          $result   = $this->get_class_by_name($response,'results');

          $response = array('header' =>$header,'footer'=>$footer,'result'=>$result,'zim'=>$zim,'zim_file'=>$data['zim_list']["$zim"]);

          header('Content-Type: application/json');
          echo json_encode($response); 
      }
    }


    function get_class_by_name($text,$classname) //On prend le contenu dune class par son DOM
    {
	    $dom = new DomDocument();

      libxml_use_internal_errors(true);

      $dom->loadHTML($text);

      $finder = new DomXPath($dom);

      $nodes  = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");


        foreach($nodes as $node) {

        	if($classname=='results'||$classname=='footer'){

        		return str_replace('/'.ZIM,KIWIX,$node->C14N());
        	}else{

        		return $node->nodeValue;
        	}   
        }
     libxml_clear_errors();
    }



    public function search_plus(){

    	 //on définit les règles de succès: 	      
	    $this->form_validation->set_rules('url','url','required|trim');
  	
	    if($this->form_validation->run()) 
		{ 	          
		   
		   $url = str_replace('http://'.HOSTER,'http://'.HOSTER.':'.KIWIX_PORT,str_replace('http://'.DOMAINE_NAME,'http://'.HOSTER,str_replace("'","%27",str_replace(' ','+',$this->input->post('url')))));//Des apostrophes par des %27
		   
           $response = file_get_contents($url);
       
            if($response)
            { 

               $header = $this->get_class_by_name($response,'header');

               $footer = $this->get_class_by_name($response,'footer');

               $result = $this->get_class_by_name($response,'results');

               $statu  = 'success';

            }else{

               $header = false;

               $footer = false;

               $result = $this->lang->line('form_error');

               $statu  = 'fail';
            }		  
		}else{

			$header = false;

			$footer = false;

            $result = validation_errors();

            $statu  = 'fail';
		}

		$reponses['header']              = $header;
		$reponses['footer']              = $footer;
		$reponses['result']              = $result;
		$reponses['statu']               = $statu;
  
	    // on a notre objet $reponse (un array en fait)
        // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json');
        echo json_encode($reponses);  
    }



    //fait un test de ping de onnexion
    public function ping(){

    	echo 'on_line';
    }


      //Cette fonction va chercher les articles hasard de wikipedia sur Kiwix
    public function get_random_article(){ //http://library.kiwix.org/random?content=wiktionary_fr_all
	            
            $response = file_get_contents('http://'.HOSTER.':'.KIWIX_PORT.'/random?content='.ZIM);
            $response = mb_convert_encoding($response, 'HTML-ENTITIES', "UTF-8");

    	    $document = new DOMDocument();
    	    $document->preserveWhiteSpace = false;
            $document->formatOutput       = true;
       
            if($response)
            {
              libxml_use_internal_errors(true);
              $document->loadHTML($response);

              //On obtient le titre de la page
                $list = $document->getElementsByTagName("title");
                if ($list->length > 0) {
                     $title_text = $list->item(0)->textContent;
                }

                //On obtient le contenu de l'article
              $tags           = $document->getElementById('bodyContent');
         
              $full_text      = $this->DOMinnerHTML($tags);
            
              libxml_clear_errors();
            }		  
		

		$reponses['page_title']              = $title_text;
		$reponses['page_text']               = $full_text;
  
	    // on a notre objet $reponse (un array en fait)
        // reste juste à l'encoder en JSON et l'envoyer
        header('Content-Type: application/json');
        echo json_encode($reponses);  
    }



    public function test(){

    	echo site_url();
    }


	
}


?>