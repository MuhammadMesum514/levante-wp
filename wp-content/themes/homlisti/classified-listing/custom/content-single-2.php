<?php
/**
 * Full width layout
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.5
 */

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\Listing_Functions;
use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;

global $listing;

$custom_field_ids = Functions::get_custom_field_ids( $listing->get_last_child_category_id() );
$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}
$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
$group_id           = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;
$get_class_by_title = get_post_field( 'post_name', $group_id );
?>

<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( $get_class_by_title, $listing ); ?>>
	<?php do_action( 'rtcl_single_listing_content' ); ?>

    <div class="container pt-4 rtcl-widget-border-enable rtcl-widget-is-sticky mb-5">
        <div class="rtcl-main-content-wrapper">
            <!-- Content Sidebar -->

			<?php Helper::get_custom_listing_template( 'listing-float-menu' ); ?>

            <div class="rtcl-content-wrapper">
                <!--Listing Heading-->
				<?php //Helper::get_custom_listing_template( 'listing-heading' ); ?>
                <!--End Listing Heading-->

                <div class="row rtcl-listing-content-area">
                    <!-- Main content -->
                    <div class="<?php echo esc_attr( implode( ' ', $content_class ) ); ?>">
                        <div class="rtcl-single-listing-details">

                            <!-- Overview -->
							<?php
							$list_info_id = '';
							if ( RDTheme::$options['overview_show_hide'] && ! empty( $custom_field_ids ) && $group_id != 'select' ): ?>
                                <div class="product-overview widget" id="list-info">
									<?php if ( RDTheme::$options['overview_text'] ) : ?>
                                        <div class="item-heading">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h2 class="heading-title"><?php echo esc_html( RDTheme::$options['overview_text'] ); ?></h2>
                                                </div>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php Helper::get_custom_listing_template( 'cfg-amenities' ); ?>
                                </div>
							<?php else :
								$list_info_id = "id=list-info";
								?>
							<?php endif; ?>

                            <!-- Description -->
							<?php if ( $listing->get_the_content() ) : ?>
                                <div class="product-description widget" <?php echo esc_attr( $list_info_id ) ?>>
                                    <div class="item-heading">
                                        <h2 class="heading-title"><?php echo esc_html( RDTheme::$options['about_this_listing'] ); ?></h2>
                                    </div>
									<?php $listing->the_content(); ?>
                                </div>
							<?php endif; ?>

                            <!-- Features & Amenities -->
							<?php if ( RDTheme::$options['feature_aminities_show_hide'] && ! empty( $custom_field_ids ) ): ?>
                                <div class="product-overview product-cf-information widget">
									<?php if ( RDTheme::$options['feature_text'] ) : ?>
                                        <div class="item-heading">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h2 class="heading-title"><?php echo esc_html( RDTheme::$options['feature_text'] ); ?></h2>
                                                </div>
                                            </div>
                                        </div>
									<?php endif; ?>
									<?php $listing->the_custom_fields(); ?>
                                </div>
							<?php endif; ?>

                            <!-- Map -->
							<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ): ?>
                                <div class="product-map widget" id="map">
                                    <div class="item-heading">
                                        <h2 class="heading-title"><?php echo esc_html( RDTheme::$options['map_location_label'] ); ?></h2>
                                    </div>
									<?php //$listing->the_map(); ?>
									<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
                                </div>
							<?php endif; ?>

                            <!-- Floor Plan -->
							<?php Helper::get_custom_listing_template( 'floor-plan' ); ?>

                            <!-- Video -->
							<?php if ( ! empty( $video_urls ) ): ?>
                                <div class="product-video widget" id="video">
                                    <div class="item-heading">
                                        <h2 class="heading-title"><?php echo esc_html( RDTheme::$options['property_video_label'] ); ?></h2>
                                    </div>
                                    <div class="item-img">
										<?php $listing->the_thumbnail( 'rtcl-gallery' ); ?>
                                        <div class="video-icon">
                                            <a class="play-btn popup-youtube" href="<?php echo esc_url( $video_urls[0] ); ?>">
                                                <i class="rt-play-circle"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
							<?php endif; ?>

                            <!-- Yelp Nearby Place -->
                            <div id="list-review">
								<?php
								if ( Listing_Functions::is_enable_yelp_review() ) {
									Helper::get_custom_listing_template( 'yelp-review' );
								}
								?>
                            </div>

                            <!-- 360 degree view -->
							<?php
							if ( Listing_Functions::is_enable_panorama_view() ) {
								Helper::get_custom_listing_template( 'panorama-view' );
							}
							?>

	                        <?php Helper::get_custom_listing_template( 'virtual-tour-iframe' ); ?>

                            <?php do_action( 'rtcl_single_listing_social_profiles' ) ?>

                            <!-- Walk Score -->
							<?php if ( ! empty( $listing->user_contact_location_at_single() ) && RDTheme::$options['walkscore_control'] ): ?>
								<?php
								$location = implode( ', ', $listing->user_contact_location_at_single() );
								do_action( 'homlisti_walkscore', $location );
								?>
							<?php endif; ?>

							<?php do_action( 'rtcl_single_listing_review' ); ?>

							<?php if ( RDTheme::$options['listing_detail_sidebar'] && $sidebar_position === "bottom" ): ?>
                                <!-- Sidebar -->
								<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
							<?php endif; ?>
							<?php do_action( 'rtcl_single_listing_inner_sidebar' ); ?>
                        </div>
                        <!-- Related Listing -->
                    </div>

					<?php if ( RDTheme::$options['listing_detail_sidebar'] && in_array( $sidebar_position, [ 'left', 'right' ] ) ): ?>
						<?php do_action( 'rtcl_single_listing_sidebar' ); ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>

	<?php if ( RDTheme::$options['show_related_listing'] ) : ?>
        <div class="container">
            <div class="row">
                <div class="col-12 mb-5 listing-title-wrap-enable">
					<?php $listing->the_related_listings(); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

</div>