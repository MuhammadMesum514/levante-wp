<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$show_dots   = (in_array($data['navigation'], ['dots', 'both']));
$show_arrows = (in_array($data['navigation'], ['arrows', 'both']));

$slides_count  = count($data['sliders']);
$slider_option = json_encode($data['swiper_data']);

//wp_enqueue_script('swiper');
?>
<div class="rt-main-slider-wrapper style2">
	<div class="rt-slider-wrapper swiper-container rt-swiper-slider <?php echo esc_attr($data['arrow_visibility']) ?>"
         data-options="<?php echo esc_js($slider_option); ?>"
         data-gallery = <?php echo esc_attr('disable') ?>>
		<div class="rt-slider swiper-wrapper">
			<?php if ($data['carousel_images']) :
				foreach ($data['carousel_images'] as $slide) : ?>
					<div class="swiper-slide">

                            <?php echo wp_get_attachment_image($slide['id'], $data['thumbnail_size']) ?>

					</div>
				<?php endforeach;
			endif; ?>
		</div>

		<?php if (1 < $slides_count) : ?>
			<?php if ($show_dots) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ($show_arrows) : ?>
				<div class="elementor-swiper-button elementor-swiper-button-prev rt-prev">
					<i class="eicon-chevron-left" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php _e('Previous', 'homlisti-core'); ?></span>
				</div>
				<div class="elementor-swiper-button elementor-swiper-button-next rt-next">
					<i class="eicon-chevron-right" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php _e('Next', 'homlisti-core'); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	</div>

</div>