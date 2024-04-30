<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\RDTheme;
use \WP_Widget;
use \RT_Widget_Fields;

class About_Widget extends WP_Widget {

	public function __construct() {
		$id = HOMLIST_CORE_THEME_PREFIX . '_about';
		parent::__construct(
			$id, // Base ID
			esc_html__( 'HomListi: About', 'homlisti-core' ), // Name
			[
				'description' => esc_html__( 'HomListi: About', 'homlisti-core' ),
			] );
	}

	public function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );
		$html = '';
		if ( ! empty( $instance['logo'] ) ) {
			$html = wp_get_attachment_image_src( $instance['logo'], 'full' );
			$html = $html[0];
			$html = '<div class="footer-logo"><a href="' . home_url( '/' ) . '"><img src="' . $html . '" alt="' . $html . '"></a></div>';
		} elseif ( ! empty( $instance['title'] ) ) {
			$html = apply_filters( 'widget_title', $instance['title'] );
			$html = $args['before_title'] . $html . $args['after_title'];
		} else {
			$custom_logo_id     = get_theme_mod( 'custom_logo' );
			$default_logo       = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : [
				Helper::get_img( 'logo.svg' ),
				157,
				40
			];
			$default_light_logo = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : [
				Helper::get_img( 'logo_light.svg' ),
				157,
				40
			];
			$main_logo          = ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo'], 'full' )
				: $default_logo;
			$light_logo         = ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] )
				? wp_get_attachment_image_src( RDTheme::$options['logo_light'], 'full' ) : $default_light_logo;
			if ( ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] )
			     && ! ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] )
			) {
				$light_logo = $main_logo;
			}
			if ( ! ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] )
			     && ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] )
			) {
				$main_logo = $light_logo;
			}
			if ( 1 == RDTheme::$footer_style && isset( $main_logo[0] ) ) {
				$html = '<div class="footer-logo one"><a href="' . home_url( '/' ) . '"><img src="' . $main_logo[0] . '" alt="' . esc_html__( 'Footer main Logo', 'homlisti-core' )
				        . '" width="' . $main_logo[1] . '" height="' . $main_logo[2] . '"></a></div>';
			} elseif ( isset( $light_logo[0] ) ) {
				$html = '<div class="footer-logo two"><a href="' . home_url( '/' ) . '"><img src="' . $light_logo[0] . '" alt="' . esc_html__( 'Footer light Logo',
						'homlisti-core' ) . '" width="' . $light_logo[1] . '" height="' . $light_logo[2] . '"></a></div>';
			}
		}

		echo wp_kses_post( $html );
		?>
        <p><?php if ( ! empty( $instance['description'] ) ) {
				echo wp_kses_post( $instance['description'] );
			} ?></p>
        <ul class="footer-social">
			<?php
			if ( ! empty( $instance['facebook'] ) ) {
				?>
                <li class="rtin-facebook"><a href="<?php echo esc_url( $instance['facebook'] ); ?>" target="_blank"><i
                            class="fab fa-facebook-f"></i></a></li><?php
			}
			if ( ! empty( $instance['twitter'] ) ) {
				?>
                <li class="rtin-twitter"><a href="<?php echo esc_url( $instance['twitter'] ); ?>" target="_blank"><i
                            class="fab fa-twitter"></i></a></li><?php
			}
			if ( ! empty( $instance['linkedin'] ) ) {
				?>
                <li class="rtin-linkedin"><a href="<?php echo esc_url( $instance['linkedin'] ); ?>" target="_blank"><i
                            class="fab fa-linkedin-in"></i></a></li><?php
			}
			if ( ! empty( $instance['pinterest'] ) ) {
				?>
                <li class="rtin-pinterest"><a href="<?php echo esc_url( $instance['pinterest'] ); ?>" target="_blank"><i
                            class="fab fa-pinterest-p"></i></a></li><?php
			}
			if ( ! empty( $instance['instagram'] ) ) {
				?>
                <li class="rtin-instagram"><a href="<?php echo esc_url( $instance['instagram'] ); ?>" target="_blank"><i
                            class="fab fa-instagram"></i></a></li><?php
			}
			if ( ! empty( $instance['youtube'] ) ) {
				?>
                <li class="rtin-youtube"><a href="<?php echo esc_url( $instance['youtube'] ); ?>" target="_blank"><i
                            class="fab fa-youtube"></i></a></li><?php
			}
			if ( ! empty( $instance['rss'] ) ) {
				?>
                <li class="rtin-rss"><a href="<?php echo esc_url( $instance['rss'] ); ?>" target="_blank"><i
                            class="fas fa-rss"></i></a></li><?php
			}

			if ( ! empty( $instance['zalo'] ) ) {
				?>
                <li class="rtin-rss"><a href="<?php echo esc_url( $instance['zalo'] ); ?>" target="_blank">
                    <img src="<?php echo esc_url( Helper::get_img( 'zalo-logo.svg' ) ) ?>"
                         alt="<?php echo esc_attr( 'Zalo Logo', 'homlisti' ) ?>">
                </a></li><?php
			}

			if ( ! empty( $instance['telegram'] ) ) {
				?>
                <li class="rtin-rss"><a href="tel:<?php echo esc_url( $instance['telegram'] ); ?>" target="_blank">
                    <i class="fas fa-paper-plane"></i>
                </a></li><?php
			}
			?>
        </ul>

		<?php
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ) {
		$instance                = [];
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['logo']        = ( ! empty( $new_instance['logo'] ) ) ? sanitize_text_field( $new_instance['logo'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? wp_kses_post( $new_instance['description'] ) : '';
		$instance['facebook']    = ( ! empty( $new_instance['facebook'] ) ) ? sanitize_text_field( $new_instance['facebook'] ) : '';
		$instance['twitter']     = ( ! empty( $new_instance['twitter'] ) ) ? sanitize_text_field( $new_instance['twitter'] ) : '';
		$instance['linkedin']    = ( ! empty( $new_instance['linkedin'] ) ) ? sanitize_text_field( $new_instance['linkedin'] ) : '';
		$instance['pinterest']   = ( ! empty( $new_instance['pinterest'] ) ) ? sanitize_text_field( $new_instance['pinterest'] ) : '';
		$instance['instagram']   = ( ! empty( $new_instance['instagram'] ) ) ? sanitize_text_field( $new_instance['instagram'] ) : '';
		$instance['youtube']     = ( ! empty( $new_instance['youtube'] ) ) ? sanitize_text_field( $new_instance['youtube'] ) : '';
		$instance['rss']         = ( ! empty( $new_instance['rss'] ) ) ? sanitize_text_field( $new_instance['rss'] ) : '';
		$instance['zalo']        = ( ! empty( $new_instance['zalo'] ) ) ? sanitize_text_field( $new_instance['zalo'] ) : '';
		$instance['telegram']    = ( ! empty( $new_instance['telegram'] ) ) ? sanitize_text_field( $new_instance['telegram'] ) : '';

		return $instance;
	}

	public function form( $instance ) {
		$defaults = [
			'title'       => '',
			'logo'        => '',
			'description' => '',
			'facebook'    => '',
			'twitter'     => '',
			'linkedin'    => '',
			'pinterest'   => '',
			'instagram'   => '',
			'youtube'     => '',
			'rss'         => '',
			'zalo'        => '',
			'telegram'    => '',
		];
		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = [
			'title'       => [
				'label' => esc_html__( 'Title', 'homlisti-core' ),
				'type'  => 'text',
			],
			'logo'        => [
				'label' => esc_html__( 'Logo', 'homlisti-core' ),
				'type'  => 'image',
				'desc'  => esc_html__( 'If you don\'t choose logo then default logo will show from Customizer > General', 'homlisti-core' ),
			],
			'description' => [
				'label' => esc_html__( 'Description', 'homlisti-core' ),
				'type'  => 'textarea',
			],
			'facebook'    => [
				'label' => esc_html__( 'Facebook URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'twitter'     => [
				'label' => esc_html__( 'Twitter URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'linkedin'    => [
				'label' => esc_html__( 'Linkedin URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'pinterest'   => [
				'label' => esc_html__( 'Pinterest URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'instagram'   => [
				'label' => esc_html__( 'Instagram URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'youtube'     => [
				'label' => esc_html__( 'YouTube URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'rss'         => [
				'label' => esc_html__( 'Rss Feed URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'zalo'        => [
				'label' => esc_html__( 'Zalo Feed URL', 'homlisti-core' ),
				'type'  => 'url',
			],
			'telegram'    => [
				'label' => esc_html__( 'Telegram Feed URL', 'homlisti-core' ),
				'type'  => 'url',
			],
		];

		RT_Widget_Fields::display( $fields, $instance, $this );
	}

}