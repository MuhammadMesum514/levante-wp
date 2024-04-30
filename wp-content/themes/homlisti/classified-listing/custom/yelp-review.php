<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\HomListi_Core\YelpReview;
global $listing;
$categories = get_post_meta( $listing->get_id(), 'homlisti_yelp_categories', true );
$location   = implode( ', ', $listing->user_contact_location_at_single() );
?>
<div class="product-places widget" id="yelp_nearby_place">
    <div class="item-heading">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="heading-title"><?php esc_html_e( 'Yelp Nearby Places', 'homlisti' ); ?></h2>
            </div>
        </div>
    </div>
    <div class="places-list">
		<?php
		if ( ! empty( $categories ) ) {
			$yelp = new YelpReview();
			foreach ( $categories as $term ) {
				$businessList = $yelp->query_api( $term, $location );
				if ( empty( $businessList ) ) {
					continue;
				}
				?>
                <div class="media">
					<?php if ( $yelp->get_yelp_category_icon( $term ) ): ?>
                        <div class="item-icon text-royalblue">
                            <i class="<?php echo esc_attr( $yelp->get_yelp_category_icon( $term ) ); ?>"></i>
                        </div>
					<?php endif; ?>
                    <div class="media-body">
                        <h3 class="item-title">
							<?php echo esc_html( $yelp->get_yelp_category_title( $term ) ); ?>
                        </h3>
                        <ul class="organization-list">
							<?php
							foreach ( $businessList as $business ) {
								$name        = $business->name;
								$rating      = $business->rating;
								$distance    = $business->distance * 0.00062137;
								$reviewCount = $business->review_count;
								?>
                                <li>
                                    <div class="institue-name">
										<?php echo esc_html( $name ); ?>
										<?php echo sprintf( __( "(%s miles)", 'homlisti' ), number_format( $distance, 2 ) ); ?>
                                    </div>
                                    <div class="item-rating">
										<?php YelpReview::print_yelp_rating( $rating ); ?>
                                    </div>
                                    <div class="item-reviews"><?php echo sprintf( __( "(<span>%s</span>) Reviews", 'homlisti' ), $reviewCount ); ?></div>
                                </li>
								<?php
							}
							?>
                        </ul>
                    </div>
                </div>
				<?php
			}
		}
		?>
    </div>
</div>