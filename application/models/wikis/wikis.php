<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wikis extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
	
	
	

		
	//Cette fonction regarde s'il ya de nouveaux commentaires pour un talk
public function if_new_com($my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    $id_follow = $this->his_id_follower($this->session->userdata('user_id'));
			
            //On compte le nombre de commentaire de ce talk
            $this->db->where('id_follow',$id_follow);
			$this->db->where('timestamp >',$my_time);
			$this->db->where('user_id <>',$this->session->userdata('user_id'));
            $this->db->from('begoo_wiki_chat');
            $nbre_com = $this->db->count_all_results();	
	  
		    if($nbre_com > 0)
	        { 		  
		     return 'yes';//Oui il ya de nouveaux commentaires
		    }
        }			
	}


	
//Cette fonction prend les tous les nouveaux commentaires de talk nons lu par l'user connecté
public function new_com($my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
            $id_follow = $this->his_id_follower($this->session->userdata('user_id'));
			
	        $this->db->select('user_name,msg');
	        $this->db->from('begoo_wiki_chat');
	        $this->db->where('id_follow',$id_follow); 
			$this->db->where('timestamp >',$my_time);
			$this->db->where('user_id <>',$this->session->userdata('user_id'));
			$this->db->order_by("timestamp");
	        $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        { 
              $reponse = $query->result_array();
			  
		     return $reponse;
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	
	
	
	
	
	
//Cette fonction prend les tout nouveau commentaire de talk nons lu par l'user connecté
public function new_com_aff($id_follow,$my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	        $this->db->select('*');
	        $this->db->from('begoo_wiki_chat');
	        $this->db->where('id_follow',$id_follow); 
			$this->db->where('timestamp >',$my_time);
	        $this->db->order_by("timestamp");
		    $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        { 
             $reponse = $query->result();
			  
		     return $reponse;
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	


	
	//Cette fonction renvoi l'id du chat wiki sur le quel est branchez l'utilisateur
public function wiki_followed()
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
	     $this->db->select('id_follow');
	     $this->db->from('begoo_wiki_follower');
		 $this->db->where('follower',$this->session->userdata('user_id'));
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)
			{		
	         $row = $query->row();
             return $row->id_follow;
			}
		}
	}	
	



	
//Cette fonction prévient les amis de l'user connecté pour action effectuée
public function prevent_my_friends($message,$url)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
		   $friends = $this->users->all_contact($this->session->userdata('numero'));
	
	        if($friends!==false)//Sije trouve ses amis ,je les fait une notification
	        {	
                foreach($friends as $my_friends)
			    {
			      $data = array(
                  'notification_user'          => $my_friends->friend,
                  'notification_msg'           => $message,
			      'notification_url'           => $url,
				  'notification_lu'            => 1,
                  'notification_timestamp'     => time(),
			      'notification_logo'          => 1				  
                  );

                  $this->db->insert('begoo_notification', $data); 
			   
			     $this->db->query('UPDATE begoo_user SET user_note = user_note + 1 WHERE user_number ="'.$my_friends->friend.'"');				
				}
		    }
		}
	}	

	
	
//Ici explose un titre donné qui a des underscore et on renvoi
public function explodeIt_and_FeelPAgeId($url_page)
    {  
      //on explose la chaine pour ressortir le titre de  la page
	  $page_url_expl = explode('/',$url_page);
	   
	  //on compte le nombre de champ
	  $nbre_champ = count($page_url_expl);
	   
	  //on ressort le titre de la page sans espace de nom
	  $title = $page_url_expl[$nbre_champ-1];
			 
	  return $title;		
	}	
	
	
	
 //on cris "FOLLOW ME"
public function follow_me()
    { 
	 //On l'enlève de la liste des follower s'il est là
	 $verification_id_dollow = $this->his_id_follower($this->session->userdata('user_id'));
	 
	    if($verification_id_dollow)//s'il suivait déjà quelqu'un
		{
		    $this->db->delete('begoo_wiki_follower', array('follower' => $this->session->userdata('user_id')));//on le supprime des followers
        }	
		
	   //sil n'est pas déjà suivi je le fait suivre   		
	   $this->db->where('follow_user',$this->session->userdata('user_id'));
       $this->db->from('begoo_wiki_follow');
       
	   $result = $this->db->count_all_results();
		  
	    if($result == 0)//s'il oui 
	    { 
          //je fait qu'on le suive
			$data = array(
                  'follow_user'     => $this->session->userdata('user_id')
                  );

          $this->db->insert('begoo_wiki_follow', $data);
		  
		  
		  //on le fait suivre lui-même pour pour permmetre le chat
		  $data = array(
                       'follower'     => $this->session->userdata('user_id'),
					   'id_follow'    => 'page_url_'.$this->session->userdata('user_id')
                        ); 
						
		  $this->db->insert('begoo_wiki_follower', $data);
                 
          $reponse = 'yes';

          return $reponse;      				  
	    }
	}




