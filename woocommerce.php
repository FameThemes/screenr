<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Screenr
 */

get_header();
$layout = screenr_get_layout('no');
?>
<div id="content" class="site-content">
    <div id="content-inside" class="container <?php echo esc_attr( screenr_get_layout( 'no') ); ?>-sidebar">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php
                do_action( 'woocommerce_before_main_content' );
                woocommerce_content();
                /**
                 * Hook: woocommerce_after_main_content.
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
                ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php
        if ( $layout != 'no' ) {
            get_sidebar('shop');
        }
        ?>

    </div><!--#content-inside -->
</div><!-- #content -->

<?php get_footer(); ?>
