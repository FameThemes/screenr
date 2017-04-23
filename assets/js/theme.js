function string_to_number( string ) {
	if ( typeof string === 'number' ) {
		return string;
	}
	if ( typeof string === 'string' ) {
		var n = string.match(/[^\d\.]+$/);
        if ( ! n ) {
            n = string.match(/[\d\.]+$/);
        }
		if (n) {
			return parseFloat(n[0]);
		} else {
			return 0;
		}
	}
	return 0;
}

function string_to_bool( v ) {
	if (  typeof v === 'boolean' ){
		return v;
	}

	if (  typeof v === 'number' ){
		return v === 0  ? false : true;
	}

	if (  typeof v === 'string' ){
		if ( v === 'true' || v === '1' ) {
			return true;
		} else {
			return false;
		}
	}

	return false;
}


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


jQuery( document ).ready( function( $ ){

    var mobile_max_width =  1140; // Media max width for mobile
    var main_navigation = jQuery('.main-navigation .nav-menu');
    var stite_header =  $( '.site-header' );

	/**
	* Initialise Menu Toggle
	*/
	jQuery('#nav-toggle').on('click', function(event){
		event.preventDefault();
		jQuery('#nav-toggle').toggleClass('nav-is-visible');
		jQuery('.main-navigation .nav-menu').toggleClass("nav-menu-mobile");
		jQuery('.header-widget').toggleClass("header-widget-mobile");

        if ( main_navigation.hasClass( 'nav-menu-mobile' ) && $( window).width() <= mobile_max_width ) {
            var h = $( window).height( ) - stite_header.height();
            main_navigation.css( {
                height: h,
                overflow: 'auto',
            });
        } else {
            main_navigation.removeAttr( 'style' );
        }

	});

    $( window).resize( function(){
        if ( main_navigation.hasClass( 'nav-menu-mobile' ) && $( window).width() <= mobile_max_width ) {
            var h = $( window).height( ) - stite_header.height();
            main_navigation.css( {
                height: h,
                overflow: 'auto',
            });
        } else {
            main_navigation.removeAttr( 'style' );
        }
    } );


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

		$( window ).scroll(function () {

			var scrolled = $(window).scrollTop();
			if (scrolled > 0) {
				$('body').addClass('scrolled');
			} else {
				$('body').removeClass('scrolled');
			}

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
            var start = 0;
            if ( is_fixed_header && is_transparent ) {
                if ( $( '.swiper-slider' ).length ) {
                    start = $( '.swiper-slider' ).eq( 0 ).offset( ).top + $( '.swiper-slider' ).outerHeight();
                    start  = start - header_h - topbar ;
                } else if ( $( '.page-header-cover' ).length ) {
                    start = $( '.page-header-cover' ).eq( 0 ).offset( ).top + $( '.page-header-cover' ).outerHeight();
                    start  = start - header_h - topbar ;
                }
            }

			var scrollTop =  $(document).scrollTop();

			if ( scrollTop > start  ) {
				if ( ! is_transparent ) {
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

	}

	function get_header_top_height(){
		var hh = ( jQuery('#wpadminbar').height() || 0 ) + ( jQuery('.site-header.sticky-header').height() || 0  );
        if ( $( '#masthead.site-header').hasClass( 'transparent' ) ){
            if ( $( window ).scrollTop() == 0 ) {
                hh -= 15;
            }
        }
        return  hh;
	}

    // Add active class to menu when scroll to active section.
	if ( string_to_bool( Screenr.is_home_front_page ) ) {
		// Navigation click to section.
		jQuery('.home #site-navigation li a[href*="#"]').on('click', function(event){
			event.preventDefault();
			var _h = this.hash;
			if ( $( '.nav-menu' ).hasClass( 'nav-menu-mobile' ) ) {
				$( '#nav-toggle' ).trigger( 'click' );
			}
			smoothScroll ( _h );
		});

		jQuery( window ).scroll(function () {
			var currentNode = null;
			var header_top_height = get_header_top_height();
			jQuery('.site-main section').each(function () {
				var s = $(this);
				var currentId = s.attr('id') || '';
				if ( jQuery( window ).scrollTop() >= s.offset().top - header_top_height - 10 ) {
					currentNode = currentId;
				}

			});

			jQuery('#site-navigation li').removeClass('current-menu-item');
			if ( currentNode ) {
				jQuery('#site-navigation li').find('a[href$="#' + currentNode + '"]').parent().addClass('current-menu-item');
			}
		});
	} else {
		jQuery( '#site-navigation li.menu-item-type-custom' ).removeClass('current-menu-item');
	}

    // Move to the right section on page load.
    jQuery( window ).load( function(){
        var urlCurrent = location.hash;
        if (jQuery(urlCurrent).length>0 ) {
            smoothScroll(urlCurrent);
        }
    });

    // Other scroll to elements
    jQuery( 'body' ).on('click', '.swiper-slide a[href*="#"]:not([href="#"]), .parallax-content a[href*="#"]:not([href="#"]), .back-top-top', function(event){
        event.preventDefault();
		if ( $( '.nav-menu' ).hasClass( 'nav-menu-mobile' ) ) {
			$( '#nav-toggle' ).trigger( 'click' );
		}
        smoothScroll( jQuery( this.hash ) );
    });

    // Smooth scroll animation
    function smoothScroll( urlhash ) {
        if ( urlhash.length <= 0 ) {
            return false;
        }
		var header_top_height = get_header_top_height();
        jQuery("html, body").animate({
            scrollTop: ( jQuery( urlhash ).offset().top - header_top_height + 3 ) + "px"
        }, {
            duration: 800,
            easing: "swing"
        });
        return false;
    }

	// Next section
	$( 'body').on( 'click', '.btn-next-section', function( e ){
		e.preventDefault();
		var current_section = $( this).closest( 'section' );
		if ( current_section.next( ).length > 0 ) {
			smoothScroll( '#'+ current_section.next().attr( 'id' ) );
		}

	} );


    /**
     * Custom  Slider
     */
	var video_support = function(){
		return !!document.createElement('video').canPlayType;
	};
	var is_video_support = video_support();

	function set_swiper_full_screen_height(){

        if ( ! $( 'body' ).hasClass( 'page-template-template-frontpage' ) ) {
            return false;
        }
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
					});
				} else {
					s.css({
						height: h+'px',
					});
				}
			}
		} );
	}

	//----------------------------------------------

	function set_slider_padding(){
		var  hh = 0;
		// Set slider header padding
		if ( $( '#page-header-cover' ).length > 0 ) {
			hh = $( '.site-header.transparent' ).eq( 0 ).height();
			hh = hh / 2;
			$( '.swiper-intro-inner' ).css( 'margin-top', hh + 'px' );
		}

	}

	set_slider_padding();
	$( window ).resize( function(){
		set_slider_padding();
	} );


    var slider_number_item = $( '.swiper-slider .swiper-slide').length;
	var autoplay = $( '.swiper-container' ).data( 'autoplay' ) || 0;

	var swiper = new Swiper('.swiper-container', {
		// Disable preloading of all images
		preloadImages: false,
		loop: slider_number_item >  1 ? true: false,
		// Enable lazy loading
		lazyLoading: true,
		//preloadImages: false,
		autoplay: string_to_number( Screenr.autoplay ),
		speed:  string_to_number( Screenr.speed ) ,
		effect: Screenr.effect, // "slide", "fade", "cube", "coverflow" or "flip"
		//direction: 'vertical',
		pagination: '.swiper-pagination',
		paginationClickable: true,
		grabCursor: false,

		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',

        allowSwipeToPrev:  slider_number_item >  1 ? true: false,
        allowSwipeToNext:  slider_number_item >  1 ? true: false,

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
            // Need to pause all videos in the slider
            swiper.slides.each(function (index, _slide) {
                if ( $('video', _slide ).length > 0 ) {
                    $('video', _slide ).each(function () {
                        var v = $(this);
                        try {

                            v[0].currentTime = 0;
                            v[0].pause();

                            if ( v[0].readyState >= 2 ) {
                            }

                            v.on( 'ended', function(){
                                if ( slider_number_item === 1 ) {
                                    v[0].pause();
                                    v[0].currentTime = 0;
                                    v[0].play();
                                } else {
                                    v[0].pause();
                                    v[0].currentTime = 0;
                                    swiper.slideNext();
                                    swiper.startAutoplay();
                                }
                            } );

                        } catch ( e ){

                        }

                    });
                }
            });

            if ( $('video', slide).length > 0 ) {
                var v = $('video', slide).eq(0);
                try {
                    swiper.stopAutoplay();
                    v[0].currentTime = 0;
                    v[0].play();
                } catch ( e ){

                }
            }

		},
        onSlideChangeStart: function( swiper ) {
            var slide = swiper.slides[ swiper.activeIndex ];

            // Current number slide
            var n = $( slide ).attr( 'data-swiper-slide-index' ) || 0;
            n = parseInt( n );
            $( '.slide-current').text( n + 1 );

		},
		onSlideChangeEnd: function( swiper ){
			var slide = swiper.slides[swiper.activeIndex];
			// Need to pause all videos in the slider
			swiper.slides.each(function (index, slide) {
				$( slide).removeClass( 'activated' );
			});

			$( slide).addClass( 'activated' );

            if ( ! is_video_support ) {
                return;
            }

            // Need to pause all videos in the slider
            swiper.slides.each(function (index, slide) {
                if ($('video', slide).length > 0) {
                    $('video', slide).each(function () {
                        var v = $(this);
                        try {
                            v[0].currentTime = 0;
                            v[0].pause();
                        } catch ( e ){

                        }
                    });
                }
            });

            if ( $('video', slide).length > 0 ) {
                var v = $('video', slide).eq(0);
                //v[0].readyState >= 2
                try {
                    swiper.stopAutoplay();
                    v[0].currentTime = 0;
                    v[0].play();
                } catch ( e ){

                }
            }
		}

	});


    // Hide pagination if have 1 slide
    if ( slider_number_item === 1 ) {
        $( '.swiper-slider .swiper-pagination').hide();
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


	if (  string_to_bool( Screenr.slider_parallax ) || ( Screenr.header_layout !=='default' && $( '#page-header-cover' ).length > 0  ) ) {
		var slider_overlay_opacity = $('.swiper-slider.fixed .swiper-container .overlay').eq( 0 ).css('opacity') || .35;
		$( window ).scroll( function () {
			var scrolled = $( window ).scrollTop();
			var header_pos = false;
			if ( $('.site-header.sticky-header').length > 0 ) {
				header_pos = $('.site-header').eq(0).hasClass('sticky-header');
			}

			var admin_bar_h = 0;
			if ( $( '#wpadminbar' ).length > 0 ) {
				admin_bar_h = $( '#wpadminbar' ).height();
			}
			var st = scrolled * 0.7;
			// calc opactity
			var _t = 0;
			var slider_height = $('.swiper-slider').eq(0).height();
			var o = ( scrolled / ( slider_height / 2 ) );

			if ( o > 1 ) {
				o = 1;
			}
			if ( o < 0 ) {
				o = 0;
			}
			var _oo = ( o > .8 ) ? .8 : o;
			if ( _oo < slider_overlay_opacity )  {
				_oo = slider_overlay_opacity;
			}

			if ( header_pos && st > admin_bar_h ) {
				_t = st - (  admin_bar_h  );
				$('.swiper-slider .swiper-container .swiper-slide-intro').css({
					'opacity': 1 - o
				});
			} else {
				_t = 0;
				$('.swiper-slider .swiper-container .swiper-slide-intro').css({
					'opacity': 1 - o
				});
			}

			$('.swiper-slider .swiper-container').css({
				'top': _t + 'px',
			});
			$('.swiper-slider .swiper-container .overlay').css({
				'opacity': _oo
			});

			var sch = swiper.container.outerHeight();

			if (scrolled >= sch / 4) {
				swiper.container.addClass('over-1-4');
			} else {
				swiper.container.removeClass('over-1-4');
			}
			if (scrolled >= sch / 3) {
				swiper.container.addClass('over-1-3');
			} else {
				swiper.container.removeClass('over-1-3');
			}
			if (scrolled >= sch / 2) {
				swiper.container.addClass('over-1-2');
			} else {
				swiper.container.removeClass('over-1-2');
			}

			if (scrolled >= sch * 2 / 3) {
				swiper.container.addClass('over-2-3');
			} else {
				swiper.container.removeClass('over-2-3');
			}

			var next_button = swiper.container.find('.btn-next-section');
			var _btn_top = next_button.attr('data-top') || '';

			var btop = 0;
			if (!_btn_top || _btn_top === '') {
				btop = next_button.css('top');
			} else {
				btop = _btn_top;
			}
			if (top === '') {
				btop = 0;
			} else {
				btop = parseInt(btop);
			}
			if ( ! _btn_top ) {
				next_button.attr('data-top', btop);
			}
			if ( _t > 0 ) {
				next_button.css({'top': ( btop - _t ) + 'px'});
			} else {
				next_button.css({'top': ''});
			}

			$.each( swiper.slides, function ( index, slide ) {
				var slider = $( slide );
				var intro = slider.find('.swiper-slide-intro'), intro_inner = intro.find('.swiper-intro-inner');
				var _padding_top = intro_inner.css('padding-top') || 0;
				_padding_top = parseFloat( _padding_top );
				var intro_top = _padding_top;

				intro.css({'top': ''});
				var top = intro.css('top');
				top = parseInt(top);

				if ( scrolled > 0 ) {
					var _s_t, pt_top = 0;
					if ( intro_top > 0 ) {
						pt_top = scrolled /  intro_top ;
					} else {
						pt_top = .6;
					}
					if ( pt_top >= 1 ) {
						pt_top = 1;
					}
					if ( pt_top < .3 ) {
						pt_top = .3 ;
					}

					_s_t = top - scrolled + _t ;
					_s_t -= _s_t * pt_top;
					intro.css({'top': ( _s_t ) + 'px'});
				} else {
					intro.css({'top': ''});
				}

			});
		});
	}

	if ( Screenr.full_screen_slider == '1' ) {
		set_swiper_full_screen_height();
		$( window ).resize( function(){
			set_swiper_full_screen_height();
		} );
	}

    $( window ).resize( function(){
        swiper.container.find( '.swiper-slide-intro, .btn-next-section' ).removeAttr( 'data-top' ).removeAttr( 'style' );
        $( window ).trigger( 'scroll' );
    } );


	$( '.swiper-slider' ).bind( 'preview_event_changed', function(){
		alert( 'section_slider_changed' );
	} );


    $('.section-parallax, .parallax-hero').bind('inview', function ( event, visible ) {
        if ( visible == true ) {
        } else {
        }
    });

    var lastScrollTop = 0;
    // Paralax effect
    function parallaxPosition( direction ){
        var top = $( window ).scrollTop();
        var wh = $( window).height();
        $('.section-parallax, .parallax-hero').each( function(  ){
            var $el = $( this );
            var h = $el.height();
            var r = .3;
            if ( wh > h ) {
                r = .3;
            } else {
                r = .6;
            }

            var section_h = $el.height();
            var is_inview = $el.data( 'inview' );
            if ( is_inview ) {
                var offsetTop = $el.offset().top;
                var diff, bgTop;
                diff = top - offsetTop;
                bgTop = Math.round( diff * r );
                if ( bgTop > h ) {
                    bgTop = h;
                }
                if ( wh > h * 2 ) {
                    $('.parallax-bg', $el).css('background-position', '50% ' + ( 0 - ( section_h + bgTop ) ) + 'px');
                } else {
                    $('.parallax-bg', $el).css('background-position', '50% ' + ( bgTop ) + 'px');
                }
            }

        } );
    }
    $(window).scroll(function(e){
        var top = $( window ).scrollTop();
        var direction = '';
        if ( top > lastScrollTop ){
            direction = 'down';
        } else {
            direction = 'up';
        }
        lastScrollTop = top ;
        parallaxPosition( );
    });
    $(window).resize( function(){
        parallaxPosition( );
    } );

    $(window).trigger('scroll');


} );



