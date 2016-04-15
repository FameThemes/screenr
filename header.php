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


	<header id="masthead" class="site-header transparent- sticky-header-" role="banner">
		<div class="container">
			<div class="site-branding">
				<?php
				if ( function_exists( 'the_custom_logo' ) ) {
					the_custom_logo();
				}
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

			<div class="header-right-wrapper">
				<a href="#" id="nav-toggle"><?php _e('Menu', 'screenr'); ?><span></span></a>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<ul class="nav-menu">
						<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s')); ?>
					</ul>
				</nav>
				<!-- #site-navigation -->
			</div>

		</div>
	</header><!-- #masthead -->



	<div class="swiper-slider full-screen fixed" >
		<div class="swiper-container">

			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<img src="http://localhost/fame/screenr/wp-content/uploads/2016/04/15I3XcS.jpg" />
					<div class=" swiper-slide-intro">
						<h2 class="swiper-slide-heading">This is slider 1</h2>
						<div class="swiper-slide-desc">Place a short tagline here and large welcome message like above.<br/>
							Unlimited background slides, parallax effect and video background too.
						</div>
						<div class="swiper-slide-actions">
							<a class="btn btn-primary btn-lg" href="http://localhost/fame/onepress/#services">Our Services</a>
							<a class="btn btn-secondary-outline btn-lg" href="http://localhost/fame/onepress/#contact">Get Started</a>
						</div>
					</div>
					<div class="overlay"></div>

				</div>

				<div class="swiper-slide">
					<video id="owl-test-video" class="fillWidth" >
						<source src="http://localhost/fame/screenr/wp-content/uploads/2016/04/Sunset-Lapse.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
					</video>

					<div class=" swiper-slide-intro">
						<h2 class="swiper-slide-heading">This is video slide</h2>
						<div class="swiper-slide-desc">Place a short tagline here and large welcome message like above.<br/>
							Unlimited background slides, parallax effect and video background too.
						</div>
						<div class="swiper-slide-actions">
							<a class="btn btn-primary btn-lg" href="http://localhost/fame/onepress/#services">Our Services</a>
							<a class="btn btn-secondary-outline btn-lg" href="http://localhost/fame/onepress/#contact">Get Started</a>
						</div>
					</div>

					<div class="overlay"></div>

				</div>

				<div class="swiper-slide">
					<img src="http://localhost/fame/screenr/wp-content/uploads/2016/04/14lL9AE.jpg" />

					<div class="swiper-slide-intro">
						<h2 class="swiper-slide-heading">This is slide 3</h2>
						<div class="swiper-slide-desc">Place a short tagline here and large welcome message like above.<br/>
							Unlimited background slides, parallax effect and video background too.
						</div>
						<div class="swiper-slide-actions">
							<a class="btn btn-primary btn-lg" href="http://localhost/fame/onepress/#services">Our Services</a>
							<a class="btn btn-secondary-outline btn-lg" href="http://localhost/fame/onepress/#contact">Get Started</a>
						</div>
					</div>

					<div class="overlay"></div>

				</div>

			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>
			<!-- Add Navigation -->
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
	</div>



