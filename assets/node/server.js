
var PeerServer = require('peer').PeerServer;
var server = new PeerServer({ port: 9000 });


var delete_timer = 600000;//10*60000; //10 min

var path_upload_dir = null;

var tab_verif_connected = [];//Ce tableau verifi les followed qui son connectés

var io = require('socket.io').listen(8080,{ log: false }) ;
var fs = require('fs');

console.log('kwiki Ready');

//File manager

//We delete all the file of the upload directory

//We create a list of file
var list_file_hash_name = [];
var list_file_name = [];
var list_file_time = [];

var timer = 0;

function add_file(file_name,file_hash_name){
   
   list_file_name.push(file_name);
   list_file_hash_name.push(file_hash_name);
   list_file_time.push(timer);
}


function delete_file(entry){
    
    list_file_name.splice(entry,1);
    list_file_time.splice(entry,1);
    fs.unlinkSync('../uploader/uploads/'+list_file_hash_name[entry]);

    list_file_hash_name.splice(entry,1);
}

var timer_to_delete_file =  timer_to_delete_file();

function timer_to_delete_file(){
    
    setInterval(function(){

    	var file_number = list_file_name.length

    	if(file_number > 0 && path_upload_dir!=null){
             
            for(i=0;i < list_file_time.length; i++){

            	var verif_time_file = list_file_time[i]+3600;

            	if(timer <= verif_time_file){

                    //We dilete the file
                    delete_file(i);
            	}
            }
    	}

    	timer = timer + delete_timer;//We increment time every delete_timer in order to replace Date object.

    },delete_timer);
}

