<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Screenr
 */

?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
		$footer_columns = absint( get_theme_mod( 'footer_layout' , 4 ) );
		if ( $footer_columns > 0 ) { ?>
			<div class="footer-widgets section-padding">
				<div class="container">
					<div class="row">
						<?php
						$max_cols = 12;
						$layouts = 12;
						if ( $footer_columns > 1 ){
							$default = "12";
							switch ( $footer_columns ) {
								case 4:
									$default = '3+3+3+3';
									break;
								case 3:
									$default = '4+4+4';
									break;
								case 2:
									$default = '6+6';
									break;
							}
							$layouts = get_theme_mod( 'footer_custom_'.$footer_columns.'_columns', $default );
						}

						$layouts = explode( '+', $layouts );
						foreach ( $layouts as $k => $v ) {
							$v = absint( trim( $v ) );
							$v =  $v >= $max_cols ? $max_cols : $v;
							$layouts[ $k ] = $v;
						}

						for ( $count = 0; $count < $footer_columns; $count++ ) {
							$col = isset( $layouts[ $count ] ) ? $layouts[ $count ] : '';
							if ( $col ) {
								?>
								<div id="footer-<?php echo esc_attr( $count + 1 ) ?>" class="col-md-<?php echo esc_attr( $col ); ?> col-sm-12 footer-column widget-area sidebar" role="complementary">
									<?php dynamic_sidebar('footer-' . ( $count + 1 ) ); ?>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		<?php }  ?>

		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'screenr' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'screenr' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'screenr' ), 'screenr', '<a href="https://www.famethemes.com" rel="designer">FameThemes</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