jQuery( document ).ready( function( $ ){

    /**
     * Video lightbox
     */
    if ($.fn.lightGallery ) {
        $(".videolightbox-popup").lightGallery({});
    }

    // Counter
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    // Video
    jQuery('.site-content').fitVids();

    // Ajax load more posts
	$( 'body' ).on( 'click', '.content-grid-loadmore.blt-ajax', function( e ){
		e.preventDefault();
		var button = $( this );
		if ( ! button.prop( 'is_loading' ) ) {
			button.prop( 'is_loading', true );
			button.addClass( 'loading' );

			button.find( 'i' ).removeClass( 'fa-angle-double-down' ).addClass( 'fa-spinner fa-spin' );

			var paged = button.prop('paged') || 2;
			// It's always form 2 because page 1 already there.
			if ( paged <= 2 ) {
				paged = 2;
			}
			var data = {
				'action': 'screenr_ajax_posts',
				'paged': paged
			};

			jQuery.get( Screenr.ajax_url, data, function ( response ) {
				response = '<div>' + response + '</div>';
				response = $( response );
				button.prop( 'paged', paged + 1 );
				button.prop( 'is_loading', false );
				button.removeClass( 'loading' );
				button.find( 'i' ).removeClass( 'fa-spinner fa-spin' ).addClass( 'fa-angle-double-down' );

				var num_post = $( 'article', response ).length;

				$('article', response).each(function (index, post) {
					$('#section-news-posts').append( post );
				});

				if (num_post <= 0) {
					button.hide();
				} else {

				}

			});
		}

	} );

} );


