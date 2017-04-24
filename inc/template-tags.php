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
function screenr_posted_on( $echo = true ) {
    $html = $posted_on = $byline = $cat = $comment = '';
    if ( get_theme_mod( 'show_post_date', 1 ) ) {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        $time_string = sprintf($time_string,
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date('c')),
            esc_html(get_the_modified_date())
        );

        $posted_on =  '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

    }

    // show_post_author
    if ( get_theme_mod( 'show_post_author', 1 ) ) {
        $byline = sprintf(
            esc_html_x('Author: %s', 'post author', 'screenr'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );
    }

    if ( get_theme_mod( 'show_post_cate', 1 ) ) {
        $category = get_the_category();
        if ($category[0]) {
            $cat = sprintf(
                esc_html_x('Category: %s', 'category', 'screenr'),
                '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . $category[0]->cat_name . '</a>'
            );
        }
    }

    if ( get_post_meta( 'show_post_comment', 1 ) ) {
        if (!post_password_required() && (comments_open() || get_comments_number())) {
            $comment .= '<span class="comments-link">';
            $comment .= '<i class="fa fa-comments-o"></i> ';
            $comment .= get_comments_number_text(0, 1, '%');
            $comment .= '</span>';
        }
    }

    if ( $posted_on != '' ) {
        $html .='<span class="posted-on"><i aria-hidden="true" class="fa fa-clock-o"></i> ' . $posted_on . '</span>';
    }

    if ( $byline ) {
        $html .= '<span class="byline"> ' . $byline . '</span> ';
    }

    if ( $cat ) {
        $html .= '<span class="meta-cate">' . $cat . '</span>';
    }

    if ( $comment ) {
        $html .= $comment;
    }

    if ( $echo ) {
        echo $html;
    }

    return $html;
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
					screenr_add_excerpt_length( apply_filters( 'screenr_grid_excerpt_length', 13 ) );
					the_excerpt();
					screenr_remove_excerpt_length();
					?>
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

		if ( $latest_posts->have_posts() ) {
			while ($latest_posts->have_posts()) : $latest_posts->the_post();
				screenr_loop_post_item($post_class);
			endwhile;
		}

        wp_reset_postdata();
        wp_die();

	}

}
add_action( 'wp_ajax_screenr_ajax_posts', 'screenr_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_screenr_ajax_posts', 'screenr_ajax_load_more_posts' );


if ( ! function_exists( 'screenr_comment' ) ) {
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own screenr_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @return void
     */
    function screenr_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php esc_html_e('Pingback:', 'screenr'); ?><?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'screenr'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment clearfix">

                    <?php echo get_avatar($comment, 60); ?>

                    <div class="comment-wrapper">

                        <header class="comment-meta comment-author vcard">
                            <?php
                            printf('<cite><b class="fn">%1$s</b> %2$s</cite>',
                                get_comment_author_link(),
                                // If current post author is also comment author, make it known visually.
                                ($comment->user_id === $post->post_author) ? '<span>' . __('Post author', 'screenr') . '</span>' : ''
                            );
                            printf('<a class="comment-time" href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                esc_url(get_comment_link($comment->comment_ID)),
                                get_comment_time('c'),
                                /* translators: 1: date, 2: time */
								get_comment_date()
                            );
                            comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'screenr'), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'])));
                            edit_comment_link(__('Edit', 'screenr'), '<span class="edit-link">', '</span>');
                            ?>
                        </header>
                        <!-- .comment-meta -->

                        <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'screenr'); ?></p>
                        <?php endif; ?>

                        <div class="comment-content entry-content">
                            <?php comment_text(); ?>
                            <?php ?>
                        </div>
                        <!-- .comment-content -->

                    </div>
                    <!--/comment-wrapper-->

                </article>
                <!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }
}



if ( ! function_exists( 'screenr_get_section_gallery_data' ) ) {
	/**
	 * Get Gallery data
	 *
	 * @since 1.2.6
	 *
	 * @return array
	 */
	function screenr_get_section_gallery_data()
	{

		$source = 'page';
		if( has_filter( 'screenr_get_section_gallery_data' ) ) {
			$data =  apply_filters( 'screenr_get_section_gallery_data', false );
			return $data;
		}

		$data = array();

		switch ( $source ) {
			default:
				$page_id = get_theme_mod( 'gallery_source_page' );
				$images = '';
				if ( $page_id ) {
					$gallery = get_post_gallery( $page_id , false );
					if ( $gallery ) {
						$images = $gallery['ids'];
					}
				}

				$image_thumb_size = apply_filters( 'screenr_gallery_page_img_size', 'screenr-service-small' );

				if ( ! empty( $images ) ) {
					$images = explode( ',', $images );
					foreach ( $images as $post_id ) {
						$post = get_post( $post_id );
						if ( $post ) {
							$img_thumb = wp_get_attachment_image_src($post_id, $image_thumb_size );
							if ($img_thumb) {
								$img_thumb = $img_thumb[0];
							}

							$img_full = wp_get_attachment_image_src( $post_id, 'full' );
							if ($img_full) {
								$img_full = $img_full[0];
							}

							if ( $img_thumb && $img_full ) {
								$data[ $post_id ] = array(
									'id'        => $post_id,
									'thumbnail' => $img_thumb,
									'full'      => $img_full,
									'title'     => $post->post_title,
									'content'   => $post->post_content,
								);
							}
						}
					}
				}
				break;
		}

		return $data;

	}
}

