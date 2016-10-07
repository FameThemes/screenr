// COLOR ALPHA -----------------------------

/**
 * Alpha Color Picker JS
 */

( function( $ ) {

    /**
     * Override the stock color.js toString() method to add support for
     * outputting RGBa or Hex.
     */
    Color.prototype.toString = function( flag ) {

        // If our no-alpha flag has been passed in, output RGBa value with 100% opacity.
        // This is used to set the background color on the opacity slider during color changes.
        if ( 'no-alpha' == flag ) {
            return this.toCSS( 'rgba', '1' ).replace( /\s+/g, '' );
        }

        // If we have a proper opacity value, output RGBa.
        if ( 1 > this._alpha ) {
            return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
        }

        // Proceed with stock color.js hex output.
        var hex = parseInt( this._color, 10 ).toString( 16 );
        if ( this.error ) { return ''; }
        if ( hex.length < 6 ) {
            for ( var i = 6 - hex.length - 1; i >= 0; i-- ) {
                hex = '0' + hex;
            }
        }

        return '#' + hex;
    };

    /**
     * Given an RGBa, RGB, or hex color value, return the alpha channel value.
     */
    function acp_get_alpha_value_from_color( value ) {
        var alphaVal;

        // Remove all spaces from the passed in value to help our RGBa regex.
        value = value.replace( / /g, '' );

        if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
            alphaVal = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[1] ).toFixed(2) * 100;
            alphaVal = parseInt( alphaVal );
        } else {
            alphaVal = 100;
        }

        return alphaVal;
    }

    /**
     * Force update the alpha value of the color picker object and maybe the alpha slider.
     */
    function acp_update_alpha_value_on_color_input( alpha, $input, $alphaSlider, update_slider ) {
        var iris, colorPicker, color;

        iris = $input.data( 'a8cIris' );
        colorPicker = $input.data( 'wpWpColorPicker' );

        // Set the alpha value on the Iris object.
        iris._color._alpha = alpha;

        // Store the new color value.
        color = iris._color.toString();

        // Set the value of the input.
        $input.val( color );
        $input.trigger( 'color_change' );

        // Update the background color of the color picker.
        colorPicker.toggler.css({
            'background-color': color
        });

        // Maybe update the alpha slider itself.
        if ( update_slider ) {
            acp_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
        }

        // Update the color value of the color picker object.
        $input.wpColorPicker( 'color', color );
    }

    /**
     * Update the slider handle position and label.
     */
    function acp_update_alpha_value_on_alpha_slider( alpha, $alphaSlider ) {
        $alphaSlider.slider( 'value', alpha );
        $alphaSlider.find( '.ui-slider-handle' ).text( alpha.toString() );
    }

    $.fn.alphaColorPicker = function() {

        return this.each( function() {

            // Scope the vars.
            var $input, startingColor, paletteInput, showOpacity, defaultColor, palette,
                colorPickerOptions, $container, $alphaSlider, alphaVal, sliderOptions;

            // Store the input.
            $input = $( this );

            // We must wrap the input now in order to get our a top level class
            // around the HTML added by wpColorPicker().
            $input.wrap( '<div class="alpha-color-picker-wrap"></div>' );

            // Get some data off the input.
            paletteInput = $input.attr( 'data-palette' ) || 'true';
            showOpacity  = $input.attr( 'data-show-opacity' ) || 'true';
            defaultColor = $input.attr( 'data-default-color' ) || '';

            // Process the palette.
            if ( paletteInput.indexOf( '|' ) !== -1 ) {
                palette = paletteInput.split( '|' );
            } else if ( 'false' == paletteInput ) {
                palette = false;
            } else {
                palette = true;
            }

            // Get a clean starting value for the option.
            startingColor = $input.val().replace( /\s+/g, '' );
            //startingColor = $input.val().replace( '#', '' );
            //console.log( startingColor );

            // If we don't yet have a value, use the default color.
            if ( '' == startingColor ) {
                startingColor = defaultColor;
            }

            // Set up the options that we'll pass to wpColorPicker().
            colorPickerOptions = {
                change: function( event, ui ) {
                    var key, value, alpha, $transparency;

                    key   = $input.attr( 'data-customize-setting-link' );
                    value = $input.wpColorPicker( 'color' );

                    // Set the opacity value on the slider handle when the default color button is clicked.
                    if ( defaultColor == value ) {
                        alpha = acp_get_alpha_value_from_color( value );
                        $alphaSlider.find( '.ui-slider-handle' ).text( alpha );
                    }

                    // If we're in the Customizer, send an ajax request to wp.customize
                    // to trigger the Save action.
                    if ( typeof wp.customize != 'undefined' ) {
                        wp.customize( key, function( obj ) {
                            obj.set( value );
                        });
                    }

                    $transparency = $container.find( '.transparency' );

                    // Always show the background color of the opacity slider at 100% opacity.
                    $transparency.css( 'background-color', ui.color.toString( 'no-alpha' ) );
                    $input.trigger( 'color_change' );
                },
                palettes: palette // Use the passed in palette.
            };

            // Create the colorpicker.
            $input.wpColorPicker( colorPickerOptions );

            $container = $input.parents( '.wp-picker-container:first' );

            // Insert our opacity slider.
            $( '<div class="alpha-color-picker-container">' +
                '<div class="min-click-zone click-zone"></div>' +
                '<div class="max-click-zone click-zone"></div>' +
                '<div class="alpha-slider"></div>' +
                '<div class="transparency"></div>' +
                '</div>' ).appendTo( $container.find( '.wp-picker-holder' ) );

            $alphaSlider = $container.find( '.alpha-slider' );

            // If starting value is in format RGBa, grab the alpha channel.
            alphaVal = acp_get_alpha_value_from_color( startingColor );

            // Set up jQuery UI slider() options.
            sliderOptions = {
                create: function( event, ui ) {
                    var value = $( this ).slider( 'value' );

                    // Set up initial values.
                    $( this ).find( '.ui-slider-handle' ).text( value );
                    $( this ).siblings( '.transparency ').css( 'background-color', startingColor );
                },
                value: alphaVal,
                range: 'max',
                step: 1,
                min: 0,
                max: 100,
                animate: 300
            };

            // Initialize jQuery UI slider with our options.
            $alphaSlider.slider( sliderOptions );

            // Maybe show the opacity on the handle.
            if ( 'true' == showOpacity ) {
                $alphaSlider.find( '.ui-slider-handle' ).addClass( 'show-opacity' );
            }

            // Bind event handlers for the click zones.
            $container.find( '.min-click-zone' ).on( 'click', function() {
                acp_update_alpha_value_on_color_input( 0, $input, $alphaSlider, true );
            });
            $container.find( '.max-click-zone' ).on( 'click', function() {
                acp_update_alpha_value_on_color_input( 100, $input, $alphaSlider, true );
            });

            // Bind event handler for clicking on a palette color.
            $container.find( '.iris-palette' ).on( 'click', function() {
                var color, alpha;

                color = $( this ).css( 'background-color' );
                alpha = acp_get_alpha_value_from_color( color );

                acp_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );

                // Sometimes Iris doesn't set a perfect background-color on the palette,
                // for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
                // To compensante for this we round the opacity value on RGBa colors here
                // and save it a second time to the color picker object.
                if ( alpha != 100 ) {
                    color = color.replace( /[^,]+(?=\))/, ( alpha / 100 ).toFixed( 2 ) );
                }

                $input.wpColorPicker( 'color', color );
            });

            // Bind event handler for clicking on the 'Default' button.
            $container.find( '.button.wp-picker-default' ).on( 'click', function() {
                var alpha = acp_get_alpha_value_from_color( defaultColor );

                acp_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
            });

            // Bind event handler for typing or pasting into the input.
            $input.on( 'input', function() {
                var value = $( this ).val();
                var alpha = acp_get_alpha_value_from_color( value );

                acp_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
            });

            // Update all the things when the slider is interacted with.
            $alphaSlider.slider().on( 'slide', function( event, ui ) {
                var alpha = parseFloat( ui.value ) / 100.0;

                acp_update_alpha_value_on_color_input( alpha, $input, $alphaSlider, false );

                // Change value shown on slider handle.
                $( this ).find( '.ui-slider-handle' ).text( ui.value );
            });
        });
    }

}( jQuery ));


