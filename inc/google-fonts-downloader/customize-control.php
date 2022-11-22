<?php

namespace Screenr\GoogleFonts\Downloader;

if (class_exists('WP_Customize_Control')) {

	/**
	 * Typography control class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	class Customize_Control extends \WP_Customize_Control
	{

		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'google-fonts-downloader';

		/**
		 * Array
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $l10n = array();

		/**
		 * Set up our control.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object $manager
		 * @param  string $id
		 * @param  array  $args
		 * @return void
		 */
		public function __construct($manager, $id, $args = array())
		{

			// Let the parent class do its thing.
			parent::__construct($manager, $id, $args);

			// Make sure we have labels.
			$this->l10n = wp_parse_args(
				$this->l10n,
				array(
					'download'          => esc_html__('Download', 'screenr'),
					'downloading'          => esc_html__('Downloading', 'screenr'),
					'warning'          => esc_html__('Please save settings before do this action!', 'screenr'),

				)
			);
		}

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function to_json()
		{
			parent::to_json();

			$control_values  = $this->value();
			$control_values  = json_decode($control_values, true);
			if (!is_array($control_values)) {
				$control_values = [];
			}

			$download_types = [
				'woff2' => 1,
				'woff' => false,
				'ttf' => 1,
				'eot' => false,
				'svg' => false,
			];

			$type_labels = [];
			foreach ($download_types as $format => $default) {
				$disabled = 'woff2' == $format;
				$value = isset($control_values[$format]) ? absint($control_values[$format]) : $default;
				if ($disabled) {
					$value = 1;
				}
				$type_labels[] = [
					'default' => $default,
					'name' => $format,
					'value' => isset($control_values[$format]) ? absint($control_values[$format]) : $value,
					'disable' => $disabled,
					'label' => sprintf('.%s', $format)
				];
			}

			// Loop through each of the settings and set up the data for it.
			// $this->json['value']         = is_array( $this->value() ) ?  json_encode( $this->value() ) :  $this->value() ;
			$this->json['value']        =  (object) $control_values;
			$this->json['labels']       = $this->l10n;
			$this->json['font_types']   =  $type_labels;
			$this->json['ajax_action']   =  get_stylesheet() . '_download_google_fonts';
		}


		/**
		 * Get url of any dir
		 *
		 * @param string $file full path of current file in that dir
		 * @return string
		 */
		public static function get_url()
		{
			return get_template_directory_uri().'/inc/google-fonts-downloader';
		}


		/**
		 * Enqueue scripts/styles.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue()
		{
			$uri = $this->get_url();
			$theme = get_stylesheet();
			$prefix_id = $theme . '-gfd-customize-control';
			wp_register_script($prefix_id, esc_url($uri . '/customize.js'), array('customize-controls'));
			wp_enqueue_script($prefix_id);
		}

		/**
		 * Underscore JS template to handle the control's output.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function content_template()
		{

?>
			<div class="google-font-wrap">

				<div class="google-font-header">
					<# if ( data.label ) { #>
						<span class="customize-control-title">{{ data.label }}</span>
						<# } #>

							<# if ( data.description ) { #>
								<span class="description customize-control-description">{{{ data.description }}}</span>
								<# } #>
				</div>

				<div class="google-font-settings customize-control-checkbox">

					<span class="customize-inside-control-row">
						<input id="{{data.settings.default}}-check-dl" type="checkbox" data-name="disable" value="1" <# if( data.value?.disable===1 ) { #> checked="checked" <# } #> />
							<label for="{{data.settings.default}}-check-dl"><?php _e('Disable google fonts.', 'screenr'); ?></label>
					</span>

					<span class="customize-inside-control-row">
						<input id="{{data.settings.default}}-check-dl" type="checkbox" data-name="download" value="1" <# if( data.value?.download===1 ) { #> checked="checked" <# } #> />
							<label for="{{data.settings.default}}-check-dl"><?php _e('Check this box to download google fonts to your server.', 'screenr'); ?></label>
					</span>

					<div><?php _e('Download font types:', 'screenr'); ?></div>
					<# for( let i=0; i< data.font_types.length; i ++ ) { const item=data.font_types[i]; const itemId=data.settings.default; #>
						<span class="customize-inside-control-row">
							<input id="{{itemId}}-format-{{item.name}}" <# if( item.disable ) { #> disabled="disabled" <# } #>
								<# if( item.value===1 ) { #> checked="checked" <# } #>
										type="checkbox" data-name="{{ item.name }}" value="1">
										<label for="{{itemId}}-format-{{item.name}}">{{{ item.label }}}</label>
						</span>
						<# } #>
				</div>

				<p class="gfd-actions">
					<button class="download button-secondary"><?php esc_html_e('Download font files', 'screenr'); ?></button>
					<button class="clear button-secondary"><?php esc_html_e('Clear', 'screenr'); ?></button>
				</p>

			</div>
<?php
		}
	}
}
