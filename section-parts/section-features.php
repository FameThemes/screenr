<?php
$items = get_theme_mod( 'features_items' );

if ( is_array( $items ) && ! empty( $items ) ) {
    $layout = absint( get_theme_mod( 'features_layout', 3 ) );
    if ( $layout == 0 ){
        $layout = 3;
    }
    ?>
    <section id="<?php echo esc_attr( get_theme_mod( 'features_id', 'features' ) ); ?>" class="section-features section-padding screenr-section section-padding-empty">
        <div class="features-content features-<?php echo esc_attr( $layout ); ?>-columns card-group">
        <?php
        global $post;
        $count = 0;
        $number_item =  count( $items );
        foreach ( $items as $item ){
            $item = wp_parse_args( $item, array(
                'page_id' => '',
                'thumb_type' => 'image',
                'icon' => '',
                'svg' => '',
                'readmore' => '',
                'bg_color' => '',
            ) );

            if ( ! $item['page_id'] ) {
                continue;
            }
            $post =  get_post( $item['page_id'] );
            if ( ! $post ) {
                continue;
            }

            setup_postdata( $post );
            $count ++;

            $style = '';
            if ( $item['bg_color'] ){
                $style = ' style="background-color: #'.esc_attr( $item['bg_color'] ).'" ';
            }

            ?>
            <div class="features__item card"<?php echo $style; ?>>
                <?php
                switch ( $item['thumb_type'] ) {
                    case 'icon':
                        echo '<div class="features__item-media icon">';
                        if ( ! $item['icon'] ) {
                            $item['icon'] = 'briefcase';
                        }
                        $item['icon'] = 'fa-'.$item['icon'];
                        echo '<i class="fa '.esc_attr( $item['icon'] ).' fa-7x"></i>';
                        echo '</div>';
                        break;
                    case 'svg':
                        echo '<div class="features__item-media icon">';
                        echo force_balance_tags( $item['svg'] );
                        echo '</div>';
                        break;
                    default:
                        echo '<div class="features__item-media">';
                        if ( has_post_thumbnail( $item['page_id'] ) ) {
                            the_post_thumbnail( 'post-thumbnail' );
                        }
                        echo '</div>';
                }
                ?>
                <div class="features__item-content">
                    <h3><?php echo get_the_title( $item['page_id'] ); ?></h3>
                    <?php echo get_the_excerpt( $item['page_id'] ); ?>
                    <?php if ( $item['readmore'] ){ ?>
                    <div class="features__item-content-button">
                        <a href="<?php the_permalink(); ?>" class="btn btn-secondary-outline"><?php esc_html_e( 'Read More', 'screenr' ); ?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php

        if ( $count % absint( $layout ) == 0 ) {
            echo '</div><!-- /.features-content  -->'."\n";
            echo '<div class="features-content features-' . esc_attr($layout) . '-columns card-group">'."\n";
        }

        }// end loop items
        ?>
        </div><!-- /.features-content  -->

    </section>
<?php
wp_reset_postdata();
} ?>