<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\HomListi\Customizer\RDTheme_Customizer;
use Rtcl\Helpers\Functions;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listings_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		$group_list = $this->custom_field_group_list();


		// Single Listing Layout
		$wp_customize->add_setting(
			'single_listing_style',
			[
				'default'           => $this->defaults['single_listing_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'single_listing_style',
			[
				'label'       => esc_html__( 'Property Details Style', 'homlisti' ),
				'section'     => 'listings_section',
				'description' => esc_html__( 'Select property details page style', 'homlisti' ),
				'type'        => 'select',
				'choices'     => [
					'1' => esc_html__( 'Slider Layout (Default)', 'homlisti' ),
					'2' => esc_html__( 'Full Width Image', 'homlisti' ),
					'3' => esc_html__( 'Grid Layout Image', 'homlisti' ),

				],
			]
		);

		// Single Listing Layout
		$wp_customize->add_setting(
			'listing_price_style',
			[
				'default'           => $this->defaults['listing_price_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_price_style',
			[
				'label'       => esc_html__( 'Listing Price Style', 'homlisti' ),
				'section'     => 'listings_section',
				'description' => esc_html__( 'Change listing full price to (K, M, B, T) OR (K, Lac, Cr)', 'homlisti' ),
				'type'        => 'select',
				'choices'     => [
					'full'      => esc_html__( 'Show Full Price', 'homlisti' ),
					'short'     => esc_html__( 'Show Short form (K, M, B, T etc)', 'homlisti' ),
					'short-lac' => esc_html__( 'Show Short form (K, Lac, Cr etc)', 'homlisti' ),
				],
			]
		);

		// WalkScore Title
		$wp_customize->add_setting(
			'listing_price_precision',
			[
				'default'           => $this->defaults['listing_price_precision'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_price_precision',
			[
				'label'       => esc_html__( 'Price precision', 'homlisti' ),
				'description' => esc_html__( 'Enter Price precision. Default value is 3. This field will work if you choose short form price.', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'number',
			]
		);

		// Custom Field Group List
		$wp_customize->add_setting(
			'custom_group_individual',
			[
				'default'           => $this->defaults['custom_group_individual'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual',
			[
				'label'       => esc_html__( 'Custom Field Group As Overview', 'homlisti' ),
				'section'     => 'listings_section',
				'description' => esc_html__( 'Select a custom field group to show on the top section of listing details page', 'homlisti' ),
				'type'        => 'select',
				'choices'     => $group_list,
			]
		);
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'listing_detail_sidebar',
			[
				'default'           => $this->defaults['listing_detail_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'listing_detail_sidebar',
				[
					'label'   => esc_html__( 'Listing Sidebar Visibility', 'homlisti' ),
					'section' => 'listings_section',
				]
			) );

		// Enable WalkScore
		$wp_customize->add_setting(
			'walkscore_control',
			[
				'default'           => $this->defaults['walkscore_control'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'walkscore_control',
				[
					'label'   => esc_html__( 'WalkScore Visibility', 'homlisti' ),
					'section' => 'listings_section',
				]
			) );
		// WalkScore Title
		$wp_customize->add_setting(
			'walkscore_title',
			[
				'default'           => $this->defaults['walkscore_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'walkscore_title',
			[
				'label'       => esc_html__( 'WalkScore Title', 'homlisti' ),
				'description' => esc_html__( 'Add title for walkscore section', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		// WalkScore API
		$wp_customize->add_setting(
			'walkscore_api_key',
			[
				'default'           => $this->defaults['walkscore_api_key'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'walkscore_api_key',
			[
				'label'       => esc_html__( 'WalkScore API Key', 'homlisti' ),
				'description' => esc_html__( 'Add API Key provided from walkscore', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);
		// Overview Visibility
		$wp_customize->add_setting(
			'overview_show_hide',
			[
				'default'           => $this->defaults['overview_show_hide'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'overview_show_hide',
				[
					'label'   => esc_html__( 'Overview Visibility', 'homlisti' ),
					'section' => 'listings_section',
				]
			) );

		//Overview Title
		$wp_customize->add_setting(
			'overview_text',
			[
				'default'           => $this->defaults['overview_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'overview_text',
			[
				'label'       => esc_html__( 'Overview Label', 'homlisti' ),
				'description' => esc_html__( 'You may change Overview title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		//About this listing title
		$wp_customize->add_setting(
			'about_this_listing',
			[
				'default'           => $this->defaults['about_this_listing'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'about_this_listing',
			[
				'label'       => esc_html__( 'About This Listing Label', 'homlisti' ),
				'description' => esc_html__( 'You may change About This Listing title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		//Map Location title
		$wp_customize->add_setting(
			'map_location_label',
			[
				'default'           => $this->defaults['map_location_label'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'map_location_label',
			[
				'label'       => esc_html__( 'Map Location Label', 'homlisti' ),
				'description' => esc_html__( 'You may change Map Location title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		//Property Video title
		$wp_customize->add_setting(
			'property_video_label',
			[
				'default'           => $this->defaults['property_video_label'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'property_video_label',
			[
				'label'       => esc_html__( 'Property Video Label', 'homlisti' ),
				'description' => esc_html__( 'You may change Property Video title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		//3D Virtual Tour Title
		$wp_customize->add_setting(
			'virtual_tour_label',
			[
				'default'           => $this->defaults['virtual_tour_label'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'virtual_tour_label',
			[
				'label'       => esc_html__( '3D Virtual Tour Label', 'homlisti' ),
				'description' => esc_html__( 'You may change Virtual Tour title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);


		// Features & Amenities Visibility
		$wp_customize->add_setting(
			'feature_aminities_show_hide',
			[
				'default'           => $this->defaults['feature_aminities_show_hide'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'feature_aminities_show_hide',
				[
					'label'   => esc_html__( 'Features & Amenities Visibility', 'homlisti' ),
					'section' => 'listings_section',
				]
			) );

		//Features & Amenities Title
		$wp_customize->add_setting(
			'feature_text',
			[
				'default'           => $this->defaults['feature_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'feature_text',
			[
				'label'       => esc_html__( 'Features & Amenities text', 'homlisti' ),
				'description' => esc_html__( 'You may change Features & Amenities title from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);


		// Remove Listing Type Prefix
		$wp_customize->add_setting(
			'remove_listing_type_prefix',
			[
				'default'           => $this->defaults['remove_listing_type_prefix'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'remove_listing_type_prefix',
				[
					'label'       => esc_html__( 'Remove Listing Type Prefix (Eg. For)', 'homlisti' ),
					'description' => esc_html__( 'Check for hiding listing type prefix (For) from everywhere.', 'homlisti' ),
					'section'     => 'listings_section',
				]
			) );

		//Features & Amenities Title
		$wp_customize->add_setting(
			'listing_type_prefix_text',
			[
				'default'           => $this->defaults['listing_type_prefix_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_type_prefix_text',
			[
				'label'       => esc_html__( 'Listing type prefix text', 'homlisti' ),
				'description' => esc_html__( 'You may change Listing type prefix text from here', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		// Show Floating Menu
		$wp_customize->add_setting(
			'show_floating_menu',
			[
				'default'           => $this->defaults['show_floating_menu'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_floating_menu',
				[
					'label'       => esc_html__( 'Show Floating Side Menu', 'homlisti' ),
					'description' => esc_html__( 'Check for showing listing details floating menu ', 'homlisti' ),
					'section'     => 'listings_section',
				]
			) );

		// Show Button Area
		$wp_customize->add_setting(
			'show_listing_button_area',
			[
				'default'           => $this->defaults['show_listing_button_area'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_button_area',
				[
					'label'       => esc_html__( 'Show Button Area', 'homlisti' ),
					'description' => esc_html__( 'Show or hide button area from single listing page header', 'homlisti' ),
					'section'     => 'listings_section',
				]
			) );


		// Show Button Area
		$wp_customize->add_setting(
			'show_related_listing',
			[
				'default'           => $this->defaults['show_related_listing'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing',
				[
					'label'       => esc_html__( 'Show Related Listing', 'homlisti' ),
					'description' => esc_html__( 'Show or hide related listing from listing details page', 'homlisti' ),
					'section'     => 'listings_section',
				]
			) );

		// Show Button Area
		$wp_customize->add_setting(
			'show_listing_custom_fields',
			[
				'default'           => $this->defaults['show_listing_custom_fields'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_custom_fields',
				[
					'label'       => esc_html__( 'Show Listing Custom Field', 'homlisti' ),
					'description' => esc_html__( 'Show or hide listing custom fields from listing archive page', 'homlisti' ),
					'section'     => 'listings_section',
				]
			) );

		// Show Store Info on details page
		$wp_customize->add_setting(
			'show_user_info_on_details',
			[
				'default'           => $this->defaults['show_user_info_on_details'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'show_user_info_on_details',
			[
				'label'       => esc_html__( 'Select Owner/Store info', 'homlisti' ),
				'section'     => 'listings_section',
				'description' => esc_html__( 'Show Store or Owner info on Details page', 'homlisti' ),
				'type'        => 'select',
				'choices'     => [
					'show_owner_info' => esc_html__( 'Show Owner Info', 'homlisti' ),
					'show_store_info' => esc_html__( 'Show Store Info', 'homlisti' ),
				],
			]
		);

		//Listing owner title text
		$wp_customize->add_setting(
			'listing_owner_widget_title',
			[
				'default'           => $this->defaults['listing_owner_widget_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_owner_widget_title',
			[
				'label'       => esc_html__( 'Listing owner widget title', 'homlisti' ),
				'description' => esc_html__( 'You may change Listing widget title', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);


		//Listing Min Price
		$wp_customize->add_setting(
			'listing_widget_min_price',
			[
				'default'           => $this->defaults['listing_widget_min_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_min_price',
			[
				'label'       => esc_html__( 'Listing min price ', 'homlisti' ),
				'description' => esc_html__( 'This settings for listing map search width', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);
		//Listing Max Price
		$wp_customize->add_setting(
			'listing_widget_max_price',
			[
				'default'           => $this->defaults['listing_widget_max_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_max_price',
			[
				'label'       => esc_html__( 'Listing max price ', 'homlisti' ),
				'description' => esc_html__( 'This settings for listing map search width', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);

		//Listing Max Price
		$wp_customize->add_setting(
			'listing_excerpt_limit',
			[
				'default'           => $this->defaults['listing_excerpt_limit'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_excerpt_limit',
			[
				'label'       => esc_html__( 'Listing Excerpt Limit', 'homlisti' ),
				'description' => esc_html__( 'Enter Listing Excerpt Limit', 'homlisti' ),
				'section'     => 'listings_section',
				'type'        => 'text',
			]
		);
	}

	public function custom_field_group_list() {
		$group_ids = Functions::get_cfg_ids();

		$list           = [];
		$list['select'] = __( "--Select--" );
		foreach ( $group_ids as $id ) {
			$list[ $id ] = get_the_title( $id );
		}

		return $list;
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listings_Settings();
}
