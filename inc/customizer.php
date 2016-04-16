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
    $wp_customize->add_section( 'screenr_header_settings' ,
        array(
            'priority'    => 5,
            'title'       => esc_html__( 'Header', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

    // Header Transparent
    $wp_customize->add_setting( 'screenr_header_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'default',
            'active_callback'   => '', // function
            'type'              => 'option' // make this settings value can use in child theme.
        )
    );
    $wp_customize->add_control( 'screenr_header_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Header style', 'screenr'),
            'section'     => 'screenr_header_settings',
            'choices'     => array(
                'default'       => esc_html__('Default', 'screenr'),
                'fixed'         => esc_html__('Fixed', 'screenr'),
                'transparent'   => esc_html__('Fixed & Transparent', 'screenr'),
            )
        )
    );

    // Header BG Color
    $wp_customize->add_setting( 'screenr_header_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_header_bg_color',
        array(
            'label'       => esc_html__( 'Background Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => '',
        )
    ));


    // Site Title Color
    $wp_customize->add_setting( 'screenr_logo_text_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_logo_text_color',
        array(
            'label'       => esc_html__( 'Site Title Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => esc_html__( 'Only set if you don\'t use an image logo.', 'screenr' ),
        )
    ));

    // Header Menu Color
    $wp_customize->add_setting( 'screenr_menu_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_menu_color',
        array(
            'label'       => esc_html__( 'Menu Link Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => '',
        )
    ));

    // Header Menu Hover Color
    $wp_customize->add_setting( 'screenr_menu_hover_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_menu_hover_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => '',

        )
    ));

    // Header Menu Hover BG Color
    $wp_customize->add_setting( 'screenr_menu_hover_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_menu_hover_bg_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active BG Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => '',
        )
    ));

    // Reponsive Mobie button color
    $wp_customize->add_setting( 'screenr_menu_toggle_button_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'screenr_menu_toggle_button_color',
        array(
            'label'       => esc_html__( 'Responsive Menu Button Color', 'screenr' ),
            'section'     => 'screenr_header_settings',
            'description' => '',
        )
    ));


    /*------------------------------------------------------------------------*/
    /*  Section: Slider
    /*------------------------------------------------------------------------*/

    $wp_customize->add_panel( 'screenr_slider_panel' ,
        array(
            'priority'        => 130,
            'title'           => esc_html__( 'Section: Slider', 'screenr' ),
            'description'     => '',
           // 'active_callback' => 'screenr_showon_frontpage'
        )
    );

    // Slider settings
    $wp_customize->add_section( 'screenr_slider_settings' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Slider Settings', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_slider_panel',
        )
    );

    // Show section
    $wp_customize->add_setting( 'screenr_slider_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'screenr_slider_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'screenr_slider_settings',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );
    // Section ID
    $wp_customize->add_setting( 'screenr_slider_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('slider', 'screenr'),
        )
    );
    $wp_customize->add_control( 'screenr_slider_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'screenr_slider_settings',
            'description'   => 'The section id, we will use this for link anchor.'
        )
    );

    // Show slider full screen
    $wp_customize->add_setting( 'screenr_slider_fullscreen',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'screenr_slider_fullscreen',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Make slider section full screen', 'screenr'),
            'section'     => 'screenr_slider_settings',
            'description' => esc_html__('Check this box to make slider section full screen.', 'screenr'),
        )
    );

    // Slider content padding top
    $wp_customize->add_setting( 'screenr_slider_pdtop',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('10', 'screenr'),
        )
    );
    $wp_customize->add_control( 'screenr_slider_pdtop',
        array(
            'label'           => esc_html__('Padding Top:', 'screenr'),
            'section'         => 'screenr_slider_settings',
            'description'     => 'The slider content padding top in percent (%).',
            //'active_callback' => 'screenr_slider_fullscreen_callback'
        )
    );

    // Slider content padding bottom
    $wp_customize->add_setting( 'screenr_slider_pdbotom',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('10', 'screenr'),
        )
    );
    $wp_customize->add_control( 'screenr_slider_pdbotom',
        array(
            'label'           => esc_html__('Padding Bottom:', 'screenr'),
            'section'         => 'screenr_slider_settings',
            'description'     => 'The slider content padding bottom in percent (%).',
           // 'active_callback' => 'screenr_slider_fullscreen_callback'
        )
    );

    $wp_customize->add_section( 'screenr_slider_items' ,
        array(
            'priority'    => 6,
            'title'       => esc_html__( 'Slider Content', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_slider_panel',
        )
    );


    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'screenr_slider_items',
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
            'screenr_slider_items',
            array(
                'label'     => esc_html__('Items', 'screenr'),
                'description'   => '',
                'priority'     => 40,
                'section'       => 'screenr_slider_items',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'onepress'), // [live_title]
                'max_item'      => 99, // Maximum item can add

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
                        'title' => esc_html__('Image/Video', 'screenr'),
                        'type'  =>'media',
                        'desc'  => esc_html__('Can use image or video here', 'screenr'),
                        'default' => array(
                            'url' => '',
                            'id' => ''
                        )
                    ),



                    'btn_1' => array(
                        'title' => esc_html__('Button 1 text', 'screenr'),
                        'type'  =>'text',
                    ),

                    'btn_1_link' => array(
                        'title' => esc_html__('Button 1 link', 'screenr'),
                        'type'  =>'text',
                    ),

                    'btn_2' => array(
                        'title' => esc_html__('Button 2 text', 'screenr'),
                        'type'  =>'text',
                    ),

                    'btn_2_link' => array(
                        'title' => esc_html__('Button 2 link', 'screenr'),
                        'type'  =>'text',
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
    $wp_customize->add_setting( 'screenr_slider_overlay_color',
        array(
            'sanitize_callback' => 'screenr_sanitize_color_alpha',
            'default'           => 'rgba(0,0,0,.3)',
            'transport' => 'refresh', // refresh or postMessage
        )
    );
    $wp_customize->add_control( new Screenr_Alpha_Color_Control(
            $wp_customize,
            'screenr_slider_overlay_color',
            array(
                'label' 		=> esc_html__('Background Overlay Color', 'screenr'),
                'section' 		=> 'screenr_slider_items',
                'priority'      => 130,
            )
        )
    );

    $wp_customize->add_section( 'screenr_slider_content_layout1' ,
        array(
            'priority'    => 9,
            'title'       => esc_html__( 'Slider Content Layout', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_slider_panel',

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

    wp_localize_script( 'customize-controls', 'onepress_customizer_settings', array(
        'number_action' => $number_action,
        'is_plus_activated' => class_exists( 'OnePress_PLus' ) ? 'y' : 'n',
        'action_url' => admin_url( 'themes.php?page=ft_onepress&tab=actions_required' )
    ) );
}
*/

