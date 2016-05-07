<?php
$items = get_theme_mod( 'services_items' );

if ( is_array( $items ) && ! empty( $items ) ) {
    $title      = get_theme_mod( 'services_title', __( 'Our Services', 'screenr' ) );
    $subtitle   = get_theme_mod( 'services_subtitle',__( 'Section subtitle', 'screenr' ) );
    $desc       = get_theme_mod( 'services_desc' );
    $layout     = absint( get_theme_mod( 'services_layout', 2 ) );
    if ( $layout == 0 ){
        $layout = 2;
    }
    ?>
    <?php if ( ! screenr_is_selective_refresh() ) { ?>
    <section id="<?php echo esc_attr(get_theme_mod('services_id', 'services')); ?>" class="section-services section-padding section-meta screenr-section">
    <?php } ?>
        <div class="container">
            <?php if (  $title || $subtitle || $desc ) { ?>
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo wp_kses_post( $desc ); ?></div><?php } ?>
            </div>
            <?php } ?>
            <div class="section-content services-content">

                <div class="row">
                    <?php
                    global $post;
                    $count = 0;
                    $classes = '';
                    switch( $layout ){
                        case 1:
                            $classes = 'col-sm-12';
                            break;
                        case 2:
                            $classes = 'col-sm-6';
                            break;
                        case 3:
                            $classes = 'col-sm-4';
                            break;
                        case 4:
                            $classes = 'col-sm-3';
                            break;
                        default:
                            $classes = 'col-sm-6';
                    }

                    foreach ( $items as $item ){
                        $item = wp_parse_args( $item, array(
                            'page_id'    => '',
                            'thumb_type' => '',
                            'thumb_type' => '',
                            'icon'       => '',
                            'readmore'   => '',
                        ) );

                        if ( ! $item['page_id'] ) {
                            continue;
                        }
                        $post = get_post( $item['page_id'] );
                        if ( ! $post ) {
                            continue;
                        }
                        $count++;
                        setup_postdata( $post );

                    ?>
                    <div class="<?php echo esc_attr( $classes ); ?>">
                        <?php
                        switch( $item['thumb_type'] ) {

                            case 'image_overlay':
                                ?>
                                <div class="card card-inverse service__media">
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail('post-thumbnail', array('class' => 'card-img', 'width' => '', 'height' => ''));
                                    }
                                    ?>
                                    <div class="card-img-overlay">
                                        <div class="service-card-content">
                                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <div class="card-text"><?php the_excerpt(); ?></div>
                                        </div>
                                        <?php if ( $item['readmore'] ){ ?>
                                            <a href="<?php the_permalink(); ?>" class="service-button"><?php esc_html_e( 'More detail &rarr;', 'screenr' ); ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'icon':
                                ?>
                                <div class="card card-block service__media-icon">
                                    <div class="service-card-content">
                                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="card-text"><?php the_excerpt(); ?></div>
                                    </div>
                                    <?php if ( $item['icon'] ){ ?>
                                    <div class="service-card-icon">
                                        <i class="fa <?php echo 'fa-'.esc_attr( $item['icon'] ); ?> fa-3x" aria-hidden="true"></i>
                                    </div>
                                    <?php } ?>
                                    <?php if ( $item['readmore'] ){ ?>
                                        <a href="<?php the_permalink(); ?>" class="service-button"><?php esc_html_e( 'More detail &rarr;', 'screenr' ); ?></a>
                                    <?php } ?>
                                </div>
                                <?php
                                break;
                            case 'no_thumb':
                                ?>
                                <div class="card card-block">
                                    <div class="service-card-content">
                                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="card-text"><?php the_excerpt(); ?></p>
                                    </div>
                                </div>
                                <?php
                                break;

                            default: // image_top
                                ?>
                                <div class="card service__media top">
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail('post-thumbnail', array('class' => 'card-img-top', 'width' => '', 'height' => ''));
                                    }
                                    ?>
                                    <div class="card-ig-overlay card-block">
                                        <div class="service-card-content">
                                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <div class="card-text"><?php the_excerpt(); ?></div>
                                        </div>
                                        <?php if ( $item['readmore'] ){ ?>
                                            <a href="<?php the_permalink(); ?>" class="service-button"><?php esc_html_e( 'More detail &rarr;', 'screenr' ); ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                                break;
                        } // end switch case.
                        ?>
                    </div>
                    <?php
                        if ( $count % $layout == 0 ) {
                            echo '</div><!-- /.row  -->'."\n";
                            echo '<div class="row">'."\n";
                        }
                    } // end loop items ?>

                </div>

            </div>
        </div>
    <?php if ( ! screenr_is_selective_refresh() ) { ?>
    </section>
    <?php } ?>
    <?php
}
wp_reset_postdata();
?>