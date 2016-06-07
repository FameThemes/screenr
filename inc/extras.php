<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Screenr
 */

/**
 * Get media from a variable
 *
 * @param array $media
 * @return false|string
 */
if ( ! function_exists( 'screenr_get_media_url' ) ) {
    function screenr_get_media_url($media = array())
    {
        $media = wp_parse_args($media, array('url' => '', 'id' => ''));
        $url = '';
        if ($media['id'] != '') {
            $url = wp_get_attachment_url($media['id']);
        }
        if ($url == '' && $media['url'] != '') {
            $url = $media['url'];
        }
        return $url;
    }
}
if ( ! function_exists( 'rgb2hex' ) ) {
    function rgb2hex( $rgb )
    {
        return '#' . sprintf('%02x', $rgb['r']) . sprintf('%02x', $rgb['g']) . sprintf('%02x', $rgb['b']);
    }
}

function color_alpha_parse( $color_alpha ){
    $s = str_replace( array( 'rgba', '(', ')', ';' ), '', $color_alpha );
    $arr = explode(',', $s );
    $r = false;
    if ( count( $arr ) > 2 ) {
        $r = array(
            'color' => array(
                'r' => $arr[0],
                'g' => $arr[1],
                'b' => $arr[2],
            ),
            'alpha' => 1,
        );

        if ( count( $arr ) > 3 ) {
            $r['opacity'] = floatval( $arr[3] );
        }
    }

    return $r;
}


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

    if ( get_theme_mod( 'screenr_hide_sitetitle' ) ) {
        $classes[] = 'no-site-title';
    } else {
        $classes[] = 'has-site-title';
    }

    if ( get_theme_mod( 'screenr_hide_tagline' ) ) {
        $classes[] = 'no-site-tagline';
    } else {
        $classes[] = 'has-site-tagline';
    }


    if ( get_option( 'header_layout' ) != 'default' ) {
        $classes[] = 'header-layout-fixed';
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
    $header_bg_color =  get_theme_mod( 'header_bg_color' );
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
$menu_color =  get_theme_mod( 'menu_color' );
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
$menu_hover_color =  get_theme_mod( 'menu_hover_color' );
if ( $menu_hover_color ) {
    ?>
    .nav-menu > li > a:hover,
    .nav-menu > li.current-menu-item > a,
    .no-scroll .sticky-header.transparent .nav-menu > li.current-menu-item > a,
    .sticky-header.transparent .nav-menu > li.current-menu-item > a {
        color: #<?php echo $menu_hover_color; ?>;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
    }
    .sticky-header.transparent .nav-menu > li:hover > a::after, .sticky-header.transparent .nav-menu > li.current-menu-item > a::after {
        border-bottom-color: #<?php echo $menu_hover_color; ?>;
    }
    <?php
    } // END $menu_hover_color

    /**
     * Menu hover background color
     */
    $menu_hover_bg =  get_theme_mod( 'menu_hover_bg_color' );
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
    $menu_button_color =  get_theme_mod( 'menu_toggle_button_color' );
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
    $logo_text_color =  get_theme_mod( 'logo_text_color' );
    if ( $logo_text_color ) {
    ?>
    .site-branding .site-title, .site-branding .site-text-logo, .site-branding .site-title a, .site-branding .site-text-logo a,
    .no-scroll .transparent .site-branding .site-title a{
        color: #<?php echo $logo_text_color; ?>;
    }
    <?php
    }

    $slider_overlay_color =  get_theme_mod( 'slider_overlay_color' );
    $c =  color_alpha_parse( $slider_overlay_color );
    if ( $slider_overlay_color && $c ) {
    ?>
    .swiper-slider .swiper-slide .overlay {
        background-color: <?php echo rgb2hex( $c['color'] ); ?>;
        opacity: <?php echo $c['opacity']; ?>;
    }
    <?php
    }

    $v_overlay = get_theme_mod( 'videolightbox_overlay' );
    if ( $v_overlay ){
    ?>
    .parallax-window.parallax-videolightbox .parallax-mirror::before{
        background-color: <?php echo $v_overlay; ?>;
    }
    <?php
    }

    // Page header
    $page_header_bg_overlay =  get_theme_mod( 'page_header_bg_overlay' );
    $bg_cover = get_theme_mod( 'page_header_bg_color', 'e86240' );
    $c =  color_alpha_parse( $page_header_bg_overlay );
    if ( $c ){
    ?>
    #page-header-cover.swiper-slider .swiper-slide .overlay {
        background-color: <?php echo rgb2hex( $c['color'] ); ?>;
        opacity: <?php echo $c['opacity']; ?>;
    }
    <?php
    }
    ?>
    #page-header-cover.swiper-slider.no-image .swiper-slide .overlay {
        background-color: #<?php echo $bg_cover; ?>;
        opacity: 1;
    }
    <?php
    $footer_w_bg = get_theme_mod( 'footer_widgets_bg' );
    if ( $footer_w_bg ) {
    ?>
    .footer-widgets {
        background-color: #<?php echo $footer_w_bg; ?>;
    }
    <?php } ?>

    <?php
    $footer_w_color = get_theme_mod( 'footer_widgets_color' );
    if ( $footer_w_color ) {
    ?>
    .footer-widgets, .footer-widgets a, .footer-widgets .sidebar .widget a, .footer-widgets caption,
    .footer-widgets .widget-title {
        color: #<?php echo $footer_w_color; ?>;
    }
    <?php } ?>

    <?php
    $footer_c_bg = get_theme_mod( 'footer_copyright_bg' );
    if ( $footer_c_bg ) {
    ?>
    .site-footer .site-info {
        background-color: #<?php echo $footer_c_bg; ?>;
    }
    <?php } ?>

    <?php
    $footer_c_color = get_theme_mod( 'footer_copyright_color' );
    if ( $footer_c_color ) {
    ?>
    .site-footer .site-info, .site-footer .site-info a {
        color: #<?php echo $footer_c_color; ?>;
    }
    <?php } ?>

    <?php
    ?>
    </style>
    <?php
    $css =  ob_get_clean();
    $css = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $css ) );
    wp_add_inline_style( 'screenr-style', $css );
}

