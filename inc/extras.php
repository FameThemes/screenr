<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Screenr
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function screenr_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'screenr_body_classes' );


/**
 * Add custom css from theme options
 *
 */
function screenr_custom_style(){
    $css = '';
    ob_start();
    ?>
    <style type="text/css">
    <?php
     /**
         * Header background
         */
    $header_bg_color =  get_theme_mod( 'screenr_header_bg_color' );
    if ( $header_bg_color ) {
    ?>
    .site-header, .is-fixed .site-header.header-fixed.transparent {
        background: #<?php echo $header_bg_color; ?>;
        border-bottom: 0px none;
    }

    <?php
} // END $header_bg_color

/**
 * Menu color
 */
$menu_color =  get_theme_mod( 'screenr_menu_color' );
if ( $menu_color ) {
    ?>
    .nav-menu > li > a,
    .no-scroll .sticky-header.transparent .nav-menu > li > a {
        color: #<?php echo $menu_color; ?>;
    }
    <?php
} // END $menu_color

/**
 * Menu hover color
 */
$menu_hover_color =  get_theme_mod( 'screenr_menu_hover_color' );
if ( $menu_hover_color ) {
    ?>
    .nav-menu > li > a:hover,
    .nav-menu > li.current-menu-item > a,
    .no-scroll .sticky-header.transparent .nav-menu > li.current-menu-item > a{
        color: #<?php echo $menu_hover_color; ?>;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
    }
    <?php
    } // END $menu_hover_color

    /**
     * Menu hover background color
     */
    $menu_hover_bg =  get_theme_mod( 'screenr_menu_hover_bg_color' );
    if ( $menu_hover_bg ) {
        ?>
    @media screen and (min-width: 1140px) {
        .nav-menu > li:last-child > a {
            padding-right: 17px;
        }
        .nav-menu > li > a:hover,
        .nav-menu > li.nav-current-item > a
        {
            background: #<?php echo $menu_hover_bg; ?>;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
        }
    }
    <?php
    } // END $menu_hover_bg

    /**
     * Reponsive Mobie button color
     */
    $menu_button_color =  get_theme_mod( 'screenr_menu_toggle_button_color' );
    if ( $menu_button_color ) {
        ?>
    #nav-toggle span,
    #nav-toggle span::before,
    #nav-toggle span::after,
    #nav-toggle.nav-is-visible span::before,
    #nav-toggle.nav-is-visible span::after,

    .no-scroll .sticky-header.transparent #nav-toggle span,
    .no-scroll .sticky-header.transparent #nav-toggle span::before,
    .no-scroll .sticky-header.transparent #nav-toggle span::after,
    .no-scroll .sticky-header.transparent #nav-toggle.nav-is-visible span::before,
    .no-scroll .sticky-header.transparent #nav-toggle.nav-is-visible span::after
    {
        background: #<?php echo $menu_button_color; ?>;
    }
    <?php
    }

    /**
     * Site Title
     */
    $screenr_logo_text_color =  get_theme_mod( 'screenr_logo_text_color' );
    if ( $screenr_logo_text_color ) {
        ?>
    .site-branding .site-title, .site-branding .site-text-logo, .site-branding .site-title a, .site-branding .site-text-logo a,
    .no-scroll .transparent .site-branding .site-title a{
        color: #<?php echo $screenr_logo_text_color; ?>;
    }
    <?php
    }
?>
    </style>
    <?php
    $css =  ob_get_clean();
    $css = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $css ) );
    wp_add_inline_style( 'screenr-style', $css );
}

add_action( 'wp_enqueue_scripts', 'screenr_custom_style', 100 );
