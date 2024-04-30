<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\Controls\Customizer_Separator_Control;
use radiustheme\HomListi\Customizer\RDTheme_Customizer;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Contact_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_contact_controls' ] );
	}

	public function register_contact_controls( $wp_customize ) {
		// Address
		$wp_customize->add_setting( 'contact_address',
			[
				'default'           => $this->defaults['contact_address'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( 'contact_address',
			[
				'label'   => __( 'Address', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'textarea',
			]
		);
		// Phone
		$wp_customize->add_setting( 'contact_phone',
			[
				'default'           => $this->defaults['contact_phone'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
		$wp_customize->add_control( 'contact_phone',
			[
				'label'   => __( 'Phone', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'text',
			]
		);
		// Email
		$wp_customize->add_setting( 'contact_email',
			[
				'default'           => $this->defaults['contact_email'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_email',
			]
		);
		$wp_customize->add_control( 'contact_email',
			[
				'label'   => __( 'Email', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'email',
			]
		);
		// Website
		$wp_customize->add_setting( 'contact_website',
			[
				'default'           => $this->defaults['contact_website'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'contact_website',
			[
				'label'   => __( 'Website', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		/**
		 * Separator
		 */
		$wp_customize->add_setting( 'social_separator',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_html',
			] );
		$wp_customize->add_control( new Customizer_Separator_Control( $wp_customize, 'social_separator', [
			'settings' => 'social_separator',
			'section'  => 'contact_info_section',
		] ) );
		// Facebook
		$wp_customize->add_setting( 'facebook',
			[
				'default'           => $this->defaults['facebook'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'facebook',
			[
				'label'   => __( 'Facebook', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Twitter
		$wp_customize->add_setting( 'twitter',
			[
				'default'           => $this->defaults['twitter'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'twitter',
			[
				'label'   => __( 'Twitter', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Instagram
		$wp_customize->add_setting( 'instagram',
			[
				'default'           => $this->defaults['instagram'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'instagram',
			[
				'label'   => __( 'Instagram', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Linkedin
		$wp_customize->add_setting( 'linkedin',
			[
				'default'           => $this->defaults['linkedin'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'linkedin',
			[
				'label'   => __( 'Linkedin', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Youtube
		$wp_customize->add_setting( 'youtube',
			[
				'default'           => $this->defaults['youtube'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'youtube',
			[
				'label'   => __( 'Youtube', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Pinterest
		$wp_customize->add_setting( 'pinterest',
			[
				'default'           => $this->defaults['pinterest'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'pinterest',
			[
				'label'   => __( 'Pinterest', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
		// Skype
		$wp_customize->add_setting( 'skype',
			[
				'default'           => $this->defaults['skype'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'skype',
			[
				'label'   => __( 'Skype', 'homlisti' ),
				'section' => 'contact_info_section',
				'type'    => 'url',
			]
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Contact_Settings();
}
