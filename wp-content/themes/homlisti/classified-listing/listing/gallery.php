<?php
/**
 * @var array[] $images
 * @var array[] $videos
 * @var string $video_url
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\HomListi\RDTheme;
use radiustheme\HomListi\Helper;
use Rtcl\Helpers\Functions;

global $listing;
$opt_layout  = ! empty( RDTheme::$options['single_listing_style'] ) ? RDTheme::$options['single_listing_style'] : '1';
$meta_layout = get_post_meta( $listing->get_id(), 'listing_layout', true );
if ( ! $meta_layout || 'default' == $meta_layout ) {
	$style = $opt_layout;
} else {
	$style = $meta_layout;
}

$thumbSize            = $style == '2' ? 'full' : 'rtcl-gallery';
$total_gallery_image  = count( $images );
$total_gallery_videos = count( $videos );
$total_gallery_item   = $total_gallery_image + $total_gallery_videos;

if ( $total_gallery_item ) :
	$owl_class = $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ? " owl-carousel" : '';
	?>
    <div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper">

		<?php

		if ( '3' == $style ) {
			$has_many_images = '';
			if ( $total_gallery_image > 4 ) {
				$has_many_images = 'has-more-images';
			}
			?>
            <div class="listing-gallery <?php echo esc_attr( $has_many_images ) ?>">
				<?php
				if ( $total_gallery_videos ) {
					foreach ( $videos as $index => $video_url ) { ?>
                        <div class="rtcl-img-item blocks-gallery-item">
                            <iframe class="rtcl-lightbox-iframe"
                                    src="<?php echo Functions::get_sanitized_embed_url( $video_url ) ?>"
                                    style="width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000"
                                    frameborder="0" webkitAllowFullScreen
                                    mozallowfullscreen allowFullScreen></iframe>
                        </div>
						<?php
					}
				}

				if ( $total_gallery_image ) {
					foreach ( $images as $index => $image ) :
						$image_attributes = wp_get_attachment_image_src( $image->ID, $thumbSize );
						$image_full = wp_get_attachment_image_src( $image->ID, 'large' );
						$should_hide = '';

						$d_none_condition = $total_gallery_videos ? 2 : 3;
						if ( $index > $d_none_condition ) {
							$should_hide = 'd-none';
						}
						?>

                        <div class="rtcl-img-item blocks-gallery-item <?php echo esc_attr( $should_hide ) ?>">
                            <a href="<?php echo esc_url( $image_full[0] ); ?>">
                                <img src="<?php echo esc_url( $image_attributes[0] ); ?>"
                                     alt="<?php esc_attr( $listing->the_title() ); ?>"
                                     data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                     class="rtcl-responsive-img"/>
                            </a>
							<?php
							$_total_gallery_condition = $total_gallery_videos ? 3 : 4;
							$_index_check             = $total_gallery_videos ? 2 : 3;
							if ( $total_gallery_image > $_total_gallery_condition && $index === $_index_check ) {
								echo "<span class='count-overlay'><img src='" . esc_url( Helper::get_img( 'img-icon.svg' ) ) . "' alt='" . esc_attr__( 'Gallery Image', 'homlisti' ) . "'>{$total_gallery_image}+</span>";
							}
							?>
                        </div>
					<?php endforeach;
				}
				?>
            </div>
			<?php
		}

		?>

        <!-- Slider -->
        <div class="rtcl-slider">
            <div class="swiper-wrapper">
				<?php

				if ( $total_gallery_videos ) {
					foreach ( $videos as $index => $video_url ) { ?>
                        <div class="swiper-slide rtcl-slider-item ">
                            <iframe class="rtcl-lightbox-iframe"
                                    src="<?php echo Functions::get_sanitized_embed_url( $video_url ) ?>"
                                    style="width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000"
                                    frameborder="0" webkitAllowFullScreen
                                    mozallowfullscreen allowFullScreen></iframe>
                        </div>
						<?php
					}
				}


				if ( $total_gallery_image ) {
					foreach ( $images as $index => $image ) :
						$image_attributes = wp_get_attachment_image_src( $image->ID, $thumbSize );
						$image_full = wp_get_attachment_image_src( $image->ID, 'full' );
						?>
                        <div class="swiper-slide rtcl-slider-item">
                            <img src="<?php echo esc_html( $image_attributes[0] ); ?>"
                                 data-src="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image_width="<?php echo esc_attr( $image_full[1] ) ?>"
                                 data-large_image_height="<?php echo esc_attr( $image_full[2] ) ?>"
                                 alt="<?php echo get_the_title( $image->ID ); ?>"
                                 data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                 class="rtcl-responsive-img"/>
                        </div>
					<?php endforeach;
				}

				?>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>


		<?php
		//Style 2 hea
		if ( '2' == $style ) {
			Helper::get_custom_listing_template( 'listing-heading' );
		}
		?>


        <!--Default Slider -->

		<?php if ( $total_gallery_item > 1 ): ?>
            <!-- Slider nav -->
            <div class="rtcl-slider-nav">
                <div class="swiper-wrapper">
					<?php
					if ( $total_gallery_videos ) {
						foreach ( $videos as $index => $video_url ) { ?>
                            <div class="swiper-slide rtcl-slider-thumb-item rtcl-slider-video-thumb">
                                <img src="<?php echo Functions::get_embed_video_thumbnail_url( $video_url ) ?>"
                                     class="rtcl-gallery-thumbnail" alt=""/>
                            </div>
							<?php
						}
					}
					if ( $total_gallery_image ) {
						foreach ( $images as $index => $image ) : ?>
                            <div class="swiper-slide rtcl-slider-thumb-item">
								<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery-thumbnail' ); ?>
                            </div>
						<?php endforeach;
					} ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
		<?php endif; ?>


    </div>
<?php endif;