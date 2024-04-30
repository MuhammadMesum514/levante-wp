<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Contact_Form extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Contact Form', 'homlisti-core' );
		$this->rt_base = 'rt-contact-form';
		parent::__construct( $data, $args );
	}

    protected function register_controls() {

        $this->start_controls_section(
            'sec_general',
            [
                'label' => esc_html__( 'General', 'homlisti-core' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Choose Image', 'homlisti-core' ),
                'type' => Controls_Manager::MEDIA,
                'selectors' => array( '{{WRAPPER}} .rtin-right' => 'background-image: url({{URL}})' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => esc_html__( 'Title', 'homlisti-core' ),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => 'Lorem Ipsum'
            ]
        );

        $this->add_control(
            'content',
            [
                'label'     => esc_html__( 'Shortcode', 'homlisti-core' ),
                'type'      => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'description',
            [
                'label'     => esc_html__( 'Description', 'homlisti-core' ),
                'type'      => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'type' => Controls_Manager::SLIDER,
                'label'   => __( 'Min Height', 'homlisti-core' ),
                'size_units' => array( 'px' ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                ),
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'selectors' => array(
                    '{{WRAPPER}} .rtin-left' => 'min-height: {{SIZE}}{{UNIT}};',
                )
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
		$data = $this->get_settings();

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}