<?php

// Change number product to show
function screenr_number_products_to_show(){

    $n = absint( get_theme_mod( 'shop_number_product', 20 ) );
    if ( ! $n ) {
        $n = 20;
    }
    return $n;
}
add_filter( 'loop_shop_per_page','screenr_number_products_to_show', 20 );

function screenr_shop_change_page_layout( $value ){
    if ( is_cart() || is_checkout() || is_account_page() ) {
        return 'no';
    }
    return $value;
}

add_filter( 'theme_mod_layout_settings', 'screenr_shop_change_page_layout' );

if ( ! function_exists( 'woocommerce_content' ) ) {

    /**
     * Output WooCommerce content.
     *
     * This function is only used in the optional 'woocommerce.php' template.
     * which people can add to their themes to add basic woocommerce support.
     * without hooks or modifying core templates.
     *
     */
    function woocommerce_content() {

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();

                wc_get_template_part( 'content', 'single-product' );

            endwhile;

        } else { ?>


            <?php if ( have_posts() ) : ?>

                <?php do_action( 'woocommerce_before_shop_loop' ); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php woocommerce_product_subcategories(); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action( 'woocommerce_after_shop_loop' ); ?>

            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                <?php do_action( 'woocommerce_no_products_found' ); ?>

            <?php endif;

        }
    }
}

/**
 *  Remove star on loop product
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * Remove cross sell and up-sell in page page
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );