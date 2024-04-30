<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Controls_Manager;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RT_Listing_Category extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'RT Listing Categories', 'homlisti-core' );
		$this->rt_base = 'rt-listing-category';

		$this->rt_translate = [
			'cols'        => [
				'1' => __( '1 Columns', 'homlisti-core' ),
				'2' => __( '2 Columns', 'homlisti-core' ),
				'3' => __( '3 Columns', 'homlisti-core' ),
				'4' => __( '4 Columns', 'homlisti-core' ),
				'5' => __( '5 Columns', 'homlisti-core' ),
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
					'style2' => __( 'Style 2 - Slider', 'homlisti-core' ),
				],

			]
		);

		$this->add_responsive_control(
			'gird_column',
			[
				'label'     => esc_html__( 'Grid Column', 'homlisti-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->rt_translate['cols'],
				'default'   => '5',
				'condition' => [
					'layout!' => [ 'style2' ],
				],
			]
		);

		$this->add_responsive_control(
			'slider_column',
			[
				'label'          => esc_html__( 'Choose Column', 'homlisti-core' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '5',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => $this->rt_translate['slider_cols'],
				'condition'      => [
					'layout' => [ 'style2' ],
				],
			]
		);


		$this->add_control(
			'categories',
			[
				'type'        => Controls_Manager::SELECT2,
				'label'       => esc_html__( 'Choose Categories', 'homlisti-core' ),
				'options'     => $category_dropdown,
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'number',
			[
				'label'   => esc_html__( 'Number of Categories', 'homlisti-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '5',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Order by', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name'   => __( 'Name', 'homlisti-core' ),
					'ID'     => __( 'Order by post ID', 'homlisti-core' ),
					'count'  => __( 'Count', 'homlisti-core' ),
					'parent' => __( 'Parent', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Sort By', 'homlisti-core' ),
				'options' => [
					'ASC'  => esc_html__( 'Ascending', 'homlisti-core' ),
					'DESC' => esc_html__( 'Descending', 'homlisti-core' ),
				],
				'default' => 'ASC',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'        => __( 'Alignment', 'homlisti-core' ),
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
				'toggle'       => true,
				'prefix_class' => 'content-text-',
			]
		);

		$this->end_controls_section();

		/*
		 * Icon Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'icon_settings',
			[
				'label' => esc_html__( 'Icon Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cat_icon_style',
			[
				'label'   => __( 'Icon Source', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'       => __( 'Default Icon From Category', 'homlisti-core' ),
					'default_image' => __( 'Default Image From Category', 'homlisti-core' ),
					'custom_icon'   => __( 'Custom Icon', 'homlisti-core' ),
					'custom_image'  => __( 'Custom Image', 'homlisti-core' ),
				],
			]
		);

		//TODO: Custom ICON
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'custom_icon',
			[
				'label'   => __( 'Icon', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-star',
					'library' => 'solid',
				],

			]
		);
		$this->add_control(
			'custom_icon_list',
			[
				'label'     => __( 'Icon List', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'condition' => [
					'cat_icon_style' => 'custom_icon',
				],
			]
		);


		//TODO: Custom Image
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'custom_image',
			[
				'label'   => __( 'Choose Image', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'custom_image_list',
			[
				'label'     => __( 'Image List', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'condition' => [
					'cat_icon_style' => 'custom_image',
				],
			]
		);

		$this->add_control(
			'thumb_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'icon_font_size',
			[
				'label'      => __( 'Icon Font Size', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 150,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner .category-thumbnail i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner .category-thumbnail svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'cat_icon_style!' => 'custom_image',
				],
			]
		);

		$this->add_control(
			'icon_img_size',
			[
				'label'      => __( 'Image Size', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 30,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner .category-thumbnail img' => 'width: {{SIZE}}{{UNIT}};height:auto',
				],
				'condition'  => [
					'cat_icon_style' => 'custom_image',
				],
			]
		);


		$this->start_controls_tabs(
			'thumbnail_tabs',
			[
				'condition' => [
					'cat_icon_style!' => 'custom_image',
				],
			]
		);

		$this->start_controls_tab(
			'thumbnail_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner .category-thumbnail i'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner .category-thumbnail svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnail_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);


		$this->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Title Color', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover .category-thumbnail i' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


		//Title Settings
		//=======================================

		$this->start_controls_section(
			'cat_title_setting',
			[
				'label' => esc_html__( 'Category Title Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'cat_title_typography',
				'label'    => __( 'Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .category-content .cat-title',
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label'              => __( 'Title Vertical Margin', 'homlisti-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', '%', 'em' ],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .rt-listing-category-wrapper .category-content .cat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//TODO: Start Tab
		$this->start_controls_tabs(
			'title_style_tabs'
		);

		$this->start_controls_tab(
			'title_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'cat_title_color',
			[
				'label'     => __( 'Title Color', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .category-content .cat-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'cat_title_color_hover',
			[
				'label'     => __( 'Title Color - Hover', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover .category-content .cat-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		//TODO: End Tab


		$this->end_controls_section();


		//Title Settings
		//=======================================

		$this->start_controls_section(
			'cat_count_setting',
			[
				'label' => esc_html__( 'Category Count Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'cat_count_typography',
				'label'    => __( 'Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .category-content .cat-count-number',
			]
		);

		$this->add_control(
			'cat_count_suffix',
			[
				'label'   => __( 'Count Suffix Text', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Listings', 'homlisti-core' ),
			]
		);

		//TODO: Start Tab
		$this->start_controls_tabs(
			'count_style_tabs'
		);

		$this->start_controls_tab(
			'count_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'cat_count_color',
			[
				'label'     => __( 'Count Color', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .category-content .cat-count-number' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'count_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'cat_count_color_hover',
			[
				'label'     => __( 'Count Color - Hover', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover .category-content .cat-count-number' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		//TODO: End Tab

		$this->end_controls_section();


		//Carousel Settings
		//=======================================
		$this->start_controls_section(
			'carousel_settings',
			[
				'label'     => esc_html__( 'Carousel Settings', 'homlisti-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => [ 'style2' ],
				],
			]
		);


		$this->add_control(
			'slider_space',
			[
				'label'      => __( 'Slider Gap', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
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
				'default'      => 'yes',
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
				'default'      => false,
			]
		);

		$this->add_control(
			'center_mode',
			[
				'label'        => __( 'Center Mode', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => false,
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
			'adaptiveHeight',
			[
				'label'        => __( 'Adaptive Height', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
				'label'   => __( 'Arrow Visibility', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'on-hover',
				'options' => [
					'default'       => __( 'Always Show', 'homlisti-core' ),
					'on-hover' => __( 'Show on Hover', 'homlisti-core' ),
				],
				'prefix_class' => 'arrow-visibility-'
			]
		);


		$this->add_responsive_control(
			'arrow_font_size',
			[
				'label'      => __( 'Arrow Font Size', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 8,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
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
					'{{WRAPPER}} .elementor-swiper-button i' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_width',
			[
				'label'      => __( 'Arrow Width', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button i' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_height',
			[
				'label'      => __( 'Arrow Height', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button i' => 'height: {{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_x_position',
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
					'{{WRAPPER}} .elementor-swiper-button-prev'      => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-swiper-button-next'      => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .elementor-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .elementor-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_y_position',
			[
				'label'      => __( 'Arrow Y Position', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 150,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .elementor-swiper-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		//TODO: Arrow Tabs Start
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
					'{{WRAPPER}} .elementor-swiper-button i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_arrow_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button i' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'arrow_box_shadow',
				'label'    => __( 'Box Shadow', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .elementor-swiper-button i',
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
				'label'     => esc_html__( 'Arrow Icon Color - Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button i:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_bg_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background - Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button i:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'arrow_box_shadow_hover',
				'label'    => __( 'Box Shadow - Hover', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .elementor-swiper-button i:hover',
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();
		//TODO: Arrow Tabs Start

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
				'label'     => __( 'Dots Alignment', 'homlisti-core' ),
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
				'condition' => [
					'dots' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'text-align: {{VALUE}}; padding: 0 15px',
				],
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
					'{{WRAPPER}} .swiper-pagination span' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .swiper-pagination span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);


		//TODO: Dots Tab Start
		$this->start_controls_tabs(
			'dots_style_tabs',
			[
				'condition' => [
					'dots' => 'yes',
				],
			]
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
					'{{WRAPPER}} .swiper-pagination span' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots' => 'yes',
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
					'{{WRAPPER}} .swiper-pagination span.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination span:hover'                           => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots' => 'yes',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		//TODO: Dots Tab End

		$this->end_controls_section();

		/*
		 * Box Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'box_settings',
			[
				'label' => esc_html__( 'Box Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_margin',
			[
				'label'      => __( 'Margin Bottom', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-listing-category-wrapper .col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'layout' => 'style1',
				],
			]
		);

		//TODO: Start Tab
		$this->start_controls_tabs(
			'box_style_tabs'
		);

		$this->start_controls_tab(
			'box_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'box_bg',
			[
				'label'     => __( 'Box Background', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow',
				'label'    => __( 'Box Shadow', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => __( 'Border', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner',
			]
		);

		$this->add_control(
			'box_radius',
			[
				'label'      => __( 'Border Radius', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'box_bg_hover',
			[
				'label'     => __( 'Box Background - Hover', 'homlisti-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner::before, {{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner::after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_box_shadow_hover',
				'label'    => __( 'Box Shadow - Hover', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border_hover',
				'label'    => __( 'Border - Hover', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover',
			]
		);

		$this->add_control(
			'box_radius_hover',
			[
				'label'      => __( 'Border Radius - Hover', 'homlisti-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-listing-category-wrapper .listing-category-inner:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		//TODO: End Tab

		$this->end_controls_section();
	}


	protected function render() {
		$data = $this->get_settings();

		if ( 'style2' == $data['layout'] ) {
			$slider_column        = intval( ( isset( $data['slider_column'] ) && $data['slider_column'] ) ? $data['slider_column'] : 5 );
			$slider_column_tablet = intval( ( isset( $data['slider_column_tablet'] ) && $data['slider_column_tablet'] ) ? $data['slider_column_tablet'] : 3 );
			$slider_column_mobile = intval( ( isset( $data['slider_column_mobile'] ) && $data['slider_column_mobile'] ) ? $data['slider_column_mobile'] : 1 );


			//Swiper Slider =============================

			$data['slider_data'] = [
				'effect'         => 'slide',
				'loop'           => $data['infinite'] ? true : false,
				'speed'          => $data['speed'],
				'autoHeight'     => ( 'yes' == $data['adaptiveHeight'] ) ? true : false,
				'slidesPerView'  => $slider_column,
				'spaceBetween'   => isset( $data['slider_space']['size'] ) ? $data['slider_space']['size'] : 30,
				'centeredSlides' => 'yes' == $data['center_mode'] ? true : false,
				'navigation'     => [
					'nextEl' => '.elementor-swiper-button-prev',
					'prevEl' => '.elementor-swiper-button-next',
				],
				'pagination'     => [
					'el'        => '.swiper-pagination',
					'clickable' => true,
					'type'      => 'bullets',
				],
				'breakpoints'    => [
					'50'   => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => isset( $data['slider_space']['size'] ) ? $data['slider_space']['size'] : 30,
					],
					'640'  => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => isset( $data['slider_space']['size'] ) ? $data['slider_space']['size'] : 30,
					],
					'768'  => [
						'slidesPerView' => $slider_column_tablet,
						'spaceBetween'  => isset( $data['slider_space']['size'] ) ? $data['slider_space']['size'] : 30,
					],
					'1024' => [
						'slidesPerView' => $slider_column,
						'spaceBetween'  => isset( $data['slider_space']['size'] ) ? $data['slider_space']['size'] : 30,
					],
				],
			];


			if ( 'yes' == $data['autoplay'] ) {
				$data['slider_data']['autoplay'] = [
					'delay'                => $data['autoplaySpeed'],
					'disableOnInteraction' => true,
					'pauseOnMouseEnter'    => true,
				];
			}

			if ( 'yes' == $data['center_mode'] ) {
				$data['slider_data']['loop'] = true;
			}
		}

		$template = 'view-1';

		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		}

		$this->rt_template( $template, $data );
	}

}