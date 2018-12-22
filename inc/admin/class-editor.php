<?php
/**
 * Support Gutenberg Editor.
 *
 * @since 1.2.0
 */
class Screenr_Editor {
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'admin_assets' ) );
	}
	public function admin_assets() {
		wp_enqueue_style( 'screenr-admin-editor-fonts', screenr_fonts_url() );

		$heading_typography = get_theme_mod( 'screenr_typo_heading' );
		$p_typography = get_theme_mod( 'screenr_typo_p' );

		$heading_css = $this->generate_code( json_decode( $heading_typography, true ) );
		$p_css = $this->generate_code( json_decode( $p_typography, true ) );
		$css = '';
		if ( '' != $heading_css ) {
			$css .= '
			.wp-admin.block-editor-page .editor-styles-wrapper h1,
			.wp-admin.block-editor-page .editor-styles-wrapper h2,
			.wp-admin.block-editor-page .editor-styles-wrapper h3,
			.wp-admin.block-editor-page .editor-styles-wrapper h4,
			.wp-admin.block-editor-page .editor-styles-wrapper h5,
			.wp-admin.block-editor-page .editor-styles-wrapper h6{
				' . $heading_css . ';
			}';
		}

		if ( '' != $p_css ) {
			$css .= '
			.wp-admin.block-editor-page .editor-styles-wrapper{
				' . $p_css . ';
			}';
		}
		wp_add_inline_style( 'screenr-admin-editor-fonts', $css );
	}
	public function generate_code( $settings ) {
		$code = '';
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $k => $v ) {
				if ( '' != $v ) {
					$code .= $k . ':' . $v . ';';
				}
			}
		}
		return $code;
	}

	public function generate_typo_css( $typo_settings, $css_selector ) {
		$data = wp_parse_args(
			$typo_settings,
			array(
				'font-family'     => '',
				'color'           => '',
				'font-style'      => '',
				'font-weight'     => '',
				'font-size'       => '',
				'line-height'     => '',
				'letter-spacing'  => '',
				'text-transform'  => '',
				'text-decoration' => '',
			)
		);

		return $data;
	}
}
if ( is_admin() ) {
	new Screenr_Editor();
}
