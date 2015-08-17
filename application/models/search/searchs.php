<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Searchs extends CI_Model {
    
function __construct()
	{
       parent::__construct();
	   
	   //on charge la librairie de la bdd
	    $this->load->database();
		
		$this->load->helper('inflector');//CE helper est pour les underscores pour les espace de caractère
    }
  
  
 //Cette fonction compte le nombre de resultat de cola
public function counter_cola($chaine)
    { 
	   //On fouille maintenant
	  $this->db->like('titre',$chaine);
	  $this->db->from('begoo_file');	 
	   
	  return $this->db->count_all_results();	         
	}
	
	
	
	
	
//Cette fonction recherche dans les fichiers
public function cola($chaine)
    {    
	    //On fouille maintenant
	  $this->db->select('id_file,auteur,extension,capacite,nbre_commentaire,favoris,timestamp,titre,view');
	  $this->db->from('begoo_file');
	  $this->db->like('titre',$chaine); 
	  $this->db->order_by("timestamp", "desc");
	  $this->db->limit(50);
	  
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
	
////////////////////////////////////////////////
//Cette fonction compte le nombre de resultat de discussion
public function counter_talk($chaine)
    { 
	   //On fouille maintenant
	  $this->db->like('talk_talk',$chaine);
	  $this->db->from('begoo_talk');
      $this->db->where('private',0);	  
	   
	  return $this->db->count_all_results();	         
	}
	
	
	
	
	
//Cette fonction recherche dans les talk
public function talk($chaine)
    {    
	    //On fouille maintenant
	  $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	  $this->db->from('begoo_talk');
	  $this->db->where('private',0);
	  $this->db->like('talk_talk',$chaine); 
	  $this->db->order_by("timestamp_talk", "desc");
	  $this->db->limit(50);
	  
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
	
	
	



//Cette fonction affiche les ajout de resultat de recherche
public function plus($quoi,$nbre_msg,$chaine)
    {
	    //On voie si je suis connecté
		if($this->session->userdata('logged_in'))
	    {  
	     //On sélectionnne la liste des messages
		    switch($quoi)
			{
			    case'wiki':
				      $this->db->select('page_id,page_title,page_latest,page_is_new,page_touched');
	                  $this->db->from('page');
	                  $this->db->like('page_title',$chaine);
                      $this->db->or_like('page_title', ucfirst($chaine));					  
	                  $this->db->order_by("page_counter", "desc"); 					 
                      $this->db->limit(50,$nbre_msg);
		  
		             $query_quoi = $this->db->get();
		    
		                if($query_quoi->num_rows()!==0)
	                    {
			              return $query_quoi->result_array();
			            }
						else
						{
						   return false;
						}
                break;
                case'file':
				      $this->db->select('id_file,auteur,extension,capacite,nbre_commentaire,favoris,timestamp,titre,view');
	                  $this->db->from('begoo_file');
	                  $this->db->like('titre',$chaine);
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
				case'talk':
				     $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	                 $this->db->from('begoo_talk');
	                 $this->db->where('private',0);
	                 $this->db->like('talk_talk',$chaine); 
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
				default:
				    return false;
				break;
			}
        }			
	
	}




 //Cette fonction compte le nombre de resultat de fichier d'un user
public function counter_cola_user($chaine)
    { 
	   //On fouille maintenant
	  $this->db->where('auteur',$chaine);
	  $this->db->from('begoo_file');	 
	   
	  return $this->db->count_all_results();	         
	}
	
	
	
	
	
//Cette fonction recherche dans les fichier d'un user
public function cola_user($chaine)
    {    
	    //On fouille maintenant
	  $this->db->select('id_file,auteur,extension,capacite,nbre_commentaire,favoris,timestamp,titre,view');
	  $this->db->from('begoo_file');
	  $this->db->where('auteur',$chaine); 
	  $this->db->order_by("timestamp", "desc");
	  $this->db->limit(50);
	  
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




//Cette fonction compte le nombre de resultat de fichier d'un user
public function counter_talk_user($chaine)
    { 
	   //On fouille maintenant
	  $this->db->where('auteur_talk',$chaine);
	  $this->db->from('begoo_talk');	 
	   
	  return $this->db->count_all_results();	         
	}
	
	
	
	
	
//Cette fonction recherche dans les fichier d'un user
public function talk_user($chaine)
    {    
	    //On fouille maintenant
	  $this->db->select('id_talk,auteur_talk,talk_talk,timestamp_talk,favoris_talk,comment_talk');
	  $this->db->from('begoo_talk');
	  $this->db->where('auteur_talk',$chaine); 
	  $this->db->order_by("timestamp_talk", "desc");
	  $this->db->limit(50);
	  
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


//Cette fonction recherche dans les fichier d'un user
public function find_da_man($chaine)
    { 
	    if($this->session->userdata('logged_in'))
	    { 
         //On regarde s'il est inscrit
	        if(!$this->users->is_user($chaine))
		    { 
		     //si non on l'inscrit et on fait un mp pour lui signaler la personne qui a fait la recherche sur lui.Gare aux amourettes :)	  	
		     $message = $this->lang->line('statu_kongossa');
			 
		      //et on envoi
		     $this->msgs->SendIt($this->session->userdata('numero'),$this->connects->if_exist($chaine),$message);
			}
		}
	}


//Cette fonction liste l'historique des 100 derniers articles de l'utilisateur
public function historic($nombre)
    {        
	 //On fouille maintenant
	  $this->db->select('page_id,page_title,timestamp');
	  $this->db->from('begoo_wiki_historique');
      $this->db->where('user_id',$this->session->userdata('user_id'));	  
	  $this->db->order_by("timestamp", "desc");
	  $this->db->limit($nombre); 
	  $query = $this->db->get();
	  
		if($query->num_rows()!==0)
	    {  		
		  return $query->result_array();
		}
		else
		{
		 return false;
		}	         
	}
	
	
	
	
//Cette fonction liste les articles les plus consultés
public function more_see($nombre)
    {        
	 //On fouille maintenant
	  $this->db->select('consult_title,consult_page_id');
	  $this->db->from('begoo_wiki_consult');
      $this->db->order_by("consult_counter", "desc");
	  $this->db->limit($nombre); 
	  $query = $this->db->get();
	  
		if($query->num_rows()!==0)
	    {  		
		  return $query->result_array();
		}
		else
		{
		 return false;
		}	         
	}
	
 
 }
?>	