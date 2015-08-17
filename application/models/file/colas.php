<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Colas extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
    }
  
  
 //Cette insert un nouveau fichier
public function insert_file($titre,$capacite,$extension,$url)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	     //On insert le message
		  $data = array(
                 'auteur'       => $this->session->userdata('numero'),
                 'url'          => underscore($url),
				 'timestamp'    => time(),
				 'extension'    => $extension,
				 'titre'        => $titre,
				 'capacite'     => $capacite
                   );

          $this->db->insert('begoo_file', $data);
		  
		  $last_insert = mysql_insert_id();
		  		  
		    //On prévient ses amis de l'insertion du nouveau fichier en notification
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_file');
			 			
			 $url = '/file/cola/look_file/'.$last_insert;			
			
			 $this->prevent_my_friends($message,$url);
			 
			
		  //et on dit que tout s'est bien passé
		  return $last_insert;
		}
	}
	
	


//Cette fonction affiche les fichiers
public function MaListe_File($quoi,$nbre_msg)
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    {  
	     //On sélectionnne la liste des messages
		    switch($quoi)
			{
			    case'new':
				     $this->db->select('*');
	                 $this->db->from('begoo_file');
	                 $this->db->order_by("timestamp", "desc"); 					 
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
				     $this->db->select('*');
	                 $this->db->from('begoo_file');
	                 $this->db->order_by("view", "desc"); 					 
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
				     $this->db->select('*');
	                 $this->db->from('begoo_file');				 
                     $this->db->where('auteur',$this->session->userdata('numero'));
					 $this->db->order_by("timestamp","desc"); 					 
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


	
	
	
//Cette insert un nouveau commentaire pour un fichier
public function insert_file_com($id_file,$file_com)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
	     //On insert le message
		  $data = array(
                 'auteur'              => $this->session->userdata('numero'),
                 'commentaire'         => $file_com,
				 'timestamp'           => time(),
				 'id_file'             => $id_file
                   );

          $this->db->insert('begoo_file_com', $data);
		   		  
		    
			 $this->db->query('UPDATE begoo_file SET nbre_commentaire = nbre_commentaire + 1 WHERE id_file ="'.$id_file.'"');//On ajoute le nombre de commentaire du fichier 
		 
		 //On averti celui qui a mis le fichier par notification du nouveau commentaire
		  //On appelle la fonction qui s'occuper de la notification personnelle
	      $this->load->model('user/users');
		  
		   $query_mec = $this->db->query('SELECT auteur FROM begoo_file WHERE id_file="'.$id_file.'"');

           $row = $query_mec->row();
		   
		    if($row->auteur!==$this->session->userdata('numero'))
		    {
		   
		     $user = $row->auteur;
		 
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_com_file').'<br>'. character_limiter($file_com, 20);
			
			 $url = '/file/cola/look_file/'.$id_file;
			 
			 $type_note = 3;

		     $this->users->notify_user($message,$url,$user,$type_note);
			}
		 
		  return true;
		}
	}
	
	
	
	

	
	
//Cette fonction affiche un fichier particulier
public function look_file($id_file)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	     //On fouille maintenant
	     $this->db->select('*');
	     $this->db->from('begoo_file');
	     $this->db->where('id_file',$id_file); 
	     $query = $this->db->get();
	  
		    if($query->num_rows()!==0)
	        {  
                //On ajoute +1 vue sur le nombre de fois que le fichier a été visité
				$this->db->query('UPDATE begoo_file SET view = view + 1 WHERE id_file ="'.$id_file.'"'); 
		 			
		     return $query->result();
		    }
		    else
		    {
		     return false;
		    }
        }			
	}
	
	
	
	

	
