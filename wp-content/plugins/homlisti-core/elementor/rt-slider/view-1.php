<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$show_dots   = ( in_array( $data['navigation'], [ 'dots', 'both' ] ) );
$show_arrows = ( in_array( $data['navigation'], [ 'arrows', 'both' ] ) );

$slides_count  = count( $data['sliders'] );
$slider_option = json_encode( $data['swiper_data'] );

$animation_overflow = '';
if ( 'hidden' == $data['animation_overflow'] ) {
	$animation_overflow = "overflow:hidden;";
}
//wp_enqueue_script( 'swiper' );

//echo esc_attr( $data['enable_gallery_thumb'] );
//echo htmlspecialchars( wp_json_encode( $data['swiper_data'] ) );
//echo esc_attr( $slider_option );
?>
<div class="rt-main-slider-wrapper style1">
    <div class="rt-slider-wrapper rt-swiper-slider swiper <?php echo esc_attr( $data['arrow_visibility'] ) ?>"
         data-options='<?php echo esc_attr(wp_json_encode( $data['swiper_data'] )); ?>'
         data-gallery="<?php echo esc_attr( $data['enable_gallery_thumb'] ); ?>">
        <div class="rt-slider swiper-wrapper">
			<?php if ( $data['sliders'] ) :
				foreach ( $data['sliders'] as $slide ) :
					$image = wp_get_attachment_image_url( $slide['slider_image']['id'], $data['thumbnail_size'] );
					if ( ! $image ) {
						$image = $slide['slider_image']['url'];
					}

					$target   = $slide['slider_link']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $slide['slider_link']['nofollow'] ? ' rel="nofollow"' : '';
					$btn_link = $slide['slider_link']['url'] ? $slide['slider_link']['url'] : '#';

					// Title Animatin
					$title_x_paralax        = $slide["title_x_paralax"]["size"] ? ' data-swiper-parallax-x="' . $slide["title_x_paralax"]["size"] . '"' : '';
					$title_y_paralax        = $slide["title_y_paralax"]["size"] ? ' data-swiper-parallax-y="' . $slide["title_y_paralax"]["size"] . '"' : '';
					$title_paralax_scale    = $slide["title_paralax_scale"]["size"] ? ' data-swiper-parallax-scale="' . $slide["title_paralax_scale"]["size"] . '"' : '';
					$title_paralax_opacity  = ' data-swiper-parallax-opacity="' . $slide["title_paralax_opacity"]["size"] . '"';
					$title_paralax_duration = $slide["title_paralax_duration"]["size"] ? ' data-swiper-parallax-duration="' . $slide["title_paralax_duration"]["size"] . '"' : '';
					$title_paralax_delay    = $slide["title_paralax_delay"]["size"] ? 'transition-delay:' . $slide["title_paralax_delay"]["size"] . 'ms' : '';

					// Subtitle Animatin
					$subtitle_x_paralax        = $slide["subtitle_x_paralax"]["size"] ? ' data-swiper-parallax-x="' . $slide["subtitle_x_paralax"]["size"] . '"' : '';
					$subtitle_y_paralax        = $slide["subtitle_y_paralax"]["size"] ? ' data-swiper-parallax-y="' . $slide["subtitle_y_paralax"]["size"] . '"' : '';
					$subtitle_paralax_scale    = $slide["subtitle_paralax_scale"]["size"] ? ' data-swiper-parallax-scale="' . $slide["subtitle_paralax_scale"]["size"] . '"' : '';
					$subtitle_paralax_opacity  = ' data-swiper-parallax-opacity="' . $slide["subtitle_paralax_opacity"]["size"] . '"';
					$subtitle_paralax_duration = $slide["subtitle_paralax_duration"]["size"] ? ' data-swiper-parallax-duration="' . $slide["subtitle_paralax_duration"]["size"]
					                                                                           . '"' : '';
					$subtitle_paralax_delay    = $slide["subtitle_paralax_delay"]["size"] ? 'transition-delay:' . $slide["subtitle_paralax_delay"]["size"] . 'ms' : '';

					// Button Animatin
					$btn_x_paralax        = $slide["btn_x_paralax"]["size"] ? ' data-swiper-parallax-x="' . $slide["btn_x_paralax"]["size"] . '"' : '';
					$btn_y_paralax        = $slide["btn_y_paralax"]["size"] ? ' data-swiper-parallax-y="' . $slide["btn_y_paralax"]["size"] . '"' : '';
					$btn_paralax_scale    = $slide["btn_paralax_scale"]["size"] ? ' data-swiper-parallax-scale="' . $slide["btn_paralax_scale"]["size"] . '"' : '';
					$btn_paralax_opacity  = ' data-swiper-parallax-opacity="' . $slide["btn_paralax_opacity"]["size"] . '"';
					$btn_paralax_duration = $slide["btn_paralax_duration"]["size"] ? ' data-swiper-parallax-duration="' . $slide["btn_paralax_duration"]["size"] . '"' : '';
					$btn_paralax_delay    = $slide["btn_paralax_delay"]["size"] ? 'transition-delay:' . $slide["btn_paralax_delay"]["size"] . 'ms' : '';

					// BG Animation
					$slider_zoomin = $slider_zoomout = '';
					if ( $slide['slider_bg_animation'] == 'zoom-in' ) {
						$slider_zoomin = 'data-swiper-parallax-scale="1.1" data-swiper-parallax-duration="1500"';
					} elseif ( $slide['slider_bg_animation'] == 'zoom-out' ) {
						$slider_zoomout = 'zoom-out';
					}

					?>
                    <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ) ?>">

                        <div class="slider-inner-wrapper">
                            <div class="bg <?php echo esc_attr( $slider_zoomout ); ?>" <?php echo $slider_zoomin ?>
                                 style="background-image:url(<?php echo esc_url( $image ) ?>)"></div>

							<?php if ( ! ( $data['enable_gallery_thumb'] == 'enable' && $data['hide_all_content'] == 'yes' ) ) : ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
											<?php if ( $slide['slider_title'] ) : ?>
                                                <div class="slider-title-wrap rt-slider-content-wrap" style="<?php echo esc_attr( $animation_overflow ); ?>">
                                                    <h2 style="<?php echo esc_attr( $title_paralax_delay ) ?>" <?php echo( $title_x_paralax . $title_y_paralax
													                                                                       . $title_paralax_scale . $title_paralax_opacity
													                                                                       . $title_paralax_duration ) ?>>
														<?php echo $slide['slider_title'] ?>
                                                    </h2>
                                                </div>
											<?php endif; ?>

											<?php if ( $slide['slider_subtitle'] ) : ?>
                                                <div class="slider-subtitle-wrap rt-slider-content-wrap" style="<?php echo esc_attr( $animation_overflow ); ?>">
                                                    <h4 style="<?php echo esc_attr( $subtitle_paralax_delay ) ?>" <?php echo( $subtitle_x_paralax . $subtitle_y_paralax
													                                                                          . $subtitle_paralax_scale . $subtitle_paralax_opacity
													                                                                          . $subtitle_paralax_duration ) ?>><?php echo $slide['slider_subtitle'] ?></h4>
                                                </div>
											<?php endif; ?>

											<?php if ( $data['button_text'] ) : ?>
                                                <div class="slider-button rt-slider-content-wrap" style="<?php echo esc_attr( $animation_overflow ); ?>">
                                                    <div class="slider-btn" style="<?php echo esc_attr( $btn_paralax_delay ) ?>" <?php echo( $btn_x_paralax . $btn_y_paralax
													                                                                                         . $btn_paralax_scale
													                                                                                         . $btn_paralax_opacity
													                                                                                         . $btn_paralax_duration ) ?>>
                                                        <a href="<?php echo esc_url( $btn_link ) ?>" class="slider-dark-button" <?php echo $target
														                                                                                   . $nofollow; ?>><span><?php echo esc_html( $data['button_text'] ) ?></span></a>
                                                    </div>
                                                </div>
											<?php endif; ?>
                                        </div>
                                    </div>

                                </div>
							<?php endif; ?>
                        </div>

                    </div>
				<?php endforeach;
			endif; ?>
        </div>

		<?php if ( 1 < $slides_count ) : ?>
			<?php if ( $show_dots ) : ?>
                <div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ( $show_arrows ) : ?>
                <div class="elementor-swiper-button elementor-swiper-button-prev rt-prev">
                    <i class="eicon-chevron-left" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php _e( 'Previous', 'homlisti-core' ); ?></span>
                </div>
                <div class="elementor-swiper-button elementor-swiper-button-next rt-next">
                    <i class="eicon-chevron-right" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php _e( 'Next', 'homlisti-core' ); ?></span>
                </div>
			<?php endif; ?>
		<?php endif; ?>

    </div>

	<?php if ( $data['enable_gallery_thumb'] ) :
		// Swiper Gallery Options
		$gallery_opt = $data['swiper_gallery']; ?>
        <div class="rt-slider-gallery-wrapper">
            <div class="rt-gallery-thumbs swiper"
                 data-space_between="<?php echo esc_attr( $gallery_opt['spaceBetween'] ) ?>"
                 data-slides_per_view="<?php echo esc_attr( $gallery_opt['slidesPerView'] ) ?>"
                 data-slider_loop="<?php echo esc_attr( $gallery_opt['sliderLoop'] ); ?>">
                <div class="swiper-wrapper">
					<?php if ( $data['sliders'] ) :
						foreach ( $data['sliders'] as $slide ) :
							$image = wp_get_attachment_image_url( $slide['slider_image']['id'], 'rdtheme-size2' );
							if ( ! $image ) {
								$image = $slide['slider_image']['url'];
							}
							?>
                            <div class="swiper-slide">
                                <div class="img-bg" style="background-image:url(<?php echo esc_url( $image ) ?>)"></div>
                            </div>
						<?php endforeach;
					endif; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

</div>