<?php

$title      = get_theme_mod( 'gallery_title', esc_html__( 'Gallery', 'screenr' ) );
$subtitle   = get_theme_mod( 'gallery_subtitle' );
$desc       = get_theme_mod( 'gallery_desc' );
$classes    = 'section-gallery section-padding onepage-section';

$layout = get_theme_mod( 'gallery_layout', 'default' );
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod('gallery_id', 'gallery')); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'gallery' ) ); ?>">
    <?php } ?>
    <div class="g-layout-<?php echo esc_attr( $layout ); ?> container">
        <?php if (  $title || $subtitle || $desc ) { ?>
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
            </div>
        <?php } ?>
        <div class="section-content gallery-content">
            <?php
            screenr_gallery_generate();
            ?>
        </div>
    </div>
    <?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
