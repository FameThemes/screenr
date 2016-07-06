<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Screenr
 */

if ( ! function_exists( 'screenr_is_selective_refresh' ) ) {
	function screenr_is_selective_refresh()
	{
		$check = isset($GLOBALS['screenr_is_selective_refresh']) && $GLOBALS['screenr_is_selective_refresh'] ? true : false;
		if ( ! $check ) {
			if ( is_customize_preview() && isset( $_POST['partials'] ) ) {
				$check = true;
			}
		}
		return $check;
	}
}

function screenr_is_fullscreen(){
	return get_theme_mod( 'slider_fullscreen' );
}

function screenr_not_fullscreen() {
	return ! screenr_is_fullscreen();
}


if ( ! function_exists( 'screenr_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function screenr_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'screenr' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'Author: %s', 'post author', 'screenr' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	$category = get_the_category();
	$cat = '';
	if ( $category[0] ) {
		$cat = sprintf(
			esc_html_x( 'Category: %s', 'category', 'screenr' ),
			'<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . $category[0]->cat_name . '</a>'
		);
	}

	echo '<span class="posted-on"><i aria-hidden="true" class="fa fa-clock-o"></i> ' . $posted_on . '</span><span class="byline"> ' . $byline . '</span> ' . '<span class="meta-cate">' . $cat . '</span>'; // WPCS: XSS OK.
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		echo '<i class="fa fa-comments-o"></i> ';
		comments_popup_link( '0', '1', '%' );
		echo '</span>';
	}

}
endif;

if ( ! function_exists( 'screenr_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function screenr_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'screenr' ) );
		if ( $categories_list && screenr_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'screenr' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'screenr' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'screenr' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'screenr' ), esc_html__( '1 Comment', 'screenr' ), esc_html__( '% Comments', 'screenr' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'screenr' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function screenr_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'screenr_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'screenr_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so screenr_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so screenr_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in screenr_categorized_blog.
 */
function screenr_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'screenr_categories' );
}
add_action( 'edit_category', 'screenr_category_transient_flusher' );
add_action( 'save_post',     'screenr_category_transient_flusher' );


if ( ! function_exists( 'screenr_loop_post_item' ) ) {
	function screenr_loop_post_item( $post_class = '')
	{
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
			<?php if (has_post_thumbnail()) : ?>
				<div class="entry-thumb">
					<a href="<?php echo esc_url(get_permalink()); ?>">
						<?php the_post_thumbnail('screenr-blog-grid-small'); ?>
					</a>
				</div>
			<?php endif; ?>
			<div class="entry-grid-elements">
				<?php
				$category = get_the_category();
				if ($category[0]) {
					echo '<div class="entry-grid-cate">';
					echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . $category[0]->cat_name . '</a>';
					echo '</div>';
				}
				?>
				<header class="entry-header">
					<?php the_title('<div class="entry-grid-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></div>'); ?>
				</header><!-- .entry-header -->
				<div class="entry-excerpt">
					<?php
					echo wp_trim_words(get_the_content(), 13, ' ...'); ?>
				</div><!-- .entry-content -->
				<div class="entry-grid-more">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e('Read On', 'screenr'); ?> <i aria-hidden="true" class="fa fa-arrow-circle-o-right"></i></a>
				</div>
			</div>
		</article><!-- #post-## -->
		<?php
	}
}

if ( ! function_exists( 'screenr_ajax_load_more_posts' ) ) {
	function screenr_ajax_load_more_posts()
	{

		$paged = isset( $_REQUEST[ 'paged'] ) ?  absint( $_REQUEST[ 'paged' ]  ) :  1;
		$latest_posts = new WP_Query(array(
			'posts_per_page' => absint(get_theme_mod('news_num_post', 3)),
			'ignore_sticky_posts' => true,
			'paged' => $paged,
		));

		$layout = absint(get_theme_mod('news_layout', 3));
		if (!$layout) {
			$layout = 3;
		}
		$post_class = '';
		switch ( $layout ) {
			case 1:
				$post_class = 'col-md-12';
				break;
			case 2:
				$post_class = 'col-md-6';
				break;
			case 4:
				$post_class = 'col-md-6 col-lg-3';
				break;
			default:
				$post_class = 'col-md-6 col-lg-4';
				break;
		}

		if ($latest_posts->have_posts()) {
			while ($latest_posts->have_posts()) : $latest_posts->the_post();
				screenr_loop_post_item($post_class);
			endwhile;
		} else {

		}
	}

}
add_action( 'wp_ajax_screenr_ajax_posts', 'screenr_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_screenr_ajax_posts', 'screenr_ajax_load_more_posts' );
