<?php
/**
 * Screenr functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Screenr
 */

if ( ! function_exists( 'screenr_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function screenr_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Screenr, use a find and replace
		 * to change 'screenr' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'screenr', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_post_type_support( 'page', 'excerpt' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'screenr-blog-grid-small', 350, 200, true );
		add_image_size( 'screenr-blog-grid', 540, 300, true );
		add_image_size( 'screenr-blog-list', 790, 400, true );
		add_image_size( 'screenr-service-small', 538, 280, true );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
			// 'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'screenr' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'screenr_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support(
			'custom-header',
			array(
				'default-image'          => get_template_directory_uri() . '/assets/images/header-default.jpg',
				'width'                  => 1600,
				'height'                 => 800,
				'flex-height'            => false,
				'flex-width'             => false,
				'uploads'                => true,
				'random-default'         => false,
				'header-text'            => false,
				'default-text-color'     => '',
				'wp-head-callback'       => '',
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
			)
		);

		// Recommend plugins.
		add_theme_support(
			'recommend-plugins',
			array(
				'contact-form-7' => array(
					'name' => esc_html__( 'Contact Form 7', 'screenr' ),
					'active_filename' => 'contact-form-7/wp-contact-form-7.php',
				),
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * WooCommerce support.
		 */
		add_theme_support( 'woocommerce' );
		// Add support for WooCommerce.
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		/**
		 * Add support for Gutenberg.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/reference/theme-support/
		 */
		add_theme_support( 'editor-styles' );
		add_theme_support( 'align-wide' );
	}
endif;
add_action( 'after_setup_theme', 'screenr_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function screenr_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'screenr_content_width', 790 );
}
add_action( 'after_setup_theme', 'screenr_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function screenr_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'screenr' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop', 'screenr' ),
				'id'            => 'sidebar-shop',
				'description'   => '',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'screenr' ),
			'id'            => 'footer-1',
			'description'   => screenr_sidebar_desc( 'footer-1' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'screenr' ),
			'id'            => 'footer-2',
			'description'   => screenr_sidebar_desc( 'footer-2' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'screenr' ),
			'id'            => 'footer-3',
			'description'   => screenr_sidebar_desc( 'footer-3' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'screenr' ),
			'id'            => 'footer-4',
			'description'   => screenr_sidebar_desc( 'footer-4' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

}
add_action( 'widgets_init', 'screenr_widgets_init' );

/**
 * Add Google Fonts, editor styles to WYSIWYG editor
 */
function screenr_editor_styles() {
	add_editor_style( array( 'assets/css/editor-style.css', screenr_fonts_url() ) );
}
add_action( 'after_setup_theme', 'screenr_editor_styles' );

/**
 * Enqueue scripts and styles.
 */
function screenr_scripts() {
	$theme = wp_get_theme();
	$version = $theme->get( 'Version' );

	wp_enqueue_style( 'screenr-fonts', screenr_fonts_url(), array(), null );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', false, '4.0.0' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', false, '4.0.0' );
	wp_enqueue_style( 'screenr-style', get_template_directory_uri() . '/style.css' );

	wp_enqueue_script( 'screenr-plugin', get_template_directory_uri() . '/assets/js/plugins.js', array( 'jquery' ), '4.0.0', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '4.0.0', true );

	$screenr_js = array(
		'ajax_url'           => admin_url( 'admin-ajax.php' ),
		'full_screen_slider' => ( get_theme_mod( 'slider_fullscreen' ) ) ? true : false,
		'header_layout'      => get_theme_mod( 'header_layout' ),
		'slider_parallax'    => ( get_theme_mod( 'slider_parallax', 1 ) == 1 ) ? 1 : 0,
		'is_home_front_page' => ( is_page_template( 'template-frontpage.php' ) && is_front_page() ) ? 1 : 0,
		'autoplay'           => 7000,
		'speed'              => 700,
		'effect'             => 'slide',
		'gallery_enable'     => '',
	);

	// Load gallery scripts
	$galley_disable  = get_theme_mod( 'gallery_disable' ) == 1 ? true : false;
	if ( ! $galley_disable || is_customize_preview() ) {
		$screenr_js['gallery_enable'] = 1;
		$display = get_theme_mod( 'gallery_display', 'grid' );
		if ( ! is_customize_preview() ) {
			switch ( $display ) {
				case 'masonry':
					wp_enqueue_script( 'screenr-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true );
					break;
				case 'justified':
					wp_enqueue_script( 'screenr-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true );
					break;
				case 'slider':
				case 'carousel':
					wp_enqueue_script( 'screenr-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true );
					break;
				default:
					break;
			}
		} else {
			wp_enqueue_script( 'screenr-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true );
			wp_enqueue_script( 'screenr-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true );
			wp_enqueue_script( 'screenr-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true );
		}
	}

	wp_enqueue_style( 'screenr-gallery-lightgallery', get_template_directory_uri() . '/assets/css/lightgallery.css' );

	wp_enqueue_script( 'screenr-theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'screenr-theme', 'Screenr', apply_filters( 'screenr_localize_script', $screenr_js ) );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'screenr-woocommerce', get_template_directory_uri() . '/woocommerce.css' );
	}

}
add_action( 'wp_enqueue_scripts', 'screenr_scripts' );

if ( ! function_exists( 'screenr_fonts_url' ) ) :
	/**
	 * Register default Google fonts
	 */
	function screenr_fonts_url() {
		$fonts_url = '';

		/*
		  Translators: If there are characters in your language that are not
		* supported by Open Sans, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$open_sans = _x( 'on', 'Open Sans font: on or off', 'screenr' );

		/*
		 Translators: If there are characters in your language that are not
		* supported by Montserrat, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$montserrat = _x( 'on', 'Montserrat font: on or off', 'screenr' );

		if ( 'off' !== $montserrat || 'off' !== $open_sans ) {
			$font_families = array();

			if ( 'off' !== $open_sans ) {
				$font_families[] = 'Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic';
			}

			if ( 'off' !== $montserrat ) {
				$font_families[] = 'Montserrat:400,700';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Slider
 */
require get_template_directory() . '/inc/class-slider.php';

/**
 * Section navigation
 *
 * @since 1.1.9
 */
require get_template_directory() . '/inc/class-sections-navigation.php';

if ( class_exists( 'WooCommerce' ) ) {
	/**
	 * Woocommerce
	 */
	require get_template_directory() . '/inc/wc.php';

}

/**
 * Add theme info page
 */
require get_template_directory() . '/inc/dashboard.php';

/**
 * Add admin editor style
 */
require get_template_directory() . '/inc/admin/class-editor.php';

// require_once( trailingslashit( get_template_directory() ) . 'trt-customizer-pro/example-1/class-customize.php' );
