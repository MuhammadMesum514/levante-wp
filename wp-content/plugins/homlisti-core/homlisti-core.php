<?php
/*
Plugin Name: HomListi Core
Plugin URI: https://www.radiustheme.com
Description: HomListi Theme Core Plugin
Version: 1.6.17
Author: RadiusTheme
Author URI: https://www.radiustheme.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\HomListi\Listing_Functions;
use Rtcl\Helpers\Functions;
use radiustheme\HomListi\RDTheme;

if ( ! defined( 'HOMLIST_CORE' ) ) {
	define( 'HOMLIST_CORE', '1.6.17' );
	define( 'HOMLIST_CORE_THEME_PREFIX', 'homlisti' );
	define( 'HOMLIST_CORE_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'HOMLIST_CORE_BASE_DIR', plugin_dir_path( __FILE__ ) );
}

require_once HOMLIST_CORE_BASE_DIR . 'demo-users/user-importer.php';

class HomListi_Core {

	public $plugin = 'homlisti-core';
	public $action = 'homlisti_theme_init';
	protected static $instance;

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'demo_importer' ], 17 );
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 20 );
		add_action( $this->action, [ $this, 'after_theme_loaded' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_script' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'homlisti_walkscore', [ $this, 'show_walkscore' ] );
		if ( isset( $_GET['export_user'] ) && $_GET['export_user'] == 1 ) {
			Homlisti_Core_Demo_User_Import::export_users();
		}
	}

	public function show_walkscore( $location ) {
		$title   = RDTheme::$options['walkscore_title'];
		$api_key = RDTheme::$options['walkscore_api_key'];

		$html = '<div class="product-walk widget" id="walk_score">
                <div class="item-heading">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="heading-title">' . esc_html( $title ) . '</h3>
                        </div>
                    </div>
                </div>
                <div id="ws-walkscore-tile"></div>
                <script type="text/javascript">
                    var ws_wsid = "' . esc_attr( $api_key ) . '";
                    var ws_address = "' . $location . '";
                    var ws_format = "tall";
                    var ws_width = "100%";
                    var ws_height = "350";
                </script>
                <script type="text/javascript" src="https://www.walkscore.com/tile/show-walkscore-tile.php"></script>
            </div>';
		printf( "%s", $html );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function after_theme_loaded() {
		require_once HOMLIST_CORE_BASE_DIR . 'lib/sidebar-generator/init.php'; // Sidebar generator
		require_once HOMLIST_CORE_BASE_DIR . 'lib/wp-svg/init.php'; // SVG support
		require_once HOMLIST_CORE_BASE_DIR . 'inc/shortcode.php'; // Shortcode
		require_once HOMLIST_CORE_BASE_DIR . 'inc/hooks.php'; // Hooks
		require_once HOMLIST_CORE_BASE_DIR . 'widgets/rt-contact.php'; // Contact Widget
		require_once HOMLIST_CORE_BASE_DIR . 'widgets/mortgage-calculator.php'; // Mortgage Calculator
		require_once HOMLIST_CORE_BASE_DIR . 'optimization/__init__.php'; // Mortgage Calculator
		if ( class_exists( 'Rtcl' ) && class_exists( 'RtclPro' ) ) {
			require_once HOMLIST_CORE_BASE_DIR . 'inc/Api/init.php'; // Api integration
		}

		if ( class_exists( 'Rtcl' ) && Listing_Functions::is_enable_yelp_review() ) {
			require_once HOMLIST_CORE_BASE_DIR . 'inc/yelp-review.php'; // Core Function
		}

		if ( defined( 'RT_FRAMEWORK_VERSION' ) ) {
			require_once HOMLIST_CORE_BASE_DIR . 'inc/post-meta.php'; // Post Meta
			require_once HOMLIST_CORE_BASE_DIR . 'widgets/init.php'; // Widgets
		}

		if ( did_action( 'elementor/loaded' ) ) {
			require_once HOMLIST_CORE_BASE_DIR . 'elementor/init.php'; // Elementor
		}
	}

	public function demo_importer() {
		require_once HOMLIST_CORE_BASE_DIR . 'inc/demo-importer.php';
	}

	public function register_script() {
		wp_register_script( 'pannellum', HOMLIST_CORE_BASE_URL . 'assets/js/pannellum.js', '', '2.5.6', true );
		wp_register_style( 'pannellum', HOMLIST_CORE_BASE_URL . 'assets/css/pannellum.css', '', '2.5.6' );
		wp_register_script( 'tweenmax', HOMLIST_CORE_BASE_URL . 'assets/js/tween-max.js', '', '1.20.2', true );
	}

	public function enqueue_script() {
		if ( class_exists( 'Rtcl' ) && Functions::is_listing() ) {
			wp_enqueue_style( 'pannellum' );
			wp_enqueue_script( 'pannellum' );
		}
		wp_enqueue_script( 'tweenmax' );
	}

	public function load_textdomain() {
		load_plugin_textdomain( $this->plugin, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public static function social_share( $sharer = [] ) {
		include HOMLIST_CORE_BASE_DIR . 'inc/social-share.php';
	}

}

HomListi_Core::instance();