<?php

$title      = get_theme_mod( 'contact_title', __( 'Contact Us', 'screenr' ) );
$subtitle   = get_theme_mod( 'contact_subtitle', __( 'Keep in touch', 'screenr' ) );
$desc       = get_theme_mod( 'contact_desc', __( 'Fill out the form below and you will hear from us shortly.', 'screenr' ) );
$content    = get_theme_mod( 'contact_content' );
$items      = get_theme_mod( 'contact_items' );
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod('contact_id', 'contact')); ?>" class="section-contact section-padding onepage-section">
<?php } ?>
    <div class="container">
        <?php if (  $title || $subtitle || $desc ) { ?>
        <div class="section-title-area">
            <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
            <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
            <?php if ( $desc ) { ?><div class="section-desc"><?php echo do_shortcode( apply_filters( 'the_content', $desc ) ); ?></div><?php } ?>
        </div>
        <?php } ?>
        <div class="section-content">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <?php if ( ! empty ( $items ) ) { ?>
                    <div class="contact-details">
                        <div class="row">
                            <?php
                            $layout = absint( get_theme_mod( 'contact_layout', 3 ) );
                            if ( $layout == 0 ) {
                                $layout = 3;
                            }

                            $classes = '';
                            switch( $layout ){
                                case 2:
                                    $classes = 'col-lg-6';
                                    break;
                                case 3:
                                    $classes = 'col-lg-4';
                                    break;
                                case 4:
                                    $classes = 'col-lg-3';
                                    break;
                                default:
                                    $classes = 'col-lg-6';
                            }

                            foreach ( ( array ) $items as $item ) {
                                $items = wp_parse_args( $item, array(
                                    'title' => '',
                                    'icon'  => '',
                                    'url'   => '',
                                ) );
                                ?>
                                <div class="contact-detail <?php echo esc_attr( $classes ); ?> col-md-6">
                                    <?php if ( $item['icon'] ){ ?><span class="contact-icon"><i aria-hidden="true" class="fa <?php echo esc_attr( $item['icon'] ); ?> fa-2x"></i></span><?php } ?>
                                    <?php if ( $item['url'] ){ ?><a href="<?php echo antispambot( $item['url'] ); ?>"><?php } ?>
                                        <span class="contact-detail-value"><?php echo esc_html( $item['title'] ); ?></span>
                                    <?php if ( $item['url'] ){ ?></a><?php } ?>
                                </div>
                            <?php }// end loop items ?>

                        </div>
                    </div>
                    <?php } ?>
                    <?php if ( $content ) { ?>
                    <div class="contact-form-fields">
                        <?php echo do_shortcode( apply_filters( 'the_content', $content ) ); ?>
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
