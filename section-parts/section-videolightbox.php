<?php

$video_url =  get_theme_mod( 'videolightbox_video', '#' );
$title =  get_theme_mod( 'videolightbox_title', __( 'Parallax & Video Lightbox - Perfected', 'screenr' ) );

if ( ! screenr_is_selective_refresh() ) {
    $parallax_url = get_theme_mod( 'videolightbox_parallax_img' );
    $parallax_url =  apply_filters( 'video_parallax_bg', $parallax_url );

    $classes = 'section-videolightbox section-padding section-padding-larger section-inverse onepage-section';
    if ( $parallax_url ) {
    ?>
    <div class="parallax-videolightbox section-parallax">
        <div class="parallax-bg" style="<?php echo esc_attr( "background-image: url('".$parallax_url."')  "); ?>"></div>
    <?php } ?>
    <section id="<?php echo esc_attr( get_theme_mod( 'videolightbox_id', 'video' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'videolightbox' ) ); ?>">
<?php } ?>
        <div class="container">
            <?php if ( $title ){ ?>
            <h2 class="videolightbox__heading"><?php echo wp_kses_post( balanceTags( $title ) ) ; ?></h2>
            <?php } ?>
            <div class="videolightbox__icon videolightbox-popup">
                <a href="<?php echo esc_attr( $video_url ); ?>" data-scr="<?php echo esc_attr( $video_url ); ?>" class="popup-video">
                    <span class="video_icon"><i class="fa fa-play"></i></span>
                </a>
            </div>

        </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
    </section>
    <?php
    if ( $parallax_url ) { ?>
        </div>
        <?php
    }
}
