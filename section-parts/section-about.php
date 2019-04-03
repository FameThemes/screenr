<?php
global $post;
$title      = get_theme_mod( 'about_title', esc_html__( 'About us', 'screenr' ) );
$subtitle   = get_theme_mod( 'about_subtitle' );
$desc       = get_theme_mod( 'about_desc', esc_html__( 'We provide creative solutions that gets the attention of our global clients.', 'screenr' ) );
$classes = 'screenr-section section-about section-padding section-padding-larger';
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod( 'about_id', 'about' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'about' ) ); ?>">
<?php } ?>
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<?php if ( $title || $subtitle || $desc ) { ?>
				<div class="section-title-area">
					<?php if ( $subtitle ) {
						?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
					<?php if ( $title ) {
						?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
					<?php if ( $desc ) {
						?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
				</div>
				<?php } ?>
			</div>

			<?php
			$page_id = get_theme_mod( 'about_page_id' );
			if ( $page_id && ( $post = get_post( $page_id ) ) ) {
				setup_postdata( $post );
				?>
				<div class="col-md-7">
					<div class="section-about-content">
						<?php
						if ( get_theme_mod( 'about_page_content_type' ) == 'content' ) {
							the_content();
						} else {
							the_excerpt();
						}
						?>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>

		</div>
	</div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