//Cette prend les commentaires d'un fichier
public function file_comments($id_file)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {
            //On compte le nombre de commentaire de ce fichier
            $this->db->where('id_file',$id_file);
            $this->db->from('begoo_file_com');
            $nbre_com = $this->db->count_all_results();	

			
	        //On fouille maintenant
	        $this->db->select('*');
	        $this->db->from('begoo_file_com');
	        $this->db->where('id_file',$id_file); 
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
	
	
		
	
	
	
//Cette fonction prend tous les commentaires d'un fichier 
public function all_com($id_file)
    {  
        if($this->session->userdata('logged_in'))//s'il est connecté
	    {	
	        $this->db->select('*');
	        $this->db->from('begoo_file_com');
	        $this->db->where('id_file',$id_file); 
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

	
	
	


//Cette fonction met un fichier en favori
public function favorite($id_file)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    {
		   $this->db->query('UPDATE begoo_file SET favoris = favoris + 1 WHERE id_file ="'.$id_file.'"'); 

            //ON prévient ses amis s'ils n'ont jamais été prévenus
            if($this->if_favorite($id_file))
            {
			 //On prévient ses amis en notification
			 $message = '<span class="user_post">'.$this->connects->username($this->session->userdata('numero'),0).'</span> '.$this->lang->line('note_advice_talk');
			 $url = '/file/cola/look_file/'.$id_file;
			
			 $this->prevent_my_friends($message,$url);


              //ET On confirma à tout jamais que ce post a déjà été mis en favoris par lui
			    $data = array(
                 'user_number'       => $this->session->userdata('numero'),
                 'id_file'           => $id_file
                   );

               $this->db->insert('begoo_file_favorite', $data);
              			  
            }			
		   
		  //On renvoi maintenant en réponse le nombre de fois qe ce fichier a été mis en favoris
	     $this->db->select('favoris');
	     $this->db->from('begoo_file');
		 $this->db->where('id_file',$id_file);
	     $query = $this->db->get();
	  
		    if($query->num_rows() > 0)//S'il ne suit pas,on renvoi true
			{		
	         $row = $query->row();
             return $row->favoris;
			}
		}
	}



//Cette fonction vérifi si l'user a déjà mis en favoris un fichier précis
public function if_favorite($id_file)
    { 
	    if($this->session->userdata('logged_in'))//s'il est connecté
	    { 
		  //On renvoi maintenant en réponse le nombre de fois qe ce fichier a été mis en favoris
		  $this->db->where('id_file',$id_file);
		  $this->db->where('user_number',$this->session->userdata('numero'));
	     
          $this->db->from('begoo_file_favorite');
          
	     
		    if($this->db->count_all_results() == 0)//On retourn true s'il ne la jamais fait
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
	
	        if($friends!==false)//Si je trouve ses amis ,je les fait une notification
	        {	
                foreach($friends as $my_friends)
			    {
			      $data = array(
                  'notification_user'          => $my_friends->friend,
                  'notification_msg'           => $message,
			      'notification_url'           => $url,
				  'notification_lu'            => 1,
                  'notification_timestamp'     => time(),
			      'notification_logo'          => 3				  
                  );

                  $this->db->insert('begoo_notification', $data); 
			   
			     $this->db->query('UPDATE begoo_user SET user_note = user_note + 1 WHERE user_number ="'.$my_friends->friend.'"');				
				}
		    }
		}
	}	
	
	
	
	
//Cette fonction affiche l'extension des fichiers
public function extension($extension)
    { 
	    switch($extension)
		{
		    case '.zip':
			    return '<i class="icon-briefcase"></i>';
			break;
			
			case '.rar':
			    return '<i class="icon-briefcase"></i>';
			break;
			
			case '.png':
			    return '<i class="icon-camera"></i>';
			break;
			
			case '.jpg':
			    return '<i class="icon-camera"></i>';
			break;
			
			case '.jpeg':
			    return '<i class="icon-camera"></i>';
			break;
			
			case '.gif':
			    return '<i class="icon-camera"></i>';
			break;
			
			case '.mp3':
			    return '<i class="icon-music"></i>';
			break;
			
			case '.flv':
			    return '<i class="icon-film"></i>';
			break;
			
			case '.pdf':
			    return '<img src="'.base_url().'/assets/smileys/pdf.png" >';
			break;
			
			case '.doc':
			    return '<i class="icon-file"></i>';
			break;
			
			case '.docx':
			    return '<i class="icon-file"></i>';
			break;
			
			default:
			    return '<i class="icon-question-sign"></i>';
			break;
		
		}
	}
	
}
?>	