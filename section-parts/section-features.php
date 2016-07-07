<?php
$layout = absint( get_theme_mod( 'features_layout', 3 ) );
if ( $layout == 0 ){
    $layout = 3;
}
$title      = get_theme_mod( 'features_title' );
$subtitle   = get_theme_mod( 'features_subtitle' );
$desc       = get_theme_mod( 'features_desc' );
$classes    = 'section-features section-padding screenr-section section-padding-empty';
$items      = get_theme_mod( 'features_items' );

if ( ! screenr_is_selective_refresh() ) {
?>
<section id="<?php echo esc_attr( get_theme_mod( 'features_id', 'features' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'features' ) ); ?>">
<?php } ?>
    <?php
    if ( $title || $subtitle || $desc ) {
        ?>
        <div class="container">
            <div class="section-title-area">
                <?php if ( $subtitle ) { ?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
                <?php if ( $title ) { ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
                <?php if ( $desc ) { ?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
            </div>
        </div>
        <?php
    }

    if ( is_array( $items ) && ! empty( $items ) ) {
        ?>
        <div class="features-content features-<?php echo esc_attr($layout); ?>-columns card-group">
            <?php

            global $post;
            $count = 0;
            $number_item = count($items);
            foreach ($items as $item) {
                $item = wp_parse_args($item, array(
                    'page_id' => '',
                    'thumb_type' => 'image',
                    'icon' => '',
                    'svg' => '',
                    'readmore' => '',
                    'readmore_txt' => esc_html__('Read More', 'screenr'),
                    'bg_color' => '',
                ));

                if (!$item['page_id']) {
                    continue;
                }
                $post = get_post($item['page_id']);
                if (!$post) {
                    continue;
                }

                setup_postdata($post);
                $count++;

                $style = '';
                if ($item['bg_color']) {
                    $style = ' style="background-color: #' . esc_attr($item['bg_color']) . '" ';
                }

                ?>
                <div class="features__item card"<?php echo $style; ?>>
                    <?php
                    switch ($item['thumb_type']) {
                        case 'icon':
                            if ($item['icon']) {
                                echo '<div class="features__item-media icon">';
                                echo '<i class="' . esc_attr($item['icon']) . ' fa-7x"></i>';
                                echo '</div>';
                            }
                            break;
                        case 'svg':
                            echo '<div class="features__item-media icon">';
                            echo force_balance_tags($item['svg']);
                            echo '</div>';
                            break;
                        default:
                            echo '<div class="features__item-media">';
                            if (has_post_thumbnail($item['page_id'])) {
                                the_post_thumbnail('post-thumbnail');
                            }
                            echo '</div>';
                    }
                    ?>
                    <div class="features__item-content">
                        <h3><?php echo get_the_title($item['page_id']); ?></h3>
                        <?php echo get_the_excerpt($item['page_id']); ?>
                        <?php if ($item['readmore']) { ?>
                            <div class="features__item-content-button">
                                <a href="<?php the_permalink(); ?>"
                                   class="btn btn-secondary-outline"><?php echo ($item['readmore_txt']) ? force_balance_tags($item['readmore_txt']) : esc_html__('Read More', 'screenr'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php

                if ($count % absint($layout) == 0) {
                    echo '</div><!-- /.features-content  -->' . "\n";
                    echo '<div class="features-content features-' . esc_attr($layout) . '-columns card-group">' . "\n";
                }

            }// end loop items
            ?>
        </div><!-- /.features-content  -->
        <?php
    }
    wp_reset_postdata();
    ?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php }

