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
$layout = get_theme_mod( 'shop_layout_settings', 'no' );
?>
<div id="content" class="site-content">
    <div id="content-inside" class="container <?php echo esc_attr( get_theme_mod( 'shop_layout_settings', 'no' ) ); ?>-sidebar">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <?php
                woocommerce_content();
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
