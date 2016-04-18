<?php

$video_url =  get_theme_mod( 'videolightbox_video' );
if ( $video_url ) {
    $parallax_url = get_theme_mod( 'videolightbox_parallax_img' );
    $parallax_url =  apply_filters( 'video_parallax_bg', $parallax_url );
    $title =  get_theme_mod( 'videolightbox_title' );
    if ( $parallax_url ) {
    ?>
    <div class="parallax-id parallax-window" data-over-scroll-fix="true" data-z-index="1" data-speed="0.3" data-image-src="<?php echo esc_url( $parallax_url ); ?>" data-parallax="scroll" data-position="center" data-bleed="0">
    <?php } ?>
        <section id="<?php echo esc_attr( get_theme_mod( 'videolightbox_id', 'video' ) ); ?>" class="section-videolightbox section-padding section-padding-larger section-inverse onepage-section">
            <div class="container">
                <div class="videolightbox__icon">
                    <a href="<?php echo esc_url( $video_url ); ?>" class="popup-video">
                        <span class="video_icon"><i class="fa fa-play"></i></span>
                    </a>
                </div>
                <?php if ( $title ){ ?>
                <h2 class="videolightbox__heading"><strong><?php echo esc_html( $title ) ?></strong></h2>
                <?php } ?>
            </div>
        </section>
    <?php if ( $parallax_url ) { ?>
    </div>
    <?php
    }
} ?>
