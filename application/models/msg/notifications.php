<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 
	
//Cette fonction affiche un talk
public function ListeNote($nbre_msg)
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 	    
		  $this->db->select('notification_id,notification_user,notification_msg,notification_url,notification_timestamp,notification_logo');
	      $this->db->from('begoo_notification');
		  $this->db->where('notification_user',$this->session->userdata('numero'));
	      $this->db->order_by("notification_timestamp", "desc");                    					 
          $this->db->limit(50,$nbre_msg);
		  
		  $query_quoi = $this->db->get();
		  
		    if($query_quoi->num_rows()!==0)
	        {
			   //On met à zero le nombre de notification
			   $this->db->query('UPDATE begoo_user SET user_note = 0 WHERE user_number ="'.$this->session->userdata('numero').'"'); 
			   
			  return $query_quoi->result();
			}
			else
			{
			  return false;
			}
        }			
	
	}
	
	
///////////////////////////////////////////////////////////////////////////////	
	
//Cette fonction entre une nouvelle notification de pub
public function new_pub($message)
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
            
			 //on met le tout à la bdd
			
		  $data = array(
            'message'         => $message,
			'timestamp'       => date('d-m-Y H:i'),
			'timestamp_brute' => time(),
            ); 
						
		  $this->db->insert('begoo_wiki_notification_pub', $data);
           
		  return true;
        }			
	}
	
	
	
	
//Cette fonction affiche les pubs qui sont consultable en ligne
public function ListePub($nbre_msg)
    {    	    
		  $this->db->select('*');
	      $this->db->from('begoo_wiki_notification_pub');
	      $this->db->order_by("id", "desc");                    					 
          $this->db->limit($nbre_msg);
		  
		  $query_quoi = $this->db->get();
		  
		    if($query_quoi->num_rows()!==0)
	        {
			  return $query_quoi->result_array();
			}
			else
			{
			  return false;
			}   			
	}
	
	
	
	
	
	//Cette fonction affiche les pubs qui sont consultables hors ligne
public function ListePub_out($nbre_msg)
    {
		$this->db->select('message,slogan,timestamp,image');
	    $this->db->from('begoo_wiki_notification_pub');
		$this->db->where("out_line",1);
	    $this->db->order_by("id", "desc");                    					 
          $this->db->limit($nbre_msg);
		  
		  $query_quoi = $this->db->get();
		  
		    if($query_quoi->num_rows()!==0)
	        {
			  return $query_quoi->result_array();
			}
			else
			{
			  return false;
			}    			
	}
	
//Cette fonction compte le nombre de notification venu de moins il ya 10 jours
public function CountPub()
    {
	  $delai = 10; //delais de notification
	  
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
          //convertissons dabord 10 jours en seconde
		  $day_to_second = $delai *24*3600;
           
          $difference = time() - $day_to_second;
		  
		  $this->db->where('timestamp_brute >',$difference);
          $this->db->from('begoo_wiki_notification_pub');
         
		  return $this->db->count_all_results();	 
        }
        else
        {
           return 'connect_him';
        }		   
	}



	//Cette fonction compte le nombre de notification venu de moins il ya 10 jours
public function CountPub_out()
    {
	  $delai = 10; //delais de notification
	  
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
          //convertissons dabord 10 jours en seconde
		  $day_to_second = $delai *24*3600;
           
          $difference = time() - $day_to_second;
		  
		  $this->db->where('timestamp_brute >',$difference);
          $this->db->where('out_line',1);
          $this->db->from('begoo_wiki_notification_pub'); 

		  return $this->db->count_all_results();	 
        }
        else
        {
           return 'connect_him';
        }		   
	}	
	

	
}
?>	