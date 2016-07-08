<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Screenr
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( 'post' === get_post_type() && $meta = screenr_posted_on( false ) ) : ?>
		<div class="entry-meta">
			<?php echo $meta; ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>

		<?php
        if ( ! get_theme_mod( 'disable_featured_image', 0 ) ) {
            if (has_post_thumbnail()) {
                echo '<div class="entry-thumb">';
                the_post_thumbnail('screenr-blog-list');
                echo '</div>';
            }
        }
		?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( esc_html__( 'Continue reading %s', 'screenr' ), '<span class="meta-nav">&rarr;</span>' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'screenr' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php screenr_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