add_action( 'wp_enqueue_scripts', 'screenr_custom_style', 30 );


/**
 * Setup page header cover
 *
 * @return bool
 */
function screenr_page_header_cover()
{

    if ( is_page_template( 'template-frontpage.php' ) ) {
        return false;
    }

    $image = $title = $desc = '';
    if ( is_singular() ) {
        if ( is_single() ) {
            $title = get_theme_mod( 'page_blog_title', esc_html__( 'The Blog', 'screenr' ) );
        } else {
            $title = get_the_title();
        }
    } elseif ( is_category() || is_tag() ||  is_tax() ) {
        $title = single_cat_title( '', false );
        $desc  = term_description();
    } elseif ( is_search() ) {
        $title = sprintf( esc_html__( 'Search Results for: %s', 'screenr' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
    } elseif (is_day()) {
        $title = sprintf( esc_html__( 'Daily Archives: %s', 'screenr' ), get_the_date() );

    } elseif ( is_month() ) {
        $title = sprintf( esc_html__( 'Monthly Archives: %s', 'screenr' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'screenr') ) );
    } elseif ( is_year() ) {
        $title = printf( esc_html__( 'Yearly Archives: %s', 'screenr' ), get_the_date( _x( 'Y', 'yearly archives date format', 'screenr') ) );
    } elseif ( is_home() || is_front_page() ) {
        $title = get_theme_mod( 'page_blog_title', esc_html__( 'The Blog', 'screenr' ) );
    } elseif ( is_author() ) {
        if ( have_posts() ) {
            /*
             * Queue the first post, that way we know what author
             * we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop properly
             * with a call to rewind_posts().
             */
            the_post();
            $title = sprintf( esc_html__('Author Archives: %s', 'screenr' ), get_the_author() );

            /*
             * Since we called the_post() above, we need to rewind
             * the loop back to the beginning that way we can run
             * the loop properly, in full.
             */
            rewind_posts();
        }
    }  else {
        $title = esc_html__( 'Archives', 'screenr' );
    }

    if ( ! $image ) {
        $image = get_theme_mod( 'page_header_bg_image' );
    }

   // $is_parallax  = get_theme_mod( 'page_header_parallax' ) == 1 ? true : false;
    $is_parallax  = true;
    $item = array(
        'position'  => 'center',
        'pd_top'    => get_theme_mod( 'page_header_pdtop') == '' ? 13 : get_theme_mod( 'page_header_pdtop'),
        'pd_bottom' => get_theme_mod( 'page_header_pdbottom' ) == '' ? 13 : get_theme_mod( 'page_header_pdbottom' ) ,
        'title'     => $title,
        'desc'      => $desc,
    );

    $classes = array(
        'section-slider',
        'swiper-slider',
    );

    if ( $is_parallax ) {
        $classes[] = 'fixed';
    }

    if ( $image ) {
        $classes[] = 'has-image';
    } else {
        $classes[] = 'no-image';
    }

    $item = apply_filters( 'screenr_page_header_item', $item );
    $classes = apply_filters( 'screenr_page_header_cover_class', $classes );

    ?>
    <section id="page-header-cover" class="<?php echo esc_attr(  join( ' ', $classes ) ); ?>" >
        <div class="swiper-container" data-autoplay="0">
            <div class="swiper-wrapper">
                <?php
                $style = "";
                if ( $image ) {
                    $style = ' style="background-image: url(\''.esc_url( $image ).'\');" ';
                }

                $html = '<div class="swiper-slide slide-align-'.esc_attr( $item['position'] ).'"'.$style.'>';

                $style  = '';
                if  ( $item['pd_top'] != '' ) {
                    $style .='padding-top: '.floatval( $item['pd_top'] ).'%; ';
                }
                if  ( $item['pd_bottom'] != '' ) {
                    $style .='padding-bottom: '.floatval( $item['pd_bottom'] ).'%; ';
                }
                if ( $style != '' ) {
                    $style = ' style="'.$style.'" ';
                }
                $html .= '<div class="swiper-slide-intro">';
                $html .= '<div class="swiper-intro-inner"'.$style.'>';
                if ( $item['title'] ) {
                    $html .= '<h2 class="swiper-slide-heading">'.wp_kses_post( $item['title'] ).'</h2>';
                }
                if ( $item['desc'] ) {
                    $html .= '<div class="swiper-slide-desc">'.apply_filters( 'the_content', $item['desc'] ).'</div>';
                }

                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="overlay"></div>';
                $html .= '</div>';

                echo $html;
                ?>
            </div>
        </div>
    </section>
    <?php
}

add_action( 'after_site_header', 'screenr_page_header_cover' );



if ( ! function_exists( 'screenr_admin_scripts' ) ) {
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function screenr_admin_scripts($hook)
    {
        if ($hook === 'widgets.php' || $hook === 'appearance_page_ft_screenr') {
            wp_enqueue_style('screenr-admin-css', get_template_directory_uri() . '/assets/css/admin.css');
        }
    }
}
add_action( 'admin_enqueue_scripts', 'screenr_admin_scripts' );

/**
 * Get theme actions required
 *
 * @return array|mixed|void
 */
function screenr_get_actions_required( ) {

    $actions = array();
    $front_page = get_option( 'page_on_front' );
    $actions['page_on_front'] = 'dismiss';
    $actions['page_template'] = 'dismiss';
    if ( $front_page <= 0  ) {
        $actions['page_on_front'] = 'active';
        $actions['page_template'] = 'active';

    } else {
        if ( get_post_meta( $front_page, '_wp_page_template', true ) == 'template-frontpage.php' ) {
            $actions['page_template'] = 'dismiss';
        } else {
            $actions['page_template'] = 'active';
        }
    }

    $actions = apply_filters( 'screenr_get_actions_required', $actions );
    $actions_dismiss =  get_option( 'screenr_actions_dismiss' );

    if (  $actions_dismiss && is_array( $actions_dismiss ) ) {
        foreach ( $actions_dismiss as $k => $v ) {
            if ( isset ( $actions[ $k ] ) ) {
                $actions[ $k ] = 'dismiss';
            }
        }
    }

    return $actions;
}

add_action('switch_theme', 'screenr_reset_actions_required');
function screenr_reset_actions_required () {
    delete_option('screenr_actions_dismiss');
}

/**
 * Output the status of widets for footer column.
 *
 */
function screenr_sidebar_desc( $sidebar_id ) {

    $desc           = '';
    $column         = str_replace( 'footer-', '', $sidebar_id );
    $footer_columns = absint( get_theme_mod( 'footer_layout', 4 ) );

    if ( $column > $footer_columns ) {
        $desc = esc_html__( 'This widget area is currently disabled. You can enable it Customizer &rarr; Theme Options &rarr; Footer section.', 'wp-coupon' );
    }

    return esc_html( $desc );
}