// Gallery
jQuery( document ).ready( function( $ ){

	/**
	 * Gallery
	 */
	function _gallery_init( $context ){
		// justified
		if ( $.fn.justifiedGallery ) {
            $( '.gallery-justified', $context ).imagesLoaded( function() {
                $( '.gallery-justified', $context).each( function(){
                    var margin = $( this).attr( 'data-spacing' ) || 20;
                    margin = string_to_number( margin );
                    $( this ).justifiedGallery({
                        rowHeight: 120,
                        margins: margin,
                        selector: 'a, div:not(.spinner), .inner'
                    });
                } );
            });
		}


		// Slider
		if ( $.fn.owlCarousel ) {

            $( '.gallery-slider', $context ).imagesLoaded( function() {
                // Slider
                $( '.gallery-slider', $context ).owlCarousel({
                    items: 1,
                    itemsCustom: false,
                    itemsDesktop: 1,
                    itemsDesktopSmall: 1,
                    itemsTablet: 1,
                    itemsTabletSmall: false,
                    itemsMobile: 1,
                    singleItem: true,
                    itemsScaleUp: false,

                    slideSpeed : 200,
                    paginationSpeed : 800,
                    rewindSpeed : 1000,
                    autoPlay : 4000,
                    stopOnHover : true,

                    navigation : true,
                    navigationText : ["<i class='lg-icon'></i>", "<i class='lg-icon'></i>"],

                    pagination : false,
                    paginationNumbers : false,
                    autoHeight : true,
                });
            });

            $('.gallery-carousel', $context).imagesLoaded( function() {
                $('.gallery-carousel', $context).each(function () {
                    var n = $(this).attr('data-col') || 5;
                    n = string_to_number(n);
                    if (n <= 0) {
                        n = 5;
                    }

                    $(this).owlCarousel({
                        items: n,
                        itemsCustom: false,
                        itemsDesktop: [1199, ( n > 4) ? 4 : n],
                        itemsDesktopSmall: [979, ( n > 3) ? 3 : n],
                        itemsTablet: [768, ( n > 2) ? 2 : n],
                        itemsTabletSmall: false,
                        itemsMobile: [479, ( n > 2) ? 2 : n],
                        singleItem: false,
                        itemsScaleUp: false,

                        slideSpeed: 200,
                        paginationSpeed: 800,
                        rewindSpeed: 1000,
                        autoPlay: 4000,
                        stopOnHover: true,

                        navigation: true,
                        navigationText: ["<i class='lg-icon'></i>", "<i class='lg-icon'></i>"],

                        pagination: false,
                        paginationNumbers: false,
                    });

                });
            });

		}


		function isotope_init (){
			if ( $.fn.isotope ) {

                $(".gallery-masonry", $context).imagesLoaded( function() {
                    $(".gallery-masonry", $context).each(function () {
                        var m = $(this);
                        var gutter = m.attr('data-gutter') || 10;
                        var columns = m.attr('data-col') || 5;

                        //console.log(columns);

                        gutter = string_to_number(gutter);
                        columns = string_to_number(columns);

                        var w = $(window).width();
                        if (w <= 940) {
                            columns = columns > 2 ? columns - 1 : columns;
                        }

                        if (w <= 720) {
                            columns = columns > 3 ? 3 : columns;
                        }

                        if (w <= 576) {
                            columns = columns > 2 ? 2 : columns;
                        }

                        //gutter = gutter / 2;
                        // m.parent().css({'margin-left': -gutter, 'margin-right': -gutter});
                        m.find('.g-item').css({'width': ( 100 / columns  ) + '%', 'float': 'left', 'padding': 0});
                        // m.find('.g-item .inner').css({'padding': gutter / 2});
                        m.isotope({
                            // options
                            itemSelector: '.g-item',
                            percentPosition: true,
                            masonry: {
                                columnWidth: '.inner'
                            }
                        });

                    });
                });
			}
		}

		isotope_init();
		$( window ).resize( function(){
			isotope_init();
		} );

		if ( $.fn.lightGallery ) {
			$('.enable-lightbox', $context).lightGallery({
				mode: 'lg-fade',
				selector: 'a',
				// cssEasing : 'cubic-bezier(0.25, 0, 0.25, 1)'

			});
		}
	}

	_gallery_init( $( '.gallery-content' ) );

	if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh ) {
		wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
			if ( placement.partial.id == 'section-gallery' ) {
				_gallery_init( placement.container.find( '.gallery-content' ) );
			}
		} );
	}

} );

