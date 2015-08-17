<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Majs extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette fonction compte son nombre de message et son nombre de notification
public function notif_msg()
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	     //On cherche s'il s'est déjà connecté une fois
	     $this->db->select('user_msg,user_note,talk_recall');
	     $this->db->from('begoo_user');
	     $this->db->where('user_id',$this->session->userdata('user_id')); 
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)
	        {  		
		        return $query->result();
		    }
		}          
	}
	
	
	
//Cette fonction met à jour la liste des membres connectés
public function user_connected()
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	     //On cherche s'il ya son nom dans la liste des connectés
	    
	     $this->db->where('user_number',$this->session->userdata('numero'));
		 $this->db->from('begoo_connected'); 
	     $verdict = $this->db->count_all_results();
		    if($verdict == 0)//Sil n'est pas dans cette liste
	        {  
			  //On l'inscrit dans cette liste
		       $data = array(
                'user_number'          => $this->session->userdata('numero'),
                'user_timer'       => time(),
				'user_id'       => $this->session->userdata('user_id')
                );

                $this->db->insert('begoo_connected', $data);
		    }
			
			if($verdict > 0)//Sil est dans cette liste
	        {  
			  //On met son timestamp à jour
		       $data = array(
                'user_timer'       => time()
                );
             $this->db->where('user_number', $this->session->userdata('numero'));
             $this->db->update('begoo_connected', $data);             
		    }
		}

      //Ici on met à jour la liste des membres connectés(On supprime tous les membres connectés il ya plus de 5 minutes)
	    
        $this->db->delete('begoo_connected', array('user_timer <' =>time()-300));//Et pi c'est tout	  
	}


	
//regardon si cette user $numero est connecté
public function if_connect($numero)
    { 
	 //On cherche s'il ya son nom dans la liste des connectés
	     $this->db->where('user_number',$numero); 
		 $this->db->from('begoo_connected');
	     
		 $response = $this->db->count_all_results();
	    
		    if($response == 0)//Sil n'est pas dans cette liste
	        {  
			  return false;
		    }
			
			if($response > 0)//Sil est dans cette liste
	        {  
			 return true;            
		    }
			else
			{
			  return false;
			}
	}
	
}
?>	