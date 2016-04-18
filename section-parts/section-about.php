<?php
$page_id = get_theme_mod( 'about_page_id' );
global $post;
if ( $page_id && ( $post = get_post( $page_id ) )) {
    setup_postdata( $post );
?>
<section id="<?php echo esc_attr( get_theme_mod( 'about_id', 'about' ) ); ?>" class="screenr-section section-about section-padding section-padding-larger">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="section-title-area">
                    <h2 class="section-title"><?php the_title(); ?></h2>
                    <?php if ( $tagline = get_theme_mod( 'about_tagline' ) ) { ?>
                    <div class="section-desc"><?php echo esc_html( $tagline ); ?></div>
                    <?php } ?>
                    </div>
            </div>
            <div class="col-md-7">
                <div class="section-content">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
wp_reset_postdata();
?>