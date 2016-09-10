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

    // Load custom sections.
    require get_template_directory() . '/inc/customizer-sections.php';

    // Register custom section types.
    $wp_customize->register_section_type( 'Screenr_Customize_Section_Plus' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

    $pages           = get_pages();
    $option_pages    = array();
    $option_pages[0] = esc_html__( 'Select page', 'screenr' );
    foreach( $pages as $p ){
        $option_pages[ $p->ID ] = $p->post_title;
    }

    $wp_customize->add_setting( 'screenr_hide_sitetitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => 0,
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        'screenr_hide_sitetitle',
        array(
            'label' 		=> esc_html__('Hide site title', 'screenr'),
            'section' 		=> 'title_tagline',
            'type'          => 'checkbox',
        )
    );

    $wp_customize->add_setting( 'screenr_hide_tagline',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        'screenr_hide_tagline',
        array(
            'label' 		=> esc_html__('Hide site tagline', 'screenr'),
            'section' 		=> 'title_tagline',
            'type'          => 'checkbox',

        )
    );

    /*------------------------------------------------------------------------*/
    /*  Upgrade Panel
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'screenr_plus_upgrade',
            array(
                'title'     => esc_html__( 'Screenr Plus', 'screenr' ),
                'priority'  => 180,
                'plus_text' => esc_html__( 'Upgrade Now', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Site Options
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'screenr_options',
        array(
            'priority'       => 170,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Theme Options', 'screenr' ),
            'description'    => '',
        )
    );


    /* Theme styling
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'theme_styling' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Styling', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

    // Move background setting to theme styling
    if ( $wp_customize->get_control('background_color') ) {
        $wp_customize->get_control('background_color')->section = 'theme_styling';
    }

    $wp_customize->add_setting( 'primary_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color',
        array(
            'label'       => esc_html__( 'Primary color', 'screenr' ),
            'section'     => 'theme_styling',
            'description' => '',
            'priority'    => 3,
        )
    ));

    /* Typography
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'screenr_typography_plus',
            array(
                'title'     => esc_html__( 'Typography', 'screenr' ),
                'priority'  => 4,
                'panel'     => 'screenr_options',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );

    /* Header
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'header_settings' ,
        array(
            'priority'    => 7,
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

    /* Default menu style
     * --------------------------------------*/
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



    /* Transparent menu style
    * --------------------------------------*/


    // Header BG Color
    $wp_customize->add_setting( 'header_t_bg_color',
        array(
            'sanitize_callback' => 'screenr_sanitize_color_alpha',
            'default' => 'rgba(0,0,0,.8)'
        ) );
    $wp_customize->add_control( new Screenr_Alpha_Color_Control( $wp_customize, 'header_t_bg_color',
        array(
            'label'       => esc_html__( 'Background Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));


    // Header Menu Color
    $wp_customize->add_setting( 'menu_t_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_t_color',
        array(
            'label'       => esc_html__( 'Menu Link Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));

    // Header Menu Hover Color
    $wp_customize->add_setting( 'menu_t_hover_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_t_hover_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',

        )
    ));

    // Header Menu Hover Color
    $wp_customize->add_setting( 'menu_t_hover_border_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_t_hover_border_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active border color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',

        )
    ));

    // Header Menu Hover BG Color
    $wp_customize->add_setting( 'menu_t_hover_bg_color',
        array(
            'sanitize_callback' => 'sanitize_hex_color_no_hash',
            'sanitize_js_callback' => 'maybe_hash_hex_color',
            'default' => ''
        ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_t_hover_bg_color',
        array(
            'label'       => esc_html__( 'Menu Link Hover/Active BG Color', 'screenr' ),
            'section'     => 'header_settings',
            'description' => '',
        )
    ));



    //----------------------------------
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

    /* Page Header
   ----------------------------------------------------------------------*/

        // Header background BG Color
        $wp_customize->add_setting( 'page_header_bg_color',
            array(
                'sanitize_callback'     => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback'  => 'maybe_hash_hex_color',
                'default'               => '000000'
            ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_header_bg_color',
            array(
                'label'       => esc_html__( 'Background color', 'screenr' ),
                'section'     => 'header_image',
                'description' => '',
            )
        ));

        $wp_customize->add_setting( 'page_header_bg_overlay',
            array(
                'sanitize_callback' => 'screenr_sanitize_color_alpha',
                'default' => ''
            ) );
        $wp_customize->add_control( new Screenr_Alpha_Color_Control( $wp_customize, 'page_header_bg_overlay',
            array(
                'label'       => esc_html__( 'Background image overlay color', 'screenr' ),
                'section'     => 'header_image',
                'description' => '',
            )
        ));


        // Header page padding top
        $wp_customize->add_setting( 'page_header_pdtop',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '13',
            )
        );
        $wp_customize->add_control( 'page_header_pdtop',
            array(
                'label'       => esc_html__('Padding top', 'screenr'),
                'section'     => 'header_image',
                'description' => esc_html__('The page header padding top in percent (%).', 'screenr'),
            )
        );

        // Header page padding top
        $wp_customize->add_setting( 'page_header_pdbottom',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '13',
            )
        );
        $wp_customize->add_control( 'page_header_pdbottom',
            array(
                'label'       => esc_html__('Padding bottom', 'screenr'),
                'section'     => 'header_image',
                'description' => esc_html__('The page header padding bottom in percent (%).', 'screenr'),
            )
        );




    /* Blog settings
  ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'blog_settings' ,
        array(
            'priority'    => 4,
            'title'       => esc_html__( 'Blog Settings', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

        // Blog page title
        $wp_customize->add_setting( 'page_blog_title',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => esc_html__('The Blog', 'screenr'),
            )
        );
        $wp_customize->add_control( 'page_blog_title',
            array(
                'label'       => esc_html__('Blog title', 'screenr'),
                'section'     => 'blog_settings',
                'description' => esc_html__('Custom page header title on single posts.', 'screenr'),
            )
        );


        // Disable featured image in single post
        $wp_customize->add_setting( 'disable_featured_image',
            array(
                'sanitize_callback' => 'screenr_sanitize_checkbox',
                'default'           => 0,
            )
        );
        $wp_customize->add_control( 'disable_featured_image',
            array(
                'label'       => esc_html__('Disable featured image in single post', 'screenr'),
                'type'        => 'checkbox',
                'section'     => 'blog_settings',
            )
        );


        // Blog post date
        $wp_customize->add_setting( 'show_post_date',
            array(
                'sanitize_callback' => 'screenr_sanitize_checkbox',
                'default'           => 1,
            )
        );
        $wp_customize->add_control( 'show_post_date',
            array(
                'label'       => esc_html__('Display post date', 'screenr'),
                'type'        => 'checkbox',
                'section'     => 'blog_settings',
                'description' => esc_html__('Display post date on single post or posts listing page.', 'screenr'),
            )
        );

        // Blog post author
        $wp_customize->add_setting( 'show_post_author',
            array(
                'sanitize_callback' => 'screenr_sanitize_checkbox',
                'default'           => 1,
            )
        );
        $wp_customize->add_control( 'show_post_author',
            array(
                'label'       => esc_html__('Display post author', 'screenr'),
                'type'        => 'checkbox',
                'section'     => 'blog_settings',
                'description' => esc_html__('Display post author on single post or posts listing page.', 'screenr'),
            )
        );


        // Blog post comment
        $wp_customize->add_setting( 'show_post_comment',
            array(
                'sanitize_callback' => 'screenr_sanitize_checkbox',
                'default'           => 1,
            )
        );
        $wp_customize->add_control( 'show_post_comment',
            array(
                'label'       => esc_html__('Display post comments', 'screenr'),
                'type'        => 'checkbox',
                'section'     => 'blog_settings',
                'description' => esc_html__('Display post comments on single post or posts listing page.', 'screenr'),
            )
        );

        // Blog post cate
        $wp_customize->add_setting( 'show_post_cate',
            array(
                'sanitize_callback' => 'screenr_sanitize_checkbox',
                'default'           => 1,
            )
        );
        $wp_customize->add_control( 'show_post_cate',
            array(
                'label'       => esc_html__('Display post category', 'screenr'),
                'type'        => 'checkbox',
                'section'     => 'blog_settings',
                'description' => esc_html__('Display post category on single post or posts listing page.', 'screenr'),
            )
        );


    /* Blog settings
 ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'layout_settings' ,
        array(
            'priority'    => 5,
            'title'       => esc_html__( 'Layout Settings', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

    // Blog page title
    $wp_customize->add_setting( 'layout_settings',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'right',
        )
    );
    $wp_customize->add_control( 'layout_settings',
        array(
            'label'       => esc_html__('Site Layout', 'screenr'),
            'section'     => 'layout_settings',
            'type' => 'select',
            'choices' => array(
                'right' => esc_html__('Right Sidebar', 'screenr'),
                'left'  => esc_html__('Left Sidebar', 'screenr'),
            ),
            'description' => esc_html__('Select your site layout', 'screenr'),
        )
    );

    /* Page Footer
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'page_footer_settings' ,
        array(
            'priority'    => 20,
            'title'       => esc_html__( 'Footer', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
        )
    );

        // Features columns
        $wp_customize->add_setting( 'footer_layout',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 4,
            )
        );
        $wp_customize->add_control( 'footer_layout',
            array(
                'type'        => 'select',
                'label'       => esc_html__('Footer Layout', 'screenr'),
                'section'     => 'page_footer_settings',
                'description' => esc_html__('Number footer columns to display.', 'screenr'),
                'choices' => array(
                    4  => 4,
                    3  => 3,
                    2  => 2,
                    1  => 1,
                    0  => esc_html__('Disable footer widgets', 'screenr'),
                )
            )
        );


        // Custom 3 columns
        $wp_customize->add_setting( 'footer_custom_2_columns',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '6+6',
            )
        );
        $wp_customize->add_control( 'footer_custom_2_columns',
            array(
                'label'       => esc_html__('Custom footer 2 columns width', 'screenr'),
                'section'     => 'page_footer_settings',
                'description' => esc_html__('Enter int numbers and sum of them must smaller or equal 12, separated by "+"', 'screenr'),
            )
        );

        // Custom 3 columns
        $wp_customize->add_setting( 'footer_custom_3_columns',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '4+4+4',
            )
        );
        $wp_customize->add_control( 'footer_custom_3_columns',
            array(
                'label'       => esc_html__('Custom footer 3 columns width', 'screenr'),
                'section'     => 'page_footer_settings',
                'description' => esc_html__('Enter int numbers and sum of them must smaller or equal 12, separated by "+"', 'screenr'),
            )
        );

        // Custom 4 columns
        $wp_customize->add_setting( 'footer_custom_4_columns',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '3+3+3+3',
            )
        );
        $wp_customize->add_control( 'footer_custom_4_columns',
            array(
                'label'       => esc_html__('Custom footer 4 columns width', 'screenr'),
                'section'     => 'page_footer_settings',
                'description' => esc_html__('Enter int numbers and sum of them must smaller or equal 12, separated by "+"', 'screenr'),
            )
        );


        // Footer widgets background
        $wp_customize->add_setting( 'footer_widgets_bg',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_bg',
                array(
                    'label'       => esc_html__('Footer widgets background color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer widgets text color
        $wp_customize->add_setting( 'footer_widgets_heading',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_heading',
                array(
                    'label'       => esc_html__('Footer widgets heading', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer widgets text color
        $wp_customize->add_setting( 'footer_widgets_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_color',
                array(
                    'label'       => esc_html__('Footer widgets text color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer widgets link color
        $wp_customize->add_setting( 'footer_widgets_link_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_link_color',
                array(
                    'label'       => esc_html__('Footer widgets link color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer widgets link hover color
        $wp_customize->add_setting( 'footer_widgets_link_hover_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_link_hover_color',
                array(
                    'label'       => esc_html__('Footer widgets link hover color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer copyright border top
        $wp_customize->add_setting( 'footer_copyright_border_top',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_copyright_border_top',
                array(
                    'label'       => esc_html__('Footer copyright border top color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );


        // Footer copyright bg
        $wp_customize->add_setting( 'footer_copyright_bg',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_copyright_bg',
                array(
                    'label'       => esc_html__('Footer copyright background color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        // Footer copyright color
        $wp_customize->add_setting( 'footer_copyright_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_copyright_color',
                array(
                    'label'       => esc_html__('Footer copyright color', 'screenr'),
                    'section'     => 'page_footer_settings',
                )
            )
        );

        $wp_customize->add_setting( 'footer_copyright_editor_message',
            array(
                'sanitize_callback' => 'screenr_sanitize_text',
                'default'           => '',
            )
        );
    	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'footer_copyright_editor_message',
    			array(
                    'type'        => 'group_heading_message',
                    'title'       => esc_html__('Change Footer Copyright Text and Hide Theme Author Link', 'screenr'),
                    'section'     => 'page_footer_settings',
                    'description' => sprintf( esc_html__('Upgrade to %1s in order to change site footer copyright information and hide theme author link via Customizer.', 'screenr'), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ),
    			)
    		)
    	);


    /* Theme styling
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'custom_css' ,
        array(
            'priority'    => 100,
            'title'       => esc_html__( 'Custom CSS', 'screenr' ),
            'description' => '',
            'panel'       => 'screenr_options',
            'capability' => 'edit_theme_options',
        )
    );

    $wp_customize->add_setting( 'screenr_custom_css',
        array(
            'sanitize_callback' => 'screenr_sanitize_css',
            'default' => '',
            'type' => 'option',
            'transport' => 'postMessage',
        ) );
    $wp_customize->add_control(
        'screenr_custom_css',
        array(
            'label'       => esc_html__( 'Custom CSS', 'screenr' ),
            'section'     => 'custom_css',
            'description' => '',
            'type'        => 'textarea',
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Panel: Section Order & Styling
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section( 'front_page_sections_order_styling',
        array(
            'priority'       => 151,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Frontpage Sections Order & Styling', 'screenr' ),
            'description'    => '',
            'active_callback' => 'screenr_showon_frontpage'
        )
    );

        $wp_customize->add_setting( 'sections_order_message',
            array(
                'sanitize_callback' => 'screenr_sanitize_text',
                'default'           => '',
            )
        );
    	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'sections_order_message',
    			array(
                    'type'        => 'group_heading_message',
                    'title'       => esc_html__('Drag & Drop Section Orders', 'screenr'),
                    'section'     => 'front_page_sections_order_styling',
                    'description' => sprintf( esc_html__('Check out the %1s version for full control over the frontpage SECTIONS ORDER!', 'screenr'), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ),
    			)
    		)
    	);
        $wp_customize->add_setting( 'sections_styling_text',
            array(
                'sanitize_callback' => 'screenr_sanitize_text',
                'default'           => '',
            )
        );
    	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'sections_styling_text',
    			array(
                    'type'        => 'group_heading_message',
                    'title'       => esc_html__('Advandced Section Styling', 'screenr'),
                    'section'     => 'front_page_sections_order_styling',
                    'description' => sprintf( esc_html__('Check out the %1s version for full control over the section styling which includes background color, image, video, parallax effect, custom style and more ...', 'screenr'), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ),
    			)
    		)
    	);


    /**
     * @see screen_showon_frontpage
     */
    $wp_customize->add_panel( 'front_page_sections',
        array(
            'priority'       => 150,
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => esc_html__( 'Frontpage Sections', 'screenr' ),
            'description'    => '',
            'active_callback' => 'screenr_showon_frontpage'
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Panel: Sections
    /*------------------------------------------------------------------------*/

    /**
     * @see screen_showon_frontpage
     */
    $wp_customize->add_panel( 'front_page_sections',
        array(
            'priority'       => 150,
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

    // Slider ID
    $wp_customize->add_setting( 'slider_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('hero', 'screenr'),
        )
    );
    $wp_customize->add_control( 'slider_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_slider',
            'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    $slider_content = screenr_get_default_slider_content();

    $wp_customize->add_setting(
        'slider_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => apply_filters( 'screenr_default_slider_items', array(
                    array(
                        'content_layout_1' => $slider_content,
                        'media'=> array(
                            'url' => get_template_directory_uri() . '/assets/images/hero.jpg',
                            'id' => ''
                        )
                    )
                )
            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'slider_items',
            array(
                'label'     => esc_html__('Hero Item', 'screenr'),
                'description'   => '',
                'section'       => 'section_slider',
                'live_title_id' => 'title', // apply for input text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 1, // Maximum item can add
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items (display as a slider) and self-hosted background video option.', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
                'fields'    => array(
                    'content_layout_1' => array(
                        'title' => esc_html__('Content', 'screenr'),
                        'type'  =>'editor',
                        'mod'   =>'html',
                        'default' => $slider_content
                    ),
                    'media' => array(
                        'title' => esc_html__('Background Image', 'screenr'),
                        'type'  =>'media',
                        'default' => array(
                            'url' => '',
                            'id' => ''
                        )
                    ),
                    'position' => array(
                        'title' => esc_html__('Content align', 'screenr'),
                        'type'  =>'select',
                        'options' => array(
                            'center' => esc_html__('Center', 'screenr'),
                            'left' => esc_html__('Left', 'screenr'),
                            'right' => esc_html__('Right', 'screenr'),
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

    // Enable Parallax Effect.
    $wp_customize->add_setting( 'slider_parallax',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control( 'slider_parallax',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Enable parallax effect', 'screenr'),
            'section'     => 'section_slider',
            'description' => esc_html__('Check this box to enable parallax effect for hero section.', 'screenr'),
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
            'label'       => esc_html__('Make hero section full screen', 'screenr'),
            'section'     => 'section_slider',
            'description' => esc_html__('Check this box to make hero section full screen.', 'screenr'),
        )
    );


    // Slide padding
    $wp_customize->add_setting( 'slider_pd_top',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',

        )
    );
    $wp_customize->add_control( 'slider_pd_top',
        array(
            'label' 		=> esc_html__('Padding top', 'screenr'),
            'section' 		=> 'section_slider',
            'description'   => esc_html__( 'The slider content padding top in percent (%).', 'screenr' ),
            'active_callback'   => 'screenr_not_fullscreen'
        )
    );

    // Slide padding
    $wp_customize->add_setting( 'slider_pd_bottom',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'slider_pd_bottom',
        array(
            'label' 		=> esc_html__('Padding bottom', 'screenr'),
            'section' 		=> 'section_slider',
            'description'   => esc_html__( 'The slider content padding bottom in percent (%).', 'screenr' ),
            'active_callback'   => 'screenr_not_fullscreen'
        )
    );


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

    // Group Heading
	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'feature_setting_group_heading',
			array(
				'type' 			=> 'group_heading_top',
				'title'			=> esc_html__( 'Section Settings', 'screenr' ),
				'section' 		=> 'section_features'
			)
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

    // Features Title
    $wp_customize->add_setting( 'features_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'features_title',
        array(
            'label' 		=> esc_html__('Section Title:', 'screenr'),
            'section' 		=> 'section_features',
        )
    );

    // Features Subtitle
    $wp_customize->add_setting( 'features_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'features_subtitle',
        array(
            'label' 		=> esc_html__('Section Subtitle:', 'screenr'),
            'section' 		=> 'section_features',
        )
    );

    // Features Description
    $wp_customize->add_setting( 'features_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        'features_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_features',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'feature_content_group_heading',
			array(
				'type' 			=> 'group_heading',
				'title'			=> esc_html__( 'Section Content', 'screenr' ),
				'section' 		=> 'section_features'
			)
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
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items and unlock other premium features!', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
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
                        'type'  =>'icon',
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

                    'readmore_txt' => array(
                        'title' => esc_html__('Read more text', 'screenr'),
                        'type'  =>'textarea',
                        'default' => esc_html__('Read More', 'screenr'),
                        "required" => array( 'readmore', '=', '1' )
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


    /*------------------------------------------------------------------------*/
    /*  Section: About
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_about' ,
        array(
            'title'       => esc_html__( 'About', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
            'priority'    => 7,
        )
    );

    // Group Heading
	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'about_setting_group_heading',
			array(
				'type' 			=> 'group_heading_top',
				'title'			=> esc_html__( 'Section Settings', 'screenr' ),
				'section' 		=> 'section_about'
			)
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

    // About Title
    $wp_customize->add_setting( 'about_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__( 'About us', 'screenr' ),
        )
    );
    $wp_customize->add_control( 'about_title',
        array(
            'label' 		=> esc_html__('Section Title:', 'screenr'),
            'section' 		=> 'section_about',
        )
    );

    // About Subtitle
    $wp_customize->add_setting( 'about_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'about_subtitle',
        array(
            'label' 		=> esc_html__('Section Subtitle:', 'screenr'),
            'section' 		=> 'section_about',
        )
    );

    // About Description
    $wp_customize->add_setting( 'about_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__( 'We provide creative solutions that get attention and meaningful to clients around the world.', 'screenr' ),
        )
    );
    $wp_customize->add_control(
        'about_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_about',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'about_content_group_heading',
			array(
				'type' 			=> 'group_heading',
				'title'			=> esc_html__( 'Section Content', 'screenr' ),
				'section' 		=> 'section_about'
			)
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
    $wp_customize->add_setting( 'about_page_content_type',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => 'excerpt',
        )
    );
    $wp_customize->add_control( 'about_page_content_type',
        array(
            'label' 		=> esc_html__('Page content type:', 'screenr'),
            'section' 		=> 'section_about',
            'type' 		    => 'select',
            'choices'       => array(
                'excerpt' => esc_html__('Page excerpt', 'screenr'),
                'content' => esc_html__('Page Content', 'screenr'),
            ),
            'description'   => esc_html__('Select content type of page above to display on this section.', 'screenr' )
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
            'priority'    => 9,
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'video_lightbox_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_videolightbox'
            )
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
        )
    );

    // About ID
    $wp_customize->add_setting( 'videolightbox_id',
        array(
            'sanitize_callback' => 'sanitize_text_field',
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
            'default'           => __( 'Parallax & Video Lightbox - Perfected', 'screenr' ),
        )
    );
    $wp_customize->add_control( 'videolightbox_title',
        array(
            'label' 		=> esc_html__('Title:', 'screenr'),
            'section' 		=> 'section_videolightbox',
            'description'   => esc_html__('Short text about this section.', 'screenr' )
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'video_lightbox_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_videolightbox'
            )
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
            'description'   => esc_html__('Youtube or Vimeo url, e.g: https://www.youtube.com/watch?v=xxxxx', 'screenr' )
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

    // Overlay color
    $wp_customize->add_setting( 'videolightbox_overlay',
        array(
            'sanitize_callback' => 'screenr_sanitize_color_alpha',
            'default'           => 'rgba(0,0,0,.4)',
            'transport' => 'refresh', // refresh or postMessage
        )
    );
    $wp_customize->add_control( new Screenr_Alpha_Color_Control(
            $wp_customize,
            'videolightbox_overlay',
            array(
                'label' 		=> esc_html__('Background Overlay Color', 'screenr'),
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
            'priority'    => 11,
        )
    );

    // Group Heading
	$wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'service_setting_group_heading',
			array(
				'type' 			=> 'group_heading_top',
				'title'			=> esc_html__( 'Section Settings', 'screenr' ),
				'section' 		=> 'section_services'
			)
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

    // Section title
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

    // Section subtitle
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

    // Services Description
    $wp_customize->add_setting( 'services_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        'services_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_services',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'service_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_services'
            )
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
                'max_item'      => 4, // Maximum item can add
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items and unlock other premium features!', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
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
                            'icon'           => esc_html__('Font icon', 'screenr'),
                            'no_thumb'       => esc_html__('No thumbnail', 'screenr'),
                        )
                    ),
                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'type'  =>'icon',
                        "required" => array( 'thumb_type', '=', 'icon' )
                    ),
                    'readmore' => array(
                        'title' => esc_html__('Show readmore link', 'screenr'),
                        'type'  =>'checkbox',
                        'default' => 1,
                    ),

                    'readmore_txt' => array(
                        'title' => esc_html__('Read more text', 'screenr'),
                        'type'  =>'textarea',
                        'default' => esc_html__( 'More detail &rarr;', 'screenr' ),
                        "required" => array( 'readmore', '=', '1' )
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
    /*  Section: Clients
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_clients' ,
        array(
            'title'       => esc_html__( 'Clients', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
            'priority'    => 13,
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'clients_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_clients'
            )
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
            'default'           => esc_html__('We had been featured on', 'screenr'),
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
        'clients_desc',
        array(
            'label' 		=> esc_html__('Section description:', 'screenr'),
            'section' 		=> 'section_clients',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'clients_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_clients'
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
                'max_item'      => 5, // Maximum item can add
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items and unlock other premium features!', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
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


    /*------------------------------------------------------------------------*/
    /*  Section: Counter
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_counter' ,
        array(
            'title'       => esc_html__( 'Counter', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
            'priority'    => 15,
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'counter_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_counter'
            )
        )
    );

    // Show section
    $wp_customize->add_setting( 'counter_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'counter_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_counter',
        )
    );

    // Section ID
    $wp_customize->add_setting( 'counter_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('counter', 'screenr'),
        )
    );
    $wp_customize->add_control( 'counter_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_counter',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // Section title
    $wp_customize->add_setting( 'counter_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'counter_title',
        array(
            'label' 		=> esc_html__('Section title:', 'screenr'),
            'section' 		=> 'section_counter',
        )
    );

    // Section subtitle
    $wp_customize->add_setting( 'counter_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Some Fun Facts about our agency?', 'screenr'),
        )
    );
    $wp_customize->add_control( 'counter_subtitle',
        array(
            'label' 		=> esc_html__('Section subtitle:', 'screenr'),
            'section' 		=> 'section_counter',
        )
    );

    // Section Description
    $wp_customize->add_setting( 'counter_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        'counter_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_counter',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'counter_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_counter'
            )
        )
    );

    /**
     * @see screenr_sanitize_repeatable_data_field
     */
    $wp_customize->add_setting(
        'counter_items',
        array(
            'sanitize_callback' => 'screenr_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
            'default' => array(

            )
        ) );

    $wp_customize->add_control(
        new Screenr_Customize_Repeatable_Control(
            $wp_customize,
            'counter_items',
            array(
                'label'     => esc_html__('Content Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_counter',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 4, // Maximum item can add
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items and unlock other premium features!', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(
                    'title' => array(
                        'title' => esc_html__('Title', 'screenr'),
                        'type'  =>'text',
                    ),
                    'number' => array(
                        'title' => esc_html__('Number', 'screenr'),
                        'type'  =>'text',
                    ),
                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'type'  =>'icon',

                    ),
                    'before_number' => array(
                        'title' => esc_html__('Before number', 'screenr'),
                        'type'  =>'text',
                    ),
                    'after_number' => array(
                        'title' => esc_html__('After number', 'screenr'),
                        'type'  =>'text',
                    ),
                    'bg_color' => array(
                        'title' => esc_html__('Custom background color', 'screenr'),
                        'type'  =>'color',
                    ),
                ),
            )
        )
    );

    // Counter columns
    $wp_customize->add_setting( 'counter_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 3,
        )
    );
    $wp_customize->add_control( 'counter_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout Settings', 'screenr'),
            'section'     => 'section_counter',
            'description' => esc_html__('Number columns to display.', 'screenr'),
            'choices' => array(
                12 => 1,
                6 => 2,
                4 => 3,
                3 => 4,
            )
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: News
    /*------------------------------------------------------------------------*/

    $wp_customize->add_section( 'section_news' ,
        array(
            'title'       => esc_html__( 'Latest News', 'screenr' ),
            'description' => '',
            'panel'       => 'front_page_sections',
            'priority'    => 21,
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'news_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_news'
            )
        )
    );

    // Show section
    $wp_customize->add_setting( 'news_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'news_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_news',
        )
    );

    // News ID
    $wp_customize->add_setting( 'news_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('news', 'screenr'),
        )
    );
    $wp_customize->add_control( 'news_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_news',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    $wp_customize->add_setting( 'news_title',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Latest News', 'screenr'),
        )
    );
    $wp_customize->add_control( 'news_title',
        array(
            'label' 		=> esc_html__('Section title:', 'screenr'),
            'section' 		=> 'section_news',
        )
    );

    $wp_customize->add_setting( 'news_subtitle',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => __( 'Section subtitle', 'screenr' ),
        )
    );
    $wp_customize->add_control( 'news_subtitle',
        array(
            'label' 		=> esc_html__('Section subtitle:', 'screenr'),
            'section' 		=> 'section_news',
        )
    );

    // Section description
    $wp_customize->add_setting( 'news_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        'news_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_news',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'news_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_news'
            )
        )
    );

    // Number posts to show
    $wp_customize->add_setting( 'news_num_post',
        array(
            'sanitize_callback' => 'absint',
            'default'           => 3,
        )
    );
    $wp_customize->add_control( 'news_num_post',
        array(
            'label' 		=> esc_html__('Number Posts:', 'screenr'),
            'section' 		=> 'section_news',
            'description'   => esc_html__('How many posts you want to show.', 'screenr' )
        )
    );

    $wp_customize->add_setting( 'news_layout',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => 3,
        )
    );
    $wp_customize->add_control( 'news_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout Settings', 'screenr'),
            'section'     => 'section_news',
            'description' => esc_html__('Number item per row to display.', 'screenr'),
            'choices' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
            )
        )
    );


    $wp_customize->add_setting( 'news_loadmore',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => 'ajax',
        )
    );
    $wp_customize->add_control( 'news_loadmore',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Load more posts button', 'screenr'),
            'section'     => 'section_news',
            'description' => esc_html__('Number item per row to display.', 'screenr'),
            'choices' => array(
                'ajax' => esc_html__('Ajax load', 'screenr'),
                'link' => esc_html__('Custom link', 'screenr'),
                'hide' => esc_html__('Hide', 'screenr'),
            )
        )
    );

    $wp_customize->add_setting( 'news_more_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'news_more_text',
        array(
            'label'       => esc_html__('Custom load more button label', 'screenr'),
            'section'     => 'section_news',
        )
    );

    $wp_customize->add_setting( 'news_more_link',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'news_more_link',
        array(
            'label'       => esc_html__('Custom load more posts link', 'screenr'),
            'section'     => 'section_news',
            'description' => esc_html__('Link to your posts page.', 'screenr'),
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
            'priority'    => 22,
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'contact_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_contact'
            )
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

    // Section description
    $wp_customize->add_setting( 'contact_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('Fill out the form below and you will hear from us shortly.', 'screenr'),
        )
    );
    $wp_customize->add_control(
        'contact_desc',
        array(
            'label' 		=> esc_html__('Section Description:', 'screenr'),
            'section' 		=> 'section_contact',
            'type' 		    => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'contact_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_contact'
            )
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
        'contact_content',
        array(
            'label' 		=> esc_html__('Content:', 'screenr'),
            'section' 		=> 'section_contact',
            'type' 		    => 'textarea',
            'description'   => esc_html__('You can install any contact form plugin such as Contact Form 7 and then paste the shortcode of the form here.', 'screenr'),
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
                'label'     => esc_html__('Contact Detail Items', 'screenr'),
                'description'   => '',
                'section'       => 'section_contact',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'screenr'), // [live_title]
                'max_item'      => 4, // Maximum item can add
                'limited_msg' 	=> sprintf( esc_html__( 'Upgrade to %1$s to be able to add more items and unlock other premium features!', 'screenr' ), '<a target="_blank" href="'.esc_url( screenr_get_plus_url() ).'">'.esc_html__( 'Screenr Plus', 'screenr' ).'</a>' ),
                //'allow_unlimited' => false, // Maximum item can add
                'fields'    => array(

                    'title' => array(
                        'title' => esc_html__('Title', 'screenr'),
                        'type'  =>'text',
                    ),

                    'icon' => array(
                        'title' => esc_html__('Font icon', 'screenr'),
                        'type'  =>'icon',
                    ),

                    'url' => array(
                        'title' => esc_html__('URL', 'screenr'),
                        'type'  =>'text',
                        'desc'  => esc_html__('Custom url', 'screenr'),
                    ),
                ),
            )
        )
    );

    /*------------------------------------------------------------------------*/
    /*  Premium Sections
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'premium_section_projects',
            array(
                'title'     => esc_html__( 'Projects', 'screenr' ),
                'priority'  => 30,
                'panel'     => 'front_page_sections',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'premium_section_testimonials',
            array(
                'title'     => esc_html__( 'Testimonials', 'screenr' ),
                'priority'  => 32,
                'panel'     => 'front_page_sections',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'premium_section_team',
            array(
                'title'     => esc_html__( 'Team', 'screenr' ),
                'priority'  => 32,
                'panel'     => 'front_page_sections',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'premium_section_pricing',
            array(
                'title'     => esc_html__( 'Pricing', 'screenr' ),
                'priority'  => 32,
                'panel'     => 'front_page_sections',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );
    $wp_customize->add_section( new Screenr_Customize_Section_Plus( $wp_customize, 'premium_section_cta',
            array(
                'title'     => esc_html__( 'Call To Action', 'screenr' ),
                'priority'  => 32,
                'panel'     => 'front_page_sections',
                'plus_text' => esc_html__( 'Go Plus', 'screenr' ),
                'plus_url'  => screenr_get_plus_url()
            )
        )
    );

    do_action( 'screenr_customize_after_register', $wp_customize );
}
add_action( 'customize_register', 'screenr_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function screenr_customize_preview_js() {
	wp_enqueue_script( 'screenr_customizer_preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-selective-refresh' ), false, true );
}
add_action( 'customize_preview_init', 'screenr_customize_preview_js', 65 );


function screenr_customize_controls_enqueue_scripts(){
    wp_localize_script( 'customize-controls', 'C_Icon_Picker',
        apply_filters( 'c_icon_picker_js_setup',
            array(
                'search'    => esc_html__( 'Search', 'screenr' ),
                'fonts' => array(
                    'font-awesome' => array(
                        // Name of icon
                        'name' => esc_html__( 'Font Awesome', 'screenr' ),
                        // prefix class example for font-awesome fa-fa-{name}
                        'prefix' => 'fa',
                        // font url
                        'url' => get_template_directory_uri() .'/assets/css/font-awesome.min.css',
                        // Icon class name, separated by |
                        'icons' => 'fa-500px|fa-adjust|fa-adn|fa-align-center|fa-align-justify|fa-align-left|fa-align-right|fa-amazon|fa-ambulance|fa-american-sign-language-interpreting|fa-anchor|fa-android|fa-angellist|fa-angle-double-down|fa-angle-double-left|fa-angle-double-right|fa-angle-double-up|fa-angle-down|fa-angle-left|fa-angle-right|fa-angle-up|fa-apple|fa-archive|fa-area-chart|fa-arrow-circle-down|fa-arrow-circle-left|fa-arrow-circle-o-down|fa-arrow-circle-o-left|fa-arrow-circle-o-right|fa-arrow-circle-o-up|fa-arrow-circle-right|fa-arrow-circle-up|fa-arrow-down|fa-arrow-left|fa-arrow-right|fa-arrow-up|fa-arrows|fa-arrows-alt|fa-arrows-h|fa-arrows-v|fa-asl-interpreting|fa-assistive-listening-systems|fa-asterisk|fa-at|fa-audio-description|fa-automobile|fa-backward|fa-balance-scale|fa-ban|fa-bank|fa-bar-chart|fa-bar-chart-o|fa-barcode|fa-bars|fa-battery-0|fa-battery-1|fa-battery-2|fa-battery-3|fa-battery-4|fa-battery-empty|fa-battery-full|fa-battery-half|fa-battery-quarter|fa-battery-three-quarters|fa-bed|fa-beer|fa-behance|fa-behance-square|fa-bell|fa-bell-o|fa-bell-slash|fa-bell-slash-o|fa-bicycle|fa-binoculars|fa-birthday-cake|fa-bitbucket|fa-bitbucket-square|fa-bitcoin|fa-black-tie|fa-blind|fa-bluetooth|fa-bluetooth-b|fa-bold|fa-bolt|fa-bomb|fa-book|fa-bookmark|fa-bookmark-o|fa-braille|fa-briefcase|fa-btc|fa-bug|fa-building|fa-building-o|fa-bullhorn|fa-bullseye|fa-bus|fa-buysellads|fa-cab|fa-calculator|fa-calendar|fa-calendar-check-o|fa-calendar-minus-o|fa-calendar-o|fa-calendar-plus-o|fa-calendar-times-o|fa-camera|fa-camera-retro|fa-car|fa-caret-down|fa-caret-left|fa-caret-right|fa-caret-square-o-down|fa-caret-square-o-left|fa-caret-square-o-right|fa-caret-square-o-up|fa-caret-up|fa-cart-arrow-down|fa-cart-plus|fa-cc|fa-cc-amex|fa-cc-diners-club|fa-cc-discover|fa-cc-jcb|fa-cc-mastercard|fa-cc-paypal|fa-cc-stripe|fa-cc-visa|fa-certificate|fa-chain|fa-chain-broken|fa-check|fa-check-circle|fa-check-circle-o|fa-check-square|fa-check-square-o|fa-chevron-circle-down|fa-chevron-circle-left|fa-chevron-circle-right|fa-chevron-circle-up|fa-chevron-down|fa-chevron-left|fa-chevron-right|fa-chevron-up|fa-child|fa-chrome|fa-circle|fa-circle-o|fa-circle-o-notch|fa-circle-thin|fa-clipboard|fa-clock-o|fa-clone|fa-close|fa-cloud|fa-cloud-download|fa-cloud-upload|fa-cny|fa-code|fa-code-fork|fa-codepen|fa-codiepie|fa-coffee|fa-cog|fa-cogs|fa-columns|fa-comment|fa-comment-o|fa-commenting|fa-commenting-o|fa-comments|fa-comments-o|fa-compass|fa-compress|fa-connectdevelop|fa-contao|fa-copy|fa-copyright|fa-creative-commons|fa-credit-card|fa-credit-card-alt|fa-crop|fa-crosshairs|fa-css3|fa-cube|fa-cubes|fa-cut|fa-cutlery|fa-dashboard|fa-dashcube|fa-database|fa-deaf|fa-deafness|fa-dedent|fa-delicious|fa-desktop|fa-deviantart|fa-diamond|fa-digg|fa-dollar|fa-dot-circle-o|fa-download|fa-dribbble|fa-dropbox|fa-drupal|fa-edge|fa-edit|fa-eject|fa-ellipsis-h|fa-ellipsis-v|fa-empire|fa-envelope|fa-envelope-o|fa-envelope-square|fa-envira|fa-eraser|fa-eur|fa-euro|fa-exchange|fa-exclamation|fa-exclamation-circle|fa-exclamation-triangle|fa-expand|fa-expeditedssl|fa-external-link|fa-external-link-square|fa-eye|fa-eye-slash|fa-eyedropper|fa-facebook|fa-facebook-f|fa-facebook-official|fa-facebook-square|fa-fast-backward|fa-fast-forward|fa-fax|fa-feed|fa-female|fa-fighter-jet|fa-file|fa-file-archive-o|fa-file-audio-o|fa-file-code-o|fa-file-excel-o|fa-file-image-o|fa-file-movie-o|fa-file-o|fa-file-pdf-o|fa-file-photo-o|fa-file-picture-o|fa-file-powerpoint-o|fa-file-sound-o|fa-file-text|fa-file-text-o|fa-file-video-o|fa-file-word-o|fa-file-zip-o|fa-files-o|fa-film|fa-filter|fa-fire|fa-fire-extinguisher|fa-firefox|fa-first-order|fa-flag|fa-flag-checkered|fa-flag-o|fa-flash|fa-flask|fa-flickr|fa-floppy-o|fa-folder|fa-folder-o|fa-folder-open|fa-folder-open-o|fa-font|fa-fonticons|fa-fort-awesome|fa-forumbee|fa-forward|fa-foursquare|fa-frown-o|fa-futbol-o|fa-gamepad|fa-gavel|fa-gbp|fa-ge|fa-gear|fa-gears|fa-genderless|fa-get-pocket|fa-gg|fa-gg-circle|fa-gift|fa-git|fa-git-square|fa-github|fa-github-alt|fa-github-square|fa-gitlab|fa-gittip|fa-glass|fa-glide|fa-glide-g|fa-globe|fa-google|fa-google-plus|fa-google-plus-square|fa-google-wallet|fa-graduation-cap|fa-gratipay|fa-group|fa-h-square|fa-hacker-news|fa-hand-grab-o|fa-hand-lizard-o|fa-hand-o-down|fa-hand-o-left|fa-hand-o-right|fa-hand-o-up|fa-hand-paper-o|fa-hand-peace-o|fa-hand-pointer-o|fa-hand-rock-o|fa-hand-scissors-o|fa-hand-spock-o|fa-hand-stop-o|fa-hard-of-hearing|fa-hashtag|fa-hdd-o|fa-header|fa-headphones|fa-heart|fa-heart-o|fa-heartbeat|fa-history|fa-home|fa-hospital-o|fa-hotel|fa-hourglass|fa-hourglass-1|fa-hourglass-2|fa-hourglass-3|fa-hourglass-end|fa-hourglass-half|fa-hourglass-o|fa-hourglass-start|fa-houzz|fa-html5|fa-i-cursor|fa-ils|fa-image|fa-inbox|fa-indent|fa-industry|fa-info|fa-info-circle|fa-inr|fa-instagram|fa-institution|fa-internet-explorer|fa-intersex|fa-ioxhost|fa-italic|fa-joomla|fa-jpy|fa-jsfiddle|fa-key|fa-keyboard-o|fa-krw|fa-language|fa-laptop|fa-lastfm|fa-lastfm-square|fa-leaf|fa-leanpub|fa-legal|fa-lemon-o|fa-level-down|fa-level-up|fa-life-bouy|fa-life-buoy|fa-life-ring|fa-life-saver|fa-lightbulb-o|fa-line-chart|fa-link|fa-linkedin|fa-linkedin-square|fa-linux|fa-list|fa-list-alt|fa-list-ol|fa-list-ul|fa-location-arrow|fa-lock|fa-long-arrow-down|fa-long-arrow-left|fa-long-arrow-right|fa-long-arrow-up|fa-low-vision|fa-magic|fa-magnet|fa-mail-forward|fa-mail-reply|fa-mail-reply-all|fa-male|fa-map|fa-map-marker|fa-map-o|fa-map-pin|fa-map-signs|fa-mars|fa-mars-double|fa-mars-stroke|fa-mars-stroke-h|fa-mars-stroke-v|fa-maxcdn|fa-meanpath|fa-medium|fa-medkit|fa-meh-o|fa-mercury|fa-microphone|fa-microphone-slash|fa-minus|fa-minus-circle|fa-minus-square|fa-minus-square-o|fa-mixcloud|fa-mobile|fa-mobile-phone|fa-modx|fa-money|fa-moon-o|fa-mortar-board|fa-motorcycle|fa-mouse-pointer|fa-music|fa-navicon|fa-neuter|fa-newspaper-o|fa-object-group|fa-object-ungroup|fa-odnoklassniki|fa-odnoklassniki-square|fa-opencart|fa-openid|fa-opera|fa-optin-monster|fa-outdent|fa-pagelines|fa-paint-brush|fa-paper-plane|fa-paper-plane-o|fa-paperclip|fa-paragraph|fa-paste|fa-pause|fa-pause-circle|fa-pause-circle-o|fa-paw|fa-paypal|fa-pencil|fa-pencil-square|fa-pencil-square-o|fa-percent|fa-phone|fa-phone-square|fa-photo|fa-picture-o|fa-pie-chart|fa-pied-piper|fa-pied-piper-alt|fa-pied-piper-pp|fa-pinterest|fa-pinterest-p|fa-pinterest-square|fa-plane|fa-play|fa-play-circle|fa-play-circle-o|fa-plug|fa-plus|fa-plus-circle|fa-plus-square|fa-plus-square-o|fa-power-off|fa-print|fa-product-hunt|fa-puzzle-piece|fa-qq|fa-qrcode|fa-question|fa-question-circle|fa-question-circle-o|fa-quote-left|fa-quote-right|fa-ra|fa-random|fa-rebel|fa-recycle|fa-reddit|fa-reddit-alien|fa-reddit-square|fa-refresh|fa-registered|fa-remove|fa-renren|fa-reorder|fa-repeat|fa-reply|fa-reply-all|fa-resistance|fa-retweet|fa-rmb|fa-road|fa-rocket|fa-rotate-left|fa-rotate-right|fa-rouble|fa-rss|fa-rss-square|fa-rub|fa-ruble|fa-rupee|fa-safari|fa-save|fa-scissors|fa-scribd|fa-search|fa-search-minus|fa-search-plus|fa-sellsy|fa-send|fa-send-o|fa-server|fa-share|fa-share-alt|fa-share-alt-square|fa-share-square|fa-share-square-o|fa-shekel|fa-sheqel|fa-shield|fa-ship|fa-shirtsinbulk|fa-shopping-bag|fa-shopping-basket|fa-shopping-cart|fa-sign-in|fa-sign-language|fa-sign-out|fa-signal|fa-signing|fa-simplybuilt|fa-sitemap|fa-skyatlas|fa-skype|fa-slack|fa-sliders|fa-slideshare|fa-smile-o|fa-snapchat|fa-snapchat-ghost|fa-snapchat-square|fa-soccer-ball-o|fa-sort|fa-sort-alpha-asc|fa-sort-alpha-desc|fa-sort-amount-asc|fa-sort-amount-desc|fa-sort-asc|fa-sort-desc|fa-sort-down|fa-sort-numeric-asc|fa-sort-numeric-desc|fa-sort-up|fa-soundcloud|fa-space-shuttle|fa-spinner|fa-spoon|fa-spotify|fa-square|fa-square-o|fa-stack-exchange|fa-stack-overflow|fa-star|fa-star-half|fa-star-half-empty|fa-star-half-full|fa-star-half-o|fa-star-o|fa-steam|fa-steam-square|fa-step-backward|fa-step-forward|fa-stethoscope|fa-sticky-note|fa-sticky-note-o|fa-stop|fa-stop-circle|fa-stop-circle-o|fa-street-view|fa-strikethrough|fa-stumbleupon|fa-stumbleupon-circle|fa-subscript|fa-subway|fa-suitcase|fa-sun-o|fa-superscript|fa-support|fa-table|fa-tablet|fa-tachometer|fa-tag|fa-tags|fa-tasks|fa-taxi|fa-television|fa-tencent-weibo|fa-terminal|fa-text-height|fa-text-width|fa-th|fa-th-large|fa-th-list|fa-themeisle|fa-thumb-tack|fa-thumbs-down|fa-thumbs-o-down|fa-thumbs-o-up|fa-thumbs-up|fa-ticket|fa-times|fa-times-circle|fa-times-circle-o|fa-tint|fa-toggle-down|fa-toggle-left|fa-toggle-off|fa-toggle-on|fa-toggle-right|fa-toggle-up|fa-trademark|fa-train|fa-transgender|fa-transgender-alt|fa-trash|fa-trash-o|fa-tree|fa-trello|fa-tripadvisor|fa-trophy|fa-truck|fa-try|fa-tty|fa-tumblr|fa-tumblr-square|fa-turkish-lira|fa-tv|fa-twitch|fa-twitter|fa-twitter-square|fa-umbrella|fa-underline|fa-undo|fa-universal-access|fa-university|fa-unlink|fa-unlock|fa-unlock-alt|fa-unsorted|fa-upload|fa-usb|fa-usd|fa-user|fa-user-md|fa-user-plus|fa-user-secret|fa-user-times|fa-users|fa-venus|fa-venus-double|fa-venus-mars|fa-viacoin|fa-viadeo|fa-viadeo-square|fa-video-camera|fa-vimeo|fa-vimeo-square|fa-vine|fa-vk|fa-volume-control-phone|fa-volume-down|fa-volume-off|fa-volume-up|fa-warning|fa-wechat|fa-weibo|fa-weixin|fa-whatsapp|fa-wheelchair|fa-wheelchair-alt|fa-wifi|fa-wikipedia-w|fa-windows|fa-won|fa-wordpress|fa-wpbeginner|fa-wpforms|fa-wrench|fa-xing|fa-xing-square|fa-y-combinator|fa-y-combinator-square|fa-yahoo|fa-yc|fa-yc-square|fa-yelp|fa-yen|fa-yoast|fa-youtube|fa-youtube-play|fa-youtube-square',
                    ),
                )

            )
        )
    );
}

add_action( 'customize_controls_enqueue_scripts', 'screenr_customize_controls_enqueue_scripts' );

/*------------------------------------------------------------------------*/
/*  Screenr Sanitize Functions.
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
    return absint( $input );
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
    return screenr_sanitize_text( $string );
}

/**
 * Sanitize CSS code
 *
 * @param $string
 * @return string
 */
