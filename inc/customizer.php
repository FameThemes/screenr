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

    // Retina Logo
    $wp_customize->add_setting( 'retina_logo',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'			=> 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'retina_logo',
            array(
                'label'       => esc_html__('Retina Logo', 'screenr'),
                'section'     => 'title_tagline',
            )
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


	/* Section Navigation
	   ----------------------------------------------------------------------*/
	$wp_customize->add_section( 'sections_navigation' ,
		array(
			'priority'    => 7,
			'title'       => esc_html__( 'Sections Navigation', 'screenr' ),
			'description' => '',
			'panel'       => 'screenr_options',
		)
	);

	Screenr_Dots_Navigation::get_instance()->add_customize( $wp_customize, 'sections_navigation' );


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
                'no'  => esc_html__('No Sidebar', 'screenr'),
            ),
            'description' => esc_html__('Select your site layout', 'screenr'),
        )
    );

    if ( class_exists( 'WooCommerce' ) ) {
        // Shop layout

        $wp_customize->add_section( 'shop_layout_settings' ,
            array(
                'priority'    => 6,
                'title'       => esc_html__( 'Shop Layout Settings', 'screenr' ),
                'description' => '',
                'panel'       => 'screenr_options',
            )
        );

        $wp_customize->add_setting( 'shop_layout_settings',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'no',
            )
        );
        $wp_customize->add_control( 'shop_layout_settings',
            array(
                'label'       => esc_html__('Shop Layout', 'screenr'),
                'section'     => 'shop_layout_settings',
                'type' => 'select',
                'choices' => array(
                    'no' => esc_html__('No Sidebar', 'screenr'),
                    'right' => esc_html__('Right Sidebar', 'screenr'),
                    'left'  => esc_html__('Left Sidebar', 'screenr'),
                ),
                'description' => esc_html__('Select your shop layout', 'screenr'),
            )
        );

        $wp_customize->add_setting( 'shop_number_product',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 20,
            )
        );
        $wp_customize->add_control( 'shop_number_product',
            array(
                'label'       => esc_html__('Number of products to display', 'screenr'),
                'section'     => 'shop_layout_settings',
            )
        );

    }


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
                'description' => esc_html__('Enter numbers with a total maximum value of 12, separated by "+"', 'screenr'),
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
                'description' => esc_html__('Enter numbers with a total maximum value of 12, separated by "+"', 'screenr'),
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
                'description' => esc_html__('Enter numbers with a total maximum value of 12, separated by "+"', 'screenr'),
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
    	
    	
    
    /* Google settings
    ----------------------------------------------------------------------*/
    /**
	 * @since  1.2.5
	 */
    $wp_customize->add_section( 'google_font_section' ,
		array(
			'priority'    => 200,
			'title'       => esc_html__( 'Google Fonts', 'screenr' ),
			'description' => '',
			'panel'       => 'screenr_options',
		)
	);

	$wp_customize->register_control_type('Screenr\GoogleFonts\Downloader\Customize_Control');
		// OnePress_Misc_Control
		$wp_customize->add_setting(
			'google_font_settings',
			array(
				'sanitize_callback' => 'screenr_sanitize_text',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Screenr\GoogleFonts\Downloader\Customize_Control(
				$wp_customize,
				'google_font_settings',
				array(
					'label'        => esc_html__('Google Fonts', 'screenr'),
					'section'      => 'google_font_section',
					'priority'    => 19,
				)
			)
		);
		
	

    /* Theme styling
    ----------------------------------------------------------------------*/
    if ( ! function_exists( 'wp_get_custom_css' ) ) {  // Back-compat for WordPress < 4.7.

        $wp_customize->add_section('custom_css',
            array(
                'priority' => 100,
                'title' => esc_html__('Custom CSS', 'screenr'),
                'description' => '',
                'panel' => 'screenr_options',
                'capability' => 'edit_theme_options',
            )
        );

        $wp_customize->add_setting('screenr_custom_css',
            array(
                'sanitize_callback' => 'screenr_sanitize_css',
                'default' => '',
                'type' => 'option',
                'transport' => 'postMessage',
            ));
        $wp_customize->add_control(
            'screenr_custom_css',
            array(
                'label' => esc_html__('Custom CSS', 'screenr'),
                'section' => 'custom_css',
                'description' => '',
                'type' => 'textarea',
            )
        );
    } else {
        $wp_customize->get_section( 'custom_css' )->priority = 994;
    }

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
                    'description' => sprintf( esc_html__('Check out the %s version for full control over the frontpage SECTIONS ORDER!', 'screenr'), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ),
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
                    'title'       => esc_html__('Advanced Section Styling', 'screenr'),
                    'section'     => 'front_page_sections_order_styling',
                    'description' => sprintf( esc_html__('Check out the %1$s version for full control over the section styling which includes background color, image, video, parallax effect, custom style and more ...', 'screenr'), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ),
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
            'default'           => esc_html__( 'We provide creative solutions that gets the attention of our global clients.', 'screenr' ),
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
    /*  Section: Gallery
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section( 'section_gallery' ,
        array(
            'priority'        => 10,
            'title'           => esc_html__( 'Gallery', 'screenr' ),
            'description'     => '',
            'panel'           => 'front_page_sections',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'gallery_setting_group_heading',
            array(
                'type' 			=> 'group_heading_top',
                'title'			=> esc_html__( 'Section Settings', 'screenr' ),
                'section' 		=> 'section_gallery'
            )
        )
    );

    // Show Content
    $wp_customize->add_setting( 'gallery_disable',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control( 'gallery_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'screenr'),
            'section'     => 'section_gallery',
            'description' => esc_html__('Check this box to hide this section.', 'screenr'),
        )
    );

    // Section ID
    $wp_customize->add_setting( 'gallery_id',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => esc_html__('gallery', 'screenr'),
        )
    );
    $wp_customize->add_control( 'gallery_id',
        array(
            'label'     => esc_html__('Section ID:', 'screenr'),
            'section' 		=> 'section_gallery',
            'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'screenr' )
        )
    );

    // Title
    $wp_customize->add_setting( 'gallery_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Gallery', 'screenr'),
        )
    );
    $wp_customize->add_control( 'gallery_title',
        array(
            'label'     => esc_html__('Section Title', 'screenr'),
            'section' 		=> 'section_gallery',
            'description'   => '',
        )
    );

    // Sub Title
    $wp_customize->add_setting( 'gallery_subtitle',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'gallery_subtitle',
        array(
            'label'     => esc_html__('Section Subtitle', 'screenr'),
            'section' 		=> 'section_gallery',
            'description'   => '',
        )
    );

    // Description
    $wp_customize->add_setting( 'gallery_desc',
        array(
            'sanitize_callback' => 'screenr_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control(
        'gallery_desc',
        array(
            'label' 		=> esc_html__('Section Description', 'screenr'),
            'section' 		=> 'section_gallery',
            'description'   => '',
            'type'          => 'textarea',
        )
    );

    // Group Heading
    $wp_customize->add_control( new Screenr_Group_Settings_Heading_Control( $wp_customize, 'gallery_content_group_heading',
            array(
                'type' 			=> 'group_heading',
                'title'			=> esc_html__( 'Section Content', 'screenr' ),
                'section' 		=> 'section_gallery',
                'priority'      => 30,
            )
        )
    );
    // Gallery Source
    $wp_customize->add_setting( 'gallery_source',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'validate_callback' => 'screenr_gallery_source_validate',
            'default'           => 'page',
        )
    );
    $wp_customize->add_control( 'gallery_source',
        array(
            'label'     	=> esc_html__('Select Gallery Source', 'screenr'),
            'section' 		=> 'section_gallery',
            'type'          => 'select',
            'priority'      => 35,
            'choices'       => array(
                'page'      => esc_html__('Page', 'screenr'),
                'facebook'  => 'Facebook',
                'instagram' => 'Instagram',
                'flickr'    => 'Flickr',
            )
        )
    );

    // Source page settings
    $wp_customize->add_setting( 'gallery_source_page',
        array(
            'sanitize_callback' => 'screenr_sanitize_number',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'gallery_source_page',
        array(
            'label'     	=> esc_html__('Select Gallery Page', 'screenr'),
            'section' 		=> 'section_gallery',
            'type'          => 'select',
            'priority'      => 60,
            'choices'       => $option_pages,
            'description'   => esc_html__('Select a page which have content contain [gallery] shortcode.', 'screenr'),
        )
    );


    // Gallery Layout
    $wp_customize->add_setting( 'gallery_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'default',
        )
    );
    $wp_customize->add_control( 'gallery_layout',
        array(
            'label'     	=> esc_html__('Layout', 'screenr'),
            'section' 		=> 'section_gallery',
            'type'          => 'select',
            'priority'      => 65,
            'choices'       => array(
                'default'      => esc_html__('Default, inside container', 'screenr'),
                'full-width'  => esc_html__('Full Width', 'screenr'),
            )
        )
    );

    // Gallery Display
    $wp_customize->add_setting( 'gallery_display',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'default',
        )
    );
    $wp_customize->add_control( 'gallery_display',
        array(
            'label'     	=> esc_html__('Display', 'screenr'),
            'section' 		=> 'section_gallery',
            'type'          => 'select',
            'priority'      => 70,
            'choices'       => array(
                'grid'      => esc_html__('Grid', 'screenr'),
                'carousel'    => esc_html__('Carousel', 'screenr'),
                'slider'      => esc_html__('Slider', 'screenr'),
                'justified'   => esc_html__('Justified', 'screenr'),
                'masonry'     => esc_html__('Masonry', 'screenr'),
            )
        )
    );

    // Gallery grid spacing
    $wp_customize->add_setting( 'gallery_spacing',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 20,
        )
    );
    $wp_customize->add_control( 'gallery_spacing',
        array(
            'label'     	=> esc_html__('Item Spacing', 'screenr'),
            'section' 		=> 'section_gallery',
            'priority'      => 75,

        )
    );

    // Gallery grid spacing
    $wp_customize->add_setting( 'gallery_row_height',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 120,
        )
    );
    $wp_customize->add_control( 'gallery_row_height',
        array(
            'label'     	=> esc_html__('Row Height', 'screenr'),
            'section' 		=> 'section_gallery',
            'priority'      => 80,

        )
    );

    // Gallery grid gird col
    $wp_customize->add_setting( 'gallery_col',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '4',
        )
    );
    $wp_customize->add_control( 'gallery_col',
        array(
            'label'     	=> esc_html__('Layout columns', 'screenr'),
            'section' 		=> 'section_gallery',
            'priority'      => 85,
            'type'          => 'select',
            'choices'       => array(
                '1'      => 1,
                '2'      => 2,
                '3'      => 3,
                '4'      => 4,
                '5'      => 5,
                '6'      => 6,
            )

        )
    );

    // Gallery max number
    $wp_customize->add_setting( 'gallery_number',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 10,
        )
    );
    $wp_customize->add_control( 'gallery_number',
        array(
            'label'     	=> esc_html__('Number items', 'screenr'),
            'section' 		=> 'section_gallery',
            'priority'      => 90,
        )
    );
    // Gallery grid spacing
    $wp_customize->add_setting( 'gallery_lightbox',
        array(
            'sanitize_callback' => 'screenr_sanitize_checkbox',
            'default'           => 1,
        )
    );
    $wp_customize->add_control( 'gallery_lightbox',
        array(
            'label'     	=> esc_html__('Enable Lightbox', 'screenr'),
            'section' 		=> 'section_gallery',
            'priority'      => 95,
            'type'          => 'checkbox',
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
            'default'           => esc_html__('We have been featured on', 'screenr'),
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

	$wp_customize->add_setting( 'screenr_clients_target',
		array(
			'sanitize_callback' => 'screenr_sanitize_checkbox',
			'default'           => null,
		)
	);
	$wp_customize->add_control( 'screenr_clients_target',
		array(
			'label' 		=> __('Open Link In New Window', 'screenr'),
			'section' 		=> 'section_clients',
			'type'          => 'checkbox',
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

	$wp_customize->add_setting( 'news_cat',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 0,
		)
	);

	$wp_customize->add_control( new Screenr_Category_Control(
		$wp_customize,
		'news_cat',
		array(
			'label'       => esc_html__( 'Category to show', 'screenr' ),
			'section'     => 'section_news',
			'description' => '',
		)
	) );

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
    $icons_v6 = include(dirname(__FILE__) . '/list-icon-v6.php');
	wp_localize_script(
		'customize-controls',
		'C_Icon_Picker',
		apply_filters(
			'c_icon_picker_js_setup',
			array(
				'search'    => esc_html__('Search', 'screenr'),
				'fonts' => array(
					'font-awesome' => array(
						// Name of icon
						'name' => esc_html__('Font Awesome', 'screenr'),
						// prefix class example for font-awesome fa-fa-{name}
						'prefix' => '',
						// font url
						'url' => [
							[
								'key' => 'onepress-fa',
								'url' => esc_url(add_query_arg(array('ver' => '6.5.1'), get_template_directory_uri() . '/assets/fontawesome-v6/css/all.min.css')),
							],
							[
								'key' => 'onepress-fa-shims',
								'url' => esc_url(add_query_arg(array('ver' => '6.5.1'), get_template_directory_uri() . '/assets/fontawesome-v6/css/v4-shims.min.css')),
							],
						],
						// Icon class name, separated by |
						'icons' => $icons_v6,
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


function screenr_gallery_source_validate( $validity, $value ){
    if ( ! class_exists( 'Screenr_PLus' ) ) {
        if ( $value != 'page' ) {
            $validity->add('notice', sprintf( esc_html__('Upgrade to %1s to unlock this feature.', 'screenr' ), '<a target="_blank" href="'. screenr_get_plus_url() .'">Screenr Plus</a>' ) );
        }
    }
    return $validity;
}


function screenr_showon_frontpage() {
    return is_page_template( 'template-frontpage.php' ) || is_front_page();
}

require get_template_directory() . '/inc/customizer-selective-refresh.php';

add_action( 'customize_controls_enqueue_scripts', 'screenr_customize_js_settings' );

function screenr_customize_js_settings(){
    $number_action =  0;
    if ( function_exists( 'screenr_get_actions_required' ) ) {
        $actions = screenr_get_actions_required();
        $number_action = $actions['number_notice'];
    }
    
    wp_localize_script( 'customize-controls', 'screenr_customizer_settings', array(
        'number_action' => $number_action,
        'is_plus' => defined( 'SCREENR_PLUS_PATH' ) && SCREENR_PLUS_PATH ? true : false,
        'action_url' => add_query_arg( array( 'page' => 'ft_screenr', 'tab' => 'actions_required' ), admin_url( 'themes.php' ) )
    ) );
}
