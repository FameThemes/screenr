<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Screenr
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'screenr' ); ?></a>
    <?php
    $header_classes = array();
    $header_classes[] = 'site-header';
    $header_layout = get_theme_mod( 'header_layout' );
    if ( $header_layout == 'fixed' ){
        $header_classes[] = 'sticky-header';
    } else if (  $header_layout == 'transparent' ) {
        $header_classes[] = 'sticky-header';
        $header_classes[] = 'transparent';
    }

    ?>
	<header id="masthead" class="<?php echo esc_attr( join( ' ', $header_classes ) );?>" role="banner">
		<div class="container">
			<?php screenr_branding(); ?>

			<div class="header-right-wrapper">
				<a href="#" id="nav-toggle"><?php esc_html_e('Menu', 'screenr'); ?><span></span></a>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<ul class="nav-menu">
						<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s')); ?>
					</ul>
				</nav>
				<!-- #site-navigation -->
			</div>

		</div>
	</header><!-- #masthead -->
<?php
do_action( 'screenr_after_site_header' );
