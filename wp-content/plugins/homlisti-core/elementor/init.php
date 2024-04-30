<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Plugin;
use radiustheme\HomListi\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once HOMLIST_CORE_BASE_DIR . '/elementor/controls/traits-icons.php';

// Elementor default widget control
require_once __DIR__ . '/el-extend.php';

class Custom_Widget_Init {

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'init' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_category' ] );
		add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'homlisti_flaticon_tab' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_style' ] );
		//add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );
		//add_action( "elementor/frontend/after_enqueue_scripts", [ $this, 'rt_load_scripts' ] );
	}

	public function editor_style() {
		$img = plugins_url( 'icon.png', __FILE__ );
		wp_enqueue_style( 'flaticon' );
		wp_add_inline_style( 'elementor-editor', '.elementor-element .icon .rdtheme-el-custom{content: url(' . $img . ');width: 28px;}' );
		wp_add_inline_style( 'elementor-editor', '.elementor-panel .select2-container {min-width: 100px !important; min-height: 30px !important;}' );
		wp_add_inline_style( 'elementor-editor', '.elementor-panel .elementor-control-type-heading .elementor-control-title {color: #93013d !important}' );
	}

	//load editor script
	public function editor_scripts() {
		wp_enqueue_script( 'select2' );
		wp_enqueue_script( 'rt-el-editor-script', HOMLIST_CORE_BASE_URL . 'elementor/assets/el_editor.js', [ 'jquery' ], HOMLIST_CORE, true );
	}

	//load frontend script
	public function rt_load_scripts() {
		//wp_enqueue_script( 'imagesloaded' );
		//wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'select2' );
		wp_enqueue_script( 'elementor-script', HOMLIST_CORE_BASE_URL . 'elementor/assets/scripts.js', [ 'jquery' ], HOMLIST_CORE, true );
	}

	public function init() {
		require_once __DIR__ . '/base.php';

		// dirname => classname /@dev
		$widgets = [
			'title'             => 'Title',
			'video-icon'        => 'Video_Icon',
			'title-animated'    => 'Title_Animated',
			'post'              => 'Post',
			'text-button'       => 'Button',
			'info-box'          => 'Info_Box',
			'testimonial'       => 'Testimonial_Carousel',
			'parallax'          => 'RT_Parallax',
			'rt-slider'         => 'RT_Slider',
			'team'              => 'Rt_Team',
			'progress-bar'      => 'RT_Progress_Bar',
			'pricing-table'     => 'Pricing_Table',
			'image-placeholder' => 'Image_Placeholder',

		];

		if ( class_exists( 'Rtcl' ) ) {
			$widgets += [
				'rt-properties'        => 'RT_Properties',
				'listing-location-box' => 'Listing_Location_Box',
				'agent'                => 'RT_Agent',
				'listing-category'     => 'RT_Listing_Category',
			];
		}

		foreach ( $widgets as $dirname => $class ) {
			$template_name = '/elementor-custom/' . $dirname . '/class.php';
			if ( file_exists( STYLESHEETPATH . $template_name ) ) {
				$file = STYLESHEETPATH . $template_name;
			} elseif ( file_exists( TEMPLATEPATH . $template_name ) ) {
				$file = TEMPLATEPATH . $template_name;
			} else {
				$file = __DIR__ . '/' . $dirname . '/class.php';
			}

			require_once $file;

			$classname = __NAMESPACE__ . '\\' . $class;
			Plugin::instance()->widgets_manager->register( new $classname );
		}
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function homlisti_flaticon_tab( $tabs = [] ) {
		// Append new icons
		$flat_icons = ElementorIconTrait::flaticon_icons();

		$tabs['homlisti-flaticon-icons'] = [
			'name'          => 'homlisti-flaticon-icons',
			'label'         => esc_html__( 'Flat Icons', 'homlisti-core' ),
			'labelIcon'     => 'fab fa-elementor',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => Helper::get_css( 'flaticon' ),
			'icons'         => $flat_icons,
			'ver'           => '1.0',
		];

		return $tabs;
	}

	public function widget_category( $class ) {
		$id         = HOMLIST_CORE_THEME_PREFIX . '-widgets'; // Category /@dev
		$properties = [
			'title' => __( 'RadiusTheme Elements', 'homlisti-core' ),
		];

		Plugin::$instance->elements_manager->add_category( $id, $properties );
	}

}

new Custom_Widget_Init();