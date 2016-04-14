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



	<div class="swiper-full-screen" >
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<video id="owl-test-video" class="fillWidth" height="300px">
						<source src="http://localhost/fame/screenr/wp-content/uploads/2016/04/Sunset-Lapse.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
					</video>
					<div class="swiper-slide-intro">
						<div class="swiper-slide-heading">This is header</div>
						<div class="swiper-slide-dessc">Place a short tagline here and large welcome message like above.
							Unlimited background slides, parallax effect and video background too.
						</div>
						<a href="/">link</a>
					</div>
				</div>
				<div class="swiper-slide">
					<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_caramel.jpg" />
					<div class="swiper-slide-intro">
						<div class="swiper-slide-heading">This is header</div>
						<div class="swiper-slide-dessc">Place a short tagline here and large welcome message like above.
							Unlimited background slides, parallax effect and video background too.
						</div>
						<input type="submit" value="Search" class="search-submit">
					</div>
				</div>
				<div class="swiper-slide">
					<img src="http://flexslider.woothemes.com/images/kitchen_adventurer_donut.jpg" />
					<div class="swiper-slide-intro">
						<div class="swiper-slide-heading">This is header</div>
						<div class="swiper-slide-dessc">Place a short tagline here and large welcome message like above.
							Unlimited background slides, parallax effect and video background too.
						</div>
						<input type="submit" value="Search" class="search-submit">
					</div>
				</div>

			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>
		</div>
	</div>
