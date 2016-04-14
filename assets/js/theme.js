/**
 * skip-link-focus-fix.js
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();

/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */
( function() {
	var container, button, menu, links, subMenus;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );
	subMenus = menu.getElementsByTagName( 'ul' );

	// Set menu items with submenus to aria-haspopup="true".
	for ( var i = 0, len = subMenus.length; i < len; i++ ) {
		subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
	}

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}


} )();





jQuery(document).ready(function( $ ) {
	// https://github.com/woothemes/FlexSlider/wiki/FlexSlider-Properties
	/*

	 $('#slider').flexslider("play") //Play slideshow
	 $('#slider').flexslider("pause") //Pause slideshow
	 $('#slider').flexslider("stop") //Stop slideshow
	 $('#slider').flexslider("next") //Go to next slide
	 $('#slider').flexslider("prev") //Go to previous slide
	 $('#slider').flexslider(3) //Go fourth slide
	 */

	function my_slider( slider ){
		var current_slider =  slider.slides[ slider.currentSlide ];
		if ( $( 'video', current_slider).length > 0 ){
			var v =  $( 'video', current_slider).eq( 0 );
			//if ( slider.vars.slideshow ) {
				slider.pause();
				v[0].play();
			//}

			v.on('timeupdate', function() {
				var currentPos = v[0].currentTime; //Get currenttime
				var maxduration = v[0].duration; //Get video duration

				if ( currentPos >= maxduration  ) {
					//v[0].pause();
					//slider.next();
					if ( slider.vars.slideshow ) {
						slider.flexAnimate(slider.getTarget("next"));
						if (!slider.playing) {
							slider.play();
						}
					} else {

					}
				}
			 	var percentage = 100 * currentPos / maxduration; //in %
				$('.timebar').text(percentage + '%' + '--'+currentPos );
			});

		} else {
			//console.log( '-no video-' );
			if ( slider.vars.slideshow ) {
				if (!slider.playing) {
					slider.play();
				}
			}
		}
	}


	$('.flexslider').flexslider({
		slideshow: true,
		slideshowSpeed: 2000,
		animationSpeed: 500,
		animation: "fade",
		video: false,
		touch: true,
		pauseOnAction: true,
		useCSS: true,
		//---

		// Callback API

		start: function( slider ){ //Callback: function(slider) - Fires when the slider loads the first slide
			my_slider( slider );
		},
		before: function( a ){  //Callback: function(slider) - Fires asynchronously with each slider animation

		},
		after: function( slider ){ //Callback: function(slider) - Fires after each slider animation completes
			my_slider( slider );
		},

		end: function(){},              //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
		added: function(){},            //{NEW} Callback: function(slider) - Fires after a slide is added
		removed: function(){}           //{NEW} Callback: function(slider) - Fires after a slide is removed
	});





	/*
	$( '.next-slider').on( 'click',  function() {
		$('.flexslider').flexslider("prev");
		return false;
	});

	var v = $("#s-test-video");

	v2 = document.getElementById("s-test-video");

	console.log( v );
	console.log( v2 );

	v.on('loadedmetadata', function() {
		$('.duration').text(v[0].duration);
	});

	$( '.play-video').click( function(){
		//var v = document.getElementById("s-test-video");
		v[0].play();
		return false;
	} );

	$( '.pause-video').click( function(){
		//var v = document.getElementById("s-test-video");
		v[0].pause();
		return false;
	} );

	v.on('timeupdate', function() {
		var currentPos = v[0].currentTime; //Get currenttime
		var maxduration = v[0].duration; //Get video duration

		if ( currentPos >= maxduration ) {
			$('.flexslider').flexslider("next");
		}


		var percentage = 100 * currentPos / maxduration; //in %
		$('.timebar').text(percentage + '%' );
		if ( percentage >= 100 ) {
			console.log( 'has-stop' );
			$('.flexslider').flexslider("next");
		}

	});
	*/


});


jQuery( document ).ready( function( $ ){


	function my_ow_slider( owl ){
		var current_slider =  owl.$owlItems[ owl.currentItem ];
		console.log( 'index:'+owl.currentItem );
		if ( $( 'video', current_slider).length > 0 ){
			var v =  $( 'video', current_slider).eq( 0 );
			//if ( slider.vars.slideshow ) {
			owl.stop();
			v[0].play();
			//}

			v.on('timeupdate', function() {
				var currentPos = v[0].currentTime; //Get currenttime
				var maxduration = v[0].duration; //Get video duration

				if ( currentPos >= maxduration  ) {
					v[0].pause();
					//slider.next();

					owl.next()
					owl.play();

				}
				var percentage = 100 * currentPos / maxduration; //in %
				$('.timebar').text(percentage + '%' + '--'+currentPos );
			});

		} else {
			$('.timebar').text ('----');
			console.log( '-no video-' );

		}
	}

	$(".owl-example").owlCarousel( {
		items: 1,
		//Basic Speeds
		slideSpeed : 200,
		paginationSpeed : 800,
		rewindSpeed : 1000,

		//Autoplay
		autoPlay : 5000,
		stopOnHover : true,

		//Lazy load
		lazyLoad : true,
		lazyFollow : true,
		lazyEffect : "fade",

		afterInit: function( a , b, c){
			var owl = this;
			console.log( '-afterInit-' );
			my_ow_slider( owl )
		},

		afterMove: function( a , b, c){
			var owl = this;
			console.log( '-afterMove-' );
			my_ow_slider( owl )
		}
	});





} );