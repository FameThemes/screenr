<?php
/**
 * Screenr Theme Customizer.
 *
 * @package Screenr
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function screenr_customize_register( $wp_customize ) {

    // Load custom controls.
    require get_template_directory() . '/inc/customizer-controls.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


    /*------------------------------------------------------------------------*/
    /*  Site Options
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'screenr_options',
        array(
            'priority'       => 22,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Theme Options', 'screenr' ),
            'description'    => '',
        )
    );

    /* Header
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'header_settings' ,
        array(
            'priority'    => 5,
            'title'       => esc_html__( 'Header', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

    // Header Transparent
    $wp_customize->add_setting( 'header_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'default',
            'active_callback'   => '', // function
            'type'              => 'option' // make this settings value can use in child theme.
        )
    );
    $wp_customize->add_control( 'header_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Header style', 'screenr'),
            'section'     => 'header_settings',
            'choices'     => array(
                'default'       => esc_html__('Default', 'screenr'),
                'fixed'         => esc_html__('Fixed', 'screenr'),
                'transparent'   => esc_html__('Fixed & Transparent', 'screenr'),
            )
        )
    );

    // Header BG Color
    $wp_customize->add_setting( 'header_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_bg_color',
        array(
            'label'       => esc_html__( 'Background Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));


    // Site Title Color
    $wp_customize->add_setting( 'logo_text_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'logo_text_color',
        array(
            'label'       => esc_html__( 'Site Title Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => esc_html__( 'Only set if you don\'t use an image logo.', 'screenr' ),
        )
    ));

    // Header Menu Color
    $wp_customize->add_setting( 'menu_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_color',
        array(
            'label'       => esc_html__( 'Menu Link Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));

    // Header Menu Hover Color
    $wp_customize->add_setting( 'menu_hover_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',

        )
    ));

    // Header Menu Hover BG Color
    $wp_customize->add_setting( 'menu_hover_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_bg_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active BG Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));

    // Reponsive Mobie button color
    $wp_customize->add_setting( 'menu_toggle_button_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_toggle_button_color',
        array(
            'label'       => esc_html__( 'Responsive Menu Button Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));


    /*------------------------------------------------------------------------*/
    /*  Panel: Sections
    /*------------------------------------------------------------------------*/

    /**
     * @see screen_showon_frontpage
     */
    $wp_customize->add_panel( 'front_page_sections',
        array(
            'priority'       => 25,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Frontpage Sections', 'screenr' ),
            'description'    => '',
            'active_callback' => 'screenr_showon_frontpage'
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Section: Hero Slider
    /*------------------------------------------------------------------------*/


    // Slider settings
    $wp_customize->add_section( 'section_slider' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Hero', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'slider_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'slider_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_slider',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'slider_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => array(
                array(
                    'image'=> array(
                        'url' => get_template_directory_uri().'/assets/images/slider5.jpg',
                        'id' => ''
                    )
                )
            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'slider_items',
            array(
                'label'     => esc_html__('Content Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_slider',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 2, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="#">Screenr Plus</a> to be able to add more items and unlock other premium features!', 'screenr' ),
                //'allow_unlimited' => false, // Maximum item can add


                'fields'    => array(
                    'title' => array(
                        'title' => esc_html__('Title', 'screenr'),
                        'type'  =>'text',
                        'default' => esc_html__('This is slider title', 'screenr'),
                    ),
                    'desc' => array(
                        'title' => esc_html__('Description', 'screenr'),
                        'type'  =>'editor',
                        'default' => esc_html__('This is slider description', 'screenr'),
                    ),
                    'media' => array(
                        'title' => esc_html__('Image', 'screenr'),
                        'type'  =>'media',
                        'default' => array(
                            'url' => '',
                            'id' => ''
                        )
                    ),

                    'align' => array(
                        'title' => esc_html__('Content align', 'screenr'),
                        'type'  =>'select',
                        'options' => array(
                            'center' => esc_html__('Center', 'screenr'),
                            'left' => esc_html__('Left', 'screenr'),
                            'right' => esc_html__('Right', 'screenr'),
                            'bottom' => esc_html__('Bottom', 'screenr'),
                        )
                    ),

                    'v_align' => array(
                        'title' => esc_html__('Content vertical align', 'screenr'),
                        'type'  =>'select',
                        'options' => array(
                            'center' => esc_html__('Center', 'screenr'),
                            'top' => esc_html__('Top', 'screenr'),
                            'bottom' => esc_html__('Bottom', 'screenr'),
                        )
                    ),
                ),

            )
        )
    );

    // Overlay color
    $wp_customize->add_setting( 'slider_overlay_color',
        array(
            'sanitize_callback' => 'screenr_sanitize_color_alpha',
            'default'           => 'rgba(0,0,0,.3)',
            'transport' => 'refresh', // refresh or postMessage
        )
    );
    $wp_customize->add_control( new Screenr_Alpha_Color_Control(
            $wp_customize,
            'slider_overlay_color',
            array(
                'label' 		=> esc_html__('Background Overlay Color', 'screenr'),
                'section' 		=> 'section_slider',
            )
        )
    );


    // Slider ID
    $wp_customize->add_setting( 'slider_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('slider', 'screenr'),
        )
    );
    $wp_customize->add_control( 'slider_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_slider',
            'description'   => 'The section id, we will use this for link anchor.'
        )
    );


    // Show slider full screen
    $wp_customize->add_setting( 'slider_fullscreen',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'slider_fullscreen',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Make slider section full screen', 'screenr'),
            'section'     => 'section_slider',
            'description' => esc_html__('Check this box to make slider section full screen.', 'screenr'),
        )
    );

    // Slider content padding top
    $wp_customize->add_setting( 'slider_pdtop',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
        )
    );
    $wp_customize->add_control( 'slider_pdtop',
        array(
            'label'           => esc_html__('Padding Top:', 'screenr'),
            'section'         => 'section_slider',
            'description'     => 'The slider content padding top in percent (%).',
            //'active_callback' => 'screenr_slider_fullscreen_callback'
        )
    );

    // Slider content padding bottom
    $wp_customize->add_setting( 'slider_pdbotom',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
        )
    );
    $wp_customize->add_control( 'slider_pdbotom',
        array(
            'label'           => esc_html__('Padding Bottom:', 'screenr'),
            'section'         => 'section_slider',
            'description'     => 'The slider content padding bottom in percent (%).',
           // 'active_callback' => 'screenr_slider_fullscreen_callback'
        )
    );


    // END For Slider layout ------------------------




}
add_action( 'customize_register', 'screenr_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function screenr_customize_preview_js() {
	wp_enqueue_script( 'screenr_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'screenr_customize_preview_js' );



/*
add_action( 'customize_controls_enqueue_scripts', 'screenr_customize_js_settings' );
function screenr_customize_js_settings(){

    wp_localize_script( 'customize-controls', 'screenr_customizer_settings', array(
        'number_action' => $number_action,
        'is_plus_activated' => class_exists( 'OnePress_PLus' ) ? 'y' : 'n',
        'action_url' => admin_url( 'themes.php?page=ft_screenr&tab=actions_required' )
    ) );
}
*/


/*------------------------------------------------------------------------*/
/*  OnePress Sanitize Functions.
/*------------------------------------------------------------------------*/

function screenr_sanitize_file_url( $file_url ) {
    $output = '';
    $filetype = wp_check_filetype( $file_url );
    if ( $filetype["ext"] ) {
        $output = esc_url( $file_url );
    }
    return $output;
}


/**
 * Conditional to show more hero settings
 *
 * @param $control
 * @return bool
 */
function screenr_hero_fullscreen_callback ( $control ) {
    if ( $control->manager->get_setting('screenr_hero_fullscreen')->value() == '' ) {
        return true;
    } else {
        return false;
    }
}


function screenr_sanitize_number( $input ) {
    return balanceTags( $input );
}

function screenr_sanitize_hex_color( $color ) {
    if ( $color === '' ) {
        return '';
    }
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
        return $color;
    }
    return null;
}

function screenr_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return 0;
    }
}

function screenr_sanitize_text( $string ) {
    return wp_kses_post( balanceTags( $string ) );
}

function screenr_sanitize_html_input( $string ) {
    return wp_kses_allowed_html( $string );
}

function screenr_showon_frontpage() {
    return is_page_template( 'template-frontpage.php' );
}

