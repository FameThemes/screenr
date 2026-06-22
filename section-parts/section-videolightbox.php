<?php

$video_url = get_theme_mod( 'videolightbox_video', '#' );
$title = get_theme_mod( 'videolightbox_title', __( 'Parallax & Video Lightbox - Perfected', 'screenr' ) );

/*
 * Build the lightbox link attributes.
 * Priority: self-hosted video (MP4) > image > video URL (YouTube/Vimeo).
 */
$hosted_id    = absint( get_theme_mod( 'videolightbox_hosted_video' ) );
$image_id     = absint( get_theme_mod( 'videolightbox_image' ) );
$hosted_video = $hosted_id ? wp_get_attachment_url( $hosted_id ) : '';
$lightbox_img = $image_id ? wp_get_attachment_url( $image_id ) : '';

if ( $hosted_video ) {
	// lightGallery HTML5 video: empty href + data-html holding the <video>.
	$poster     = get_theme_mod( 'videolightbox_parallax_img' );
	$ftype      = wp_check_filetype( $hosted_video );
	$mime       = ! empty( $ftype['type'] ) ? $ftype['type'] : 'video/mp4';
	$video_html = '<video class="lg-video-object lg-html5" controls preload="none"><source src="' . esc_url( $hosted_video ) . '" type="' . esc_attr( $mime ) . '" /></video>';
	$popup_atts = ' href="" data-html="' . esc_attr( $video_html ) . '"';
	if ( $poster ) {
		$popup_atts .= ' data-poster="' . esc_url( $poster ) . '"';
	}
} elseif ( $lightbox_img ) {
	$popup_atts = ' href="' . esc_url( $lightbox_img ) . '"';
} else {
	$popup_atts = ' href="' . esc_attr( $video_url ) . '" data-scr="' . esc_attr( $video_url ) . '"';
}

if ( ! screenr_is_selective_refresh() ) {
	$parallax_url = get_theme_mod( 'videolightbox_parallax_img' );
	$parallax_url = apply_filters( 'video_parallax_bg', $parallax_url );

	$classes = 'section-videolightbox section-padding section-padding-larger section-inverse onepage-section';
	if ( $parallax_url ) {
		?>
	<div class="parallax-videolightbox section-parallax">
		<?php screenr_parallax_html( $parallax_url ); ?>
	<?php } ?>
	<section id="<?php echo esc_attr( get_theme_mod( 'videolightbox_id', 'video' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'screenr_section_class', $classes, 'videolightbox' ) ); ?>">
<?php } ?>
		<div class="container">
			<?php if ( $title ) { ?>
			<h2 class="videolightbox__heading"><?php echo wp_kses_post( balanceTags( $title ) ); ?></h2>
			<?php } ?>
			<div class="videolightbox__icon videolightbox-popup">
				<a<?php echo $popup_atts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values escaped above. ?> class="popup-video">
					<span class="video_icon"><i class="fa fa-play"></i></span>
				</a>
			</div>

		</div>
<?php if ( ! screenr_is_selective_refresh() ) { ?>
	</section>
	<?php
	if ( $parallax_url ) { ?>
		</div>
		<?php
	}
}