function screenr_sanitize_css( $string ) {
    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
    $string = strip_tags($string);
    return trim( $string );
}


if ( ! function_exists( 'screenr_sanitize_checkbox' ) ) {
    function screenr_sanitize_checkbox( $input ) {
        if ( $input == 1 ) {
            return 1;
        } else {
            return 0;
        }
    }
}


function screenr_sanitize_color_alpha( $color ){
    $color = str_replace( '#', '', $color );
    if ( '' === $color ){
        return '';
    }

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', '#' . $color ) ) {
        // convert to rgb
        $colour = $color;
        if ( strlen( $colour ) == 6 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
            list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
            return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return 'rgba('.join( ',', array( 'r' => $r, 'g' => $g, 'b' => $b, 'a' => 1 ) ).')';

    }

    return strpos( trim( $color ), 'rgb' ) !== false ?  $color : false;
}



function screenr_showon_frontpage() {
    return is_page_template( 'template-frontpage.php' );
}

require get_template_directory() . '/inc/customizer-selective-refresh.php';

add_action( 'customize_controls_enqueue_scripts', 'screenr_customize_js_settings' );

function screenr_customize_js_settings(){
    $n = 0;
    if ( function_exists( 'screenr_get_actions_required' ) ) {
        $actions = screenr_get_actions_required();
        $n = array_count_values( $actions );
    }

    $number_action =  0;
    if ( $n && isset( $n['active'] ) ) {
        $number_action = $n['active'];
    }

    wp_localize_script( 'customize-controls', 'screenr_customizer_settings', array(
        'number_action' => $number_action,
        'action_url' => add_query_arg( array( 'page' => 'ft_screenr', 'tab' => 'actions_required' ), admin_url( 'themes.php' ) )
    ) );
}
