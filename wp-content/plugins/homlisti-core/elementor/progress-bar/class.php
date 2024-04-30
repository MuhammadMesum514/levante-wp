<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class RT_Progress_Bar extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Progress Bar', 'homlisti-core' );
		$this->rt_base = 'progress';
		parent::__construct( $data, $args );
	}

    protected function register_controls() {
		$this->start_controls_section(
			'section_progress',
			[
				'label' => __( 'Progress Bar', 'homlisti-core' ),
			]
		);

        $this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Style', 'homlisti-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style 1', 'homlisti-core' ),
					'style2' => __( 'Style 2', 'homlisti-core' ),
				],

			]
		);

        $this->add_control(
			'progress_animation',
			[
				'label'   => esc_html__( 'Progress Bar Animation', 'homlisti-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'select' => __( 'Select', 'homlisti-core' ),
					'progress-bar-striped' => __( 'Striped BG', 'homlisti-core' ),
					'progress-bar-striped progress-bar-animated' => __( 'Striped Animation', 'homlisti-core' ),
				],

			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'homlisti-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'homlisti-core' ),
				'default' => __( 'My Skill', 'homlisti-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'progress_type',
			[
				'label' => __( 'Type', 'homlisti-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'homlisti-core' ),
					'info' => __( 'Info', 'homlisti-core' ),
					'success' => __( 'Success', 'homlisti-core' ),
					'warning' => __( 'Warning', 'homlisti-core' ),
					'danger' => __( 'Danger', 'homlisti-core' ),
				],
			]
		);

		$this->add_control(
			'percent',
			[
				'label' => __( 'Percentage', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control( 'display_percentage', [
			'label' => __( 'Display Percentage', 'homlisti-core' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'show',
			'options' => [
				'show' => __( 'Show', 'homlisti-core' ),
				'hide' => __( 'Hide', 'homlisti-core' ),
			],
		] );

        $this->add_control(
			'percent_y_position',
			[
				'label' => __( 'Percentage Position', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 1,
					],
				],
                'default' => [
					'size' => -26,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-percentage .wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'layout' => 'style2',
                    'display_percentage' => 'show'
                ]
			]
		);

		$this->add_control(
			'inner_text',
			[
				'label' => __( 'Inner Text', 'homlisti-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'e.g. Web Designer', 'homlisti-core' ),
				'default' => __( 'Web Designer', 'homlisti-core' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'homlisti-core' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => __( 'Progress Bar', 'homlisti-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => __( 'Bar Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar .elementor-progress-wrapper .elementor-progress-bar' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bar_bg_color',
			[
				'label' => __( 'Background Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style1 .elementor-progress-wrapper' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'style1'
				]
			]
		);

		$this->add_control(
			'bar2_bg_color',
			[
				'label' => __( 'Background Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-wrapper::before' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'layout' => 'style2'
				]
			]
		);

		$this->add_control(
			'striped_bg_color',
			[
				'label' => __( 'Striped Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style2 .progress-bar-striped' => 'background-image: linear-gradient( -45deg, {{VALUE}} 25%, transparent 25%, transparent 50%, {{VALUE}} 50%, {{VALUE}} 75%, transparent 75%, transparent);',
				],
                'condition' => [
                    'progress_animation!' => 'select'
                ]
			]
		);

		$this->add_control(
			'bar_height',
			[
				'label' => __( 'Height', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style1 .elementor-progress-bar' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'style1'
				]
			]
		);

		$this->add_control(
			'bar2_height',
			[
				'label' => __( 'Height', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-bar' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-wrapper::before' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'style2'
				]
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label' => __( 'Border Radius', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar .elementor-progress-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				],
				'condition' => [
					'layout' => 'style1'
				]
			]
		);

		$this->add_control(
			'bar2_border_radius',
			[
				'label' => __( 'Border Radius', 'homlisti-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-bar' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-wrapper::before' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'style2'
				]
			]
		);

		$this->add_control(
			'inner_text_heading',
			[
				'label' => __( 'Inner Text', 'homlisti-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bar_inline_color',
			[
				'label' => __( 'Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar .elementor-progress-bar' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-percentage .wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rt-progress-bar.style2 .elementor-progress-percentage .shape' => 'background-color: {{VALUE}};',
				],

			]
		);

		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bar_inner_typography',
				'selector' => '{{WRAPPER}} .rt-progress-bar .elementor-progress-bar',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'bar_inner_shadow',
				'selector' => '{{WRAPPER}} .rt-progress-bar .elementor-progress-bar',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title Style', 'homlisti-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'homlisti-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-progress-bar .progress-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .rt-progress-bar .progress-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .rt-progress-bar .progress-title',
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';
		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		}
        $this->rt_template( $template, $data );
	}
}