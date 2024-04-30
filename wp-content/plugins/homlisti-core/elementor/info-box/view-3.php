<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
$target   = $data['link']['is_external'] ? ' target="_blank"' : '';
$nofollow = $data['link']['nofollow'] ? ' rel="nofollow"' : '';
?>
<div class="rt-info-box-wrap-3 rt-info-box">
    <div class="service-box <?php echo esc_attr($data['bg_animation_2']) ?>">
        <div class="icon-holder <?php echo esc_attr($data['bg_animation']. ' '.$data['icon_animation'].' '.$data['image_invert']) ?>">
			<?php
			echo $data['link']['url'] ? '<a href="' . $data['link']['url'] . '"' . $target . $nofollow . '>' : null;
			if ( 'image' == $data['icon_type'] ) {
				echo "<div class='img-wrap'><div class='hover-bg'></div>";
				echo wp_get_attachment_image( $data['image_icon']['id'], 'full' );
				echo "</div>";
			} else {
				\Elementor\Icons_Manager::render_icon( $data['info_icon'], [ 'aria-hidden' => 'true' ] );
			}
			echo $data['link']['url'] ? '</a>' : null;
			?>
        </div>

        <div class="content-holder content-align">
			<?php if ( $data['title'] ) : ?>
                <h3 class="info-title">
					<?php
					echo $data['link']['url'] ? '<a href="' . $data['link']['url'] . '"' . $target . $nofollow . '>' : null;
					echo wp_kses_post( $data['title'] );
					echo $data['link']['url'] ? '</a>' : null;
					?>
                </h3>
			<?php endif; ?>

			<?php if ( $data['sub_title'] ) : ?>
                <p class="content-align"><?php echo wp_kses_post( $data['sub_title'] ); ?></p>
			<?php endif; ?>
        </div>

		<?php if ( $data['show_readmore_btn'] ) : ?>
            <div class="read-more-btn <?php echo esc_attr($data['read_more_btn_visibility']) ?>">
				<a class="button-el" href="<?php echo esc_url( $data['link']['url'] ) ?>" <?php echo esc_attr( $target . ' ' . $nofollow ) ?>>
					<div class="button-text">
						<?php ($data['btn_icon_position'] == 'left' && $data['show_btn_icon']) ? \Elementor\Icons_Manager::render_icon( $data['button_icon'], [ 'aria-hidden' => 'true' ] ) : NULL; ?>
						<span><?php echo esc_html( $data['read_more_btn_text'] ); ?></span>
						<?php ($data['btn_icon_position'] == 'right' && $data['show_btn_icon']) ? \Elementor\Icons_Manager::render_icon( $data['button_icon'], [ 'aria-hidden' => 'true' ] ) : NULL; ?>
					</div>
				</a>
            </div>
		<?php endif; ?>
    </div>
</div>
