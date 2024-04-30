<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

class TGM_Config {

	public $base;
	public $path;

	public function __construct() {
		$this->base = 'homlisti';
		$this->path = Constants::$theme_plugins_dir;

		add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
	}

	public function register_required_plugins() {
		$plugins = [
			// Bundled
			[
				'name'     => 'HomListi Core',
				'slug'     => 'homlisti-core',
				'source'   => 'homlisti-core.zip',
				'required' => true,
				'version'  => '1.6.17',
			],
			[
				'name'     => 'RT Framework',
				'slug'     => 'rt-framework',
				'source'   => 'rt-framework.zip',
				'required' => true,
				'version'  => '2.10',
			],
			[
				'name'     => 'RT Demo Importer',
				'slug'     => 'rt-demo-importer',
				'source'   => 'rt-demo-importer.zip',
				'required' => false,
				'version'  => '6.0.0',
			],
			[
				'name'     => 'Classified Listing Pro',
				'slug'     => 'classified-listing-pro',
				'source'   => 'classified-listing-pro.zip',
				'required' => true,
				'version'  => '2.1.15',
			],
			[
				'name'     => 'Classified Listing – Classified ads & Business Directory Plugin',
				'slug'     => 'classified-listing',
				'required' => true,
			],
			[
				'name'     => 'Classified Listing Store',
				'slug'     => 'classified-listing-store',
				'source'   => 'classified-listing-store.zip',
				'required' => true,
				'version'  => '1.4.30',
			],
			[
				'name'     => 'Classified Listing – Real Estate Agent Addon',
				'slug'     => 'rtcl-agent',
				'source'   => 'rtcl-agent.zip',
				'required' => true,
				'version'  => '1.0.2',
			],
			[
				'name'     => 'Review Schema Pro',
				'slug'     => 'review-schema-pro',
				'source'   => 'review-schema-pro.zip',
				'required' => false,
				'version'  => '1.1.2',
			],

			// Repository
			[
				'name'     => 'WP Fluent Forms',
				'slug'     => 'fluentform',
				'required' => false,
			],
			[
				'name'     => 'Elementor Website Builder',
				'slug'     => 'elementor',
				'required' => true,
			],
			[
				'name'     => 'Review Schema',
				'slug'     => 'review-schema',
				'required' => false,
			],
			[
				'name'     => 'MC4WP: Mailchimp for WordPress',
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			],
		];

		$config = [
			'id'           => $this->base,            // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path,              // Default absolute path to bundled plugins.
			'menu'         => $this->base . '-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                    // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}

}

new TGM_Config;