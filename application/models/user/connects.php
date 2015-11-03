<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Connects extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette fonction enregistere un nouvel utilisateur s'il n'est pas déjà enregistré une fois
public function hash_user()
    { 
    	$hash = random_string('alnum', 16);
	    
	    //We verify if the hash already exists in the database
	     $this->db->select('*');
	     $this->db->from('begoo_user');
	     $this->db->where('user_number',$hash); 
	     $query = $this->db->get();
	  
		    if($query->num_rows() == 0)//si non We insert this hash on the database
	        {
	        	$data = array(
                 'user_number'           => $hash,
                 'user_date_registration'=> time(),
                );

                $this->db->insert('begoo_user', $data);
                
                $this->sign_up('',$hash); //We create session

                return $hash;
		    }
		    else 
		    {
		       return $this->hash_user(); //We generate an other hash
			}		
	}



	public function hash_name($hash,$name) //Here We save the username associate to the hash
    { 
    	 
	    $this->db->where('user_number', $hash);
        $this->db->update('begoo_user', array('username'=>$name)); 

        $this->sign_up($name,$hash); //We create session


        $this->db->select('username,user_number');
	    $this->db->from('begoo_user');
	    $query = $this->db->get();

        //We generate a json file of all user
        $fp = fopen('./assets/node/user_list.json', 'w');
        fwrite($fp, json_encode($query->result_array()));
        fclose($fp);
	}



public function sign_up($username,$hash)
    {
    	 $user_data = array(
                   'username'         => $username,
                   'user_id'          => $hash,
                   'user_number'      => $hash,
                   'logged_in'        => TRUE,
                   );
        $this->session->set_userdata($user_data);
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