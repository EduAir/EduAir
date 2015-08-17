<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
	
	
	

	//Cette fonction envoi un notification a un utilisateur
public function notify_user($message,$url,$user,$type_note)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	              $data = array(
                  'notification_user'          => $user,
                  'notification_msg'           => $message,
			      'notification_url'           => $url,
				  'notification_lu'            => 1,
                  'notification_timestamp'     => time(),
			      'notification_logo'          => $type_note				  
                  );

                  $this->db->insert('begoo_notification', $data); 
			   
			     $this->db->query('UPDATE begoo_user SET user_note = user_note + 1 WHERE user_number ="'.$user.'"');				
				
		}          
	} 
	
	

	//Cette fonction liste tous les contacts d'un utilisateur
public function all_contact($user)
    {     
	     $this->db->select('*');
	     $this->db->from('begoo_friends');
         $this->db->where('user_id',$user);
   
	     $query = $this->db->get();
	  
		    if($query->num_rows()!== 0)
	        { 
              return $query->result_array();
		    }
			else
			{
			 return false;
			}
	}



//Cette fonction regerde si un user fait parti d la liste des amis de l'autre
public function if_my_friend($me,$numero)
    { 
      $this->db->select('friend_name');
	  $this->db->from('begoo_friends');
	  $this->db->where('user_number',$me);
      $this->db->where('friend_number',$numero);
	  $this->db->limit(1);
	  $query = $this->db->get();
        
		if($query->num_rows() > 0)
	    {
          $row = $query->row();
          $name = $row->friend_name;
 
            if($name!==$numero)//si son nom est connu
            {
             $tableau = array(1,$name);
			 
			 return $tableau;
            }
            else
            {
			  $username = $this->is_user($numero);//on regarde s'il est membre
			  
			    if($username)
	            {
			      //on met à jour son nom
				  $this->db->where('friend_number', $numero);
                  $this->db->update('begoo_friends', array('friend_name' => $username)); 
				  
				  $tableau = array(1,$username);
				  
			      return $tableau;
				}
				else
				{
			     $tableau = array(0,$name);			 
			 
			     return $tableau;
				}
			}			
        }
		else
		{
	      $friend_name = $this->my_niga($me,$numero);//on l'inscrit
		  
		  $tableau = array(0,$friend_name);
			 
		  return $tableau;
		}
	} 



//Cette fonction inscrit un ami dans une liste d'ami
public function add_friend($name,$phone)
    {
        $this->db->select('friend_number');		
	    $this->db->where('user_id',$this->session->userdata('user_id')); 
	    $this->db->where('friend_number',$phone); 
		$this->db->from('begoo_friends');
		$this->db->limit(1);
		$query = $this->db->get();
	     	  
		if($query->num_rows() == 0)
	    {		
           $data = array(
               'user_id'            => $this->session->userdata('user_id'),
               'friend_number'      => $phone,
		       'friend_name'        => $name,
            );

            $this->db->insert('begoo_friends',$data);
        }
        	
	  return true;	  
	} 



 
   //Cette fonction vérifie si l'user passé en paramtre est inscrit
public function is_user($user)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
         $this->db->select('last_username');		
	     $this->db->where('user_number',$user); 
		 $this->db->from('begoo_user');
		 $this->db->limit(1);
		 $query = $this->db->get();
	     	  
		    if($query->num_rows() > 0)
	        {
			  $row = $query->row();  		
		      
			  return $row->last_username;//on renvoi le nom
		    }
			else
			{
              return false;
			}
		}          
	} 


//Cette fonction renvoi le nom de l'inscrit
public function his_name($user)
    { 
	   $name = $this->is_user($user);//on regarde s'il est membre
	   
	    if($name)
		{
		  //je récupère son nom
		  return $name;
		}
		else//je l'inscrit
		{
		  $this->connects->if_exist($user);
		  
		 return false;
        }        
	}

 //Ici je copie tous les numéros de télépone des usr.
public function all_user_num()
    { 
	 //On cherche s'il s'est déjà connecté une fois
	 
	  $this->db->select('user_number');
      $query = $this->db->get('begoo_user');
	  
		if($query->num_rows() > 0)//si oui on retourne on renvoii true
	    {
		  return $query->result_array();
		}
    }




public function critik($text)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	        $data = array(
                  'text'          => $text,				  
                  );

            $this->db->insert('begoo_critik', $data); 		
		}          
	} 



//Ici je compte tous les utilisateurs inscrits.
public function nbre_user()
    { 
	   return $this->db->count_all_results('begoo_user');
    }	  	

}
?>	