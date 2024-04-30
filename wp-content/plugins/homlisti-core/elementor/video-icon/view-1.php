<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
use radiustheme\HomListi\Helper;

$box_shadow = '';
$shadow_color = str_replace(',', '', Helper::hex2rgb($data['animation_border1_color']));
$animation_opacity1 = $data['animation_opacity'] ? $data['animation_opacity'] : 30;
$animation_opacity2 = $animation_opacity1-10;
$animation_opacity3 = $animation_opacity1-20;
if('icon-style1' == $data['layout']) {
	$box_shadow = "box-shadow: 0 0 0 10px rgb({$shadow_color} / {$animation_opacity1}%), 0 0 0 20px rgb({$shadow_color} / {$animation_opacity2}%), 0 0 0 30px rgb({$shadow_color} / {$animation_opacity3}%);";
}
$img_url = wp_get_attachment_image_src( $data['image']['id'], 'full' );
$img_bg = '';
if($img_url) {
    $img_bg = "background-image:url(".esc_attr($img_url[0]).")";
}
?>
<div class="rt-video-icon-wrapper <?php echo esc_attr( $data['layout'] ) ?>" style="<?php echo esc_attr($img_bg) ?>">
    <div class="video-icon-inner">
        <div class="icon-left">
            <div class="icon-box">
                <a class="popup-youtube video-popup-icon" href="<?php echo esc_url( $data['video_url'] ) ?>">
                    <span class="triangle"></span>
                    <span class="rt-ripple-effect" style="<?php echo esc_attr($box_shadow) ?>"></span>
                </a>
            </div>
        </div>
		<?php if ( $data['button_text'] ) : ?>
            <div class="icon-right">
                <a class="popup-youtube button-text" href="<?php echo esc_url( $data['video_url'] ) ?>">
                    <?php echo esc_html( $data['button_text'] ) ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>

