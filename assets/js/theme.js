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

/**
 * Parallax Section
 */
( function() {

    jQuery(window).resize(function(){
        onepressParallax();
    });

    function onepressParallax() {
        var isMobile = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        };

        var testMobile = isMobile.any();

        jQuery('.section-has-parallax').each(function() {
            var $this = jQuery(this);
            var bg    = $this.find('.parallax_bg');

            jQuery(bg).css('backgroundImage', 'url(' + $this.data('bg') + ')');

            if (testMobile == null) {
                jQuery(bg).addClass('not-mobile');
                jQuery(bg).removeClass('is-mobile');
                jQuery(bg).parallax('50%', 0.4);
            }
            else {
                //jQuery(bg).css('backgroundAttachment', 'inherit');
                jQuery(bg).removeClass('not-mobile');
                jQuery(bg).addClass('is-mobile');

            }
        });
    }
})();

/**
 * Call magnificPopup when use
 */
( function() {

    jQuery('.popup-video').magnificPopup({
        //disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
        zoom: {
            enabled:true
        }
    });

})();



jQuery( document ).ready( function( $ ){

	/**
	* Initialise Menu Toggle
	*/
	jQuery('#nav-toggle').on('click', function(event){
		event.preventDefault();
		jQuery('#nav-toggle').toggleClass('nav-is-visible');
		jQuery('.main-navigation .nav-menu').toggleClass("nav-menu-mobile");
		jQuery('.header-widget').toggleClass("header-widget-mobile");
	});

	jQuery('.nav-menu li.menu-item-has-children, .nav-menu li.page_item_has_children').each( function() {
        jQuery(this).prepend('<div class="nav-toggle-subarrow"><i class="fa fa-angle-down"></i></div>');
	});

	jQuery('.nav-toggle-subarrow, .nav-toggle-subarrow .nav-toggle-subarrow').click(
		function () {
			jQuery(this).parent().toggleClass("nav-toggle-dropdown");
		}
	);


    /**
     * Fixed header
     *
     */
	var is_fixed_header = $('.site-header.sticky-header').length > 0 ? true: false;
	var is_transparent = false;
	if ( $('.site-header.sticky-header').hasClass( 'transparent' ) ){
		is_fixed_header = true;
		is_transparent  = true;
	}

	if ( is_fixed_header ) {

		$('.site-header.sticky-header').eq(0).wrap( '<div class="site-header-wrapper">' );
		var $wrap =  $( '.site-header-wrapper');
		$wrap.addClass( 'no-scroll' );

		$(document).scroll(function () {
			var header_fixed = $('.site-header').eq(0);
			var header_parent = header_fixed.parent();
			var header_h = header_fixed.height() || 0;
			var p_to_top = header_parent.position().top;
			var topbar = $('#wpadminbar').height() || 0;
			if (topbar > 0) {
				var topbar_pos = $('#wpadminbar').css('position');
				if ('fixed' !== topbar_pos) {
					p_to_top += topbar ;
					topbar = 0;
				}
			}

			var scrollTop =  $(document).scrollTop();

			if ( scrollTop > 0 ) {
				if (!is_transparent) {
					$wrap.height(header_h);
				}
				$wrap.addClass('is-fixed').removeClass('no-scroll');
				header_fixed.addClass('header-fixed');

				if ( scrollTop <  p_to_top + topbar ) {
					header_fixed.css('top', scrollTop + 'px');
				} else {
					header_fixed.css('top', topbar + 'px');
				}

				header_fixed.css('top', topbar + 'px');
				header_fixed.stop().animate({}, 400);
			} else {
				header_fixed.removeClass('header-fixed');
				header_fixed.css('top', 'auto');
				header_fixed.stop().animate({}, 400);
				if (!is_transparent) {
					$wrap.height('');
				}
				$wrap.removeClass('is-fixed').addClass('no-scroll');
			}
		});


		if ( $( '#wpadminbar').length > 0 ) {
			//$( '.site-header').css( 'top', $( '#wpadminbar').height() +'px' );
		}

		$( window ).resize( function(){
			if ( $( '#wpadminbar').length > 0 ) {
			//	$( '.site-header').css( 'top', $( '#wpadminbar').height() +'px' );
			}
		} );
       // $(document).trigger( 'scroll' );

	}



    // Get the header height and wpadminbar height if enable.
    var h = jQuery('#wpadminbar').height() + ( jQuery('.site-header.sticky-header').height()  );

    // Navigation click to section.
    jQuery('.home #site-navigation li a[href*="#"]').on('click', function(event){
        event.preventDefault();
        smoothScroll(jQuery(this.hash));
    });

    // Add active class to menu when scroll to active section.
    jQuery(window).scroll(function() {
        var currentNode = null;
        jQuery('.screenr-section').each(function(){
            var currentId = jQuery(this).attr('id');

            if(jQuery('#'+currentId).length>0 ) {
                if(jQuery(window).scrollTop() >= jQuery('#'+currentId).offset().top - h-10) {
                    currentNode = currentId;
                }
            }
        });
        jQuery('#site-navigation li').removeClass('current-menu-item').find('a[href$="#'+currentNode+'"]').parent().addClass('current-menu-item');
    });

    // Move to the right section on page load.
    jQuery(window).load(function(){
        var urlCurrent = location.hash;
        if (jQuery(urlCurrent).length>0 ) {
            smoothScroll(urlCurrent);
        }
    });

    // Other scroll to elements
    jQuery( 'body' ).on('click', '.swiper-slide a[href*="#"]:not([href="#"]), .parallax-content a[href*="#"]:not([href="#"]), .back-top-top', function(event){
        event.preventDefault();
        smoothScroll(jQuery(this.hash));
    });

    // Smooth scroll animation
    function smoothScroll(urlhash) {
        if ( urlhash.length <= 0 ) {
            return false;
        }
        jQuery("html, body").animate({
            scrollTop: (jQuery(urlhash).offset().top - h) + "px"
        }, {
            duration: 800,
            easing: "swing"
        });
        return false;
    }

	// Next section
	$( 'body').on( 'click', '.btn-next-section', function( e ){
		e.preventDefault();
		var current_section = $( this).closest( '.screenr-section' );
		if ( current_section.next( '.screenr-section').length > 0 ){
			jQuery("html, body").animate({
				scrollTop: (current_section.next( '.screenr-section').offset().top - h + 30 ) + "px"
			}, {
				duration: 800,
				easing: "swing"
			});
		}

	} );


    /**
     * Custom  Slider
     *
     */

	var video_support = function(){
		return !!document.createElement('video').canPlayType;
	};
	var is_video_support = video_support();

	function set_swiper_full_screen_height(){
		var w =  $( window).width();
		var h = $( window).height();

		var admin_bar_h = 0;
		if ( $( '#wpadminbar').length > 0 ) {
			admin_bar_h = $( '#wpadminbar').height();
		}
		var header_h = 0;
		if ( $( '.site-header').length > 0 && ! $( '.site-header').hasClass( 'transparent' )  ){
			header_h = $( '.site-header').eq( 0).height();
		}

		h = h - admin_bar_h - header_h;

		var header_pos = '';
		if ( $( '.site-header').length > 0 ){
			header_pos = $( '.site-header').eq(0).css('position');
		}

		$( '.swiper-slider').each( function(){
			var s =  $(this );
			if ( s.hasClass( 'full-screen' ) ) {
				if ( header_pos === 'fixed' ) {
					s.css({
						height: h+'px',
						marginTop: header_h +'px',
					});
				} else {
					s.css({
						height: h+'px',
					});
				}
			}
		} );
	}

    var slider_overlay_opacity = $('.swiper-slider.fixed .swiper-container .overlay').eq( 0 ).css( 'opacity' ) || .35;
	console.log( slider_overlay_opacity );
	//slider_overlay_opacity = 0.35;
	$( window ).scroll( function(){
		var scrolled = $(window).scrollTop();

		var header_pos = false;
		if ( $( '.site-header').length > 0 ){
			header_pos = $( '.site-header').eq( 0).hasClass('sticky-header');
		}

		var admin_bar_h = 0;
		if ( $( '#wpadminbar').length > 0 ) {
			admin_bar_h = $( '#wpadminbar').height();
		}
		var header_h = 0;
		if ( $( '.site-header').length > 0 && ! $( '.site-header').hasClass( 'transparent' )  ){
			header_h = $( '.site-header').eq( 0).height();
		}

		var st = scrolled * 0.7;

        // calc opactity
        var  o = slider_overlay_opacity;
        var wh =  $( window).height();
        if ( wh > scrolled ) {
            o = ( scrolled/wh ) * 1.5;
        }
        if ( o >= 0.8 ){
            o = 0.8;
        }

        if ( o <= slider_overlay_opacity ){
            o = slider_overlay_opacity;
        }

		if ( header_pos && st > admin_bar_h + header_h ) {
			//console.log( header_h + '--' + admin_bar_h );
			var _t =  st - (  admin_bar_h + header_h );
			$('.swiper-slider.fixed .swiper-container').css({
                'top': +(_t) + 'px',
            });
            $('.swiper-slider.fixed .swiper-container .overlay').css( {
                'opacity': o
            } );

			$('.swiper-slider.fixed .swiper-container .swiper-slide-intro').css( {
                'opacity': 1 - o
            } );
		} else {
			//console.log( header_h + '-====-' + admin_bar_h );
			$('.swiper-slider.fixed .swiper-container').css({
                'top': '0px',
            });

            $('.swiper-slider.fixed .swiper-container .overlay').css( {
                'opacity': o
            } );

			$('.swiper-slider.fixed .swiper-container .swiper-slide-intro').css( {
				'opacity': 1
			} );
		}

	} );

    if ( Screenr.full_screen_slider == '1' ) {
        set_swiper_full_screen_height();

        $( window ).resize( function(){
            set_swiper_full_screen_height();
        } );
    }


    var slider_number_item = $( '.swiper-slider .swiper-slide').length;

	var swiper = new Swiper('.swiper-container', {
		// Disable preloading of all images
		preloadImages: false,
		loop: slider_number_item >  1 ? true: false,
		// Enable lazy loading
		lazyLoading: true,
		//preloadImages: false,
		autoplay: 5000,
		speed:  700,
		effect: 'slide', // "slide", "fade", "cube", "coverflow" or "flip"
		//direction: 'vertical',
		pagination: '.swiper-pagination',
		paginationClickable: true,

		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',

		onInit: function( swiper ){

            var slide =  swiper.slides[ swiper.activeIndex ];

            $( slide ).addClass( 'activated' );
            var n = $( slide ) .attr( 'data-swiper-slide-index' ) || 0;
            n = parseInt( n );
            $( '.slide-current').text( n + 1 );
            $( '.slide-total').text( slider_number_item );


            if ( ! is_video_support ) {
				return;
			}


			if ( $( 'video',slide ).length > 0 ) {
				var v = $('video', slide ).eq(0);
				//if ( slider.vars.slideshow ) {
				if ( v[0].readyState >= 2 ) {
					swiper.stopAutoplay();
					v[0].currentTime = 0;
					v[0].play();
				}
			}


		},
        onSlideChangeStart: function( swiper ) {

            var slide = swiper.slides[swiper.activeIndex];
            var n = $( slide ).attr( 'data-swiper-slide-index' ) || 0;
            n = parseInt( n );
            $( '.slide-current').text( n + 1 );

			if ( ! is_video_support ) {
				return;
			}

			// Need to pause all videos in the slider
			swiper.slides.each(function (index, slide) {

				if ($('video', slide).length > 0) {
					$('video', slide).each(function () {
						var v = $(this);
						if ( v[0].readyState >= 2 ) {
							v[0].pause();
						}
					});
				}
			});

			if ($('video', slide).length > 0) {
				var v = $('video', slide).eq(0);
                //console.log( 'Video Rate: '+ v[0].readyState );
				if ( v[0].readyState >= 2 ) {
					swiper.stopAutoplay();
					v[0].currentTime = 0;
					v[0].play();
				}
			}
		},
		onSlideChangeEnd: function( swiper ){

			var slide = swiper.slides[swiper.activeIndex];
			// Need to pause all videos in the slider
			swiper.slides.each(function (index, slide) {
				$( slide).removeClass( 'activated' );
			});

			$( slide).addClass( 'activated' );
		}

	});


    if ( $( '.swiper-slider video').length > 0 ) {
        // swiper-pagination
        if ( slider_number_item === 1 ) {
            $( '.swiper-slider .swiper-pagination').hide();
        }

        $( '.swiper-slider video').on( 'timeupdate', function(){
            var v = $( this ) .eq(0);
            if ( v[0].readyState >= 2 ) {
                v.on('timeupdate', function () {

                    var currentPos = v[0].currentTime; //Get currenttime
                    var maxduration = v[0].duration; //Get video duration
                    if (currentPos >= maxduration) {
                        //console.log( slider_number_item );
                        if ( slider_number_item === 1 ) {
                            //v[0].pause();
                            //v[0].currentTime = 0;
                           // v[0].play();
                        } else {
                            v[0].pause();
                            swiper.slideNext();
                            swiper.startAutoplay();
                        }
                    }
                    //var percentage = 100 * currentPos / maxduration; //in %
                    //$('.swiper-timebar').text('Playing: ' + percentage + '%' + ' at ' + currentPos + '(s)');
                });
            }

        } );
    }

    //hover on button

    $( '.swiper-button-prev, .swiper-button-next').hover( function(){
        var b = $( this );
        var w = b.find( '.slide-count').width();
        b.animate({
            width: "+="+w,
        }, 400 , function(){
            b.addClass( 'active' );
        });
    }, function(){
        var b = $( this );
        var w = b.find( '.slide-count').width();
        b.removeClass( 'active' );
        b.animate({
            width: "-="+w,
        }, 400 , function(){
            b.removeClass( 'active' );
        });
    } );


} );
