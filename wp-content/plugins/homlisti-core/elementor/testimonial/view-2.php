<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @image_size
 */

namespace radiustheme\HomListi_Core;
$slider_data = json_encode( $data['slider_data'] );
?>
<div class="rt-el-testimonial-carousel <?php echo esc_attr( $data['layout'] ) ?>">
    <div class="slide-wrap">
        <div class="testimonial-carousel slick-carousel swiper" data-slick="<?php echo esc_attr( $slider_data ); ?>">
            <div class="swiper-wrapper">
				<?php foreach ( $data['items'] as $item ):
					if ( $item['image']['id'] ) {
						$img_src = wp_get_attachment_image_src( $item['image']['id'], 'rdtheme-square' );
						$img_url = $img_src[0];
					} else {
						$img_url = $item['image']['url'];
					}
					?>
                    <div class="swiper-slide slider-item">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="testimonial-banner" style="background-image: url(<?php echo esc_url( $img_url ) ?>);"></div>
                            </div>
                            <div class="col-md-7">
                                <div class="testimonial-content">
									<?php if ( $data['rating'] ) : ?>
                                        <div class="star-rating">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
									<?php endif; ?>
                                    <div class="rtin-content">
                                        <span><?php echo esc_html( $item['content'] ); ?></span>
                                    </div>
                                    <h3 class="item-title"><?php echo esc_html( $item['name'] ); ?></h3>
									<?php if ( $item['designation'] ): ?>
                                        <div class="item-subtitle"><?php echo esc_html( $item['designation'] ); ?></div>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
	        <?php if ( 'yes' == $data['dots'] ) : ?>
                <div class="swiper-pagination"></div>
	        <?php endif; ?>


        </div>

	    <?php if ( 'yes' == $data['arrows'] ) :
		    ?>
            <div class="elementor-swiper-button elementor-swiper-button-prev rt-prev">
                <i class="eicon-chevron-left" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e( 'Previous', 'homlisti-core' ); ?></span>
            </div>
            <div class="elementor-swiper-button elementor-swiper-button-next rt-next">
                <i class="eicon-chevron-right" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e( 'Next', 'homlisti-core' ); ?></span>
            </div>
	    <?php endif; ?>

    </div>
</div>