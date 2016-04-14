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

	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-branding">
				<?php
				if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
				endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
				<?php
				endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'screenr' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

	<div style="width: 500px; height: 300px; display: block; margin: 30px auto;" >
		<div class="flexslider">
			<ul class="slides">

				<li>
					<video id="s-test-video" class="fillWidth" height="300px">
						<source src="http://localhost/fame/screenr/wp-content/uploads/2016/04/Sunset-Lapse.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
					</video>
				</li>

				<li>
					<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_caramel.jpg" />
				</li>


				<li>
					<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_donut.jpg" />
				</li>
				<li>
					<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_cheesecake_brownie.jpg" />
				</li>
			</ul>
		</div>
	</div>

	<h2>owl slider</h2><span class="timebar">----</span>

	<div style="width: 500px; height: 300px; display: block; margin: 30px auto;" >
		<div class="owl-example">
			<div>
				<video id="s-test-video" class="fillWidth" height="300px">
					<source src="http://localhost/fame/screenr/wp-content/uploads/2016/04/Sunset-Lapse.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
				</video>
			</div>

			<div>
				<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_caramel.jpg" />
			</div>


			<div>
				<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_donut.jpg" />
			</div>
			<div>
				<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_cheesecake_brownie.jpg" />
			</div>
		</div>
	</div>
