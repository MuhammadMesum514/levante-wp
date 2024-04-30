<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Rtcl\Helpers\Functions;
use \WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RT_Properties extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'RT Properties', 'homlisti-core' );
		$this->rt_base = 'rt-properties-type-tab';

		$this->rt_translate = [
			'cols'        => [
				'12' => __( '1 Columns', 'homlisti-core' ),
				'6'  => __( '2 Columns', 'homlisti-core' ),
				'4'  => __( '3 Columns', 'homlisti-core' ),
				'3'  => __( '4 Columns', 'homlisti-core' ),
			],
			'slider_cols' => [
				'1' => __( '1 Columns', 'homlisti-core' ),
				'2' => __( '2 Columns', 'homlisti-core' ),
				'3' => __( '3 Columns', 'homlisti-core' ),
				'4' => __( '4 Columns', 'homlisti-core' ),
				'5' => __( '5 Columns', 'homlisti-core' ),
				'6' => __( '6 Columns', 'homlisti-core' ),
				'7' => __( '7 Columns', 'homlisti-core' ),
				'8' => __( '8 Columns', 'homlisti-core' ),
			],
		];

		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		$terms             = get_terms( [ 'taxonomy' => 'rtcl_category', 'fields' => 'id=>name' ] );
		$category_dropdown = [];

		$type_dropdown = Functions::get_listing_types();

		foreach ( $terms as $id => $name ) {
			$category_dropdown[ $id ] = $name;
		}

		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Style', 'homlisti-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style 1 - Grid', 'homlisti-core' ),
					'style2' => __( 'Style 2 - Thumb', 'homlisti-core' ),
					'style3' => __( 'Style 3 - Tab', 'homlisti-core' ),
					'style4' => __( 'Style 4 - Extra padding', 'homlisti-core' ),
					'style5' => __( 'Style 5 - List', 'homlisti-core' ),
					'style6' => __( 'Style 6 - Without Thumb', 'homlisti-core' ),
					'style7' => __( 'Style 7 - Slider', 'homlisti-core' ),
					'style8' => __( 'Style 8 - Slider-2', 'homlisti-core' ),
					'style9' => __( 'Style 9 - Info', 'homlisti-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'gird_column_desktop',
			[
				'label'     => esc_html__( 'Grid Column', 'homlisti-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->rt_translate['cols'],
				'default'   => '4',
				'condition' => [
					'layout!' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_responsive_control(
			'slider_column_lg_desktop',
			[
				'label'          => esc_html__( 'Slider Column', 'homlisti-core' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => $this->rt_translate['slider_cols'],
				'condition'      => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'type',
			[
				'type'     => Controls_Manager::SELECT2,
				'label'    => esc_html__( 'Type', 'homlisti-core' ),
				'options'  => $type_dropdown,
				'multiple' => true,
			]
		);

		$this->add_control(
			'cat',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Categories', 'homlisti-core' ),
				'options'    => $category_dropdown,
				'multiple'   => true,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'promotions_product',
			[
				'label'     => __( 'Filter Promotions Product', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => __( '--Select--', 'homlisti-core' ),
					'_top'     => __( 'Top Product', 'homlisti-core' ),
					'featured' => __( 'Featured Product', 'homlisti-core' ),
					'_bump_up' => __( 'Bump Up Product', 'homlisti-core' ),
				],
				'condition' => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label'       => esc_html__( 'Posts Per Page', 'homlisti-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '8',
				'description' => esc_html__( 'Write -1 to show all', 'homlisti-core' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'ids',
			[
				'label'       => esc_html__( "Posts ID's, seperated by commas", 'homlisti-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => [
					'type' => [ 'custom' ],
				],
				'description' => __( "Put the comma seperated ID's here eg. 23,26,89", 'homlisti-core' ),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Post offset', 'homlisti-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'homlisti-core' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'homlisti-core' ),
				'condition'   => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude posts', 'homlisti-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => 'true',
				'description' => __( 'Enter the post IDs separated by comma for exclude', 'homlisti-core' ),
				'condition'   => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'random',
			[
				'label'        => esc_html__( 'Change items on every page load', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'default'      => false,
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Order By', 'homlisti-core' ),
				'options'    => [
					'date'  => __( 'Date (Recents comes first)', 'homlisti-core' ),
					'title' => __( 'Title', 'homlisti-core' ),
				],
				'default'    => 'date',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
						[
							'name'     => 'random',
							'operator' => '!==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Sort By', 'homlisti-core' ),
				'options'    => [
					'asc'  => esc_html__( 'Ascending', 'homlisti-core' ),
					'desc' => esc_html__( 'Descending', 'homlisti-core' ),
				],
				'default'    => 'asc',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
						[
							'name'     => 'random',
							'operator' => '!==',
							'value'    => 'yes',
						],
					],
				],
			]
		);


		$this->add_control(
			'more_options',
			[
				'label'     => __( 'Other Options', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cat_display',
			[
				'label'        => esc_html__( 'Category Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'category_position',
			[
				'label'     => __( 'Category Position', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default'          => __( 'Default', 'homlisti-core' ),
					'cat-before-title' => __( 'Above Title', 'homlisti-core' ),
				],
				'condition' => [
					'cat_display' => 'yes',
					'layout'      => [ 'style1', 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'content_visibility',
			[
				'label'        => esc_html__( 'Content Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => [ 'style2' ],
				],
			]
		);

		$this->add_control(
			'content_limit',
			[
				'label'     => esc_html__( 'Content Limit', 'homlisti-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 30,
				'condition' => [
					'content_visibility' => 'yes',
					'layout!'            => [ 'style2' ],
				],
			]
		);

		$this->add_control(
			'field_display',
			[
				'label'        => esc_html__( 'Properties Info Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hide_price_range',
			[
				'label'        => esc_html__( 'Hide Price Range', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'hide',
				'default'      => 'hide',
				'prefix_class' => 'is-price-range-',
			]
		);
		$this->add_control(
			'hide_price_meta',
			[
				'label'        => esc_html__( 'Hide Price Meta', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'hide',
				'default'      => false,
				'prefix_class' => 'is-price-meta-',
			]
		);

		$this->add_control(
			'info_style',
			[
				'label'   => __( 'Info Style', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'space-between',
				'options' => [
					'space-between'       => __( 'Space Between (with BG)', 'homlisti-core' ),
					'list-view'           => __( 'List View (with BG)', 'homlisti-core' ),
					'space-between-no-bg' => __( 'Space Between (without BG)', 'homlisti-core' ),
					'list-view-no-bg'     => __( 'List View (without BG)', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'listing_thumb_visibility',
			[
				'label'        => esc_html__( 'Thumbnail Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				//				'condition'    => [
				//					'layout!' => 'style2',
				//				],
			]
		);

		$this->add_control(
			'listing_action_visibility',
			[
				'label'        => esc_html__( 'Action Button Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'location_visibility',
			[
				'label'        => esc_html__( 'Location Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'date_visibility',
			[
				'label'        => esc_html__( 'Date Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'views_display',
			[
				'label'        => esc_html__( 'Post View Count Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'label_display',
			[
				'label'        => esc_html__( 'Label Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'label_position',
			[
				'label'        => __( 'Label Position', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'thumb',
				'options'      => [
					'thumb'       => __( 'In Thumb', 'homlisti-core' ),
					'below_title' => __( 'Below Title', 'homlisti-core' ),
				],
				'condition'    => [
					'label_display' => 'yes',
					'layout!'       => [ 'style2', 'style5', 'style6', 'style9' ],
				],
				'render_type'  => 'template',
				'prefix_class' => 'label_position_',
			]
		);

		$this->add_control(
			'type_visibility',
			[
				'label'        => esc_html__( 'Product Type Visibility', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hanging_visibility',
			[
				'label'     => __( 'Type Hanger Visibility', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'visible',
				'options'   => [
					'visible'       => __( 'Visible', 'homlisti-core' ),
					'hanger-hidden' => __( 'Hidden', 'homlisti-core' ),
				],
				'condition' => [
					'type_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_display',
			[
				'label'        => esc_html__( 'Display Author Name', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'author_position',
			[
				'label'        => __( 'Author Position', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Default', 'homlisti-core' ),
					'in_thumb' => __( 'In Thumb', 'homlisti-core' ),
				],
				'condition'    => [
					'author_display' => 'yes',
					'layout'         => 'style1',
				],
				'prefix_class' => 'rt_author_position_',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'author_prefix',
			[
				'label'       => __( 'Author Prefix', 'homlisti-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'By', 'homlisti-core' ),
				'placeholder' => __( 'Type author prefix here', 'homlisti-core' ),
				'condition'   => [
					'author_display' => 'yes',
					'layout!'        => 'style2',
				],
			]
		);

		$this->add_control(
			'show_listing_footer',
			[
				'label'        => esc_html__( 'Show/Hide Footer', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => true,
			]
		);

		$this->add_control(
			'isotope_enable',
			[
				'label'        => esc_html__( 'Enable Isotope', 'homlisti-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'homlisti-core' ),
				'label_off'    => esc_html__( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => true,
				'condition'    => [
					'layout' => [ 'style1', 'style4', 'style5' ],
				],
			]
		);

		$this->end_controls_section();

		/*
		 * Additional Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'additional_settings',
			[
				'label' => esc_html__( 'Additional Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title Tag', 'the-post-grid' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
					'h1' => esc_html__( 'H1', 'the-post-grid' ),
					'h2' => esc_html__( 'H2', 'the-post-grid' ),
					'h3' => esc_html__( 'H3', 'the-post-grid' ),
					'h4' => esc_html__( 'H4', 'the-post-grid' ),
					'h5' => esc_html__( 'H5', 'the-post-grid' ),
					'h6' => esc_html__( 'H6', 'the-post-grid' ),
				],
			]
		);

		$this->add_control(
			'thumbnail_source',
			[
				'label'   => __( 'Thumbnail Source', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => [
					'slider'    => __( 'From Gallery - Slider', 'homlisti-core' ),
					'thumbnail' => __( 'From Thumbnail - Image', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'thumbnail_size',
			[
				'label'   => __( 'Thumbnail Size', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->rt_get_all_image_sizes(),
			]
		);


		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => __( 'Thumbnail Height', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 150,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 10,
						'max' => 300,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 60,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-thumb .thumbnail-bg' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumb_overlay_height',
			[
				'label'      => __( 'Overlay Height', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-thumb:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'box_border_radius',
			[
				'label'        => __( 'Border Radius', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'homlisti-core' ),
					'disbale' => __( 'Disbale', 'homlisti-core' ),
				],
				'prefix_class' => 'listing-border-radius-',
			]
		);

		$this->add_control(
			'listing_title_wrap',
			[
				'label'        => __( 'Title Wrap', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'homlisti-core' ),
					'disbale' => __( 'Disbale', 'homlisti-core' ),
				],
				'prefix_class' => 'listing-title-wrap-',
			]
		);

		$this->add_control(
			'listing_border',
			[
				'label'        => __( 'Listing Border', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'homlisti-core' ),
					'disbale' => __( 'Disbale', 'homlisti-core' ),
				],
				'prefix_class' => 'listing-wrap-border-',
				'condition'    => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->start_controls_tabs(
			'additional_tabs'
		);

		$this->start_controls_tab(
			'additional_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box',
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'background_overlay',
				'label'          => __( 'Thumbnail Overlay Background', 'homlisti-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Image Overlay Type', 'homlisti-core' ),
					],
				],
				'types'          => [ 'gradient' ],
				'selector'       => '{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-thumb:before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => __( 'Box Border', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'additional_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_hover',
				'label'    => __( 'Box Shadow Hover', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box:hover',
			]
		);


		$this->add_control(
			'background_overlay_title_hover',
			[
				'label' => __( 'Background Overlay Hover:', 'homlisti-core' ),
				'type'  => \Elementor\Controls_Manager::RAW_HTML,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'hover_background_overlay',
				'label'          => __( 'Thumbnail Overlay Background Hover', 'homlisti-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Image Overlay Type - Hover', 'homlisti-core' ),
					],
				],
				'types'          => [ 'gradient' ],
				'selector'       => '{{WRAPPER}} .rt-el-listing-wrapper .product-box:hover .product-thumb:before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border_hover',
				'label'    => __( 'Box Border - Hover', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


		//Carousel Settings
		//=======================================
		$this->start_controls_section(
			'carousel_settings',
			[
				'label'     => esc_html__( 'Carousel Settings', 'homlisti-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'slider_animation',
			[
				'label'   => __( 'Slider Animation', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'homlisti-core' ),
					'fade'  => __( 'Fade', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'item_overflow',
			[
				'label'        => __( 'Slider Overflow', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'hidden',
				'options'      => [
					'none'   => __( 'None', 'homlisti-core' ),
					'hidden' => __( 'Hidden', 'homlisti-core' ),
				],
				'prefix_class' => 'list-carousel-overflow-',
			]
		);


		$this->add_control(
			'arrows',
			[
				'label'        => __( 'Arrow', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'homlisti-core' ),
				'label_off'    => __( 'Hide', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'dots',
			[
				'label'        => __( 'Dots', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'homlisti-core' ),
				'label_off'    => __( 'Hide', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'draggable',
			[
				'label'        => __( 'Draggable', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'homlisti-core' ),
				'label_off'    => __( 'Off', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'        => __( 'Infinite', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'autoplaySpeed',
			[
				'label'     => __( 'Autoplay Speed', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 5000,
				'step'      => 500,
				'default'   => 3000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'centeredSlides',
			[
				'label'        => __( 'Centered Slides', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'description'  => __( 'If you use centered slider options then default column will not working.', 'homlisti-core' ),
			]
		);


		$this->add_responsive_control(
			'slider_min_width',
			[
				'label'      => __( 'Min Item Width', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 300,
						'max'  => 450,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 360,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-slide' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'centeredSlides' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => __( 'Speed', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 100,
				'max'     => 3000,
				'step'    => 100,
				'default' => 300,
			]
		);

		$this->add_control(
			'arrow_style_heading',
			[
				'label'     => __( 'Arrow Style', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_visibility',
			[
				'label'        => __( 'Arrow Visibility', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Always Show', 'homlisti-core' ),
					'on-hover' => __( 'Show on Hover', 'homlisti-core' ),
				],
				'condition'    => [
					'arrows' => 'yes',
				],
				'prefix_class' => 'listing-arrow-visibility-'
			]
		);

		$this->add_responsive_control(
			'arrow_border_radius',
			[
				'label'      => __( 'Arrow Radius', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_width_height',
			[
				'label'      => __( 'Arrow Width/Height', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'new_arrow_position',
			[
				'label'      => __( 'Arrow X Position', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 300,
						'max'  => 300,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-prev'      => 'left: {{SIZE}}{{UNIT}};right:auto;',
					'{{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-next'      => 'right: {{SIZE}}{{UNIT}};left:auto;',
					'.rtl {{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};left:auto;',
					'.rtl {{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};right:auto;',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'arrow_style_tabs',
			[
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'arrow_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Icon Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_arrow_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Icon Color Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button:hover i'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_bg_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button:hover i'     => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active i' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'dot_style_heading',
			[
				'label'     => __( 'Dots Style', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_text_align',
			[
				'label'        => __( 'Dots Alignment', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'center',
				'toggle'       => true,
				'condition'    => [
					'dots' => 'yes',
				],
				'prefix_class' => 'dots-align-',
			]
		);

		$this->add_control(
			'dots_button_style',
			[
				'label'        => __( 'Dot Style', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Default', 'homlisti-core' ),
					'creative' => __( 'Creative', 'homlisti-core' ),
				],
				'condition'    => [
					'dots' => 'yes',
				],
				'prefix_class' => 'carousel-dots-',
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label'      => __( 'Dots Radius', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_width_height',
			[
				'label'      => __( 'Dots Width/Height', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_position',
			[
				'label'      => __( 'Dots Y Position', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'dots_style_tabs'
		);

		$this->start_controls_tab(
			'dots_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Border Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'dots_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'dots_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover/Active Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span:hover'                           => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);

		$this->add_control(
			'dots_border_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Border Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span:hover'                           => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'is_shadow',
			[
				'label'        => __( 'Enable Slider Overlay', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'is-shadow-',
			]
		);

		$this->add_control(
			'shadow_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Shadow Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper.style8::before' => 'background: {{VALUE}}; background: linear-gradient(90deg, {{VALUE}} 0%, transparent 100%);',
					'{{WRAPPER}} .rt-el-listing-wrapper.style8::after'  => 'background: {{VALUE}}; background: linear-gradient(-90deg, {{VALUE}} 0%, transparent 100%);',
				],
				'condition' => [
					'is_shadow' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		/*
		 * Filter Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'filter_settings',
			[
				'label'     => esc_html__( 'Filter Settings', 'homlisti-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => 'style3',
				],
			]
		);

		$this->add_responsive_control(
			'filter_text_align',
			[
				'label'     => __( 'Alignment', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'homlisti-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .filter-wrapper' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'show_all_btn',
			[
				'label'        => __( 'Show All Button', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'homlisti-core' ),
				'label_off'    => __( 'Hide', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->start_controls_tabs(
			'filter_style_tabs'
		);

		$this->start_controls_tab(
			'filter_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'filter_color',
			[
				'label'     => __( 'Color', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filter_background',
			[
				'label'     => __( 'Background', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item'         => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item::before' => 'border-top-color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_style_hover_tab',
			[
				'label' => __( 'Hover/Active', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'filter_color_hover',
			[
				'label'     => __( 'Color on Hover', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filter_background_hover',
			[
				'label'     => __( 'Background on Hover', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover, {{WRAPPER}} .isotope-classes-tab .nav-item.current'                 => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover::before, {{WRAPPER}} .isotope-classes-tab .nav-item.current::before' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_typography',
				'label'    => __( 'Filter Typography', 'homlisti-core' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .isotope-classes-tab .nav-item',
			]
		);


		$this->add_responsive_control(
			'filter_padding',
			[
				'label'      => __( 'Button Padding', 'homlisti-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '6',
					'right'    => '40',
					'bottom'   => '6',
					'left'     => '40',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_spacing',
			[
				'label'              => __( 'Spacing', 'homlisti-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '30',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .filter-wrapper .isotope-classes-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
		//End Filter Settings

		$this->start_controls_section(
			'sec_color',
			[
				'label' => esc_html__( 'Color', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .rt-main-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .rt-main-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Content Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-content .listing-content' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Price', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-price'                    => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .product-price .rtcl-price-amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cat_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Category', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-category a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Meta', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .entry-meta' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Meta Icon', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .entry-meta i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Icon', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-bottom-content .action-btn a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'custom_field_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Custom Field Icon', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-features li i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'custom_field_text_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Custom Field Text', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-features li .listable-value' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Author Name', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-bottom-content .media .item-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sec_label_color',
			[
				'label' => esc_html__( 'Label Color', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'type_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Type Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-type > span' => 'background-image: linear-gradient(to right, {{VALUE}}, {{VALUE}})',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'type_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Type Text Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-type > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'featured_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Featured Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .feature-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'featured_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Featured Text Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .feature-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'new_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'New Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .new-badge' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'new_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'New Text Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .new-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'top_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Top Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .top-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'top_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Top Text Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .top-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'popular_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Popular Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .popular-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'popular_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Popular Text Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .popular-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sec_typo',
			[
				'label' => esc_html__( 'Typography', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Title', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-el-listing-wrapper.product-grid .product-box .item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typo',
				'label'    => esc_html__( 'Content Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box .product-content .listing-content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_type',
				'label'    => esc_html__( 'Price Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-price .rtcl-price-amount, {{WRAPPER}} .rt-el-listing-wrapper .product-box .product-price .rtcl-price-amount',
			]
		);


		$this->end_controls_section();
	}

	private function rt_isotope_query( $data ) {
		$result = [];

		// Post type
		$args = [
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => $data['number'],
		];

		// Ordering
		if ( $data['random'] ) {
			$args['orderby'] = 'rand';
		} else {
			$args['orderby'] = $data['orderby'];
			if ( $data['orderby'] == 'title' ) {
				$args['order'] = 'ASC';
			}
		}

		// Date and Meta Query results

		$args2 = [];

		if ( ! empty( $data['cat'] ) ) {
			$args2['tax_query'] = [
				[
					'taxonomy' => 'rtcl_category',
					'field'    => 'term_id',
					'terms'    => $data['cat'],
				],
			];
		}

		foreach ( $data['type'] as $key => $value ) {
			$args2['meta_query'] = [
				[
					'key'     => 'ad_type',
					'value'   => $value,
					'compare' => '=',
				],
			];

			$result[ $value ] = new WP_Query( $args + $args2 );
			$args2            = [];
		}

		return $result;
	}

	private function rt_isotope_navigation( $data ) {
		$type_list = Functions::get_listing_types();

		$navs = [];

		if ( ! empty( $type_list ) ) {
			$navs = $type_list;
		}

		$navs = apply_filters( 'classipost_isotope_navigations', $navs );

		foreach ( $navs as $key => $value ) {
			if ( ! in_array( $key, $data['type'] ) ) {
				unset( $navs[ $key ] );
			}
		}

		return $navs;
	}

	protected function render() {
		$data = $this->get_settings();

		if ( 'style7' == $data['layout'] || 'style8' == $data['layout'] ) {
			$slider_colum         = intval( ( isset( $data['slider_column_lg_desktop'] ) && $data['slider_column_lg_desktop'] ) ? $data['slider_column_lg_desktop'] : 3 );
			$slider_column_tablet = intval( ( isset( $data['slider_column_lg_desktop_tablet'] ) && $data['slider_column_lg_desktop_tablet'] )
				? $data['slider_column_lg_desktop_tablet']
				: 2 );
			$slider_column_mobile = intval( ( isset( $data['slider_column_lg_desktop_mobile'] ) && $data['slider_column_lg_desktop_mobile'] )
				? $data['slider_column_lg_desktop_mobile']
				: 1 );


			$data['slider_data'] = [
				'effect'         => 'slide',
				'loop'           => $data['infinite'] ? true : false,
				'speed'          => $data['speed'],
				'slidesPerView'  => $slider_colum,
				'spaceBetween'   => 30,
				'centeredSlides' => $data['centeredSlides'] ? true : false,
//				'navigation'     => [
//					'nextEl' => '.elementor-swiper-button-prev',
//					'prevEl' => '.elementor-swiper-button-next',
//				],
				'pagination'     => [
					'el'        => '.swiper-pagination',
					'clickable' => true,
					'type'      => 'bullets',
				],
				'breakpoints'    => [
					'50'   => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => 30,
					],
					'640'  => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => 30,
					],
					'768'  => [
						'slidesPerView' => $slider_column_tablet,
						'spaceBetween'  => 30,
					],
					'1024' => [
						'slidesPerView' => $slider_colum,
						'spaceBetween'  => 30,
					],
				],
			];


			if ( $data['autoplay'] ) {
				$data['slider_data']['autoplay'] = [
					'delay'                => $data['autoplaySpeed'],
					'disableOnInteraction' => true,
					'pauseOnMouseEnter'    => true
				];
			}
		}


		$template = 'view-1';

		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$data['queries'] = $this->rt_isotope_query( $data );
			$data['navs']    = $this->rt_isotope_navigation( $data );
			if ( empty( $data['queries'] ) ) {
				echo '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i>';
				echo __( " Please choose 2 or more ( Ad Type ) first for showing posts", "homlisti-core" );
				echo '</div>';
			}
			$template = 'view-3';
		} elseif ( 'style4' == $data['layout'] ) {
			$template = 'view-4';
		} elseif ( 'style5' == $data['layout'] ) {
			$template = 'view-5';
		} elseif ( 'style6' == $data['layout'] ) {
			$template = 'view-6';
		} elseif ( 'style7' == $data['layout'] ) {
			$template = 'view-7';
		} elseif ( 'style8' == $data['layout'] ) {
			$template = 'view-8';
		} elseif ( 'style9' == $data['layout'] ) {
			$template = 'view-9';
		}

		$this->rt_template( $template, $data );
	}

}