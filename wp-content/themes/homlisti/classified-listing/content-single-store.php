<?php
/**
 * Store single content
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.3.21
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;
use radiustheme\HomListi\Helper;
use radiustheme\HomListi\Listing_Functions;

global $store;

if ( StoreFunctions::is_store_expired() ) {
	do_action( 'rtcl_single_store_expired_content' );

	return;
}

$banner_class = $store->get_banner_url() ? '' : ' rtin-noimage';
$member_since = esc_html__( 'Member since - ', 'homlisti' ) . get_the_time( get_option( 'date_format' ) );

do_action( 'rtcl_before_single_store' );
?>

    <div class="rtin-banner-wrap">
		<?php do_action( 'rtcl_before_single_store_content' ); ?>
        <div class="rtin-banner-img<?php echo esc_attr( $banner_class ); ?>">
			<?php if ( ! $banner_class ): ?>
				<?php $store->the_banner(); ?>
			<?php endif; ?>
        </div>
        <div class="rtin-banner-content">
			<?php if ( $store->get_logo_url() ): ?>
                <div class="rtin-logo">
					<?php $store->the_logo(); ?>
					<?php $store->the_metas(); ?>
                </div>
			<?php endif; ?>
            <div class="rtin-store-title-area">
                <!-- Store Title -->

                <!--TODO: Listing Title Condition may be change -->
				<?php if ( RDTheme::$options['breadcrumb_title'] !== 'homlisti-page-title' ) : ?>
                    <h2 class="rtin-store-title"><?php $store->the_title(); ?></h2>
				<?php endif; ?>

				<?php if ( $store->get_category() ) : ?>
                    <h4 class="rtin-store-category">
						<?php
						echo wp_kses( $store->get_category(), [
							'a' => [
								'href'  => [],
								'title' => [],
							],
						] );
						?>
                    </h4>
				<?php endif; ?>

				<?php if ( $store->get_the_slogan() ): ?>
                    <div class="rtin-store-slogan"><?php $store->the_slogan(); ?></div>
				<?php endif; ?>

                <!-- Store Information -->
                <ul class="rtin-title-meta">

					<?php if ( $store_address = $store->get_address() ): ?>
                        <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i><?php echo esc_html( $store_address ); ?></li>
					<?php endif; ?>

					<?php if ( $stor_view_count = Listing_Functions::rt_get_post_view_count( $store->get_id() ) ) : ?>
						<?php if ( $stor_view_count > 999 ): ?>
                            <li data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr__( "Total Views: " . $stor_view_count, 'homlisti' ) ?>">
						<?php else : ?>
                            <li>
						<?php endif; ?>
                        <i class="fas fa-eye"></i>
						<?php
						$label = $stor_view_count < 10 ? esc_html__( 'View: ', 'homlisti' ) : esc_html__( 'Views: ', 'homlisti' );
						printf( "<span class='count-label'>%s</span><span class='count-number'>%s</span>",
							$label,
							Helper::rt_number_shorten( $stor_view_count, 1 )
						);
						?>
                        </li>
					<?php endif; ?>

					<?php if ( $store->is_rating_enable() && $store->get_review_counts() ):
						$r_label = absint( $store->get_review_counts() ) < 2 ? esc_html__( 'Review', 'homlisti' ) : esc_html__( 'Reviews', 'homlisti' );
						?>
                        <li class="store-rating">
							<?php echo Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ); ?>
                            <span class="reviews-rating-count">
                                <?php printf( "(%s %s)", $r_label, absint( $store->get_review_counts() ) ); ?>
                            </span>
                        </li>
					<?php endif; ?>

                </ul>
            </div>
        </div>
    </div>

    <div class="row store-information product-grid">
        <div class="col-lg-8 col-sm-12">
            <div class="widget single-agent-tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#overview" role="tab" aria-selected="true">
							<?php esc_html_e( 'Over View', 'homlisti' ); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#listing" role="tab" aria-selected="false">
							<?php esc_html_e( 'Our Listing', 'homlisti' ); ?>
                        </a>
                    </li>

					<?php if ( $store->is_rating_enable() && $store->get_review_counts() && false ): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#reviews" role="tab" aria-selected="false">
								<?php esc_html_e( 'Reviews', 'homlisti' ); ?>
                            </a>
                        </li>
					<?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="overview" role="tabpanel">
                        <h2><?php esc_html_e( 'About Us', 'homlisti' ); ?></h2>
						<?php if ( $store->get_the_description() ): ?>
							<?php $store->the_description(); ?>
						<?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="listing" role="tabpanel">
						<?php Functions::get_template( 'store/ad-listing' ); ?>
                    </div>
					<?php if ( $store->is_rating_enable() && $store->get_review_counts() && false ): ?>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
							<?php
							$r_label = absint( $store->get_review_counts() ) < 2 ? esc_html__( 'Review', 'homlisti' ) : esc_html__( 'Reviews', 'homlisti' );
							?>
							<?php //var_dump($store->get_average_rating()); ?>
							<?php echo Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ); ?>
                            <span class="reviews-rating-count">
                                <?php printf( "(%s %s)", $r_label, absint( $store->get_review_counts() ) ); ?>
                            </span>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="store-info">
				<?php Helper::get_custom_store_template( 'sidebar-store', true, get_defined_vars() ); ?>
            </div>
        </div>
    </div>

<?php
do_action( 'rtcl_after_single_store' );