/*!
 * jQuery Bully Plugin v0.1.3
 * Examples and documentation at http://pixelgrade.github.io/rellax/
 * Copyright (c) 2016 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
;(
    function( $, window, document, undefined ) {

        var $window = $( window ),
            windowHeight = $window.height(),
            elements = [],
            $bully,
            lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0),
            current = 0,
            inversed = false,
            frameRendered = true;

        $bully = $( '<div class="c-bully">' ).appendTo( 'body' );
        $current = $( '<div class="c-bully__bullet c-bully__bullet--active">' ).appendTo( $bully );

        (
            function update() {
                if ( frameRendered !== true ) {

                    var count = 0,
                        inverse = true;

                    var lastItemId = false;
                    var top = 0;
                    if ($( '#masthead' ).hasClass( 'is-sticky' ) ) {
                        top = $( '#masthead' ).outerHeight();
                    }

                    $.each( elements, function( i, element ) {
                        if ( lastScrollY >= element.offset.top - top - windowHeight / 2 ) {
                            count = count + 1;
                            inverse = lastScrollY < element.offset.top - top + element.height - windowHeight / 2;
                            lastItemId = element.element.id;

                        }

                    } );

                    /*
                    if ( inversed !== inverse ) {
                        inversed = inverse;
                        $bully.toggleClass( 'c-bully--inversed', inversed );
                    }
                    */

                    // New insverse
                    if ( lastItemId && typeof Screenr_Bully.sections[ lastItemId ] !== "undefined" ) {
                        if ( Screenr_Bully.sections[ lastItemId ].inverse ) {
                            $bully.addClass( 'c-bully--inversed' );
                        } else {
                            $bully.removeClass( 'c-bully--inversed' );
                        }

                    }

                    if ( count !== current ) {
                        var activeBullet = $bully.find( '#bully__'+lastItemId );
                        var bullyOffset = $bully.offset();
                        var offset = 0;
                        if ( activeBullet.length > 0 ) {
                            offset = activeBullet.offset().top - bullyOffset.top;
                        }

                        //var offset = $bully.children( '.c-bully__bullet' ).not( '.c-bully__bullet--active' ).first().outerHeight( true ) * ( count - 1 );

                        $current.removeClass( 'c-bully__bullet--squash' );
                        setTimeout( function() {
                            $current.addClass( 'c-bully__bullet--squash' );
                        } );
                        $current.css( 'top', offset );
                        current = count;

                        $bully.find( '.c-bully__bullet--pop' ).removeClass('c-bully__current');
                        activeBullet.addClass('c-bully__current');

                    }
                }

                window.requestAnimationFrame( update );
                frameRendered = true;
            }
        )();

        function reloadAll() {
            $.each( elements, function( i, element ) {
                element._reloadElement();
            } );
        }

        function staggerClass( $elements, classname, timeout ) {

            $.each( $elements, function( i, obj ) {
                obj.$bullet.addClass( classname );
                /*
                var stagger = i * timeout;

                setTimeout( function() {
                    obj.$bullet.addClass( classname );
                }, stagger );
                */
            } );
        }

        $window.on( 'load', function( e ) {
            staggerClass( elements, 'c-bully__bullet--pop', 400 );
            frameRendered = false;
        } );

        $window.on( 'scroll', function( e ) {
            if ( frameRendered === true ) {
                lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
            }
            frameRendered = false;
        } );

        $window.on( 'load resize', function() {
            reloadAll();
        } );

        function Bully( element, options ) {
            this.element = element;
            this.options = $.extend( {}, $.fn.bully.defaults, options );

            var label = '';
            var id = element.id;

            var self = this,
                $bullet = $( '<div id="bully__'+id+'" class="c-bully__bullet">' );

            if( Screenr_Bully.enable_label ) {
                if ( id && typeof Screenr_Bully.sections[ id ] !== "undefined" ) {
                    label = Screenr_Bully.sections[ id ].title;
                }
                if ( label ) {
                    $bullet.append( '<div class="c-bully__title">'+label+'</div>' );
                }
            }

            $bullet.data( 'bully-data', self ).appendTo( $bully );
            $bullet.on( 'click', function( event ) {
                event.preventDefault();
                event.stopPropagation();

                self.onClick();
            } );

            this.$bullet = $bullet;

            self._reloadElement();
            elements.push( self );
            current = 0;
        }

        Bully.prototype = {
            constructor: Bully,
            _reloadElement: function() {
                this.offset = $( this.element ).offset();
                this.height = $( this.element ).outerHeight();
            },
            _calcTop: function( top ){
                // check if has sticky
                if ($( '#masthead' ).hasClass( 'sticky-header' ) ) {
                    top -= $( '#masthead' ).outerHeight();
                }

                return top;
            },
            onClick: function() {

                var self = this,
                    $target = $( 'html, body' );

                if ( self.options.scrollDuration == 0 ) {
                    $target.scrollTop(  this._calcTop( self.offset.top )  );
                    return;
                }

                if ( self.options.scrollDuration === 'auto' ) {
                    var duration = Math.abs( lastScrollY - self.offset.top ) / (
                        self.options.scrollPerSecond / 1000
                    );
                    $target.animate( {scrollTop: this._calcTop( self.offset.top ) }, duration );
                    return;
                }

                $target.animate( {scrollTop: this._calcTop( self.offset.top ) }, self.options.scrollDuration );
            }
        };

        $.fn.bully = function( options ) {
            return this.each( function() {
                if ( ! $.data( this, "plugin_" + Bully ) ) {
                    $.data( this, "plugin_" + Bully, new Bully( this, options ) );
                }
            } );
        };

        $.fn.bully.defaults = {
            scrollDuration: 'auto',
            scrollPerSecond: 4000,
        };

        $window.on( 'rellax load', reloadAll );


    }
)( jQuery, window, document );


//init Bullly
jQuery( document ).ready( function( $ ){
    $.each( Screenr_Bully.sections, function( id, args ){
        $( '#'+id ).bully({
            scrollPerSecond: 3000,
        });
    } );
} );
