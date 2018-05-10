<?php
/**
 * The template for displaying all single attachments.
 *
 *
 * @package Screenr
 */

get_header(); ?>

	<div id="content" class="site-content">

		<div id="content-inside" class="container <?php echo esc_attr( screenr_get_layout() ); ?>-sidebar">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </header><!-- .entry-header -->
                        <?php
                        echo wp_get_attachment_image( get_the_ID(), 'full' );
                        ?>
                        <div class="entry-content">
                            <?php
                            the_content( );
                            ?>
                        </div><!-- .entry-content -->
                        <footer class="entry-footer">
                            <?php
                            if ( wp_attachment_is_image( get_the_ID() ) ) {
                                $images = array();
                                $image_sizes = get_intermediate_image_sizes();
                                array_unshift( $image_sizes, 'full' );
                                foreach( $image_sizes as $image_size ) {
                                    $image = wp_get_attachment_image_src( get_the_ID(), $image_size );
                                    $name = $image_size . ' (' . $image[1] . 'x' . $image[2] . ')';
                                    $images[] = '<a href="' . $image[0] . '">' . $name . '</a>';
                                }
                                echo implode( ' | ', $images );
                            }
                            ?>
                        </footer>
                    </article><!-- #post-## -->

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

				<?php endwhile; // End of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!--#content-inside -->
	</div><!-- #content -->

<?php get_footer(); ?>