// Upon a socket connection, a socket is created for the life of the connection
io.sockets.on('connection', function (socket) {

	/////////////////////////////////////////////////////////////////////////////////
	
	//ici l'user veut être suivi
    socket.on('follow_me', function (data) {

    	//Je le retire de la liste des suiveurs s'il est présent
    	var index_follower =  follower_list.indexOf(data.user_id);

    	if(index_follower!==-1){//S'il est dans la liste des follower on le retire

    		//On le fait laisser la room de l'acien followed
    	    socket.leave('page_url_'+follower_list_followed[index_follower]);
            
            follower_list.splice(index_follower, 1);

            follower_list_followed.splice(index_follower, 1);
    	}

		socket.join('page_url_'+data.user_id);//On créé la room qui servirra à dire quel est l'article qui est lu en temps actuel

		var index_followed = followed_list.indexOf(data.user_id); //On regarde aussi s'il est dans la liste des suivis

		if(index_followed==-1){ //S'il n'est pas dedans on l'inscrit
			
		    followed_list.push(data.user_id);//On ajoute cette personne dans la liste des suivies
		    followed_list_name.push(data.username);
		}

	    socket.broadcast.emit('follow_him',{'followedIds':followed_list,'followedName':followed_list_name});//On dit à tout le monde dans l'application que cet user veut être suivi(user_id et username)
    });


   
	//Ici on suit de près les articles d'un followed
	socket.on('fellow', function (data) { 
	   
	    socket.broadcast.to('page_url_'+data.room).emit('change_article',data); //emit to 'room' except this socket
	});
	
	
	
	//Ici on joint un followed en s'inscrivant dans son canal
	socket.on('jointe',function (data) { //(user_id,followed_id)

		//Si on le suivait
		 //je le sort de la liste des suivies
            var index_followed = followed_list.indexOf(data.follower_id);

            if(index_followed!==-1){
 
                followed_list.splice(index_followed, 1);
                followed_list_name.splice(index_followed, 1);

                //On le sort de sa prpre room
                socket.leave('page_url_'+data.follower_id);
		        socket.broadcast.to('page_url_'+data.follower_id).emit('end_follow',data);//On le dit à tout le monde

		        socket.broadcast.emit('follow_him',{'followedIds':followed_list,'followedName':followed_list_name});//on met à jour la liste des suivis
            }

        leave_other_room(data.follower_id);//Je le sort des suiveurs ailleurs

	    socket.join('page_url_'+data.followed_id);//on le fait joindre la room

	    follower_list.push(data.follower_id);
	    follower_list_followed.push(data.followed_id);   

		//On fait plus un dans le nombre de personne suivant le followed
		socket.broadcast.to(data.followed_id).emit('more_person');

		//On prend l'article actuellement lu
		socket.broadcast.to(data.followed_id).emit('get_article',data.follower_id);			
    });
	
	
	
	//Ici on récupère en réponse larticle actuellement consulté
	socket.on('this_article',function(result) {
	
	    socket.broadcast.to(result.follower_id).emit('this_page',result.page_url);       	
	});
	
	
	
	//On demande si un user et en ligne
	socket.on('is_online',function(data) {
	
	    socket.broadcast.to(data.receiver).emit('your_number',data);
     	
	});
	
	
	//On récupère le numéro d'un user
	socket.on('my_number',function(data) {
	
	    socket.broadcast.to(data.sender).emit('his_number',data);    	
	});
	
	//On récupère le numéro d'un user
	socket.on('my_tof',function(data) {
	
	    socket.broadcast.to(data.interloc_num).emit('da_tof',data);    	
	});
	
	
	
	
	//Ici le followed ne veut plus être suivi
	socket.on('follow_no', function (user_id) {

	    leave_my_room(user_id);
    });
		 
	
	//Ici je sort le followed de la room
	socket.on('leave_it', function(user_id) {
	   
	   leave_other_room(user_id);
	})
	


	function leave_other_room(user_id){//POur sortir de la room d'une autre personne

        //On regarde s'il suit quelqu'un
		var follower_index = follower_list.indexOf(user_id);

		if(follower_index!==-1){ //S'il suit quelqu'un

            //On le fait laisser la room l'ancien followed
            socket.leave('page_url_'+follower_list_followed[follower_index]);

            //On fait fait -1 dans le nombre de personne suivant le followed
		    socket.broadcast.to('page_url_'+follower_list_followed[follower_index]).emit('less_person');

		    //je retire son id du tableau des suiveurs
		        var longueur = follower_list_followed.length;

		        for(i=0;i<=longueur;i++){

		        	var follower_id = follower_list[i];

		        	if(follower_id == user_id){

		        		follower_list_followed.splice(i,1);
		        		follower_list.splice(i,1);
		        	}
		        }
		}
	}

	

	//Ici on vérifie tout le temps si les suivies sont connectées.Si ce n'est pas le cas on les supprime
	
    socket.on('disconnect',function(){

    	//console.log('disconnection');
    })

	

	socket.on('response_verif_connected',function(user_id){

        tab_verif_connected.push(user_id);
	})
	

    function verif_connected(){

    	setTimeout(function(){

    		if(tab_verif_connected.length>0){

    			for(i=0;i<=followed_list.length;i++){

    				var index_to_move = tab_verif_connected.indexOf(followed_list[i]);

    				if(index_to_move==-1){//Si il ya un followed qui n'a pas répondu je le supprime

                        followed_list.splice(i,1);
                        followed_list_name.splice(i,1);

		                socket.broadcast.emit('follow_him',{'followedIds':followed_list,'followedName':followed_list_name});//on met à jour la liste des suivis
    				}
    			}
    		}

    	},10000);
    }
	
	
	//C'est ici qu'on gère tchat dans les rooms lors des following
	socket.on('send_message', function (data) {
	   
	    //On envoi ce message à tout le monde dans a room
		socket.broadcast.to(data.interloc_num).emit('new_message',data);   
	})
	
	
	//mise à jours des notifications
	socket.on('news', function () {
	
	    socket.broadcast.emit('new_note');

    });
	
	//on signal ici qu'on rédige un message à son interlocuteur
	socket.on('im_typing',function(data){
	
	    socket.broadcast.to(data.interloc).emit('is_typing',data);
	});
	
	
	//on signal ici qu'on ne rédige plus un message à son interlocuteur
	socket.on('im_not_typing',function(data){
	
	    socket.broadcast.to(data.interloc).emit('not_typing',data);
	});
	
	
	/////////////////////////////////////////////DUO  chat/webrtc//////////////////////////////////////////////
    
	//Si l'user vient de se connecter j'ouvre une nouvelle room avec son numéro de téléphone
	socket.on('welcome', function (data) { 
	   
	    socket.join(data.user_id);	
	    socket.join(data.user_number);
	    socket.join(data.user_level);	
    });

    socket.on('welcome_pinooy',function  (number) {
    	
    	socket.join(number);
    })

	
	socket.on('verif_if_called_busy',function(data){ 

		socket.broadcast.to(data.called_number).emit('verif_if_called_busy',data);
	})
	
	
	socket.on('called_busy',function(caller_number){
	
       	socket.broadcast.to(caller_number).emit('called_busy');
	});
	
	
	socket.on('called_not_busy',function(data){
	
       	socket.broadcast.to(data.caller_number).emit('called_not_busy',data);
	});


	socket.on('call_rejected',function  (caller_number) {

		socket.broadcast.to(caller_number).emit('call_rejected');
	})
	
	
	socket.on('end_call',function(caller_number){
	
       	socket.broadcast.to(caller_number).emit('call_ended');
       	socket.emit('call_ended');
	});

	socket.on('zut',function(caller_ID){
	
       	socket.broadcast.to(caller_ID).emit('zut');
	});
    

    socket.on('stop_belling',function(caller_id){

    	socket.broadcast.to(caller_id).emit('stop_belling');
    })


    socket.on('file_sended',function(data){

    	socket.broadcast.to(data.receiver).emit('file_sended',data);

    	add_file(data.file_name,data.message);

    	//We put the name of file in list file in order to delete tthe file after 1 hour
    })


    socket.on('change_statu_camera',function (user_num) {
    	socket.broadcast.to(user_num).emit('change_statu_camera');
    });


    socket.on('open_hello_true',function  (user_num) {
    	socket.broadcast.to(user_num).emit('open_hello_true');
    })


    socket.on('send_my_screen',function  (data) {

    	socket.broadcast.to(data.receiver).emit('send_my_screen',data);
    })

    socket.on('close_modal_during_calling',function  (receiver) {
    	socket.broadcast.to(receiver).emit('close_modal_during_calling');
    })

    socket.on('stop_make_him_follow_me',function  (user_num) {
    	socket.broadcast.to(user_num).emit('stop_make_him_follow_me');
    });

    socket.on('follow_please',function  (data) {
    	socket.broadcast.to(data.receiver).emit('follow_please',data);
    })

    socket.on('follow_accepted',function  (receiver) {
    	socket.broadcast.to(receiver).emit('follow_accepted');
    });

    socket.on('follow_refuse',function  (receiver) {
    	socket.broadcast.to(receiver).emit('follow_refuse');
    });

    socket.on('big_follow',function  (data) {

    	socket.join(data.key_follow);
    	socket.broadcast.emit('big_follow',data);
    })

    socket.on('follow_accepted_anonym',function (data) {
    	socket.join(data.key_follow);
    	socket.join(data.follower_num);
    	socket.broadcast.to(data.asker_num).emit('follow_accepted_anonym',data);
    });

    socket.on('leave_group',function(key_follow){ 

    	socket.leave(key_follow);
    })

	/////////////////////////////////////////////DUO  chat/webrtc Fin//////////////////////////////////////////////




	/////////////////////////////////////////////Connexion//////////////////////////////////////////////
	socket.on('verif_connexion',function(data){

		socket.join(data.number+'_room');

		socket.broadcast.to(data.number).emit('echo',data.number);
	})

	socket.on('im_connected',function(sender){

		socket.broadcast.to(sender+'_room').emit('im_connected');

		socket.leave(sender+'_room');
	})
	/////////////////////////////////////////////Connexion//////////////////////////////////////////////

    


    //Reception du message par tout le monde//////////////////////////////////////////////////////////////
    socket.on('to_all_family',function(form_data){

    	//On regarde si l'utilisteur à les droits de passer le messag à tout le monde.
    	//Pour cela on regarde juste si il à mis le mot de passe dans le message à la fin "#school"
    	var message = form_data.message;//On prend le message
        
        var temp = new Array();
        
        temp = message.split(' ');//On le découpe en utilisant [espace] comme délimiteur

        var last_string_id = temp.length-1;

        var last_string = temp[last_string_id];

        if(last_string == pass_family){

            var message_finale = form_data.message.replace(pass_family,' ');

            var final_form = {message:message_finale,slogan:form_data.slogan,out_line:form_data.out_line};

            socket.emit('to_all_family',final_form);

            //On envoi le message en socket à tout le monde
			socket.emit('famille',final_form);

			socket.broadcast.emit('famille',final_form);
        }else{

        	socket.emit('forbid');
        }
    })
    //Reception du message par tout le monde//////////////////////////////////////////////////////////////


    //Ici on supprime la liste de ceux qui e sont pas connecté mais qui sont la liste des personnes désirantes d'être suivies


});