// WP COLOR ALPHA customizer -----------------------------
( function( api , $ ) {
    api.controlConstructor['alpha-color'] = api.Control.extend({
        ready: function() {
            var control = this;
            $( '.alpha-color-control', control.container  ).alphaColorPicker( {
                clear: function(event, ui){

                },
            });

        }

    });

} )(  wp.customize, jQuery );


// WP REPEATERABLE Customizer -----------------------------

( function( api , $ ) {

    api.controlConstructor['repeatable'] = api.Control.extend( {
        ready: function() {
            var control = this;
            control._init();
        },

        eval: function(valueIs, valueShould, operator) {

            switch( operator ) {
                case 'not_in':
                    valueShould = valueShould.split(',');
                    if ( $.inArray( valueIs , valueShould ) < 0 ){
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'in':
                    valueShould = valueShould.split(',');
                    if ( $.inArray( valueIs , valueShould ) > -1 ){
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case '!=':
                    return valueIs != valueShould;
                case '<=':
                    return valueIs <= valueShould;
                case '<':
                    return valueIs < valueShould;
                case '>=':
                    return valueIs >= valueShould;
                case '>':
                    return valueIs > valueShould;
                case '==':case '=':
                return valueIs == valueShould;
                break;
            }
        },

        conditionize: function( $context ){
            var control = this;

            if ( $context.hasClass( 'conditionized' ) ) {
                return  ;
            }
            $context.addClass( 'conditionized' );

            $context.on( 'change condition_check', 'input, select, textarea', function( e ) {
                var id =  $( this ).attr( 'data-live-id' ) || '';

                if ( id !== '' && $( '.conditionize[data-cond-option="'+id+'"]', $context ) .length > 0 ) {
                    var v = '';
                    if ( $( this ).is( 'input[type="checkbox"]' ) ) {
                        if ( $( this ).is( ':checked' ) ){
                            v =  $( this ).val();
                        } else {
                            v = 0;
                        }
                    } else if ( $( this ).is( 'input[type="radio"]' ) ) {
                        if ( $( this ).is( ':checked' ) ){
                            v =  $( this).val();
                        }
                    } else {
                        v = $( this).val();
                    }

                    $( '.conditionize[data-cond-option="'+id+'"]', $context ).each( function(){

                        var $section = $(this);
                        var listenFor = $(this).attr('data-cond-value');
                        var operator = $(this).attr('data-cond-operator') ? $(this).attr('data-cond-operator') : '==';

                        if ( control.eval( v, listenFor, operator ) ) {
                            $section.slideDown().addClass( 'cond-show').removeClass( 'cond-hide' );
                            $section.trigger( 'condition_show' );
                        } else {
                            $section.slideUp().removeClass( 'cond-show').addClass( 'cond-hide' );
                            $section.trigger( 'condition_hide' );
                        }
                    } );
                }
            } );

            /**
             * Current support one level only
             */
            $('input, select, textarea', $context ).trigger( 'condition_check' );


        },

        remove_editor: function( $context ){

        },

        editor: function( $textarea ){

        },

        _init: function(){
            var control = this;

            //var container =  control.container;
            var default_data =  control.params.fields;

            var values;
            if (  typeof control.params.value === 'string' ) {
                try {
                    values = JSON.parse( control.params.value ) ;
                }catch ( e ) {
                    values = {};
                }
            } else {
                values = control.params.value;
            }


            var max_item  = 0; // unlimited
            var limited_mg = control.params.limited_msg || '';

            if ( ! isNaN( parseInt( control.params.max_item ) ) ) {
                max_item = parseInt( control.params.max_item );
            }

            /**
             * Toggle show/hide item
             */
            control.container.on( 'click', '.widget .widget-action, .widget .repeat-control-close, .widget-title', function( e ){
                e.preventDefault();
                var p =  $( this ).closest( '.widget' );

                if ( p.hasClass( 'explained' ) ) {
                    //console.log( 'has: explained' );
                    $( '.widget-inside', p ).slideUp( 200, 'linear', function(){
                        $( '.widget-inside', p ).removeClass( 'show').addClass('hide');
                        p.removeClass( 'explained' );
                    } );
                } else {
                    // console.log( 'No: explained' );
                    $( '.widget-inside', p ).slideDown( 200, 'linear', function(){
                        $( '.widget-inside', p ).removeClass( 'hide').addClass('show');
                        p.addClass( 'explained' );
                    } );
                }

            } );

            /**
             * Remove repeater item
             */
            control.container.on( 'click', '.repeat-control-remove' , function( e ){
                e.preventDefault();
                var $context =  $( this ).closest( '.repeatable-customize-control' );

                $( "body").trigger( "repeat-control-remove-item", [$context ] );

                control.remove_editor( $context );

                $context.remove();
                control.rename();
                control.updateValue();
                control._check_max_item();
            } );

            /**
             * Get customizer control data
             *
             * @returns {*}
             */
            control.getData = function ( ){
                var f = $( '.form-data', control.container );
                var data =  $( 'input, textarea, select', f ).serialize();
                return  JSON.stringify( data ) ;
            };

            /**
             * Update repeater value
             */
            control.updateValue = function(){
                var data = control.getData();
                $( "[data-hidden-value]", control.container ).val( data );
                $( "[data-hidden-value]", control.container ).trigger( 'change' );
            };

            /**
             * Rename repeater item
             */
            control.rename = function(){
                $( '.list-repeatable li', control.container ).each( function( index ) {
                    var li =  $( this );
                    $( 'input, textarea, select', li ).each( function(){
                        var input = $( this );
                        var name = input.attr( 'data-repeat-name' ) || undefined;
                        if(  typeof name !== "undefined" ) {
                            name = name.replace(/__i__/g, index );
                            input.attr( 'name',  name );
                        }
                    } );

                } );
            };

            var frame = wp.media({
                title: wp.media.view.l10n.addMedia,
                multiple: false,
                //library: {type: 'all' },
                //button : { text : 'Insert' }
            });

            frame.on('close', function () {
                // get selections and save to hidden input plus other AJAX stuff etc.
                var selection = frame.state().get('selection');
                // console.log(selection);
            });

            control.media_current = {};
            control.media_btn = {};
            frame.on( 'select', function () {
                // Grab our attachment selection and construct a JSON representation of the model.
                var media_attachment = frame.state().get('selection').first().toJSON();
                var preview, img_url;
                img_url = media_attachment.url;

                $( '.image_id', control.media_current  ).val( media_attachment.id );
                $( '.current', control.media_current  ).removeClass( 'hide').addClass( 'show' );
                $( '.image_url', control.media_current  ).val( img_url );

                if ( media_attachment.type == 'video' ) {
                    preview = '<video width="400" controls>'+
                        '<source src="'+img_url+'" type="'+media_attachment.mime+'">'+
                        'Your browser does not support HTML5 video.'+
                    '</video>';
                    $('.thumbnail-image', control.media_current  ).html(preview);

                } else if ( media_attachment.type == 'image' ) {
                    preview = '<img src="' + img_url + '" alt="">';
                    $('.thumbnail-image', control.media_current  ).html(preview);
                }

                $('.remove-button', control.media_current  ).show();
                $( '.image_id', control.media_current  ).trigger( 'change' );
                control.media_btn.text( control.media_btn.attr( 'data-change-txt' ) );
            });


            control.handleMedia = function( $context ) {
                $('.item-media', $context ).each( function(){
                    var _item = $( this );
                    // when remove item
                    $( '.remove-button', _item ).on( 'click', function( e ){
                        e.preventDefault();
                        $( '.image_id, .image_url', _item ).val( '' );
                        $( '.thumbnail-image', _item ).html( '' );
                        $( '.current', _item ).removeClass( 'show' ).addClass( 'hide' );
                        $( this).hide();
                        $('.upload-button', _item ).text( $('.upload-button', _item ).attr( 'data-add-txt' ) );
                        $( '.image_id', _item ).trigger( 'change' );
                    } );

                    // when upload item
                    $('.upload-button', _item ).on('click', function ( e ) {
                        e.preventDefault();
                        control.media_current = _item;
                        control.media_btn = $( this );
                        frame.open();
                    });
                } );
            };

            /**
             * Init color picker
             *
             * @param $context
             */
            control.colorPicker =  function( $context ){
                // Add Color Picker to all inputs that have 'color-field' class

                if ( $('.c-color', $context).length > 0 ) {
                    $('.c-color', $context).wpColorPicker({
                        change: function (event, ui) {
                            control.updateValue();
                        },
                        clear: function (event, ui) {
                            control.updateValue();
                        },
                    });
                }

                if ( $('.c-coloralpha', $context).length > 0 ) {

                    $('.c-coloralpha', $context).each(function () {
                        var input = $(this);
                        var c = input.val();
                        c = c.replace('#', '');
                        input.removeAttr('value');
                        input.prop('value', c);
                        input.alphaColorPicker({
                            change: function (event, ui) {
                                control.updateValue();
                            },
                            clear: function (event, ui) {
                                control.updateValue();
                            },
                        });
                    });
                }


            };

            /**
             * Live title events
             *
             * @param $context
             */
            control.actions = function( $context ){
                if ( control.params.live_title_id ) {

                    if( ! $context.attr( 'data-title-format' ) ) {
                        $context.attr( 'data-title-format', control.params.title_format );
                    }

                    var format = $context.attr( 'data-title-format' ) || '';
                    // Custom for special ID
                    if ( control.id === 'sections_order_styling' ) {
                        if ( $context.find( 'input.add_by').val() !== 'click' ) {
                            format = '[live_title]';
                        }
                    }

                    // Live title
                    if ( control.params.live_title_id && $( "[data-live-id='"+ control.params.live_title_id+"']", $context ).length > 0 ) {
                        var v = '';

                        if (  $("[data-live-id='" + control.params.live_title_id + "']", $context).is( '.select-one' )  ){
                            v = $("[data-live-id='" + control.params.live_title_id + "']", $context ).find('option:selected').eq(0).text();
                        } else {
                            v = $("[data-live-id='" + control.params.live_title_id + "']", $context).eq(0).val();
                        }

                        if ( v == '' ) {
                            v = control.params.default_empty_title;
                        }

                        if ( format !== '') {
                            v = format.replace('[live_title]', v);
                        }

                        $('.widget-title .live-title', $context).text( v );

                        $context.on('keyup change', "[data-live-id='" + control.params.live_title_id + "']", function () {
                            var v = '';

                            var format = $context.attr( 'data-title-format' ) || '';
                            // custom for special ID
                            if ( control.id === 'sections_order_styling' ) {
                                if ( $context.find( 'input.add_by').val() !== 'click' ) {
                                    format = '[live_title]';
                                }
                            }

                            if ( $(this).is( '.select-one' )  ){
                                v = $(this).find('option:selected').eq( 0 ).text();
                            } else {
                                v = $(this).val();
                            }

                            if (v == '') {
                                v = control.params.default_empty_title;
                            }

                            if ( format !== '' ) {
                                v = format.replace('[live_title]', v);
                            }

                            $('.widget-title .live-title', $context).text(v);
                        });

                    } else {

                    }

                } else {
                    //console.log(  control.params.title_format );
                    //$('.widget-title .live-title', $context).text( control.params.title_format );
                }

            };


            /**
             * Check max item
             *
             * @private
             */
            control._check_max_item = function(){
                var n = $( '.list-repeatable > li.repeatable-customize-control', control.container).length;
                //console.log( n );
                if ( n>= max_item ) {
                    $( '.repeatable-actions', control.container ).hide();
                    if ( $( '.limited-msg', control.container).length <= 0 ) {
                        if ( limited_mg !== '' ) {
                            var msg = $( '<p class="limited-msg"/>' );
                            msg.html( limited_mg );
                            msg.insertAfter( $( '.repeatable-actions', control.container ) );
                            msg.show();
                        }
                    } else {
                        $( '.limited-msg', control.container ).show();
                    }

                } else {
                    $( '.repeatable-actions', control.container ).show();
                    $( '.limited-msg', control.container ).hide();
                }
                //console.log( max_item );
            };


            /**
             * Function that loads the Mustache template
             */
            control.repeaterTemplate = _.memoize(function () {
                var compiled,
                /*
                 * Underscore's default ERB-style templates are incompatible with PHP
                 * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
                 *
                 * @see trac ticket #22344.
                 */
                    options = {
                        evaluate: /<#([\s\S]+?)#>/g,
                        interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                        escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                        variable: 'data'
                    };

                return function ( data ) {
                    compiled = _.template( control.container.find('.repeatable-js-template').first().html(), null, options);
                    return compiled( data );
                };
            });

            control.template = control.repeaterTemplate();


            /**
             * Init item events
             *
             * @param $context
             */
            control.intItem = function( $context ){
                control.rename();
                control.conditionize( $context );
                control.colorPicker( $context );
                control.handleMedia( $context );
                //Special check element
                $( '[data-live-id="section_id"]', $context ).each( function(){
                    // $context.addClass( 'section-'+$( this ).val() );
                    $( this).closest( '.repeatable-customize-control').addClass( 'section-'+$( this ).val() );
                    if ( $( this ).val() === 'map' ) {
                        // console.log(  $( this).val() );
                        $context.addClass( 'show-display-field-only' );
                    }
                } );


                // Custom for special ID
                if ( control.id === 'sections_order_styling' ) {
                    if ( $context.find( 'input.add_by').val() !== 'click' ) {
                        $context.addClass( 'no-changeable' );
                        $( '.item-editor textarea', $context).addClass( 'editor-added' );

                    } else {
                        $context.find( '.item-title').removeClass( 'item-hidden ' );
                        $context.find( '.item-title input[type="hidden"]').attr( 'type', 'text' );

                        $context.find( '.item-section_id').removeClass( 'item-hidden ' );
                        $context.find( '.item-section_id input[type="hidden"]').attr( 'type', 'text' );
                    }
                }

                // Setup editor
                $( 'body' ).trigger( 'repeater-control-init-item', [ $context ] );

            };

            /**
             * Drag to sort items
             */
            $( ".list-repeatable", control.container ).sortable({
                handle: ".widget-title",
                //containment: ".customize-control-repeatable",
                containment: control.container,
                /// placeholder: "sortable-placeholder",
                update: function( event, ui ) {
                    control.rename();
                    control.updateValue();
                }
            });

            /**
             * Create existing items
             */
            if ( values.length ) {
                var _templateData, _values;

                for (var i = 0; i < values.length; i++) {

                    _templateData = $.extend( true, {}, control.params.fields );

                    _values = values[i];
                    if ( values[i] ) {
                        for ( var j in _values ) {
                            if ( _templateData.hasOwnProperty( j ) && _values.hasOwnProperty( j ) ) {
                                _templateData[ j ].value = _values[j];
                            }
                        }
                    }

                    var $html = $( control.template( _templateData ) );
                    $( '.list-repeatable', control.container ).append( $html );
                    control.intItem( $html );
                    control.actions( $html );
                }
            }


            /**
             * Add new item
             */
            control.container.on( 'click', '.add-new-repeat-item', function(){
                var $html = $( control.template( default_data ) );
                $( '.list-repeatable', control.container ).append( $html );

                // add unique ID for section if id_key is set
                if ( control.params.id_key !== '' ){
                    $html.find( '.item-'+control.params.id_key).find( 'input').val( 'sid'+( new Date().getTime() ) );
                }
                $html.find( 'input.add_by').val( 'click' );

                control.intItem( $html );
                control.actions( $html );
                control.updateValue();
                control._check_max_item();
            } );

            /**
             * Update repeater data when any events fire.
             */
            $( '.list-repeatable', control.container ).on( 'keyup change color_change', 'input, select, textarea', function( e ) {
                control.updateValue();
            });

            control._check_max_item();

        }

    } );

} )( wp.customize, jQuery );


/**
 * Icon picker
 */
jQuery( document ).ready( function( $ ) {

    window.editing_icon = false;
    var icon_picker = $( '<div class="c-icon-picker"><div class="c-icon-type-wrap"><select class="c-icon-type"></select></div><div class="c-icon-search"><input class="" type="text"></div><div class="c-icon-list"></div></div>' );
    var options_font_type  = '', icon_group = '';

    $.each( C_Icon_Picker.fonts, function( key, font ) {

        font = $.extend( {}, {
            url: '',
            name: '',
            prefix: '',
            icons: ''
        }, font );

        $('<link>')
            .appendTo('head')
            .attr({type : 'text/css', rel : 'stylesheet'})
            .attr('id', 'customizer-icon-' + key )
            .attr('href', font.url );

        options_font_type += '<option value="'+key+'">' +font.name+ '</option>';

        var icons_array = font.icons.split('|');

        icon_group += '<div class="ic-icons-group" style="display: none;" data-group-name="'+key+'">';
        $.each( icons_array, function( index, icon ){
            if ( font.prefix ) {
                icon = font.prefix + ' ' + icon;
            }
            icon_group +=  '<span data-name="'+icon+'"><i class="'+ icon +'"></i></span>';

        } );
        icon_group += '</div>';

    } );
    icon_picker.find( '.c-icon-search input' ).attr( 'placeholder', C_Icon_Picker.search );
    icon_picker.find( '.c-icon-type' ).html( options_font_type );
    icon_picker.find( '.c-icon-list' ).append( icon_group );
    $( '.wp-full-overlay' ).append( icon_picker );

    // Change icon type
    $( 'body' ).on( 'change', 'select.c-icon-type', function(){
        var t =  $( this ).val();
        icon_picker.find( '.ic-icons-group' ).hide();
        icon_picker.find( '.ic-icons-group[data-group-name="'+t+'"]' ).show();

    } );
    icon_picker.find( 'select.c-icon-type' ).trigger( 'change' );

    // When type to search
    $( 'body' ).on( 'keyup', '.c-icon-search input', function(){
        var v = $( this ).val();
        if ( v == '' ) {
            $( '.c-icon-list span' ).show();
        } else {
            $( '.c-icon-list span' ).hide();
           try {
               $( '.c-icon-list span[data-name*="'+v+'"]' ).show();
           } catch ( e ){

           }
        }
    } );

    // Edit icon
    $( 'body' ).on( 'click', '.icon-wrapper', function( e ){
        e.preventDefault();
        var icon =  $( this );
        window.editing_icon = icon;
        icon_picker.addClass( 'ic-active' );
        $( 'body' ).find( '.icon-wrapper' ).removeClass('icon-editing');
        icon.addClass( 'icon-editing' );
    } );
    // Remove icon
    $( 'body' ).on( 'click', '.item-icon .remove-icon', function( e ){
        e.preventDefault();
        var item =  $( this ).closest( '.item-icon' );
        item.find( '.icon-wrapper input' ).val( '' );
        item.find( '.icon-wrapper input' ).trigger( 'change' );
        item.find( '.icon-wrapper i' ).attr( 'class', '' );
        $( 'body' ).find( '.icon-wrapper' ).removeClass('icon-editing');
    } );

    // Selected icon
    $( 'body' ).on( 'click', '.c-icon-list span', function( e ){
        e.preventDefault();
        var icon_name =  $( this ).attr( 'data-name' ) || '';
        if ( window.editing_icon ) {
            window.editing_icon.find( 'i' ).attr( 'class', '' ).addClass( $( this ).find( 'i' ).attr( 'class' ) );
            window.editing_icon.find( 'input' ).val( icon_name ).trigger( 'change' );
        }
        icon_picker.removeClass( 'ic-active' );
        window.editing_icon = false;
        $( 'body' ).find( '.icon-wrapper' ).removeClass('icon-editing');
    } );

    $( document ).mouseup( function ( e ) {
        if ( window.editing_icon ) {
            if ( ! window.editing_icon.is( e.target ) // if the target of the click isn't the container...
                && window.editing_icon.has( e.target ).length === 0 // ... nor a descendant of the container
                && (
                    !icon_picker.is( e.target )
                    && icon_picker.has( e.target ).length === 0
                )
            ) {
                icon_picker.removeClass('ic-active');
               // window.editing_icon = false;
            }
        }
    });

} );



/* Customizer settings conditionals */
jQuery( document ).ready( function( $ ){

    var display_footer_layout = function( l ){
        $( 'li[id^="customize-control-footer_custom_"]' ).hide();
        $( 'li[id^="customize-control-footer_custom_'+l+'_columns"]' ).show();
    };

    display_footer_layout( $( '#customize-control-footer_layout select' ).val() );
    $( '#customize-control-footer_layout select' ).on( 'change', function ()  {
        display_footer_layout( $( this ).val() );
    } );

    // Header menu layout

    var  header_layout_change = function ( layout ) {
        if ( layout == 'transparent' ) {
            $( '#customize-control-header_bg_color, ' +
                '#customize-control-menu_color,' +
                ' #customize-control-menu_hover_color,' +
                ' #customize-control-menu_hover_bg_color' ).hide();

            $( '#customize-control-header_t_bg_color, ' +
                '#customize-control-menu_t_color,' +
                ' #customize-control-menu_t_hover_color,' +
                ' #customize-control-menu_t_hover_bg_color' ).show();
        } else {

            $( '#customize-control-header_bg_color, ' +
                '#customize-control-menu_color,' +
                ' #customize-control-menu_hover_color,' +
                ' #customize-control-menu_hover_bg_color' ).show();

            $( '#customize-control-header_t_bg_color, ' +
                '#customize-control-menu_t_color,' +
                ' #customize-control-menu_t_hover_color,' +
                ' #customize-control-menu_t_hover_bg_color' ).hide();

        }
    };

    header_layout_change( $( '#customize-control-header_layout select' ).val() );
    $( '#customize-control-header_layout select' ).on( 'change', function ()  {
        header_layout_change( $( this ).val() );
    } );

    // News load more posts
    var new_load_more_settings = function( t ){
        if ( t == 'hide' ) {
            $( '#customize-control-news_more_text, #customize-control-news_more_link' ).hide();
        } else if ( t == 'ajax' ) {
            $( '#customize-control-news_more_text' ).show();
            $( '#customize-control-news_more_link' ).hide();
        } else if ( t == 'link' ) {
            $( '#customize-control-news_more_text' ).show();
            $( '#customize-control-news_more_link' ).show();
        }

    };
    new_load_more_settings( $( '#customize-control-news_loadmore select' ).val() );
    $( '#customize-control-news_loadmore select' ).on( 'change', function ()  {
        new_load_more_settings( $( this ).val() );
    } );


    /**
     * For Gallery content settings
     */
    $( 'select[data-customize-setting-link="gallery_source"]').on( 'change on_custom_load', function(){
        var v = $( this).val() || '';

        $( "li[id^='customize-control-gallery_source_']").hide();
        $( "li[id^='customize-control-gallery_api_']").hide();
        $( "li[id^='customize-control-gallery_settings_']").hide();
        $( "li[id^='customize-control-gallery_source_"+v+"']").show();
        $( "li[id^='customize-control-gallery_api_"+v+"']").show();
        $( "li[id^='customize-control-gallery_settings_"+v+"']").show();

    } );

    $( 'select[data-customize-setting-link="gallery_source"]').trigger( 'on_custom_load' );

    /**
     * For Gallery display settings
     */
    $( 'select[data-customize-setting-link="gallery_display"]').on( 'change on_custom_load', function(){
        var v = $( this).val() || '';
        switch ( v ) {
            case 'slider':
                $( "#customize-control-gallery_row_height, #customize-control-gallery_col, #customize-control-gallery_spacing").hide();
                break;
            case 'justified':
                $( "#customize-control-gallery_col, #customize-control-gallery_spacing").hide();
                $( "#customize-control-gallery_row_height").show();
                break;
            case 'carousel':
                $( "#customize-control-gallery_row_height, #customize-control-gallery_col").hide();
                $( "#customize-control-onepress_g_col, #customize-control-onepress_g_spacing").show();
                break;
            case 'masonry':
                $( "#customize-control-onepress_g_row_height").hide();
                $( "#customize-control-gallery_col, #customize-control-gallery_spacing").show();
                break;
            default:
                $( "#customize-control-gallery_row_height").hide();
                $( "#customize-control-gallery_col, #customize-control-gallery_spacing").show();
        }

    } );
    $( 'select[data-customize-setting-link="gallery_display"]').trigger( 'on_custom_load' );



} );

/*
 * Plus Upgrade
 */
( function( api ) {
	api.sectionConstructor['upgrade-plus'] = api.Section.extend( {
		attachEvents: function () {},
		isContextuallyActive: function () {
			return true;
		}
	} );
} )( wp.customize );

// Plus version
jQuery( document ).ready( function( $ ){
    if ( typeof screenr_customizer_settings !== "undefined" ) {
        if (screenr_customizer_settings.number_action > 0) {
            $('.control-section-themes h3.accordion-section-title').append('<a class="theme-action-count" href="' + screenr_customizer_settings.action_url + '">' + screenr_customizer_settings.number_action + '</a>');
        }
    }
} );
