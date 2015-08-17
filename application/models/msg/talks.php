<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Talks extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette insert une nouvelle discussion
public function insert_talk($talk,$user_talk)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    if($user_talk !=='')//si le talk est privé
			{	               
			     //On insert le message
		          $data = array(
                 'auteur_talk'       => $this->session->userdata('numero'),
                 'talk_talk'         => $talk,
				 'timestamp_talk'    => time(),
				 'favoris_talk'      => 0,
				 'comment_talk'      => 0,
				 'last_comment_talk' => 0,
				 'private'           => 1
                   );				   
		    }
			else
			{
	             //On insert le message
		         $data = array(
                 'auteur_talk'       => $this->session->userdata('numero'),
                 'talk_talk'         => $talk,
				 'timestamp_talk'    => time(),
				 'favoris_talk'      => 0,
				 'comment_talk'      => 0,
				 'last_comment_talk' => 0
                   );
		    }

          $this->db->insert('begoo_talk', $data);
		  
		  $last_insert = mysql_insert_id();
		  
		    if($user_talk !=='')//si le talk est privé
			{	  
		     //on prévient ses  contacts en privé donc
				$array_user = explode(',',$user_talk); // On transforme cette chaîne en array
				
				$i= 0;
				
				$limit = count($array_user);
				
                while($i<count($array_user))
                {
                  $user_contact = $array_user[$i];
				  
				  $user_contact = intval($user_contact);
					
					if(strlen($user_contact)>5 and strlen($user_contact)<30)//Si le numéro respecte le format requis de 30 maxi caractères numérique,c'est bon
					{
					  $user_contact = $this->connects->if_exist(intval($user_contact));				 
													
				      $this->db->query('UPDATE begoo_user SET talk_recall ="'.$last_insert.'",talk_recaller ="'.$this->session->userdata('numero').'" WHERE user_number ="'.$user_contact.'"');
					  
					  
					  //On insert les utilisateurs concerné dans liste de ceux permis de lire le talk
		              $data_2 = array(
                      'id_talk'           => $last_insert,
                      'number'            => $user_contact
                         );
				   
				      $this->db->insert('begoo_talk_private', $data_2);
					  
					  $this->follow($last_insert,$user_contact); //On les fait suivre le talk
					}
				 $i++;					
				}
				
				
				 //On insert l'utilisateur qui a créé le talk aussi dans liste de ceux permis de lire le talk
		          $data_1 = array(
                 'id_talk'           => $last_insert,
                 'number'            => $this->session->userdata('numero')
                   );
				   
				 $this->db->insert('begoo_talk_private', $data_1);
			}
		  		  
		    //On prévient ses amis de la discussion en notification
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_talk').'<br>'. character_limiter($talk, 20) ;
			 			
			 $url = '/msg/talk/talking/'.$last_insert;			
			
			 $this->prevent_my_friends($message,$url);
			 
			 
			 $this->favorite($last_insert);//On met directement ce talk en favoris pour luser
		  
		     $this->follow($last_insert,$this->session->userdata('numero')); //On le fait suivre le talk
			 
			  //et on dit que tout s'est bien passé
		  $result = array(true,$this->lang->line('statu_talk_good'));
		  
		  return $result;
		}
	}
	
	
	
	
	
//Cette insert un nouveau commentaire pour une discussion
public function insert_talk_com($id_talk,$talk_com)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	     //On insert le message
		  $data = array(
                 'user'                => $this->session->userdata('numero'),
                 'commentaire'         => $talk_com,
				 'timestamp'           => time() + 1,
				 'id_talk'             => $id_talk
                   );

          $this->db->insert('begoo_talk_comment', $data);
		   		  
		    //On prévient tous ceux qui suivent ce talk pour leur signaler qu'il ya une nouvelle réction:en notification
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_com_talk').'<br>'. character_limiter($talk_com, 20);
			
			 $url = '/msg/talk/talking/'.$id_talk;			
			
			 $this->prevent_friend_talk($message,$url,$id_talk);//Cette fonction prévient les interlocuteurs de l'user connecté pour action effectuée
			 
			 $this->follow($id_talk,$this->session->userdata('numero')); //On le fait suivre le talk
			 
			 $this->db->query('UPDATE begoo_talk SET comment_talk = comment_talk + 1,last_comment_talk = "'.time().'" WHERE id_talk ="'.$id_talk.'"');//On ajoute le nombre de commentaire du talk ainsi que la date du dernier commentaire
		  
		  //et on dit que tout s'est bien passé
		  $result = array(true,$this->lang->line('statu_talk_good'));
		  
		  return $result;
		}
	}
	
	
	
	
