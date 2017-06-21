<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Screenr
 */

$layout = esc_attr( get_theme_mod( 'shop_layout_settings', 'no' ) );
if ( 'no' == $layout ) {
    return ;
}

if ( ! is_active_sidebar( 'sidebar-shop' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-shop' ); ?>
</div><!-- #secondary -->
