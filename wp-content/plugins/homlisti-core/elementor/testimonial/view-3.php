<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
$slider_data = json_encode($data['slider_data']);
$arrows = $data['arrows'] ? 'slider-arrow' : '';
?>
<div class="rt-el-testimonial-carousel <?php echo esc_attr($data['layout']) ?>">
    <div class="slide-wrap">
        <div class="testimonial-carousel slick-carousel swiper" data-slick="<?php echo esc_attr($slider_data); ?>" data-arrow="<?php echo esc_attr($arrows) ?>">
            <div class="swiper-wrapper">
            <?php foreach ( $data['items'] as $item ): ?>
                <div class="swiper-slide slider-item">
                   <div class="slick-inner">
	                   <?php
	                   if ( $item['image']['id'] ) {
		                   echo "<div class='testimonial-img'>";
		                   echo wp_get_attachment_image( $item['image']['id'], 'rdtheme-square' );
		                   echo "</div>";
	                   }
	                   ?>
                       <div class="testimonial-content">
                           <div class="rtin-content">
                               <span><?php echo esc_html( $item['content'] ); ?></span>
                           </div>
                           <div class="testimonial-footer">
                                <div class="footer-content">
                                    <?php if($data['rating']) : ?>
                                        <div class="star-rating">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="item-title"><?php echo esc_html( $item['name'] ); ?></h3>
                                    <?php if ( $item['designation'] ): ?>
                                        <div class="item-subtitle"><?php echo esc_html( $item['designation'] );?></div>
                                    <?php endif; ?>
                                </div>
                               <?php if($data['arrows']) : ?>
                                    <div class="slider-button">
<!--                                        <button class="slick-prev slick-arrow testimonial-prev testimonial-arrow" aria-label="Previous" type="button">Previous</button>-->
<!--                                        <button class="slick-next slick-arrow testimonial-next testimonial-arrow" aria-label="Next" type="button" >Next</button>-->


                                        <div class="elementor-swiper-button elementor-swiper-button-prev rt-prev">
                                            <i class="eicon-chevron-left" aria-hidden="true"></i>
                                            <span class="elementor-screen-only"><?php _e( 'Previous', 'homlisti-core' ); ?></span>
                                        </div>
                                        <div class="elementor-swiper-button elementor-swiper-button-next rt-next">
                                            <i class="eicon-chevron-right" aria-hidden="true"></i>
                                            <span class="elementor-screen-only"><?php _e( 'Next', 'homlisti-core' ); ?></span>
                                        </div>
                                    </div>
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
    </div>
</div>