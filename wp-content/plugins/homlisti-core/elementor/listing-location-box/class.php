<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Rtcl\Helpers\Link;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Listing_Location_Box extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Properties Location Box', 'homlisti-core' );
		$this->rt_base = 'rt-listing-location-box';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {

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
				'label'   => __( 'Location Style', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style # 1', 'homlisti-core' ),
					'style2' => __( 'Style # 2', 'homlisti-core' ),
					'style3' => __( 'Style # 3', 'homlisti-core' ),
					'style4' => __( 'Style # 4', 'homlisti-core' ),
					'style5' => __( 'Style # 5', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'location',
			[
				'label'   => esc_html__( 'Location', 'homlisti-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_get_categories_by_id( 'rtcl_location' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'background',
				'label'          => __( 'Background', 'homlisti-core' ),
				'types'          => [ 'classic', 'gradient', 'video' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
						'label'   => esc_html__( 'Background', 'homlisti-core' ),
					],
					'image'      => [
						'default' => [
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						],
					]
				],
				'selector'       => '{{WRAPPER}} .rt-el-listing-location-box .item-img',
			]
		);

		$this->add_control(
			'display_count',
			[
				'label'     => esc_html__( 'Display Count', 'homlisti-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'homlisti-core' ),
				'label_off' => esc_html__( 'Off', 'homlisti-core' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label'     => esc_html__( 'Enable Link', 'homlisti-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'homlisti-core' ),
				'label_off' => esc_html__( 'Off', 'homlisti-core' ),
				'default'   => 'yes',
			]
		);
		$this->add_control(
			'disable_border_radius',
			[
				'label'     => esc_html__( 'Disable Border Radius', 'homlisti-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'homlisti-core' ),
				'label_off' => esc_html__( 'Off', 'homlisti-core' ),
				'default'   => false,
			]
		);

		$this->add_control(
			'show_arrow_icon',
			[
				'label'        => __( 'Show Arrow Icon', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'homlisti-core' ),
				'label_off'    => __( 'Hide', 'homlisti-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'        => __( 'Border Radius', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'homlisti-core' ),
					'disable' => __( 'Disable', 'homlisti-core' )
				],
				'prefix_class' => 'listing-location-radiuse-'
			]
		);

		$this->end_controls_section();

		//Image Style
		//=============================================
		$this->start_controls_section(
			'image_settings',
			[
				'label' => esc_html__( 'Image Settings', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_height',
			[
				'type'            => Controls_Manager::SLIDER,
				'label'           => __( 'Image Height', 'homlisti-core' ),
				'size_units'      => [ 'px' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 240,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 220,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 200,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rt-el-listing-location-box .item-img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'bg_style_tabs'
		);

		$this->start_controls_tab(
			'bg_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'bg_overlay',
				'label'          => __( 'Overlay Color', 'homlisti-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background', 'homlisti-core' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .category-browse .category-box .item-img:after',
			]
		);

		$this->add_control(
			'img_grayscale',
			[
				'label'   => __( 'Image Grayscale', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'disable',
				'options' => [
					'disable' => __( 'Disable', 'homlisti-core' ),
					'enable' => __( 'Enable', 'homlisti-core' ),
				],
				'prefix_class' => 'rt-location-grayscale-',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'bg_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'bg_bg_overlay_hover',
				'label'          => __( 'Overlay Color - Hover', 'homlisti-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Hover', 'homlisti-core' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .category-browse .category-box .item-img .overlay',
			]
		);

		$this->add_control(
			'img_grayscale_hover',
			[
				'label'   => __( 'Image Grayscale', 'homlisti-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'disable',
				'options' => [
					'disable' => __( 'Disable', 'homlisti-core' ),
					'enable' => __( 'Enable', 'homlisti-core' ),
				],
				'prefix_class' => 'rt-location-grayscale-hover-',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		//Location Style
		//=============================================
		$this->start_controls_section(
			'location_style',
			[
				'label' => esc_html__( 'Location Title', 'homlisti-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .category-box .item-content .item-title',
			]
		);

		$this->start_controls_tabs(
			'location_style_tabs'
		);

		$this->start_controls_tab(
			'location_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'location_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-box .item-content .item-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .category-box .item-content .item-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'location_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'location_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-box .item-content .item-title:hover'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .category-box .item-content .item-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


		//Properties Count
		//=============================================
		$this->start_controls_section(
			'properties_count_settings',
			[
				'label'     => esc_html__( 'Properties Count Settings', 'homlisti-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_count' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label'        => __( 'Show Dots', 'homlisti-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'homlisti-core' ),
				'label_off'    => __( 'No', 'homlisti-core' ),
				'return_value' => 'is-dots',
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'count_type',
				'label'    => esc_html__( 'Count Typography', 'homlisti-core' ),
				'selector' => '{{WRAPPER}} .category-box:hover .item-content .item-count',
			]
		);

		$this->add_control(
			'count_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Count Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-box .item-content .item-count' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'count_dot_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Count Dot Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-box .item-content .item-count::before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout' => 'style2'
				]
			]
		);

		$this->end_controls_section();

		//Arrow button settings
		//=============================================
		$this->start_controls_section(
			'arrow_button_options',
			[
				'label'     => esc_html__( 'Arrow Button Options', 'homlisti-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => [ 'style2' ]
				]
			]
		);

		$this->start_controls_tabs(
			'arrow_btn_style_tabs'
		);

		$this->start_controls_tab(
			'arrow_btn_style_normal_tab',
			[
				'label' => __( 'Normal', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'arrow_btn_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Button Color', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-browse .category-box .item-content .link-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_btn_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Button Background', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-browse .category-box .item-content .link-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_btn_style_hover_tab',
			[
				'label' => __( 'Hover', 'homlisti-core' ),
			]
		);

		$this->add_control(
			'arrow_btn_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Button Color Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-browse .category-box .item-content .link-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrow_btn_bg_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Button Background Hover', 'homlisti-core' ),
				'selectors' => [
					'{{WRAPPER}} .category-browse .category-box .item-content .link-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function rt_term_post_count( $term_id ) {
		$args = [
			'nopaging'            => true,
			'fields'              => 'ids',
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
			'tax_query'           => [
				[
					'taxonomy' => 'rtcl_location',
					'field'    => 'term_id',
					'terms'    => $term_id,
				],
			],
		];

		$posts = get_posts( $args );

		return count( $posts );
	}

	protected function render() {
		$data = $this->get_settings();

		$term = get_term( $data['location'], 'rtcl_location' );

		if ( $term && ! is_wp_error( $term ) ) {
			$data['title']     = $term->name;
			$data['count']     = $this->rt_term_post_count( $term->term_id );
			$data['permalink'] = Link::get_location_page_link( $term );
		} else {
			$data['title']         = esc_html__( 'Please Select a Location and Background', 'homlisti-core' );
			$data['count']         = 0;
			$data['display_count'] = $data['enable_link'] = false;
		}
		$template = 'view-1';
		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$template = 'view-3';
		} elseif ( 'style4' == $data['layout'] ) {
			$template = 'view-4';
		} elseif ( 'style5' == $data['layout'] ) {
			$template = 'view-5';
		}

		$this->rt_template( $template, $data );
	}

}