//Supprimons la liste de tous ceux qui ne sont pas connecté et qui sont suivi ou alors on cris "NE ME SUIVEZ PLUS"
public function leader_del($user)
    { 	 
	    // on le supprime de la liste de ceux qui sont suivis
		$this->db->delete('begoo_wiki_follow', array('follow_user' => $user));

		//On le supprime de la liste des follower
		$this->db->delete('begoo_wiki_follower', array('id_follow' => 'page_url_'.$user)); 
	}
	
	
	
	
	
//on le fait suivre quelqu'un
public function follow_him($user)
    { 
	   //je regarde si le $user est dans la liste des suivi
	    $this->db->select('id_follow');
        $this->db->from('begoo_wiki_follow');
		$this->db->where('follow_user',$user);
		$this->db->limit(1);
		$query = $this->db->get();
		
		$verdict = $query->num_rows();
           
		    if($verdict > 0) //si 'est le cas je le branche sur lui
			{			  
              //je je me déconnecte de toute personne que je suivais avant
	          $this->db->select('id_follow');
              $this->db->from('begoo_wiki_follower');
		      $this->db->where('follower',$this->session->userdata('user_id'));
		      $this->db->limit(1);
		      $query_if = $this->db->get();
		
		      $verdict = $query_if->num_rows();
			    
				if($verdict > 0)//si il suivait une personne
				{
				    foreach ($query_if->result() as $row)
                    {
					  //je le supprime des followers de cette personne
					  $this->db->delete('begoo_wiki_follower', array('follower' => $this->session->userdata('user_id')));
					}
				}
				
				//je mebranche sur la nouvelle personne maintenant		  
			    foreach ($query->result() as $row)
                {
				 //je l'ajoute dans la liste des followers de la personne suivi
				       $data = array(
                       'follower'     => $this->session->userdata('user_id'),
					   'id_follow'    => $row->id_follow
                        ); 
						
				 $this->db->insert('begoo_wiki_follower', $data);

                }
					
			  return true;					  
		    }
	}



//on le fait suivre ne plus suivre quelqu'un
public function stop_follow_him($user)
    {	   
	  //j'enlève 1 ssur le nombre de followers de l'user
	  $query = $this->db->query('SELECT id_follow FROM begoo_wiki_follow WHERE follow_user ="'.$user.'"');
		
		if ($query->num_rows() > 0)
        {
          $row = $query->row();
          
		  $reponse = $row->id_follow;
		    
		  //j'efface mon nom des followers
          $this->db->delete('begoo_wiki_follower', array('follower' => $this->session->userdata('user_id')));
	   
		 return true;		
		}
	 return true;
	} 	


	
//je renvoi l'id du chat d'un user qui suit dans la table des follower
public function his_id_follower($user)
    { 
	     $this->db->select('id_follow');
	     $this->db->from('begoo_wiki_follower');
		 $this->db->where('follower',$user);
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)
			{		
	         $row = $query->row();
             return $row->id_follow;
			}	
	}
	
	
	
//je sélectionne les dernieres personnes qui veulent qu'on les suivent
public function leader()
    { 
	  //on récupère maintenant la liste de tous ceux qui sont suivi
	  $this->db->select('begoo_wiki_follow.id_follow,begoo_wiki_follow.follow_user,begoo_user.last_username');
	  $this->db->from('begoo_wiki_follow');
	  $this->db->join('begoo_user','begoo_wiki_follow.follow_user = begoo_user.user_id','left');
	  $this->db->where('begoo_wiki_follow.follow_user <>',$this->session->userdata('user_id'));
	  $this->db->order_by("begoo_wiki_follow.id_follow", "desc");
	  $this->db->limit(5);
	  $query = $this->db->get();
	 
		if($query->num_rows() > 0)
		{		
         return $query->result();
		}	
	}
	
	

	
	
//je sélectionne les dernieres personnes qui veulent qu'on les suivent dont le nom ou le numérocommence par...
public function find_leader($chaine)
    { 
	  $this->db->select('begoo_wiki_follow.id_follow,begoo_wiki_follow.follow_user,begoo_user.last_username,begoo_user.user_number');
	  $this->db->from('begoo_wiki_follow');
	  $this->db->join('begoo_user','begoo_wiki_follow.follow_user = begoo_user.user_id','left');
	  $this->db->like('begoo_user.user_number',$chaine);
	  $this->db->or_like('begoo_user.last_username',$chaine);
	  $this->db->where('begoo_user.user_id <>',$this->session->userdata('user_id'));
	  $this->db->order_by("begoo_wiki_follow.id_follow","desc");
	  $query = $this->db->get();
	  
		if($query->num_rows() > 0)
		{		
         return $query->result();
		}	
	}	
}
?>	