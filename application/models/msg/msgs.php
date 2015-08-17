<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msgs extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette fonction envoi un nouveau message
public function incomingMessager($interloc_num,$sender_name,$sender_num,$message)
    { 
        //enregistre le message dans la table des messages recu
		$data = array(
                 'interloc_num'      => $interloc_num,
                 'sender_name'       => $sender_name,
				 'sender_num'        => $sender_num,
				 'message'           => $message,
                 'timestamp'         => time(),
                  );

         $this->db->insert('begoo_msg', $data); 
		 
		 $this->connects->if_exist($interloc_num);//je regarde dabord si l'user est un membre,si cest pas le cas je l'inscrit
		
		//On enregistre en update dans le nombre de message du recepteur
		 $this->db->query('UPDATE begoo_user SET user_msg = user_msg + 1 WHERE user_number ="'.$interloc_num.'"');

        return true;
	}

	

	

//Cette fonction affiche la liste des messages du membre connecté
public function MaListe_Msg()
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
		 $query = $this->db->get_where('begoo_msg', array('interloc_num' => $this->session->userdata('numero')));
	     
		    if ($query->num_rows() > 0)
            {
              return $query->result_array();
            }
        }			
	}
	
	
	
	//Cette fonction supprime la liste des messages du membre connecté
public function MaListe_Msg_del()
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
		   $this->db->delete('begoo_msg', array('interloc_num' => $this->session->userdata('numero')));
        }			
	}
	
	
	

}
?>	