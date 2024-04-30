<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\RDTheme_Customizer;
use radiustheme\HomListi\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\HomListi\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\HomListi\Helper;
use WP_Customize_Color_Control;
use radiustheme\HomListi\Customizer\Controls\Customizer_Alfa_Color;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Header_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_header_controls' ] );
	}

	public function register_header_controls( $wp_customize ) {
		// Header Style
		$wp_customize->add_setting( 'header_style',
			[
				'default'           => $this->defaults['header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'header_style',
			[
				'label'       => __( 'Header Layout', 'homlisti' ),
				'description' => esc_html__( 'Select the header style', 'homlisti' ),
				'section'     => 'header_main_section',
				'choices'     => Helper::get_homlisti_header_list('header'),
			]
		) );

		//Menu Alignment
		$wp_customize->add_setting( 'menu_alignment', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['menu_alignment'],
		] );

		$wp_customize->add_control( 'menu_alignment', [
			'type'    => 'select',
			'section' => 'header_main_section', // Add a default or your own section
			'label'   => __( 'Menu Alignment', 'homlisti' ),
			'choices' => [
				'menu-left'   => __( 'Left Alignment', 'homlisti' ),
				'menu-center' => __( 'Center Alignment', 'homlisti' ),
				'menu-right'  => __( 'Right Alignment', 'homlisti' ),
			],
		] );


		//Header width
		$wp_customize->add_setting( 'header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['header_width'],
		] );

		$wp_customize->add_control( 'header_width', [
			'type'    => 'select',
			'section' => 'header_main_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'homlisti' ),
			'choices' => [
				'box-width' => __( 'Box width', 'homlisti' ),
				'fullwidth' => __( 'Fullwidth', 'homlisti' ),
			],
		] );

		// Top bar
		$wp_customize->add_setting( 'top_bar',
			[
				'default'           => $this->defaults['top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'top_bar',
			[
				'label'   => __( 'Top Bar', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );

		// Sticky Header Control
		$wp_customize->add_setting( 'sticky_header',
			[
				'default'           => $this->defaults['sticky_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'sticky_header',
			[
				'label'       => __( 'Sticky Header', 'homlisti' ),
				'description' => __( 'Show header at the top when scrolling down', 'homlisti' ),
				'section'     => 'header_main_section',
			]
		) );

		// Transparent Header
		$wp_customize->add_setting( 'tr_header',
			[
				'default'           => $this->defaults['tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'tr_header',
			[
				'label'       => __( 'Transparent Header', 'homlisti' ),
				'description' => __( 'You have to enable Banner or Slider in page to make it work properly', 'homlisti' ),
				'section'     => 'header_main_section',
			]
		) );

		//Transparent Header BG Color
		$wp_customize->add_setting( 'header_transparent_color',
			[
				'default'           => $this->defaults['header_transparent_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Alfa_Color( $wp_customize, 'header_transparent_color',
			[
				'label'           => __( 'Transparent Background Color', 'homlisti' ),
				'section'         => 'header_main_section',
				'active_callback' => 'rttheme_is_trheader_enable',
			]
		) );


		// Button Control
		$wp_customize->add_setting( 'header_btn',
			[
				'default'           => $this->defaults['header_btn'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_btn',
			[
				'label'   => __( 'Header Right Button', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );

		// Button Text
		$wp_customize->add_setting( 'header_btn_txt',
			[
				'default'           => $this->defaults['header_btn_txt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_txt',
			[
				'label'           => __( 'Button Text', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'text',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);
		// Button URL
		$wp_customize->add_setting( 'header_btn_url',
			[
				'default'           => $this->defaults['header_btn_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_url_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_url',
			[
				'label'           => __( 'Button Link', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'url',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);


		// Header Login Icon Visibility
		$wp_customize->add_setting( 'header_login_icon',
			[
				'default'           => $this->defaults['header_login_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_login_icon',
			[
				'label'   => __( 'Header Login Icon Visibility', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );



		// Header Fav Icon
		$wp_customize->add_setting( 'header_fav_icon',
			[
				'default'           => $this->defaults['header_fav_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_fav_icon',
			[
				'label'   => __( 'Header Favourite Icon Visibility', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );


		// Header Compare Icon
		$wp_customize->add_setting( 'header_compare_icon',
			[
				'default'           => $this->defaults['header_compare_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_compare_icon',
			[
				'label'   => __( 'Header Compare Icon Visibility', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );

		// Header Cart Icon
		$wp_customize->add_setting( 'header_cart_icon',
			[
				'default'           => $this->defaults['header_cart_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_cart_icon',
			[
				'label'   => __( 'Header Cart Icon Visibility', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );


		// Header Cart Icon
		$wp_customize->add_setting( 'header_search_icon',
			[
				'default'           => $this->defaults['header_search_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_search_icon',
			[
				'label'   => __( 'Header Search Icon Visibility', 'homlisti' ),
				'section' => 'header_main_section',
			]
		) );


		/**
		 * All button order
		 */

		//Button Order
		$wp_customize->add_setting( 'header_btn_order',
			[
				'default'           => $this->defaults['header_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_order',
			[
				'label'           => __( 'Header Right Button Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);

		//Login Button Order
		$wp_customize->add_setting( 'login_btn_order',
			[
				'default'           => $this->defaults['login_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'login_btn_order',
			[
				'label'           => __( 'Login Icon Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_login_btn_enabled',
			]
		);

		//Header Fav Icon Order
		$wp_customize->add_setting( 'fav_btn_order',
			[
				'default'           => $this->defaults['fav_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'fav_btn_order',
			[
				'label'           => __( 'Favourite Icon Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_fav_btn_enabled',
			]
		);

		//Header Compare Icon Order
		$wp_customize->add_setting( 'compare_btn_order',
			[
				'default'           => $this->defaults['compare_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'compare_btn_order',
			[
				'label'           => __( 'Compare Icon Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_compare_btn_enabled',
			]
		);

		//Header Cart Icon Order
		$wp_customize->add_setting( 'cart_btn_order',
			[
				'default'           => $this->defaults['cart_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'cart_btn_order',
			[
				'label'           => __( 'Cart Icon Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_cart_btn_enabled',
			]
		);

		//Header Cart Icon Order
		$wp_customize->add_setting( 'search_btn_order',
			[
				'default'           => $this->defaults['search_btn_order'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'search_btn_order',
			[
				'label'           => __( 'Search Icon Order', 'homlisti' ),
				'section'         => 'header_main_section',
				'type'            => 'number',
				'active_callback' => 'rttheme_is_cart_btn_enabled',
			]
		);

	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Header_Settings();
}
