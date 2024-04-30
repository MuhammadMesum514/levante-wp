<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\Controls\Customizer_Separator_Control;
use radiustheme\HomListi\Customizer\RDTheme_Customizer;
use radiustheme\HomListi\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\HomListi\Customizer\Controls\Customizer_Custom_Heading;
use WP_Customize_Media_Control;
use radiustheme\HomListi\Helper;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Breadcrumb_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_general_controls' ] );
	}

	public function register_general_controls( $wp_customize ) {
		$postype = Helper::get_post_types();

		// Breadcrumb
		$wp_customize->add_setting( 'breadcrumb',
			[
				'default'           => $this->defaults['breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'breadcrumb',
			[
				'label'   => __( 'Breadcrumb Visibility', 'homlisti' ),
				'section' => 'breadcrumb_section',
			]
		) );

		// Breadcrumb
		$wp_customize->add_setting( 'breadcrumb_style',
			[
				'default'           => $this->defaults['breadcrumb_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'breadcrumb_style', [
			'type'    => 'select',
			'section' => 'breadcrumb_section',
			'label'   => esc_html__( 'Breadcrumb', 'homlisti' ),
			'choices' => [
				'style-1' => esc_html__( 'Style 1', 'homlisti' ),
				'style-2' => esc_html__( 'Style 2', 'homlisti' ),
			],
		] );

		// Breadcrumb
		$wp_customize->add_setting( 'breadcrumb_title',
			[
				'default'           => $this->defaults['breadcrumb_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'breadcrumb_title', [
			'type'    => 'select',
			'section' => 'breadcrumb_section',
			'label'   => esc_html__( 'Breadcrumb Title Visibility', 'homlisti' ),
			'choices' => [
				'homlisti-page-title' => esc_html__( 'Visible', 'homlisti' ),
				'screen-reader-text'  => esc_html__( 'Hidden', 'homlisti' ),
				'disable'             => esc_html__( 'Disable', 'homlisti' ),
			],
		] );

		// Breadcrumb Image
		$wp_customize->add_setting( 'banner_image',
			[
				'default'           => $this->defaults['banner_image'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'banner_image',
			[
				'label'         => esc_html__( 'Banner/Breadcrumb Background Image', 'homlisti' ),
				'description'   => esc_html__( 'Add image to change banner background image', 'homlisti' ),
				'section'       => 'breadcrumb_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select File', 'homlisti' ),
					'change'       => esc_html__( 'Change File', 'homlisti' ),
					'default'      => esc_html__( 'Default', 'homlisti' ),
					'remove'       => esc_html__( 'Remove', 'homlisti' ),
					'placeholder'  => esc_html__( 'No file selected', 'homlisti' ),
					'frame_title'  => esc_html__( 'Select File', 'homlisti' ),
					'frame_button' => esc_html__( 'Choose File', 'homlisti' ),
				],
			]
		) );


		//Archive page title
		$wp_customize->add_setting( 'archive_page_title_heading',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_html',
			] );

		$wp_customize->add_control( new Customizer_Custom_Heading( $wp_customize, 'archive_page_title_heading', [
			'label'    => esc_html__( 'Archive page Title', 'homlisti' ),
			'settings' => 'archive_page_title_heading',
			'section'  => 'breadcrumb_section',
		] ) );

		foreach ( $postype as $post_id => $post_title ) {

			$post_type_id = str_replace( ' ', '_', $post_id );
			//Archive page title
			$wp_customize->add_setting( $post_type_id . '_archive',
				[
					'default'           => '',
					'transport'         => 'refresh',
					'sanitize_callback' => 'rttheme_text_sanitization',
				]
			);
			$wp_customize->add_control( $post_type_id . '_archive',
				[
					'label'   => __( $post_title . ' Archive Text', 'homlisti' ),
					'section' => 'breadcrumb_section',
					'type'    => 'text',
				]
			);
		}
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Breadcrumb_Settings();
}
