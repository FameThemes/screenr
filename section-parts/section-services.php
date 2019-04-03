<?php

$title      = get_theme_mod( 'services_title', esc_html__( 'Our Services', 'screenr' ) );
$subtitle   = get_theme_mod( 'services_subtitle', esc_html__( 'Section subtitle', 'screenr' ) );
$desc       = get_theme_mod( 'services_desc' );
$items = get_theme_mod( 'services_items' );
$layout     = absint( get_theme_mod( 'services_layout', 2 ) );
if ( $layout == 0 ) {
	$layout = 2;
}

$classes = 'section-services section-padding-lg section-meta screenr-section';
?>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
<section id="<?php echo esc_attr( get_theme_mod( 'services_id', 'services' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'services' ) ); ?>">
<?php } ?>
	<div class="container">
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
		<?php if ( is_array( $items ) && ! empty( $items ) ) { ?>
			<div class="section-content services-content">
				<div class="row">
					<?php
					global $post;
					$count = 0;
					$classes = '';
					switch ( $layout ) {
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

					foreach ( $items as $item ) {
						$item = wp_parse_args(
							$item,
							array(
								'page_id' => '',
								'thumb_type' => '',
								'thumb_type' => '',
								'icon' => '',
								'readmore' => '',
								'readmore_txt' => '',
							)
						);

						if ( ! $item['page_id'] ) {
							continue;
						}
						$post = get_post( $item['page_id'] );
						if ( ! $post ) {
							continue;
						}
						$count++;
						setup_postdata( $post );

						$text = $item['readmore_txt'] ? force_balance_tags( $item['readmore_txt'] ) : esc_html__( 'More detail &rarr;', 'screenr' );

						?>
						<div class="<?php echo esc_attr( $classes ); ?>">
							<?php
							switch ( $item['thumb_type'] ) {
								case 'icon':
									?>
									<div class="card card-block service__media-icon">
										<div class="service-card-content">
											<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

											<div class="card-text"><?php the_excerpt(); ?></div>
										</div>
										<?php if ( $item['icon'] ) { ?>
											<div class="service-card-icon">
												<i class="<?php echo esc_attr( $item['icon'] ); ?> fa-3x" aria-hidden="true"></i>
											</div>
										<?php } ?>
										<?php if ( $item['readmore'] ) {
											?>
											<a href="<?php the_permalink(); ?>" class="service-button"><?php echo $text; ?></a>
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
											<?php if ( $item['readmore'] ) {
												?>
												<a href="<?php the_permalink(); ?>" class="service-button"><?php echo $text; ?></a>
											<?php } ?>
										</div>
									</div>
									<?php
									break;

								default: // image_top
									?>
									<div class="card service__media top">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'screenr-service-small' );
										}
										?>
										<div class="card-ig-overlay card-block">
											<div class="service-card-content">
												<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

												<div class="card-text"><?php the_excerpt(); ?></div>
											</div>
											<?php if ( $item['readmore'] ) { ?>
												<a href="<?php the_permalink(); ?>" class="service-button"><?php echo $text; ?></a>
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
							echo '</div><!-- /.row  -->' . "\n";
							echo '<div class="row">' . "\n";
						}
					} // end loop items ?>

				</div>
			</div><!-- /.section-content -->
			<?php
}
		wp_reset_postdata();
?>
	</div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
</section>
<?php }
