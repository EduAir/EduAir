<?php
  
  //On retien l'extention du fichier
  $fileName_initial = $_FILES["fichier"]["name"];

  $extention = explode(".", $fileName_initial);

  $nbre_entre =  count($extention);

   $allowed =  array('gif','png' ,'jpg', 'pdf','doc','docx','ppt','zip','rar');

      if($nbre_entre > 1){

        $extention = $extention[$nbre_entre -1];

        $fileName = md5(time().$fileName_initial.rand(0, 9999999999)).'.'.$extention;//On génère un nom de fichier pour eviter les conflits d'écrasement
        $fileTmp = $_FILES["fichier"]["tmp_name"];
        $fileType = $_FILES["fichier"]["type"];
        $fileSize = $_FILES["fichier"]["size"];
        $fileErrorMsg = $_FILES["fichier"]["error"];

         if (!$fileTmp) { // if file not chosen
            
            echo 'no_file';
            exit();
         }else{

            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            
            if(!in_array($ext,$allowed) ) {

               echo 'not_supported';
               exit();
            }else{
               //Is file size is less than allowed size(15 Mo * 1024*1024).
               if ($fileSize > 15728640) {
           
                  echo "too_big";
                  exit();
               }else{

                  if(move_uploaded_file($fileTmp, 'uploads/'.$fileName)){

                     echo $fileName;
                  }else {

                    echo 'error';
                    exit();
                  }
               }
            }
         }   
    }else{

   	  echo "error_unknow";
   }

?>