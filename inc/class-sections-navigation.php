<?php
/**
 * Dots Navigation class
 * Class Screenr_Dots_Navigation
 *
 * @since 2.1.0
 */
class Screenr_Dots_Navigation {
	static $_instance = null;
	private $key = 'screenr_sections_nav_';

	/**
	 * Get instance
	 *
	 * @return null|Screenr_Dots_Navigation
	 */
	static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	function get_sections() {

		$sorted_sections = $sections = apply_filters(
			'screenr_frontpage_sections_order',
			array(
				'slider',
				'features',
				'about',
				'videolightbox',
				'gallery',
				'services',
				'clients',
				'counter',
				'news',
				'contact',
			)
		);

		$sections_config = array(
			'slider' => array(
				'label' => __( 'Section: Hero', 'screenr' ),
				'title' => __( 'Home', 'screenr' ),
				'default' => false,
				'inverse' => false,
				'id' => 'hero', // default id.
			),
			'features' => array(
				'label' => __( 'Section: Features', 'screenr' ),
				'title' => __( 'Features', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),
			'about' => array(
				'label' => __( 'Section: About', 'screenr' ),
				'title' => __( 'About Us', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),
			'videolightbox' => array(
				'label' => __( 'Section: Video Lightbox', 'screenr' ),
				'title' => '',
				'default' => false,
				'inverse' => false,
			),
			'gallery' => array(
				'label' => __( 'Section: Gallery', 'screenr' ),
				'title' => __( 'Gallery', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),
			'services' => array(
				'label' => __( 'Section: Services', 'screenr' ),
				'title' => __( 'Our Services', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),
			'clients' => array(
				'label' => __( 'Section: Clients', 'screenr' ),
				'title' => __( 'Our Clients', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),
			'counter' => array(
				'label' => __( 'Section: Counter', 'screenr' ),
				'title' => __( 'Our Numbers', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),

			'news' => array(
				'label' => __( 'Section: News', 'screenr' ),
				'title' => __( 'Latest News', 'screenr' ),
				'default' => false,
				'inverse' => false,
			),

			'contact' => array(
				'label' => __( 'Section: Contact', 'screenr' ),
				'title' => __( 'Get in touch', 'screenr' ),
				'default' => false,
				'inverse' => false,

			),

		);

		$new = array(
			'slider' => $sections_config['slider'],
		);

		foreach ( $sorted_sections as $id ) {
			if ( isset( $sections_config[ $id ] ) ) {
				$new[ $id ] = $sections_config[ $id ];
			}
		}
		// Filter to add more custom sections here
		return apply_filters( 'screenr_sections_navigation_get_sections', $new );

	}

	/**
	 * Get setting name
	 *
	 * @param $id
	 *
	 * @return string
	 */
	function get_name( $id ) {
		return $this->key . $id;
	}

	/**
	 * Add customize config
	 *
	 * @param $wp_customize
	 * @param $section_id
	 */
	function add_customize( $wp_customize, $section_id ) {

		$wp_customize->add_setting(
			$this->get_name( '__enable' ),
			array(
				'sanitize_callback' => 'screenr_sanitize_text',
				'default'           => false,
			)
		);
		$wp_customize->add_control(
			$this->get_name( '__enable' ),
			array(
				'label'       => __( 'Enable in section navigation', 'screenr' ),
				'section'     => $section_id,
				'type'        => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			$this->get_name( '__enable_label' ),
			array(
				'sanitize_callback' => 'screenr_sanitize_text',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			$this->get_name( '__enable_label' ),
			array(
				'label'       => __( 'Enable navigation labels', 'screenr' ),
				'description'       => __( 'By default navigation label is section title.', 'screenr' ),
				'section'     => $section_id,
				'type'        => 'checkbox',
			)
		);

		// Color Settings
		$wp_customize->add_setting(
			$this->get_name( '__color' ),
			array(
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default'              => '',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$this->get_name( '__color' ),
				array(
					'label'       => esc_html__( 'Dots color', 'screenr' ),
					'section'     => $section_id,
					'description' => '',
				)
			)
		);

		// Color Settings
		$wp_customize->add_setting(
			$this->get_name( '__color2' ),
			array(
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default'              => '',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$this->get_name( '__color2' ),
				array(
					'label'       => esc_html__( 'Dots inverse color', 'screenr' ),
					'section'     => $section_id,
					'description' => '',
				)
			)
		);

		// Section Settings
		foreach ( $this->get_sections() as $id => $args ) {

			$name = $this->get_name( $id );

			$wp_customize->add_setting(
				$id . '_em',
				array(
					'sanitize_callback' => 'screenr_sanitize_text',
				)
			);
			$wp_customize->add_control(
				new Screenr_Group_Settings_Heading_Control(
					$wp_customize,
					$id . '_em',
					array(
						'type'        => 'group_heading',
						'section'     => $section_id,
						'title'       => esc_html( $args['label'] ),
					)
				)
			);

			$wp_customize->add_setting(
				$name,
				array(
					'sanitize_callback' => 'screenr_sanitize_checkbox',
					'default'           => $args['default'],
					// 'transport'         => 'postMessage'
				)
			);
			$wp_customize->add_control(
				$name,
				array(
					'label'       => __( 'Enable in section navigation', 'screenr' ),
					'section'     => $section_id,
					'type'        => 'checkbox',
				)
			);

			$wp_customize->add_setting(
				$name . '_inverse',
				array(
					'sanitize_callback' => 'screenr_sanitize_checkbox',
					'default'           => isset( $args['inverse'] ) ? $args['inverse'] : false,
					// 'transport'         => 'postMessage'
				)
			);
			$wp_customize->add_control(
				$name . '_inverse',
				array(
					'label'       => __( 'Inverse dots color', 'screenr' ),
					'section'     => $section_id,
					'type'        => 'checkbox',
				)
			);

			$wp_customize->add_setting(
				$name . '_label',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '',
					// 'transport'         => 'postMessage'
				)
			);
			$wp_customize->add_control(
				$name . '_label',
				array(
					'label'       => __( 'Custom navigation label', 'screenr' ),
					'section'     => $section_id,
				)
			);

		}

	}

	/**
	 *
	 * Get sections settings
	 *
	 * @return array
	 */
	function get_settings() {

		$data = apply_filters( 'screenr_dots_navigation_get_settings', false );
		if ( $data ) {
			return $data;
		}

		$data = array();
		$sections = $this->get_sections();
		foreach ( $sections as $id => $args ) {

			if ( ! get_theme_mod( $id . '_disable', false )
				 || ( isset( $args['show_section'] ) && $args['show_section'] )
			) {
				$name   = $this->get_name( $id );
				$enable = get_theme_mod( $name, $args['default'] );
				if ( $enable ) {
					$default_id = isset( $args['id'] ) && $args['id'] ? $args['id'] : $id;
					$el_id = sanitize_text_field( get_theme_mod( $id . '_id', $default_id, false ) );

					if ( ! $el_id ) {
						$el_id = $default_id;
					}

					if ( ! $el_id ) {
						$el_id = $id;
					}

					$data[ $el_id ] = array(
						'id'     => $el_id,
						'o_id' => $id,
						'inverse' => get_theme_mod( $this->get_name( $id . '_inverse' ), isset( $args['inverse'] ) ? $args['inverse'] : false ),
						'enable' => get_theme_mod( $name, $args['default'] ),
						'title'  => get_theme_mod( $id . '_title', $args['title'] ),
					);
					$custom_title = get_theme_mod( $this->get_name( $id . '_label' ), false );
					if ( $custom_title ) {
						$data[ $el_id ]['title'] = $custom_title;
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Add scripts
	 * load only enabled
	 */
	function scripts() {
		if ( get_theme_mod( $this->get_name( '__enable' ), false ) ) {
			if ( is_front_page() ) {
				wp_enqueue_script( 'jquery.bully', get_template_directory_uri() . '/assets/js/jquery.bully.js', array( 'jquery' ), false, true );
				wp_localize_script(
					'jquery.bully',
					'Screenr_Bully',
					array(
						'enable_label' => get_theme_mod( $this->get_name( '__enable_label' ), true ) ? true : false,
						'sections' => $this->get_settings(),
					)
				);
			}
		}
	}

	/**
	 * Add custom style
	 * load only enabled
	 *
	 * @param $code
	 *
	 * @return string
	 */
	function custom_style( $code = false ) {
		if ( get_theme_mod( $this->get_name( '__enable' ), false ) ) {
			$color = sanitize_hex_color_no_hash( get_theme_mod( $this->get_name( '__color' ) ) );
			if ( $color ) {
				$code .= " body .c-bully { color: #{$color}; } ";
			}

			$color2 = sanitize_hex_color_no_hash( get_theme_mod( $this->get_name( '__color2' ) ) );
			if ( $color2 ) {
				$code .= " body .c-bully.c-bully--inversed { color: #{$color2}; } ";
			}
			if ( is_customize_preview() ) {
				// die( 'loadmoe' );
			}
		}

		return $code;
	}

	/**
	 * Init
	 */
	function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_filter( 'screenr_custom_style', array( $this, 'custom_style' ) );
	}

}

Screenr_Dots_Navigation::get_instance()->init();
