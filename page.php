<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Screenr
 */

get_header();


the_post();

?>
	<section id="page-header" class="screenr-section swiper-slider full-screen fixed" >
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="swiper-slide slide-align-center activated swiper-slide-active">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					}
					?>
					<div class="swiper-slide-intro">
						<div class="swiper-intro-inner">
							<?php the_title( '<h2 class="swiper-slide-heading">', '</h2>' ); ?>
						</div>
					</div>
					<div class="overlay"></div>
				</div>
			</div>
		</div>
	</section>

	<div id="content" class="site-content">
		<div id="content-inside" class="container right-sidebar">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
				<?php

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				?>
				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!--#content-inside -->
	</div><!-- #content -->

<?php get_footer(); ?>
