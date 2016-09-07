/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'screenr_hide_sitetitle', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'no-site-title' ).removeClass('has-site-title');
			} else {
				$( 'body' ).removeClass( 'no-site-title' ).addClass('has-site-title');
			}
		} );
	} );

	wp.customize( 'screenr_hide_tagline', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'no-site-tagline' ).removeClass('has-site-tagline');
			} else {
				$( 'body' ).removeClass( 'no-site-tagline' ).addClass('has-site-tagline');
			}
		} );
	} );


	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );


} )( jQuery );





