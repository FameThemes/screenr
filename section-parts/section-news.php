<?php
$title      = get_theme_mod( 'news_title', esc_html__( 'Latest News', 'screenr' ) );
$subtitle   = get_theme_mod( 'news_subtitle', esc_html__( 'Section subtitle', 'screenr' ) );
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

$classes = 'section-news section-padding section-padding-lg';

?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod( 'news_id', 'news' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'news' ) ); ?>">
<?php } ?>
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
            </div>
        <?php } ?>
        <div class="section-content section-news-content">
            <div class="row">
                <div class="content-grid" id="section-news-posts" data-layout="<?php echo esc_attr( $layout ); ?>">
                    <?php if ( $latest_posts->have_posts() ) : ?>
                        <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                            <?php screenr_loop_post_item( $post_class ); ?>
                        <?php endwhile; ?>
                    <?php else : ?>
    					<?php get_template_part( 'template-parts/content', 'none' ); ?>
    				<?php endif; ?>
                </div>
                <div class="clear"></div>
                <?php
                $t = get_theme_mod( 'news_loadmore', 'ajax' );
                if ( $t != 'hide' ) {
                    $label = get_theme_mod( 'news_more_text' );
                    $icon_name = 'fa-angle-double-down';
                    if ( $t == 'link' ) {
                        $icon_name = 'fa-angle-double-right';
                    }
                    if ( ! $label ) {
                        if ( $t == 'link' ) {
                            $label = esc_html__( 'Read Our Blog', 'screenr' );

                        } else {
                            $label = esc_html__( 'Load More News', 'screenr' );
                        }
                    }
                ?>
                <div class="content-grid-loadmore  blt-<?php echo esc_attr( $t ); ?>">
                    <a href="<?php echo ( $t == 'link' ) ? esc_url( get_theme_mod( 'news_more_link' ) ) : '#'; ?>" class="btn btn-theme-primary-outline"><?php echo esc_html( $label ); ?><i aria-hidden="true" class="fa <?php echo esc_attr( $icon_name ); ?>"></i></a>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
<?php wp_reset_postdata(); ?>
