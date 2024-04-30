<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */


$theme_info = wp_get_theme();
$theme_info = $theme_info->parent() ? $theme_info->parent() : $theme_info;
$theme_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $theme_info->get('Name'))));
update_option( 'rt_licenses', [ $theme_name . '_license_key' => '*********' ] );

if ( ! isset( $content_width ) ) {
	$content_width = 1240;
}

class HomListi_Main {

	public $theme = 'homlisti';
	public $action = 'homlisti_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
		$this->includes();
	}

	public function load_textdomain() {
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes() {
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/helper.php';
		require_once get_template_directory() . '/inc/includes.php';

		do_action( $this->action );
	}

}

new HomListi_Main;


function rt_rtcl_get_currency_symbols_cb($symbols) {
    $symbols['AED'] = 'AED'; // AED is the currency code for UAE Dirham, and we're changing it to UAE
    return $symbols;
}

add_filter('rtcl_get_currency_symbols', 'rt_rtcl_get_currency_symbols_cb');







