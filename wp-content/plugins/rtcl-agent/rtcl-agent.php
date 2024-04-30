<?php
/**
 * @wordpress-plugin
 * Plugin Name:     Classified Listing – Real Estate Agent Addon
 * Plugin URI:      https://www.radiustheme.com/downloads/classified-listing-real-estate-agent/
 * Description:     Agent addon for Classified Listing Store
 * Version:         1.0.2
 * Author:          RadiusTheme
 * Author URI:      https://radiustheme.com
 * Text Domain:     rtcl-agent
 * Domain Path:     /languages
 */

defined( 'ABSPATH' ) || die( 'Keep Silent' );

// Define RTCL_PLUGIN_FILE.
define( 'RTCL_AGENT_VERSION', '1.0.2' );
define( 'RTCL_AGENT_FILE', __FILE__ );
define( 'RTCL_AGENT_TEMPLATE_DEBUG_MODE', false );
define( 'RTCL_AGENT_URL', plugins_url( '', RTCL_AGENT_FILE ) );

include_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! is_plugin_active( 'classified-listing-store/classified-listing-store.php' ) ) {
	function rtcl_agent_required_notice() {
		?>
        <div id="message" class="error">
            <p><?php _e( 'Please install and activate Classified Listing Store to use Agent for Classified listing plugin.', 'rtcl-agent' ); ?></p>
        </div>
		<?php
	}

	add_action( 'admin_notices', 'rtcl_agent_required_notice' );

	return;
}

if ( class_exists( 'Rtcl' ) ) {
	require_once 'inc/init.php';
}