//Cette prend la derniere discussion de l'auteur
public function my_last_talk()
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	     //On fouille maintenant
	     $this->db->select('*');
	     $this->db->from('begoo_talk');
	     $this->db->where('auteur_talk',$this->session->userdata('numero')); 
	     $this->db->order_by("id_talk", "desc");
		 $this->db->limit(1);
	     $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        {  		
		     return $query->result();
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	
	
	
	
	
//Cette faonction affiche un talk particulier
public function talking($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		
		    //si la conersation est privée
			if($this->if_talk_private($id_talk))
			{
                //regardons si l'user est permit de consulter ce talk
                if($this->permit_talk($id_talk))
                {			
	              //On fouille maintenant
	              $this->db->select('*');
	              $this->db->from('begoo_talk');
	              $this->db->where('id_talk',$id_talk); 
	              $this->db->order_by("id_talk", "desc");
			    }
			    else
			    {
			      return false;
			    }
			}
			else
			{
			   //On fouille maintenant
	              $this->db->select('*');
	              $this->db->from('begoo_talk');
	              $this->db->where('id_talk',$id_talk); 
	              $this->db->order_by("id_talk", "desc");
			}
			
		  $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        {  		
		      return $query->result();
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	
	
	
	
//Cette fonction affiche un court extrait d'un talk particulier
public function talk_msg($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
            //regardons si l'user est permit de consulter ce talk
            if($this->permit_talk($id_talk))
            {			
	           //On fouille maintenant
	          $this->db->select('talk_talk');
	          $this->db->from('begoo_talk');
	          $this->db->where('id_talk',$id_talk); 
	          $query = $this->db->get();
	  
		        if($query->num_rows()!==0)
	            { 
                  $row = $query->row();				
		           
				  $talk = character_limiter($row->talk_talk,50);
				   
				  return $talk;
		        }
		    }
        }			
	}
	




//Cette faonction regarde si ona les permissions d'afficher un talk
public function permit_talk($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    if($this->if_talk_private($id_talk))
			{
             $this->db->where('id_talk',$id_talk);
			 $this->db->where('number',$this->session->userdata('numero'));
             $this->db->from('begoo_talk_private');
             $resultat = $this->db->count_all_results();
	 
		        if($resultat == 0)
	            { 		
		          return false;
		        }
		        else
		        {
		         return true;
		        }
            }
            else
            {
			 return true;
			}			
        }			
	}



//Cette faonction regarde si ona les permissions d'afficher un talk(elle est simillaire à la fonction prédédente sauf que c'est pour gérer un exception du controlleur)
public function permit_talk_special($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    if($this->if_talk_private($id_talk))
			{
             $this->db->where('id_talk',$id_talk);
			 $this->db->where('number',$this->session->userdata('numero'));
             $this->db->from('begoo_talk_private');
             $resultat = $this->db->count_all_results();
	 
		        if($resultat == 0)
	            { 		
		          return false;
		        }
		        else
		        {
		         return true;
		        }
            }			
        }			
	}	
	
	


	
//Cette faonction regarde si un talk est privé
public function if_talk_private($id_talk)
    {        
     //On fouille maintenant
	   $this->db->select('private');
	   $this->db->from('begoo_talk');
	   $this->db->where('id_talk',$id_talk); 
	   $query = $this->db->get();
	  
		if($query->num_rows()!==0)
	    { 
          $row = $query->row();				
		           
		    if($row->private ==1)
			{
			  return true;
		    }
            else
            {
			  return false;
			}			
		}					
	}	
	
	
