<?php

$title      = get_theme_mod( 'products_title', esc_html__( 'Our products', 'screenr' ) );
$subtitle   = get_theme_mod( 'products_subtitle',esc_html__( 'Section subtitle', 'screenr' ) );
$desc       = get_theme_mod( 'products_desc' );
$content    = get_theme_mod( 'products_content', '[recent_products per_page="4" columns="4"]' );

$classes = 'section-products section-padding-lg section-meta screenr-section';
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr(get_theme_mod('products_id', 'products')); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'products' ) ); ?>">
<?php } ?>
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
        <div class="section-title-area">
            <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
            <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
            <?php if ( $desc ) { ?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
        </div>
        <?php } ?>

        <div class="section-content products-content">
            <?php echo do_shortcode( apply_filters( 'screenr_content_text', wp_kses_post( $content ) ) ); ?>
        </div><!-- /.section-content -->

    </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php }
