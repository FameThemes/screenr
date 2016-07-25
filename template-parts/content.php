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
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php screenr_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php
		endif; ?>

		<?php
		if ( has_post_thumbnail( ) ) {
			echo '<div class="entry-thumb">';
				the_post_thumbnail( 'screenr-blog-list' );
			echo '</div>';
		}
		?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->
	
	<div class="entry-more">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="btn btn-theme-primary"><?php esc_html_e( 'read more', 'screenr' ); ?><i aria-hidden="true" class="fa fa-chevron-right"></i></a>
	</div>

</article><!-- #post-## -->
