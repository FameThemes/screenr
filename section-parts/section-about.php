<?php
$page_id = get_theme_mod( 'about_page_id' );
global $post;

$title      = get_theme_mod( 'about_title', __( 'About us', 'screenr' ) );
$subtitle   = get_theme_mod( 'about_subtitle' );
$desc       = get_theme_mod( 'about_desc', __( 'We provide creative solutions that get attention and meaningful to clients around the world.', 'screenr' ) );

if ( $page_id && ( $post = get_post( $page_id ) )) {
    setup_postdata( $post );
?>
<section id="<?php echo esc_attr( get_theme_mod( 'about_id', 'about' ) ); ?>" class="screenr-section section-about section-padding section-padding-larger">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <?php if (  $title || $subtitle || $desc ) { ?>
                <div class="section-title-area">
                    <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                    <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                    <?php if ( $desc ) { ?><div class="section-desc"><?php echo do_shortcode( wp_kses_post( $desc ) ); ?></div><?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="col-md-7">
                <div class="section-content">
                    <?php
                    if (  get_theme_mod( 'about_page_content_type' ) == 'content' ) {
                        the_content();
                    } else {
                        the_excerpt();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
wp_reset_postdata();
?>