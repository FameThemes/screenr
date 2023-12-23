<?php

namespace Screenr\GoogleFonts\Downloader;

require_once dirname(__FILE__) . '/customize-control.php';

function maybe_check_download_fonts()
{
	$settings = get_download_settings();
	if ($settings['download']) {
		add_filter('style_loader_src', __NAMESPACE__ . '\maybe_change_google_fonts_src', 10, 2);
	}
}
add_action('wp',  __NAMESPACE__ . '\maybe_check_download_fonts');


function maybe_change_google_fonts_src($src, $handle = null)
{
	if (false !== strpos($src, '//fonts.googleapis.com/css')) {
		$src = download_google_fonts($src);
	}
	return $src;
}


function get_download_settings()
{
	
	$option_name = 'google_font_settings';

	if (isset($GLOBALS[$option_name])) {
		return  $GLOBALS[$option_name];
	}

	$saved_settings = get_theme_mod($option_name);
	if (!is_array($saved_settings)) {
		$saved_settings = json_decode($saved_settings);
	}
	$saved_settings = wp_parse_args($saved_settings, [
		'disable' => '',
		'download' => '',
	]);
	$GLOBALS[$option_name] = $saved_settings;
	return $saved_settings;
}

/**
 * get file system.
 *
 * @return $wp_filesystem
 */
function get_file_system()
{
	global $wp_filesystem;

	if (!function_exists('\WP_Filesystem')) {
		include ABSPATH . 'wp-admin/includes/file.php';
	}

	\WP_Filesystem();
	return $wp_filesystem;
}


/**
 * Download google font to local server.
 *
 * @since 2.3.6
 *
 * @param [string] $font_link
 * @return string new Font link.
 */
function download_google_fonts($font_link)
{

	$check_url = parse_url($font_link);
	// Make sure the link must from google font.
	if (!in_array($check_url['host'], ['fonts.gstatic.com', 'fonts.googleapis.com'])) {
		return $font_link;
	}
	
	$saved_settings = get_download_settings();
	if ( $saved_settings['disable'] ) {
		return false;
	}

	$theme_name = get_stylesheet();

	$force =  false;
	// Check if force download.
	if (isset($_GET['force_download_fonts'])) {
		if (current_user_can('edit_theme_options')) {
			$force = true;
		}
	}

	$dir = WP_CONTENT_DIR . '/uploads/google-fonts/' . $theme_name;
	$dir_url = WP_CONTENT_URL . '/uploads/google-fonts/' . $theme_name;

	$download_types = [
		'woff2' => true,
		'woff' => false,
		'ttf' => true,
		'eot' => false,
		'svg' => false,
	];

	
	foreach ($download_types as $format => $default) {
		if ('woff2' != $format) {
			$download_types[$format] = isset($saved_settings[$format]) ? $saved_settings[$format] : $default;
		}
	}

	$key = md5($font_link . json_encode($download_types));
	$css_file = $theme_name . '-' . $key . '.css';
	$css_file_path = $dir . '/' . $css_file;
	$css_file_url = $dir_url . '/' . $css_file;


	// Skip if in the customize preview.
	if (function_exists('is_customize_preview')) {
		if (is_customize_preview()) {
			if (file_exists($css_file_path)) {
				return $css_file_url;
			}
			return $font_link;
		}
	}

	// If file is already existing.
	if (!$force && file_exists($css_file_path)) {
		return $css_file_url;
	}

	$fs = get_file_system();
	wp_mkdir_p($dir . '/fonts');

	$user_agents = array(
		'eot'   => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)',
		'ttf'   => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; de-at) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
		'svg'   => 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10gin_lib.cc',
		'woff'  => 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25',
		'woff2' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
	);

	$count = 0;
	$font_css_code = '';
	foreach ($download_types as $format => $download_this) {

		if (!$download_this) {
			continue;
		}

		$agent =  isset($user_agents[$format]) ? $user_agents[$format] : false;
		if (!$agent) {
			continue;
		}

		$res = wp_remote_get(
			$font_link,
			array(
				'timeout' => 60,
				'user-agent' => $agent
			)
		);

		$css_code = wp_remote_retrieve_body($res);
		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $css_code, $match);

		$urls = $match[0];
		if (is_dir($dir)) {
			foreach ($urls as $file_url) {
				if (strpos($file_url, 'l/font?kit=') && strpos($file_url, '#')) {
					$pathinfo = wp_parse_url($file_url);
					$args = wp_parse_args($pathinfo['query'], ['v' => '', 'kit' => '']);
					$name = $pathinfo['fragment'];
					$name = $pathinfo['fragment'] . '-' . $args['v'] . '-' . $args['kit'] . '.svg';
				} else {
					$pathinfo = pathinfo($file_url);
					$name = str_replace(['http://fonts.gstatic.com/s/', 'https://fonts.gstatic.com/s/'], '', $pathinfo['dirname']);
					$name = str_replace('/', '-', $name) . '-' . $pathinfo['basename'];
				}

				$name = 'fonts/' . $name;

				$local_file_url = $dir_url . '/' . $name;
				$local_file_path = $dir . '/' . $name;
				$count++;
				$css_code = str_replace($file_url, $name, $css_code);
				if (!file_exists($local_file_path)) {
					$res = wp_remote_get($file_url);
					$body = wp_remote_retrieve_body($res);
					$fs->put_contents($local_file_path, $body);
				}
			}
			$font_css_code = $css_code . "\n" .	$font_css_code;
		}
	}

	$fs->put_contents($css_file_path, $font_css_code);
	return $css_file_url;
}

