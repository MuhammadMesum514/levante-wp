<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Customizer {

	// Get our default values
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
			//self::populated_default_data();
		}

		return self::$instance;
	}

	public function populated_default_data() {
		$this->defaults = rttheme_generate_defaults();
	}

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {
		// Layout Panel
		$wp_customize->add_panel( 'rttheme_layouts_defaults',
			[
				'title'       => esc_html__( 'Layout Settings', 'homlisti' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'homlisti' ),
				'priority'    => 17,
			]
		);
		// Color Panel
		$wp_customize->add_panel( 'rttheme_color_panel',
			[
				'title'       => esc_html__( 'Color', 'homlisti' ),
				'description' => esc_html__( 'Change site color', 'homlisti' ),
				'priority'    => 17,
			]
		);
	}

	/**
	 * Customizer sections
	 */
	public function add_customizer_sections( $wp_customize ) {
		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';
		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';
		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;
		// Add General Section
		$wp_customize->add_section( 'general_section',
			[
				'title'    => esc_html__( 'General', 'homlisti' ),
				'priority' => 10,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'header_main_section',
			[
				'title'    => esc_html__( 'Header', 'homlisti' ),
				'priority' => 11,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'breadcrumb_section',
			[
				'title'    => esc_html__( 'Breadcrumb', 'homlisti' ),
				'priority' => 11,
			]
		);
		// Add Footer Section
		$wp_customize->add_section( 'footer_section',
			[
				'title'    => esc_html__( 'Footer', 'homlisti' ),
				'priority' => 12,
			]
		);
		// Add Color Section
		$wp_customize->add_section( 'site_color_section',
			[
				'title'    => esc_html__( 'Site Color', 'homlisti' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 10,
			]
		);
		$wp_customize->add_section( 'header_color_section',
			[
				'title'    => esc_html__( 'Header Color', 'homlisti' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 12,
			]
		);
		$wp_customize->add_section( 'breadcrumb_color_section',
			[
				'title'    => esc_html__( 'Breadcrumb Color', 'homlisti' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 13,
			]
		);
		$wp_customize->add_section( 'footer_color_section',
			[
				'title'    => esc_html__( 'Footer Color', 'homlisti' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 14,
			]
		);
		// Add Blog Layout Section
		$wp_customize->add_section( 'blog_layout_section',
			[
				'title'    => esc_html__( 'Blog Layout', 'homlisti' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Single Post Layout Section
		$wp_customize->add_section( 'single_post_layout_section',
			[
				'title'    => esc_html__( 'Single Post Layout', 'homlisti' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Pages Layout Section
		$wp_customize->add_section( 'page_layout_section',
			[
				'title'    => esc_html__( 'Pages Layout', 'homlisti' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Error Layout Section
		$wp_customize->add_section( 'error_layout_section',
			[
				'title'    => esc_html__( 'Error Layout', 'homlisti' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'listing_archive_layout_section',
			[
				'title'    => esc_html__( 'Listing Archive Layout', 'homlisti' ),
				'priority' => 20,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'listing_single_layout_section',
			[
				'title'    => esc_html__( 'Listing Single Layout', 'homlisti' ),
				'priority' => 21,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'agent_archive_layout_section',
			[
				'title'    => esc_html__( 'Agent Archive Layout', 'homlisti' ),
				'priority' => 22,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'agent_single_layout_section',
			[
				'title'    => esc_html__( 'Agent Single Layout', 'homlisti' ),
				'priority' => 23,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Store Layout Section
		$wp_customize->add_section( 'store_archive_layout_section',
			[
				'title'    => esc_html__( 'Store Archive Layout', 'homlisti' ),
				'priority' => 30,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// WooCommerce Archive Section
		$wp_customize->add_section( 'woocommerce_archive_layout_section',
			[
				'title'    => esc_html__( 'Woocommerce Archive Layout', 'homlisti' ),
				'priority' => 31,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// WooCommerce Archive Section
		$wp_customize->add_section( 'woocommerce_single_layout_section',
			[
				'title'    => esc_html__( 'Woocommerce Details Layout', 'homlisti' ),
				'priority' => 32,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Blog Archive Section
		$wp_customize->add_section( 'blog_archive_section',
			[
				'title'    => esc_html__( 'Blog Settings', 'homlisti' ),
				'priority' => 15,
			]
		);
		// Add Single Post Section
		$wp_customize->add_section( 'single_post_section',
			[
				'title'    => esc_html__( 'Post Details Settings', 'homlisti' ),
				'priority' => 16,
			]
		);
		// Add Listing Settings Section
		$wp_customize->add_section( 'listings_section',
			[
				'title'    => esc_html__( 'Listing Settings', 'homlisti' ),
				'priority' => 17,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'contact_info_section',
			[
				'title'    => esc_html__( 'Contact & Social', 'homlisti' ),
				'priority' => 17,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'newsletter_section',
			[
				'title'    => esc_html__( 'Newsletter Section', 'homlisti' ),
				'priority' => 17,
			]
		);

		// Contact Info
		$wp_customize->add_section( 'woocommerce_common_section',
			[
				'title'    => esc_html__( 'WooCommerce Common', 'homlisti' ),
				'priority' => 1,
				'panel'    => 'woocommerce',
			]
		);

		// Add Error Page Section
		$wp_customize->add_section( 'error_section',
			[
				'title'    => esc_html__( 'Error Page', 'homlisti' ),
				'priority' => 19,
			]
		);
	}

}
