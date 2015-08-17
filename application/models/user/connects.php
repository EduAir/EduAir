<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Connects extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette fonction enregistere un nouvel utilisateur s'il n'est pas déjà enregistré une fois
public function connect($number,$pass)
    { 
	     //On cherche s'il s'est déjà connecté une fois
	     $this->db->select('*');
	     $this->db->from('begoo_user');
	     $this->db->where('user_number', $number); 
	     $this->db->where('user_pass',$pass); 
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)//si oui on crée ses sessions
	        {
		        foreach ($query->result() as $row)
                {
		         $user_data = array(
                   'username'         => $row->username,
				   'user_id'          => $row->user_id,
                   'user_number'      => $number,
                   'logged_in'        => TRUE,
                   'user_level'       => $row->user_level,
				   'user_msg'         => $row->user_msg,
				   'user_note'        => $row->user_note,
				   'user_last_connect'=> $row->user_last_connect,
				   'user_bannis'      => $row->user_bannis
                   );
		          $this->session->set_userdata($user_data);
                }

                return $query->result_array();
		    }
		    else 
		    {
		     return false;
			}		
	}


//Cette fonction enregistere un nouvel utilisateur s'il n'est pas déjà enregistré une fois
public function get_my_connection_data()
    { 
	     //On cherche s'il s'est déjà connecté une fois
	     $this->db->select('user_id,user_bannis,user_id,user_level,user_msg,user_note,user_number,username,user_last_connect');
	     $this->db->from('begoo_user');
	     $this->db->where('user_id', $this->session->userdata('user_id')); 
	     $query = $this->db->get();

	        foreach ($query->result() as $row)
            {
		         $user_data = array(
                   'username'         => $row->username,
				   'user_id'          => $row->user_id,
                   'user_number'      => $row->user_number,
                   'logged_in'        => TRUE,
                   'user_level'       => $row->user_level,
				   'user_msg'         => $row->user_msg,
				   'user_note'        => $row->user_note,
				   'user_last_connect'=> $row->user_last_connect,
				   'user_bannis'      => $row->user_bannis
                   );
		          $this->session->set_userdata($user_data);
            }
	  
		return $query->result_array();	
	}


public function sign_up($username,$pass,$number,$filiere)
    {
    	//We see if the number is busy
	     $this->db->select('user_pass');
	     $this->db->from('begoo_user');
	     $this->db->where('user_number', $number); 
	     $query = $this->db->get();
	 
		    if($query->num_rows() > 0)//Oups!
	        {
	        	return false;
	        }else{

	             $data = array(
                 'user_number'           => $number,
                 'username'              => $username,
                 'user_pass'             => $pass,
                 'user_level'            => $filiere,
                 'user_date_registration'=> time(),
                  );

                $this->db->insert('begoo_user', $data); 
               
                return $this->connect($number,$pass);
            }
    }




    public function edit_data($username,$number,$filiere)
        {

        	if($this->session->userdata('logged_in')){
            
                $data = array(
                          'user_number'           => $number,
                          'username'              => $username,
                          'user_level'            => $filiere,
                        );

                $this->db->where('user_number', $this->session->userdata('user_number'));
                $this->db->update('begoo_user', $data);
                return $this->get_my_connection_data();
        	}
        }




    public function edit_pass($pass)
    {
    	
        if($this->session->userdata('logged_in')){
            
            $data = array(
                          'user_pass'            => $pass,
                        );

            $this->db->where('user_number', $this->session->userdata('user_number'));
            $this->db->update('begoo_user', $data);
            return $this->get_my_connection_data();
        }
    }
    	
	
	

	
	
	
}
?>	