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
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb">
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php the_post_thumbnail( 'screenr-blog-grid-small' ); ?>
			</a>
		</div>
	<?php endif; ?>
	<div class="entry-grid-elements">
		<?php
		$category = get_the_category();
		if ( $category[0] ) {
			echo '<div class="entry-grid-cate">';
			echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . $category[0]->cat_name . '</a>';
			echo '</div>';
		}
		?>
		<header class="entry-header">
			<?php the_title( '<div class="entry-grid-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></div>' ); ?>
		</header><!-- .entry-header -->
		<div class="entry-excerpt">
			<?php
			screenr_add_excerpt_length( apply_filters( 'screenr_grid_excerpt_length', 13 ) );
			the_excerpt();
			screenr_remove_excerpt_length();
			?>
		</div><!-- .entry-content -->
		<div class="entry-grid-more">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read On', 'screenr' ) ?> <i aria-hidden="true" class="fa fa-arrow-circle-o-right"></i></a>
		</div>
	</div>
</article><!-- #post-## -->
