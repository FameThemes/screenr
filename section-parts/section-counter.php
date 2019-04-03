<?php
$title      = get_theme_mod( 'counter_title' );
$subtitle   = get_theme_mod( 'counter_subtitle', esc_html__( 'Some Fun Facts about our agency?', 'screenr' ) );
$desc       = get_theme_mod( 'counter_desc' );
$id         = get_theme_mod( 'counter_id', 'counter' );
$layout     = absint( get_theme_mod( 'counter_layout', 3 ) );
$classes    = 'screenr-section section-counter section-padding section-padding-lg section-meta';

if ( ! screenr_is_selective_refresh() ) {
	?>
<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'counter' ) ); ?>">
<?php } ?>
	<div class="container">
		<?php if ( $subtitle || $title || $desc ) { ?>
		<div class="section-title-area">
			<?php if ( $subtitle ) {
				?><div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div><?php } ?>
			<?php if ( $title ) {
				?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php } ?>
			<?php if ( $desc ) {
				?><div class="section-desc"><?php echo apply_filters( 'screenr_content_text', $desc ); ?></div><?php } ?>
		</div>
		<?php } ?>
		<?php
		$items      = get_theme_mod( 'counter_items' );
		if ( ! empty( $items ) ) {
			?>
			<div class="counter-contents" data-layout="<?php echo esc_attr( $layout ); ?>">
				<div class="section-content">
					<div class="row">
						<?php
						foreach ( (array) $items as $item ) {
							$item = wp_parse_args(
								$item,
								array(
									'title' => '',
									'number' => '',
									'icon' => '',
									'bg_color' => '',
									'before_number' => '',
									'after_number' => '',
									'style' => '',
								)
							);

							?>
							<div class="col-sm-12 col-md-6 col-lg-<?php echo esc_attr( $layout ); ?>">
								<div
									class="counter-item counter-item-<?php echo esc_attr( $item['style'] ); ?>" <?php if ( $item['bg_color'] ) {
										?> style="background-color: #<?php echo esc_attr( str_replace( '#', '', $item['bg_color'] ) ); ?>" <?php } ?>>
									<span class="counter-title"><?php echo esc_html( $item['title'] ); ?></span>

									<div class="counter__number">
										<?php if ( $item['before_number'] ) { ?>
											<span class="before-number"><?php echo esc_html( $item['before_number'] ); ?></span>
										<?php } ?>
										<span class="n counter"><?php echo floatval( $item['number'] ); ?></span>
										<?php if ( $item['after_number'] ) { ?>
											<span class="after-number"><?php echo esc_html( $item['after_number'] ); ?></span>
										<?php } ?>
									</div>
									<?php if ( $item['icon'] ) { ?>
										<i class="<?php echo esc_attr( $item['icon'] ); ?> fa-3x"></i>
									<?php } ?>

								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php } ?>
