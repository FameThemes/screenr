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
    $thumbnail = '';
    if ( has_post_thumbnail() ) {
        $thumbnail = get_the_post_thumbnail_url( get_the_ID(),  'full' );
        if ( $thumbnail ) {
            $thumbnail = ' style="background-image: url('.esc_url( $thumbnail ).');" ';
        }
    }
?>
	<div id="page-header" class="page-header-cover"<?php echo $thumbnail; ?>>
        <div class="page-header-inner">
            <?php the_title( '<h1 class="site-title">', '</h1>' ); ?>
        </div>
	</div>

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