//Cette fonction affiche un talk
public function MaListe_Talk($quoi,$nbre_msg)
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    { 
	     //On sélectionnne la liste des messages
		    switch($quoi)
			{
			    case'new':
				     $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	                 $this->db->from('begoo_talk');
					 $this->db->where('private',0);
	                 $this->db->order_by("timestamp_talk", "desc");                    					 
                     $this->db->limit(50,$nbre_msg);
		  
		             $query_quoi = $this->db->get();
		  
		                if($query_quoi->num_rows()!==0)
	                    {
			              return $query_quoi->result();
			            }
						else
						{
						   return false;
						}
                break;
                case'favorite':
				     $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	                 $this->db->from('begoo_talk');
					 $this->db->where('private',0);
	                 $this->db->order_by("favoris_talk", "desc"); 					 
                     $this->db->limit(50,$nbre_msg);				 
		  
		             $query_quoi = $this->db->get();
		  
		                if($query_quoi->num_rows()!==0)
	                    {
			              return $query_quoi->result();
			            }
						else
						{
						   return false;
						}
				break;
				case'mine':
				     $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	                 $this->db->from('begoo_talk');						 
                     $this->db->where('auteur_talk',$this->session->userdata('numero'));
					 $this->db->order_by("timestamp_talk","desc"); 					 
                     $this->db->limit(50,$nbre_msg);
		  
		             $query_quoi = $this->db->get();
		  
		                if($query_quoi->num_rows()!==0)
	                    {
			              return $query_quoi->result();
			            }
						else
						{
						   return false;
						}
				break;
				case'private':
				     $this->db->select('*');
					 $this->db->from('begoo_talk_private');
					 $this->db->join('begoo_talk', 'begoo_talk_private.id_talk = begoo_talk.id_talk', 'left');
					 $this->db->where('begoo_talk_private.number',$this->session->userdata('numero'));
					 $this->db->order_by("begoo_talk.timestamp_talk","desc"); 					 
                     $this->db->limit(50,$nbre_msg);
		  
		             $query_quoi = $this->db->get();
		  
		                if($query_quoi->num_rows()!==0)
	                    {
			              return $query_quoi->result();
			            }
						else
						{
						   return false;
						}
				break;
				default:
				    return false;
				break;
			}
        }			
	
	}
	
	
//Cette prend les commentaires d'un talk
public function talk_comments($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
            //On compte le nombre de commentaire de ce talk
            $this->db->where('id_talk',$id_talk);
            $this->db->from('begoo_talk_comment');
            $nbre_com = $this->db->count_all_results();	

			
	        //On fouille maintenant
	        $this->db->select('*');
	        $this->db->from('begoo_talk_comment');
	        $this->db->where('id_talk',$id_talk); 
	        $this->db->order_by("id_com");
		    $this->db->limit(4);
	        $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        { 
              $reponse = array($nbre_com,$query->result());
			  
		     return $reponse;
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	
	
		
	//Cette prend regarde s'il ya de nouveaux commentaire pour un talk
public function if_new_com($id_talk,$my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
            //On compte le nombre de commentaire de ce talk
            $this->db->where('id_talk',$id_talk);
			$this->db->where('timestamp >',$my_time);
            $this->db->from('begoo_talk_comment');
            $nbre_com = $this->db->count_all_results();	
	  
		    if($nbre_com > 0)
	        { 		  
		     return 'yes';//Oui il ya de nouveaux commentaires
		    }
        }			
	}


	
//Cette fonction prend les tout nouveau commentaire de talk nons lu par l'user connecté
public function new_com($id_talk,$my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	        $this->db->select('id_com,user,commentaire,timestamp,huer,apprecier');
	        $this->db->from('begoo_talk_comment');
	        $this->db->where('id_talk',$id_talk); 
			$this->db->where('timestamp >',$my_time);
	        $this->db->order_by("id_com");
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
	
	
	
	
//Cette fonction prend les tous les commentaire d'un talk 
public function all_com($id_talk)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	        $this->db->select('*');
	        $this->db->from('begoo_talk_comment');
	        $this->db->where('id_talk',$id_talk); 
	        $this->db->order_by("id_com");
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

	
	
	
//Cette fonction prend les tout nouveau commentaire de talk nons lu par l'user connecté
public function new_com_aff($id_talk,$my_time)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	        $this->db->select('*');
	        $this->db->from('begoo_talk_comment');
	        $this->db->where('id_talk',$id_talk); 
			$this->db->where('timestamp >',$my_time);
	        $this->db->order_by("id_com");
		    $this->db->limit(4);
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
	
	
	
//Cette fonctionn fait suivre un talk
public function follow($id_talk,$numero)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    if($this->if_follow($id_talk))//Si on constate qu'il ne sui pas le talk on le fait suivre
			{		
	         //On insert le message
		      $data = array(
                 'follower'            => $numero,
                 'id_talk'             => $id_talk,
				 'timestamp_follow'    => time(),
                   );

              $this->db->insert('begoo_talk_follow', $data);
			}
		}
	}
	
	
