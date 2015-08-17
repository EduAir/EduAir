
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>{title}</title>
	
	<link rel="icon" href="<?php echo base_url(); ?>assets/img/favo_icon.png" />
	
	

    
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/css/bootmetro.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/css/bootmetro-responsive.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/css/bootmetro-icons.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/css/bootmetro-ui-light.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/css/datepicker.css">


   <!-- Le fav and touch icons -->
   <link rel="shortcut icon" href="assets/ico/favicon.ico">
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url();?>assets/assets/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url();?>assets/assets/ico/apple-touch-icon-114-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url();?>assets/assets/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>assets/assets/ico/apple-touch-icon-57-precomposed.png">




    <link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet">
	
    <link type="text/css" href="<?php echo base_url();?>assets/css/jquery.classynotty.css" rel="stylesheet"/>
   
	
    <link href="<?php echo base_url();?>assets/css/begoo.css" rel="stylesheet">
	
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/node/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/jstorage.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery/json2.js"></script>
    <script type="text/javascript">

   
    $(document).ready(function(){

        window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;

    	if(  window.indexedDB && window.localStorage && window.WebSocket){
    		console.log('compatible');

    	}else{
    		$('.cachot').hide();
    		$('#navigateur').fadeIn();
    		$('.loaderdot').loadingDots();
    		$('.loaderdot').toggleLoadingDots();
            $('.connect_link').html($('.browser').html());
    	}
    }); 
  

    </script>





    
    

</head>


<body>
<div class="url_chrome" url_chrome="<?php echo base_url();?>"></div>
		

	