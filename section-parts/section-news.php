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
        $post_class = 'col-md-3';
        break;
    default:
        $post_class = 'col-md-4';
        break;
}

?>
<section id="news" class="section-news section-padding">
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo do_shortcode( wp_kses_post( $desc ) ); ?></div><?php } ?>
            </div>
        <?php } ?>
        <div class="section-news-content">
            <div class="row">
                <div class="content-grid" data-layout="<?php echo esc_attr( $layout ); ?>">
                    <?php if ( $latest_posts->have_posts() ) : ?>
                        <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                                        <div class="entry-thumb">
                                            <?php the_post_thumbnail( 'screenr-blog-grid-small' ); ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            	<header class="entry-header">
                            		<?php the_title( '<h2 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            	</header><!-- .entry-header -->
                            	<div class="entry-excerpt">
                            		<?php the_excerpt(); ?>
                            	</div><!-- .entry-content -->
                            </article><!-- #post-## -->
                        <?php endwhile; ?>
                    <?php else : ?>
    					<?php get_template_part( 'template-parts/content', 'none' ); ?>
    				<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