/**
 * Generate HTML content for gallery items.
 *
 * @since 1.2.6
 *
 * @param $data
 * @param bool|true $inner
 * @return string
 */
function screenr_gallery_html( $data, $inner = true, $size = 'thumbnail' ) {
	$max_item = get_theme_mod( 'gallery_number', 10 );
	$html = '';
	if ( ! is_array( $data ) ) {
		return $html;
	}
	$n = count( $data );
	if ( $max_item > $n ) {
		$max_item =  $n;
	}
	$i = 0;
	while( $i < $max_item ){
		$photo = current( $data );
		$i ++ ;
		if ( $size == 'full' ) {
			$thumb = $photo['full'];
		} else {
			$thumb = $photo['thumbnail'];
		}

		$html .= '<a href="'.esc_attr( $photo['full'] ).'" class="g-item" title="'.esc_attr( sanitize_text_field( $photo['title'] ) ).'">';
		if ( $inner ) {
			$html .= '<span class="inner">';
			$html .= '<span class="inner-content">';
			$html .= '<img src="'.esc_url( $thumb ).'" alt="">';
			$html .= '</span>';
			$html .= '</span>';
		} else {
			$html .= '<img src="'.esc_url( $thumb ).'" alt="">';
		}

		$html .= '</a>';
		next( $data );
	}
	reset( $data );

	return $html;
}


/**
 * Generate Gallery HTML
 *
 * @since 1.2.6
 * @param bool|true $echo
 * @return string
 */
function screenr_gallery_generate( $echo = true ){

	$div = '';

	$data = screenr_get_section_gallery_data();
	$display_type = get_theme_mod( 'gallery_display', 'grid' );
	$lightbox = get_theme_mod( 'gallery_lightbox', 1 );
	$class = '';
	if ( $lightbox ) {
		$class = ' enable-lightbox ';
	}
	$col = absint( get_theme_mod( 'gallery_col', 4 ) );
	if ( $col <= 0 ) {
		$col = 4;
	}
	switch( $display_type ) {
		case 'masonry':
			$html = screenr_gallery_html( $data );
			if ( $html ) {
				$div .= '<div data-col="'.$col.'" class="g-zoom-in gallery-masonry '.$class.' gallery-grid g-col-'.$col.'">';
				$div .= $html;
				$div .= '</div>';
			}
			break;
		case 'carousel':
			$html = screenr_gallery_html( $data );
			if ( $html ) {
				$div .= '<div data-col="'.$col.'" class="g-zoom-in gallery-carousel'.$class.'">';
				$div .= $html;
				$div .= '</div>';
			}
			break;
		case 'slider':
			$html = screenr_gallery_html( $data , true , 'full' );
			if ( $html ) {
				$div .= '<div class="gallery-slider'.$class.'">';
				$div .= $html;
				$div .= '</div>';
			}
			break;
		case 'justified':
			$html = screenr_gallery_html( $data, false );
			if ( $html ) {
				$gallery_spacing = absint( get_theme_mod( 'gallery_spacing', 20 ) );
				$div .= '<div data-spacing="'.$gallery_spacing.'" class="g-zoom-in gallery-justified'.$class.'">';
				$div .= $html;
				$div .= '</div>';
			}
			break;
		default: // grid
			$html = screenr_gallery_html( $data );
			if ( $html ) {
				$div .= '<div class="gallery-grid g-zoom-in '.$class.' g-col-'.$col .'">';
				$div .= $html;
				$div .= '</div>';
			}
			break;
	}

	if ( $echo ) {
		echo $div;
	} else {
		return $div;
	}

}



if ( function_exists( 'wp_update_custom_css_post' ) ) {
    // Migrate any existing theme CSS to the core option added in WordPress 4.7.
    $css = get_option( 'screenr_custom_css' );
    if ( $css ) {
        $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
        $return = wp_update_custom_css_post( $core_css ."\n". $css );
        if ( ! is_wp_error( $return ) ) {
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            delete_option( 'screenr_custom_css' );
        }
    }
} else {
    // Back-compat for WordPress < 4.7.
}

