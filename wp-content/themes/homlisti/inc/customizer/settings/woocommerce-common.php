<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi\Customizer\Settings;

use radiustheme\HomListi\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\HomListi\Customizer\RDTheme_Customizer;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_WooCommerce_Common_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_woocommerce_controls' ] );
	}

	//	Related Product Visibility
	public function register_woocommerce_controls( $wp_customize ) {

		//Product per page
		$wp_customize->add_setting( 'wc_num_product',
			[
				'default'           => $this->defaults['wc_num_product'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_sanitize_integer',
			]
		);
		$wp_customize->add_control( 'wc_num_product',
			[
				'label'   => __( 'Product per page', 'homlisti' ),
				'section' => 'woocommerce_common_section',
				'type'    => 'number',
			]
		);

		$wp_customize->add_setting( 'woo_related_product',
			[
				'default'           => $this->defaults['woo_related_product'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'woo_related_product',
			[
				'label'   => __( 'Related Product Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );


		//	Product Description Visibility

		$wp_customize->add_setting( 'wc_description',
			[
				'default'           => $this->defaults['wc_description'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_description',
			[
				'label'   => __( 'Product Description Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );


		//	Product Review Visibility
		$wp_customize->add_setting( 'wc_reviews',
			[
				'default'           => $this->defaults['wc_reviews'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_reviews',
			[
				'label'   => __( 'Product Review Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		//	Product Additional info Visibility
		$wp_customize->add_setting( 'wc_additional_info',
			[
				'default'           => $this->defaults['wc_additional_info'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_additional_info',
			[
				'label'   => __( 'Product Additional info Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		//	Product Additional info Visibility
		$wp_customize->add_setting( 'wc_cross_sell',
			[
				'default'           => $this->defaults['wc_cross_sell'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_cross_sell',
			[
				'label'   => __( 'WC Cross Sell Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		// Product Excerpt Visibility
		$wp_customize->add_setting( 'wc_show_excerpt',
			[
				'default'           => $this->defaults['wc_show_excerpt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_show_excerpt',
			[
				'label'   => __( 'Product Excerpt Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		// Category Visibility
		$wp_customize->add_setting( 'wc_cats',
			[
				'default'           => $this->defaults['wc_cats'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_cats',
			[
				'label'   => __( 'Category Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		// Tags Visibility
		$wp_customize->add_setting( 'wc_tags',
			[
				'default'           => $this->defaults['wc_tags'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_tags',
			[
				'label'   => __( 'Tags Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		// Product Quickview Icon Visibility
		$wp_customize->add_setting( 'wc_quickview_icon',
			[
				'default'           => $this->defaults['wc_quickview_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_quickview_icon',
			[
				'label'   => __( 'Quickview Icon Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

		// Product Quickview Icon Visibility
		$wp_customize->add_setting( 'wc_wishlist_icon',
			[
				'default'           => $this->defaults['wc_wishlist_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'wc_wishlist_icon',
			[
				'label'   => __( 'Add to Wishlist Icon Visibility', 'homlisti' ),
				'section' => 'woocommerce_common_section',
			]
		) );

	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_WooCommerce_Common_Settings();
}
