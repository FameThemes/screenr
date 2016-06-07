<?php
$title      = get_theme_mod( 'news_title', __( 'News', 'screenr' ) );
$subtitle   = get_theme_mod( 'news_subtitle', __( 'Section subtitle', 'screenr' ) );
$desc       = get_theme_mod( 'news_desc' );

$latest_posts = new WP_Query( array(
    'posts_per_page'      => absint( get_theme_mod( 'news_num_post', 3 ) ),
    'ignore_sticky_posts' => true,
) );

$layout = absint( get_theme_mod( 'news_layout', 3 ) );
if ( ! $layout ) {
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

?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="news" class="section-news section-padding">
<?php } ?>
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo do_shortcode( apply_filters( 'the_content', $desc ) ); ?></div><?php } ?>
            </div>
        <?php } ?>
        <div class="section-content section-news-content">
            <div class="row">
                <div class="content-grid" data-layout="<?php echo esc_attr( $layout ); ?>">
                    <?php if ( $latest_posts->have_posts() ) : ?>
                        <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
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
                                		echo '<a href="' . get_category_link( $category[0]->term_id ) . '">' . $category[0]->cat_name . '</a>';
                                		echo '</div>';
                                	}
                                	?>
                                	<header class="entry-header">
                                		<?php the_title( '<div class="entry-grid-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></div>' ); ?>
                                	</header><!-- .entry-header -->
                                	<div class="entry-excerpt">
                                		<?php echo wp_trim_words( get_the_content(), 13, ' ...' ); ?>
                                	</div><!-- .entry-content -->
                                    <div class="entry-grid-more">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php esc_html_e( 'Read On' ) ?> <i aria-hidden="true" class="fa fa-arrow-circle-o-right"></i></a>
                                    </div>
                                </div>
                            </article><!-- #post-## -->
                        <?php endwhile; ?>
                    <?php else : ?>
    					<?php get_template_part( 'template-parts/content', 'none' ); ?>
    				<?php endif; ?>
                </div>
                <div class="clear"></div>
                <div class="content-grid-loadmore">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn btn-theme-primary-outline"><?php esc_html_e( 'Load More News', 'screenr' ); ?><i aria-hidden="true" class="fa fa-angle-double-down"></i></a>
                </div>

            </div>
        </div>
    </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
<?php wp_reset_postdata(); ?>
