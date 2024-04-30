<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\RDTheme_Customizer;
use radiustheme\HomListi\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\HomListi\Helper;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Archive_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listing_archive_layout_controls' ] );
	}

	public function register_listing_archive_layout_controls( $wp_customize ) {
		// Layout
		$wp_customize->add_setting( 'listing_archive_layout',
			[
				'default'           => $this->defaults['listing_archive_layout'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'listing_archive_layout',
			[
				'label'       => esc_html__( 'Layout', 'homlisti' ),
				'description' => esc_html__( 'Select the default template layout for listing archive', 'homlisti' ),
				'section'     => 'listing_archive_layout_section',
				'choices'     => [
					'left-sidebar'  => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-left.png',
						'name'  => esc_html__( 'Left Sidebar', 'homlisti' ),
					],
					'full-width'    => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-full.png',
						'name'  => esc_html__( 'Full Width', 'homlisti' ),
					],
					'right-sidebar' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-right.png',
						'name'  => esc_html__( 'Right Sidebar', 'homlisti' ),
					],
				],
			]
		) );
		// Sidebar
		$wp_customize->add_setting( 'listing_archive_sidebar',
			[
				'default'           => $this->defaults['listing_archive_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_sidebar', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Custom Sidebar', 'homlisti' ),
			'choices' => Helper::custom_sidebar_fields(),
		] );
		// Top bar
		$wp_customize->add_setting( 'listing_archive_top_bar',
			[
				'default'           => $this->defaults['listing_archive_top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_top_bar', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Top Bar', 'homlisti' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'homlisti' ),
				'on'      => esc_html__( 'Enable', 'homlisti' ),
				'off'     => esc_html__( 'Disable', 'homlisti' ),
			],
		] );
		// Header Layout
		$wp_customize->add_setting( 'listing_archive_header_style',
			[
				'default'           => $this->defaults['listing_archive_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_header_style', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Header Layout', 'homlisti' ),
			'choices' => Helper::get_homlisti_header_list(),
		] );

		//Menu Alignment
		$wp_customize->add_setting( 'listing_archive_menu_alignment', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['menu_alignment'],
		] );

		$wp_customize->add_control( 'listing_archive_menu_alignment', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section', // Add a default or your own section
			'label'   => __( 'Menu Alignment', 'homlisti' ),
			'choices' => [
				'default'     => __( 'Default', 'homlisti' ),
				'menu-left'   => __( 'Left Alignment', 'homlisti' ),
				'menu-center' => __( 'Center Alignment', 'homlisti' ),
				'menu-right'  => __( 'Right Alignment', 'homlisti' ),
			],
		] );

		//Header width
		$wp_customize->add_setting( 'listing_archive_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['listing_archive_header_width'],
		] );

		$wp_customize->add_control( 'listing_archive_header_width', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'homlisti' ),
			'choices' => [
				'default'   => __( 'Default', 'homlisti' ),
				'box-width' => __( 'Box width', 'homlisti' ),
				'fullwidth' => __( 'Fullwidth', 'homlisti' ),
			],
		] );

		// Transparent Header
		$wp_customize->add_setting( 'listing_archive_tr_header',
			[
				'default'           => $this->defaults['listing_archive_tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_tr_header', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Transparent Header', 'homlisti' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'homlisti' ),
				'on'      => esc_html__( 'Enable', 'homlisti' ),
				'off'     => esc_html__( 'Disable', 'homlisti' ),
			],
		] );
		// Breadcrumb
		$wp_customize->add_setting( 'listing_archive_breadcrumb',
			[
				'default'           => $this->defaults['listing_archive_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_breadcrumb', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'homlisti' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'homlisti' ),
				'on'      => esc_html__( 'Enable', 'homlisti' ),
				'off'     => esc_html__( 'Disable', 'homlisti' ),
			],
		] );
		// Padding Top
		$wp_customize->add_setting( 'listing_archive_padding_top',
			[
				'default'           => $this->defaults['listing_archive_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_padding_top',
			[
				'label'       => esc_html__( 'Content Padding Top', 'homlisti' ),
				'description' => esc_html__( 'Listing Archive Content Padding Top ', 'homlisti' ),
				'section'     => 'listing_archive_layout_section',
				'type'        => 'text',
			]
		);
		// Padding Bottom
		$wp_customize->add_setting( 'listing_archive_padding_bottom',
			[
				'default'           => $this->defaults['listing_archive_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_padding_bottom',
			[
				'label'       => esc_html__( 'Content Padding Bottom', 'homlisti' ),
				'description' => esc_html__( 'Listing Archive Content Padding Bottom', 'homlisti' ),
				'section'     => 'listing_archive_layout_section',
				'type'        => 'text',
			]
		);
		// Footer Layout
		$wp_customize->add_setting( 'listing_archive_footer_style',
			[
				'default'           => $this->defaults['listing_archive_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_footer_style', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Footer Layout', 'homlisti' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'homlisti' ),
				'1'       => esc_html__( 'Layout 1', 'homlisti' ),
				'2'       => esc_html__( 'Layout 2', 'homlisti' ),
			],
		] );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Archive_Layout_Settings();
}