function ajax_download_google_fonts()
{

	$doing = isset($_REQUEST['doing']) ? sanitize_text_field($_REQUEST['doing']) :  false;
	$data = array(
		'message' => esc_html__('Something wrong please try again.', 'screenr'),
		'settings' => get_download_settings(),
	);
	if (!current_user_can('edit_theme_options')) {
		wp_send_json_error($data, 200);
		die();
	} else {
		$fs = get_file_system();
		$theme_name = get_stylesheet();
		$dir = WP_CONTENT_DIR . '/uploads/google-fonts/' . $theme_name;
		$fs->delete($dir, true);
		if ($doing  == 'clear') {
			$data['message'] = esc_html__('Google font files deleted.', 'screenr');
		} else {
			wp_remote_get(home_url('/?force_download_fonts'));
			$data['message'] = esc_html__('Google font files downloaded.', 'screenr');
		}
		wp_send_json_success($data);
	}
	die();
}

function ajax_change_js_font_url()
{
	$url = isset($_POST['url']) ? sanitize_text_field($_POST['url']) : false;
	$verify  = wp_verify_nonce($_POST['nonce'], get_stylesheet() . '_change_js_font_url');
	if (!$verify) {
		wp_send_json([
			'url' => $url,
			'success' => false,
		]);
		die();
	}

	$new_link = download_google_fonts($url);

	wp_send_json([
		'new_url' => $new_link,
		'url' => $url,
		'success' => true,
	]);
	die();
}
add_action('wp_ajax_' . get_stylesheet() . '_download_google_fonts', __NAMESPACE__ . '\ajax_download_google_fonts');
add_action('wp_ajax_' . get_stylesheet() . '_change_js_font_url', __NAMESPACE__ . '\ajax_change_js_font_url');
add_action('wp_ajax_nopriv_' . get_stylesheet() . '_change_js_font_url', __NAMESPACE__ . '\ajax_change_js_font_url');



/**
 * Change all google fonts added by js.
 * 
 * @since 2.3.1
 *
 * @return void
 */
function maybe_change_download_from_js_append()
{

	$settings = get_download_settings();
	if (!$settings['download']) {
		return;
	}
?>
	<script>
		(function() {
			const config = <?php echo json_encode([
								'ajax_url' => admin_url(add_query_arg([
									'action' => get_stylesheet() . '_change_js_font_url',
								], 'admin-ajax.php')),
								'nonce' => wp_create_nonce(get_stylesheet() . '_change_js_font_url'),
							]) ?>;
			const headTagJs = document.getElementsByTagName('head')[0];
			// Save the original method
			const doInsertBefore = headTagJs.insertBefore;
			// Replace it!
			headTagJs.insertBefore = function(newElement, referenceElement) {

				if (!newElement.href) {
					doInsertBefore.call(headTagJs, newElement, referenceElement);
					return;
				}
				
				const url = new URL(newElement.href);
				if (!url.hostname.includes('fonts.googleapis.com') && !url.hostname.includes('fonts.gstatic.com')) {
					doInsertBefore.call(headTagJs, newElement, referenceElement);
					return;
				}

				if (window.fetch) {
					const form = new FormData();
					form.set('url', newElement.href)
					form.set('nonce', config.nonce)
					fetch(config.ajax_url, {
							method: 'POST',
							body: form
						})
						.then((response) => response.text())
						.then((body) => {
							const data = JSON.parse(body);
							if (data.success && data?.new_url) {
								newElement.href = data.new_url;
								doInsertBefore.call(headTagJs, newElement, referenceElement);
							}
						})
						.catch((error) => console.error('change_google_font_error', error));
					return;
				}
			};
		})();
	</script>
<?php
}
add_action('wp_head', __NAMESPACE__ . '\maybe_change_download_from_js_append', 0);
