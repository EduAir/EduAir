<?php

//Cette vue charge toutes les autres vues.Cool Cool Cool! cest "Mon" idée cest génial!en plus cest facile.Je sais que je suis fou des fois...hi hi hi..
	
	$this->load->view('header/header');
	
	    //cherchon s quel top charger
		    switch ($top)
			{
			    case 'wikipedia':
                    $this->load->view('header/top_wikipedia');
                break;

                default:
                    $this->load->view('header/top');
                break;					
			}
	
//Et cest terminéé! cette page a la palme d'être la moin lourde du site
?>