//Cette fonction vérifie si l'user suit un talk précis
public function if_follow($id_talk)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		  //On fouille maintenant s'il suit déjà ce talk
	     $this->db->from('begoo_talk_follow');
	     $this->db->where('follower',$this->session->userdata('numero')); 
		 $this->db->where('id_talk',$id_talk);
	     $query_ligne = $this->db->count_all_results();
		 
	  
		    if($query_ligne == 0)//S'il ne suit pas,on renvoi true
			{		
	         return true;
			}
		}
	}



//Cette fonction met un talk en favori
public function favorite($id_talk)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		   $this->db->query('UPDATE begoo_talk SET favoris_talk = favoris_talk + 1 WHERE id_talk ="'.$id_talk.'"'); 

            //ON prévient ses amis s'ils n'ont jamais été prévenus
            if($this->if_favorite($id_talk))
            {
			 //On prévient ses amis en notification
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_advice_talk');
			 $url = '/msg/talk/talking/'.$id_talk;
			
			 $this->prevent_my_friends($message,$url);


              //ET On confirma à tout jamais que ce post a déjà été mis en favoris par lui
			    $data = array(
                 'user_number'       => $this->session->userdata('numero'),
                 'id_talk'           => $id_talk
                   );

               $this->db->insert('begoo_talk_favorite', $data);
              			  
            }			
		   
		  //On renvoi maintenant en réponse le nombre de fois qe ce talk a été mis en favoris
	     $this->db->select('favoris_talk');
	     $this->db->from('begoo_talk');
		 $this->db->where('id_talk',$id_talk);
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)//S'il ne suit pas,on renvoi true
			{		
	         $row = $query->row();
             return $row->favoris_talk;
			}
		}
	}



//Cette fonction vérifi si l'user a déjà mis en favoris un tal précis
public function if_favorite($id_talk)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
		  //On renvoi maintenant en réponse le nombre de fois qe ce talk a été mis en favoris
	     $this->db->select('id_talk');
	     $this->db->from('begoo_talk_favorite');
		 $this->db->where('id_talk',$id_talk);
		 $this->db->where('user_number',$this->session->userdata('numero'));
	     $query = $this->db->get();
	  
		    if($query->num_rows() == 0)//On retourn true s'il ne la jamais fait
			{		
             return true;
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
			      'notification_logo'          => 2				  
                  );

                  $this->db->insert('begoo_notification', $data); 
			   
			     $this->db->query('UPDATE begoo_user SET user_note = user_note + 1 WHERE user_number ="'.$my_friends->friend.'"');				
				}
		    }
		}
	}	



//Cette fonction prévient les interlocuteurs de l'user connecté pour action effectuée
public function prevent_friend_talk($message,$url,$id_talk)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
		  $this->db->select('follower');
	      $this->db->from('begoo_talk_follow');
	      $this->db->where('follower <>',$this->session->userdata('numero'));
		  $this->db->where('id_talk',$id_talk);
     
	      $query_friend = $this->db->get();
	
	        if($query_friend->num_rows() > 0)//Sije trouve ses amis ,je les fait une notification
	        {	
                foreach($query_friend->result() as $my_friends)
			    {
			      $data = array(
                  'notification_user'          => $my_friends->follower,
                  'notification_msg'           => $message,
			      'notification_url'           => $url,
				  'notification_lu'            => 1,
                  'notification_timestamp'     => time(),
			      'notification_logo'          => 2			  
                  );

                  $this->db->insert('begoo_notification', $data); 
			   
			     $this->db->query('UPDATE begoo_user SET user_note = user_note + 1 WHERE user_number ="'.$my_friends->follower.'"');				
				}
		    }
		}
	}	

	
	
	
	
