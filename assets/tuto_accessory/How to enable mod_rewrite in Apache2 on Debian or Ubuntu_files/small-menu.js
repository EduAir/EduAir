/**
 * Handles toggling the main navigation menu for small screens.
 */
jQuery( document ).ready( function( $ ) {
	var $masthead = $( '#heatmapthemead-primary-menu' ),
		$masthead2 = $( '#heatmapthemead-secondary-menu' )
	    timeout = false;	

	// temporarily shoehorned this fix for short content and the secondary sidebar formatting column in here
	// I'll find a permanent home for this at some stage!
    if ($('#heatmapthemead-primary-sidebar-container').length){
		
		if ($('#heatmapthemead-primary-sidebar-container').outerHeight() < $('#heatmapthemead-the-content-container').outerHeight()) {
			$("body").addClass("heatmapthemead-long-content");
		}
    }
	
	// Okay lets continue with the small menu stuff
	$.fn.smallPrimaryMenu = function() {
		$masthead.find( '.site-navigation' ).removeClass( 'main-navigation' ).addClass( 'main-small-navigation' );
		$masthead.find( '.site-navigation p' ).removeClass( 'primary-small-nav-text' ).addClass( 'primary-menu-toggle' );

		$( '.primary-menu-toggle' ).unbind( 'click' ).click( function() {
			$masthead.find( '.menu' ).toggle();
			$( this ).toggleClass( 'toggled-on' );
		} );
	};
	
	$.fn.smallSecondaryMenu = function() {
		$masthead2.find( '.site-navigation' ).removeClass( 'secondary-navigation' ).addClass( 'secondary-small-navigation' );
		$masthead2.find( '.site-navigation p' ).removeClass( 'secondary-small-nav-text' ).addClass( 'secondary-menu-toggle' );

		$( '.secondary-menu-toggle' ).unbind( 'click' ).click( function() {
			$masthead2.find( '.menu' ).toggle();
			$( this ).toggleClass( 'toggled-on' );
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() <=800 ) {
		$.fn.smallPrimaryMenu();
		$.fn.smallSecondaryMenu();
	}

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( browserWidth <=800 ) {
				$.fn.smallPrimaryMenu();
				$.fn.smallSecondaryMenu();
			} else {
				$masthead.find( '.site-navigation' ).removeClass( 'main-small-navigation' ).addClass( 'main-navigation' );
				$masthead.find( '.site-navigation p' ).removeClass( 'menu-toggle' ).addClass( 'primary-small-nav-text' );
				$masthead.find( '.menu' ).removeAttr( 'style' );
				
				$masthead2.find( '.site-navigation' ).removeClass( 'secondary-small-navigation' ).addClass( 'secondary-navigation' );
				$masthead2.find( '.site-navigation p' ).removeClass( 'menu-toggle' ).addClass( 'secondary-small-nav-text' );
				$masthead2.find( '.menu' ).removeAttr( 'style' );
			}
		}, 0 );
	} );
} );