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


    $pages  =  get_pages();
    $option_pages = array();
    $option_pages[0] = __( 'Select page', 'onepress' );
    foreach( $pages as $p ){
        $option_pages[ $p->ID ] = $p->post_title;
    }


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

    /*------------------------------------------------------------------------*/
    /*  Section: Features
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_features' ,
        array(
            'priority'    => 5,
            'title'       => esc_html__( 'Features', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'features_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'features_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_features',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'features_items',
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
            'features_items',
            array(
                'label'     => esc_html__('Content Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_features',
                'live_title_id' => 'page_id', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 3, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="#">Screenr Plus</a> to be able to add more items and unlock other premium features!', 'screenr' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(

                    'page_id' => array(
                        'title' => esc_html__('Content page', 'screenr'),
                        'type'  =>'select',
                        'options' => $option_pages
                    ),

                    'thumb_type' => array(
                        'title' => esc_html__('Thumbnail type', 'screenr'),
                        'type'  =>'select',
                        'options' => array(
                            'image'     => esc_html__('Featured image', 'screenr'),
                            'icon' => esc_html__('Font Icon', 'screenr'),
                            'svg'       => esc_html__('SVG icon code', 'screenr'),
                        )
                    ),
                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'desc' => __('Paste your <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome</a> icon class name here.', 'screenr'),
                        'type'  =>'text',
                        "required" => array( 'thumb_type', '=', 'icon' )
                    ),
                    'svg' => array(
                        'title' => esc_html__('SVG icon code', 'screenr'),
                        'type'  =>'textarea',
                        'desc' => esc_html__('Paste svg icon code here', 'screenr'),
                        "required" => array( 'thumb_type', '=', 'svg' )
                    ),
                    'readmore' => array(
                        'title' => esc_html__('Show readmore button', 'screenr'),
                        'type'  =>'checkbox',
                        'default' => 1,
                    ),
                    'bg_color' => array(
                        'title' => esc_html__('Background Color', 'screenr'),
                        'type'  =>'color',
                    ),


                ),

            )
        )
    );


    // Features columns
    $wp_customize->add_setting( 'features_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 3,
        )
    );
    $wp_customize->add_control( 'features_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout Settings', 'screenr'),
            'section'     => 'section_features',
            'description' => esc_html__('Number item per row to display.', 'screenr'),
            'choices' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            )
        )
    );

    // Features ID
    $wp_customize->add_setting( 'features_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('features', 'screenr'),
        )
    );
    $wp_customize->add_control( 'features_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_features',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: About
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_about' ,
        array(
            'title'       => esc_html__( 'About', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'about_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'about_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_about',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // About ID
    $wp_customize->add_setting( 'about_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('about', 'screenr'),
        )
    );
    $wp_customize->add_control( 'about_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_about',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // About page
    $wp_customize->add_setting( 'about_page_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'about_page_id',
        array(
            'label' 		=> esc_html__('Display page:', 'screenr'),
            'section' 		=> 'section_about',
            'type' 		    => 'select',
            'choices'       => $option_pages,
            'description'   => esc_html__('Select page to display on this section.', 'screenr' )
        )
    );

    // About Title
    $wp_customize->add_setting( 'about_tagline',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'about_tagline',
        array(
            'label' 		=> esc_html__('Tagline:', 'screenr'),
            'section' 		=> 'section_about',
            'description'   => esc_html__('Short text about this section.', 'screenr' )
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Section: VideoLight Box
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_videolightbox' ,
        array(
            'title'       => esc_html__( 'Video Lightbox', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'videolightbox_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'videolightbox_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_videolightbox',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // About ID
    $wp_customize->add_setting( 'videolightbox_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('video', 'screenr'),
        )
    );
    $wp_customize->add_control( 'videolightbox_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_videolightbox',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );


    // LightBox Title
    $wp_customize->add_setting( 'videolightbox_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'videolightbox_title',
        array(
            'label' 		=> esc_html__('Title:', 'screenr'),
            'section' 		=> 'section_videolightbox',
            'description'   => esc_html__('Short text about this section.', 'screenr' )
        )
    );

    // LightBox Video
    $wp_customize->add_setting( 'videolightbox_video',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'videolightbox_video',
        array(
            'label' 		=> esc_html__('Video URL:', 'screenr'),
            'section' 		=> 'section_videolightbox',
            'description'   => esc_html__('Youtube or Vimeo url', 'screenr' )
        )
    );

    // LightBox Image Parallax
    $wp_customize->add_setting( 'videolightbox_parallax_img',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'videolightbox_parallax_img',
            array(
                'label' 		=> esc_html__('Parallax image:', 'screenr'),
                'section' 		=> 'section_videolightbox',
            )
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Section: Services
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_services' ,
        array(
            'title'       => esc_html__( 'Services', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'services_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'services_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_services',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // Service ID
    $wp_customize->add_setting( 'services_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('services', 'screenr'),
        )
    );
    $wp_customize->add_control( 'services_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_services',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // Section services title
    $wp_customize->add_setting( 'services_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Services', 'screenr'),
        )
    );
    $wp_customize->add_control( 'services_title',
        array(
            'label' 		=> esc_html__('Section title:', 'screenr'),
            'section' 		=> 'section_services',
        )
    );

    // Section services title
    $wp_customize->add_setting( 'services_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Section subtitle', 'screenr'),
        )
    );
    $wp_customize->add_control( 'services_subtitle',
        array(
            'label' 		=> esc_html__('Section subtitle:', 'screenr'),
            'section' 		=> 'section_services',
        )
    );

    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'services_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => array(

            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'services_items',
            array(
                'label'     => esc_html__('Content Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_services',
                'live_title_id' => 'page_id', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 6, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="#">Screenr Plus</a> to be able to add more items and unlock other premium features!', 'screenr' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(

                    'page_id' => array(
                        'title' => esc_html__('Content page', 'screenr'),
                        'type'  =>'select',
                        'options' => $option_pages
                    ),

                    'thumb_type' => array(
                        'title' => esc_html__('Item style', 'screenr'),
                        'type'  =>'select',
                        'options' => array(
                            'image_top'      => esc_html__('Featured image top', 'screenr'),
                            'image_overlay'  => esc_html__('Featured image overlay', 'screenr'),
                            'icon'           => esc_html__('Font icon', 'screenr'),
                            'no_thumb'       => esc_html__('No thumbnail', 'screenr'),
                        )
                    ),
                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'desc'  => __('Paste your <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome</a> icon class name here.', 'screenr'),
                        'type'  =>'text',
                        "required" => array( 'thumb_type', '=', 'icon' )
                    ),
                    'readmore' => array(
                        'title' => esc_html__('Show readmore link', 'screenr'),
                        'type'  =>'checkbox',
                        'default' => 1,
                    ),

                ),

            )
        )
    );


    // Features columns
    $wp_customize->add_setting( 'services_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 2,
        )
    );
    $wp_customize->add_control( 'services_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout Settings', 'screenr'),
            'section'     => 'section_services',
            'description' => esc_html__('Number columns to display.', 'screenr'),
            'choices' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            )
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: Contact
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_contact' ,
        array(
            'title'       => esc_html__( 'Contact', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'contact_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'contact_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_contact',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // Contact ID
    $wp_customize->add_setting( 'contact_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('contact', 'screenr'),
        )
    );
    $wp_customize->add_control( 'contact_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_contact',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // Section contact title
    $wp_customize->add_setting( 'contact_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Contact Us', 'screenr'),
        )
    );
    $wp_customize->add_control( 'contact_title',
        array(
            'label' 		=> esc_html__('Section title:', 'screenr'),
            'section' 		=> 'section_contact',
        )
    );

    // Section contact subtitle
    $wp_customize->add_setting( 'contact_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Keep in touch', 'screenr'),
        )
    );
    $wp_customize->add_control( 'contact_subtitle',
        array(
            'label' 		=> esc_html__('Section subtitle:', 'screenr'),
            'section' 		=> 'section_contact',
        )
    );

    // Section contact description
    $wp_customize->add_setting( 'contact_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Fill out the form below and you will hear from us shortly.', 'screenr'),
        )
    );
    $wp_customize->add_control( 'contact_desc',
        array(
            'label' 		=> esc_html__('Section description:', 'screenr'),
            'section' 		=> 'section_contact',
        )
    );

    // Section contact content
    $wp_customize->add_setting( 'contact_content',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           =>  '',
        )
    );
    $wp_customize->add_control(
        new Screenr_Editor_Custom_Control(
            $wp_customize,
            'contact_content',
            array(
                'label' 		=> esc_html__('Contact content:', 'screenr'),
                'section' 		=> 'section_contact',
            )
        )
    );

    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'contact_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => array(

            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'contact_items',
            array(
                'label'     => esc_html__('Contact Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_contact',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 3, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="#">Screenr Plus</a> to be able to add more items and unlock other premium features!', 'screenr' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(

                    'title' => array(
                        'title' => esc_html__('Title', 'screenr'),
                        'type'  =>'text',
                    ),

                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'desc'  => __('Paste your <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome</a> icon class name here.', 'screenr'),
                        'type'  =>'text',
                    ),

                    'url' => array(
                        'title' => esc_html__('URL', 'screenr'),
                        'type'  =>'text',
                        'desc'  => __('Custom url', 'screenr'),
                    ),

                ),

            )
        )
    );

    $wp_customize->add_setting( 'contact_layout',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => 3,
        )
    );
    $wp_customize->add_control( 'contact_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Items layout settings', 'screenr'),
            'section'     => 'section_contact',
            'description' => esc_html__('Number item per row to display.', 'screenr'),
            'choices' => array(
                2 => 2,
                3 => 3,
                4 => 4
            )
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: Client
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_clients' ,
        array(
            'title'       => esc_html__( 'Clients', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
        )
    );

    // Show section
    $wp_customize->add_setting( 'clients_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'clients_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_clients',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // Contact ID
    $wp_customize->add_setting( 'clients_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('clients', 'screenr'),
        )
    );
    $wp_customize->add_control( 'clients_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_clients',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // Section clients title
    $wp_customize->add_setting( 'clients_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'clients_title',
        array(
            'label' 		=> esc_html__('Section title:', 'screenr'),
            'section' 		=> 'section_clients',
        )
    );

    // Section clients subtitle
    $wp_customize->add_setting( 'clients_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Have been featured on', 'screenr'),
        )
    );
    $wp_customize->add_control( 'clients_subtitle',
        array(
            'label' 		=> esc_html__('Section subtitle:', 'screenr'),
            'section' 		=> 'section_clients',
        )
    );

    // Section clients description
    $wp_customize->add_setting( 'clients_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        new Screenr_Editor_Custom_Control(
            $wp_customize,
            'clients_desc',
            array(
                'label' 		=> esc_html__('Section description:', 'screenr'),
                'section' 		=> 'section_clients',
            )
        )
    );


    $wp_customize->add_setting(
        'clients_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => array(

            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'clients_items',
            array(
                'label'     => esc_html__('Clients', 'screenr'),
                'description'   => '',
                'section'       => 'section_clients',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 99, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="#">Screenr Plus</a> to be able to add more items and unlock other premium features!', 'screenr' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(

                    'title' => array(
                        'title' => esc_html__('Title', 'screenr'),
                        'type'  =>'text',
                    ),

                    'image' => array(
                        'title' => esc_html__('Client logo', 'screenr'),
                        'type'  =>'media',
                    ),

                    'url' => array(
                        'title' => esc_html__('Client URL', 'screenr'),
                        'type'  =>'text',
                    ),

                ),

            )
        )
    );

    $wp_customize->add_setting( 'clients_layout',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => 5,
        )
    );
    $wp_customize->add_control( 'clients_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Items layout settings', 'screenr'),
            'section'     => 'section_clients',
            'description' => esc_html__('Number item per row to display.', 'screenr'),
            'choices' => array(
                4 => 4,
                5 => 5,
                6 => 6
            )
        )
    );











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

