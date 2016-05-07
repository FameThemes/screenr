<?php
$items = get_theme_mod( 'clients_items' );

if ( is_array( $items ) && ! empty( $items ) ) {
    $layout = absint( get_theme_mod( 'clients_layout', 5 ) );
    if ( $layout == 0 ){
        $layout = 5;
    }

    $title      = get_theme_mod( 'clients_title' );
    $subtitle   = get_theme_mod( 'clients_subtitle', __( 'Have been featured on', 'screenr' ) );
    $desc       = get_theme_mod( 'clients_desc' );
    ?>
    <?php if ( ! screenr_is_selective_refresh() ) { ?>
    <section id="<?php echo esc_attr( get_theme_mod( 'clients_id', 'clients' ) ); ?>" class="section-clients section-padding section-meta screenr-section">
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
                <div class="clients-wrapper client-<?php echo esc_attr( $layout ); ?>-cols">
                    <?php
                    foreach ( $items as $item ){
                        $item = wp_parse_args( $item, array(
                            'title' => '',
                            'image' => '',
                            'url'   => '',
                        ) );

                        $image =  screenr_get_media_url( $item['image'] );
                        if ( ! $image ){
                            continue;
                        }

                        ?>
                        <div class="client-col">
                            <?php if ( $item['url'] ){ ?><a href="<?php echo esc_url( $item['url'] ); ?>"><?php } ?>
                            <img src="<?php echo esc_url( $image ) ?>" alt="">
                            <?php if ( $item['url'] ){ ?></a><?php } ?>
                        </div>
                        <?php

                    }// end loop items
                    ?>
                </div><!-- /.clients-wrapper -->
            </div>
        </div>
    <?php if ( ! screenr_is_selective_refresh() ) { ?>
    </section>
    <?php } ?>
    <?php
    wp_reset_postdata();
} ?>