//Cette fonction met un tag sur un talk
public function tag_it($id_talk,$tag)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
	        if($this->if_tag($id_talk))//Si je trouve que ce talk n'a pas de tag ,j'en fait un
	        {	
                
			      $data = array(
                  'id_talk'          => $id_talk,
                  'tag'              => $tag				  
                  );

                  $this->db->insert('begoo_talk_tag', $data);
               
                 return true;			   
		    }
			else
			{
			 return false;
			}
		}
	}
	
	
	
//Cette fonction vérifie si un talk précis est déjà taguer
public function if_tag($id_talk)
    { 	   
	 $this->db->where('id_talk', $id_talk);
     $this->db->from('begoo_talk_tag');
     $ligne =  $this->db->count_all_results();
	
	    if($ligne == 0)//Si je trouve que ce talk n'a pas de tag ,j'en fait un
	    {	
          return true;	 
		}
		else
		{
		  return false;
		}
	}
	
	
	
	
//Cette fonction fait un rappel aux membres d'un talk
public function recall($id_talk)
    { 
        if($this->session->userdata('logged_in') and $this->permit_talk($id_talk))//s'il est connecté et a les permission du talk
	    {
          //On sélectionne tous mes membres du talk présent pour leur faire un rappel
		  //on redige dabords le message
		  $message = '<h6>'.$this->lang->line('statu_rappel_talk').'</h6><p>'.$this->talk_msg($id_talk).'</p><b>'.$this->lang->line('statu_by').'</b> <span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span>';
         
		  $url = '/msg/talk/talking/'.$id_talk;	//Url de rendez-vous
		
		  $this->prevent_friend_talk($message,$url,$id_talk);//Et on passe l'info à tout le monde	

            //Maintenant on rappelle la meme information dans le compte des utilisateurs concernés dans le champ talk_recall de la table begoo_user
            			 			 
		     $this->db->select('follower');
	         $this->db->from('begoo_talk_follow');
	         $this->db->where('follower <>',$this->session->userdata('numero'));
		     $this->db->where('id_talk',$id_talk);
     
	         $query_friend = $this->db->get();
	
	        if($query_friend->num_rows() > 0)//Si je trouve ses amis ,je les fait une notification
	        {
			     $data_recall = array(
                 'talk_recall'      => $id_talk,
				 'talk_recaller'    => $this->session->userdata('numero')
                );
			   
			    foreach($query_friend->result() as $my_friends)
			    {
			     $this->db->where('user_number',$my_friends->follower);
			 
                 $this->db->update('begoo_user', $data_recall);
				 
				}
			}
        }
    }
	
	
	
//Ici récupère l'appel du talk pour afficher la fenêtrede choix
public function recall_talk($id_talk)
    { 
        if($this->session->userdata('logged_in') and $this->permit_talk($id_talk))//s'il est connecté et a les permission du talk
	    {
		   //On prend le numéro de celui qui a fait le rappel
		     $this->db->select('talk_recaller');
	         $this->db->from('begoo_user');
	         $this->db->where('user_number',$this->session->userdata('numero'));
		     
	         $query_caller = $this->db->get();
			 
			 $row = $query_caller->row();
			 
             $caller = '<b>'.$this->lang->line('statu_by').' '.$this->connects->username($row->talk_recaller,1).'</b>';//jai profité une fois pour récupérer son nom
			 
			  //On prend le message du talk 
		      $talk_talk = $this->talk_msg($id_talk);
		   
			 //Et on emballe tout
			 
			 $reponse_finale = array($talk_talk,$caller);
			
             $this->recall_talk_wipe();//On efface le recall			
			 
			 //livraison
			 return $reponse_finale;	
        }
    }
	
	//Ici récupère l'appel du talk en ajx pour afficher en ajax dans une popup qui explosera après	
public function recall_talk_wipe()
    { 
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		    //On prend le numéro de celui qui a fait le rappel
		     $this->db->select('talk_recall');
	         $this->db->from('begoo_user');
	         $this->db->where('user_number',$this->session->userdata('numero'));
		     
	         $query_caller = $this->db->get();
			 
			 $row = $query_caller->row();
			 
			 $id_talk = $row->talk_recall;
			 
		   //effacons l'entré
		   $this->db->query('UPDATE begoo_user SET talk_recall = 0, talk_recaller ="" WHERE user_number ="'.$this->session->userdata('numero').'"'); 
		   
		    return $id_talk;
        }
    }
	

	
}
?>	