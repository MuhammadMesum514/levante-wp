<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\Listing_Functions;
use \RT_Postmeta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RT_Postmeta' ) ) {
	return;
}

$Postmeta = RT_Postmeta::getInstance();

$prefix = HOMLIST_CORE_THEME_PREFIX;

/*-------------------------------------
#. Layout Settings
---------------------------------------*/
$nav_menus = wp_get_nav_menus( [ 'fields' => 'id=>name' ] );
$nav_menus = [ 'default' => __( 'Default', 'homlisti-core' ) ] + $nav_menus;
$sidebars  = [ 'default' => __( 'Default', 'homlisti-core' ) ] + Helper::custom_sidebar_fields();

$Postmeta->add_meta_box(
	"{$prefix}_page_settings",
	__( 'Layout Settings', 'homlisti-core' ),
	[ 'page', 'post' ],
	'',
	'',
	'high',
	[
		'fields' => [
			"{$prefix}_layout_settings" => [
				'label' => __( 'Layouts', 'homlisti-core' ),
				'type'  => 'group',
				'value' => [
					'layout'         => [
						'label'   => __( 'Layout', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default'       => __( 'Default from customizer', 'homlisti-core' ),
							'full-width'    => __( 'Full Width', 'homlisti-core' ),
							'left-sidebar'  => __( 'Left Sidebar', 'homlisti-core' ),
							'right-sidebar' => __( 'Right Sidebar', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'sidebar'        => [
						'label'   => __( 'Custom Sidebar', 'homlisti-core' ),
						'type'    => 'select',
						'options' => $sidebars,
						'default' => 'default',
					],
					'top_bar'        => [
						'label'   => __( 'Top Bar', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'on'      => __( 'Enable', 'homlisti-core' ),
							'off'     => __( 'Disable', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'header_width'   => [
						'label'   => __( 'Header Width', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default'   => __( 'Default from customizer', 'homlisti-core' ),
							'box-width' => __( 'Box Width', 'homlisti-core' ),
							'fullwidth' => __( 'Full Width', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'menu_alignment' => [
						'label'   => __( 'Menu Alignment', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default'     => __( 'Default from customizer', 'homlisti-core' ),
							'menu-left'   => __( 'Left Alignment', 'homlisti-core' ),
							'menu-center' => __( 'Center Alignment', 'homlisti-core' ),
							'menu-right'  => __( 'Right Alignment', 'homlisti-core' ),
						],
						'default' => 'default',
					],

					'header_style'          => [
						'label'   => __( 'Header Layout', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'1'       => __( 'Layout 1', 'homlisti-core' ),
							'2'       => __( 'Layout 2', 'homlisti-core' ),
							'3'       => __( 'Layout 3', 'homlisti-core' ),
							'4'       => __( 'Layout 4', 'homlisti-core' ),
							'5'       => __( 'Layout 5 (No BG)', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'tr_header'             => [
						'label'   => __( 'Transparent Header', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'on'      => __( 'Enable', 'homlisti-core' ),
							'off'     => __( 'Disable', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'padding_top'           => [
						'label'   => esc_html__( 'Padding Top (Page Content)', 'homlisti-core' ),
						'type'    => 'text',
						'default' => 'default',
					],
					'padding_bottom'        => [
						'label'   => esc_html__( 'Padding Bottom (Page Content)', 'homlisti-core' ),
						'type'    => 'text',
						'default' => 'default',
					],
					'padding_top_footer'    => [
						'label'   => esc_html__( 'Padding Top (Footer)', 'homlisti-core' ),
						'type'    => 'text',
						'default' => 'default',
					],
					'padding_bottom_footer' => [
						'label'   => esc_html__( 'Padding Bottom (Footer)', 'homlisti-core' ),
						'type'    => 'text',
						'default' => 'default',
					],
					'breadcrumb'            => [
						'label'   => __( 'Breadcrumb', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'on'      => __( 'Enable', 'homlisti-core' ),
							'off'     => __( 'Disable', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'breadcrumb_style'      => [
						'label'   => __( 'Breadcrumb Style', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'style-1' => __( 'Style 1', 'homlisti-core' ),
							'style-2' => __( 'Style 2', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'footer_style'          => [
						'label'   => __( 'Footer Layout', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'1'       => __( 'Layout 1', 'homlisti-core' ),
							'2'       => __( 'Layout 2', 'homlisti-core' ),
						],
						'default' => 'default',
					],
					'footer_border'         => [
						'label'   => __( 'Footer Border Top', 'homlisti-core' ),
						'type'    => 'select',
						'options' => [
							'default' => __( 'Default from customizer', 'homlisti-core' ),
							'on'      => __( 'Enable', 'homlisti-core' ),
							'off'     => __( 'Disable', 'homlisti-core' ),
						],
						'default' => 'default',
					],
				],
			],
		],
	] );

if ( class_exists( 'Rtcl' ) ) {
	$Postmeta->add_meta_box( 'listing_layout', esc_html__( 'Layout', 'homlisti-core' ), [ "rtcl_listing" ], '', '', 'high', [
		'fields' => [
			'listing_layout' => [
				'label'   => __( 'Layout', 'homlisti-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default from Customizer', 'homlisti-core' ),
					'1'       => __( 'Slider Layout', 'homlisti-core' ),
					'2'       => __( 'Full Width Image', 'homlisti-core' ),
					'3'       => __( 'Grid Layout Image', 'homlisti-core' ),
				],
				'default' => 'default',
			],

			'listing_price_style' => [
				'label'   => __( 'Listing Price Style', 'homlisti-core' ),
				'type'    => 'select',
				'options' => [
					'default'   => __( 'Default from Customizer', 'homlisti-core' ),
					'full'      => __( 'Show Full Price', 'homlisti-core' ),
					'short'     => __( 'Show Short form (K, M, B, T etc)', 'homlisti-core' ),
					'short-lac' => __( 'Show Short form (K, Lac, Cr etc)', 'homlisti-core' ),
				],
				'default' => 'default',
			],
		],
	] );
}

if ( class_exists( 'Rtcl' ) && Listing_Functions::is_enable_floor_plan() ) {
	$Postmeta->add_meta_box( 'listing_floor_plan', esc_html__( 'Floor Plan', 'homlisti-core' ), [ "rtcl_listing" ], '', '', 'default', [
		'fields' => [
			"{$prefix}_floor_plan" => [
				'type'   => 'repeater',
				'button' => esc_html__( 'Add Floor', 'homlisti-core' ),
				'value'  => [
					"title"       => [
						'label'   => esc_html__( 'Title', 'homlisti-core' ),
						'type'    => 'text',
						'default' => '',
					],
					"description" => [
						'label' => esc_html__( 'Description', 'homlisti-core' ),
						'type'  => 'textarea_html',
					],
					"bed"         => [
						'label'   => esc_html__( 'Bed', 'homlisti-core' ),
						'type'    => 'text',
						'default' => '',
					],
					"bath"        => [
						'label'   => esc_html__( 'Bath', 'homlisti-core' ),
						'type'    => 'text',
						'default' => '',
					],
					"size"        => [
						'label'   => esc_html__( 'Size', 'homlisti-core' ),
						'type'    => 'text',
						'default' => '',
					],
					"parking"     => [
						'label'   => esc_html__( 'Parking', 'homlisti-core' ),
						'type'    => 'text',
						'default' => '',
					],
					"floor_img"   => [
						'label'   => esc_html__( 'Floor Image', 'homlisti-core' ),
						'type'    => 'image',
						'default' => '',
					],
				],
			],
		],
	] );
}

// Yelp Review Category
if ( class_exists( 'Rtcl' ) && Listing_Functions::is_enable_yelp_review() ) {
	$cat_list   = [];
	$categories = YelpReview::yelp_categories();

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $key => $category ) {
			$cat_list[ $key ] = $category['title'];
		}
	}
	$Postmeta->add_meta_box( 'listing_yelp_category', esc_html__( 'Yelp Nearby Places', 'homlisti-core' ), [ "rtcl_listing" ], '', '', 'default', [
		'fields' => [
			"{$prefix}_yelp_categories" => [
				'label'   => esc_html__( 'Select Category', 'homlisti-core' ),
				'type'    => 'multi_checkbox',
				'options' => $cat_list,
				'default' => '',
			],
		],
	] );
}

// 360 degree view
if ( class_exists( 'Rtcl' ) && Listing_Functions::is_enable_panorama_view() ) {
	$Postmeta->add_meta_box( 'listing_panorama', esc_html__( 'Panorama', 'homlisti-core' ), [ "rtcl_listing" ], '', '', 'default', [
		'fields' => [
			"{$prefix}_panorama_img" => [
				'label'   => esc_html__( 'Panorama Image', 'homlisti-core' ),
				'type'    => 'image',
				'default' => '',
			],
			"{$prefix}_virtual_tour" => [
				'label'   => esc_html__( 'Embed Virtual Tour Iframe', 'homlisti-core' ),
				'type'    => 'textarea_html',
				'default' => '',
			],
		],
	] );
}