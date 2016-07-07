<?php

$title      = get_theme_mod( 'contact_title', esc_html__( 'Contact Us', 'screenr' ) );
$subtitle   = get_theme_mod( 'contact_subtitle', esc_html__( 'Keep in touch', 'screenr' ) );
$desc       = get_theme_mod( 'contact_desc', esc_html__( 'Fill out the form below and you will hear from us shortly.', 'screenr' ) );
$content    = get_theme_mod( 'contact_content' );
$classes    = 'section-contact section-padding onepage-section section-meta';
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod('contact_id', 'contact')); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'contact' ) ); ?>">
<?php } ?>
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
        <div class="section-title-area">
            <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
            <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
            <?php if ( $desc ) { ?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
        </div>
        <?php } ?>
        <div class="section-content">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <?php
                    $items      = get_theme_mod( 'contact_items' );
                    if ( ! empty ( $items ) ) {
                    ?>
                    <ul class="contact-details">
                        <?php
                        foreach ( ( array ) $items as $item ) {
                            $items = wp_parse_args( $item, array(
                                'title' => '',
                                'icon'  => '',
                                'url'   => '',
                            ) );
                            ?>
                            <li class="contact-detail">
                                <?php if ( $item['icon'] ){ ?><span class="contact-icon"><i aria-hidden="true" class="<?php echo esc_attr( $item['icon'] ); ?> fa-2x"></i></span><?php } ?>
                                <?php if ( $item['url'] ){ ?><a href="<?php echo antispambot( $item['url'] ); ?>"><?php } ?>
                                    <span class="contact-detail-value"><?php echo esc_html( $item['title'] ); ?></span>
                                <?php if ( $item['url'] ){ ?></a><?php } ?>
                            </li>
                        <?php }// end loop items ?>
                    </ul>
                    <?php } ?>
                    <?php if ( $content ) { ?>
                    <div class="contact-form-fields">
                        <?php echo apply_filters( 'screenr_content_text', $content ); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
