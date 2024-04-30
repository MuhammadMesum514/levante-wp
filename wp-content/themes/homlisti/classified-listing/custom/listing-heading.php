<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\HomListi\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Text;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;
use RtclPro\Helpers\Fns;
use Rtcl\Controllers\Hooks\TemplateHooks;
use radiustheme\HomListi\Listing_Functions;
use radiustheme\HomListi\RDTheme;

global $listing;

if ( ! $listing ) {
	return;
}

$style            = Helper::listing_single_style();
$average_rating   = $listing->get_average_rating();
$rating_count     = $listing->get_rating_count();
$can_report_abuse = Functions::get_option_item( 'rtcl_moderation_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;
$listing_type     = Listing_Functions::get_listing_type( $listing );
?>
<div class="product-heading" id="listing-home">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="product-condition">

				<?php
				if ( $listing->has_category() && '2' == $style ):
					$category = $listing->get_categories();
					$category = end( $category );
					?>
                    <span class="category">
                        <a href="<?php echo esc_url(
	                        get_term_link(
		                        $category->term_id,
		                        $category->taxonomy
	                        )
                        ); ?>"><?php echo esc_html( $category->name ) ?></a>
                </span>
				<?php endif;
				?>


				<?php if ( ! empty( $listing_type ) ) : ?>
                    <span class="listing-type-badge">
                    <?php echo sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'homlisti' ) ), $listing_type['label'] ); ?>
                </span>
				<?php endif; ?>
                <?php $listing->the_badges(); ?>
            </div>

            <!--TODO: Listing Title Condition may be change -->
			<?php if ( RDTheme::$options['breadcrumb_title'] !== 'homlisti-page-title' || $style == '2' || ! RDTheme::$options['breadcrumb'] ) :

				if ( $style == '2' || RDTheme::$options['breadcrumb_title'] == 'disable' ) { ?>
                    <h1 class="product-title"><?php $listing->the_title(); ?></h1>
					<?php
				} else {
                    if(RDTheme::$options['breadcrumb']){
                        $title_tag = 'h2';
                    } else {
	                    $title_tag = 'h1';
                    }
                    ?>
                    <<?php echo esc_html($title_tag) ?> class="product-title"><?php $listing->the_title(); ?></<?php echo esc_html($title_tag) ?>>
					<?php
				}
				?>
			<?php endif; ?>

            <div class="header-info">
                <ul class="entry-meta">

					<?php if ( current( $listing->user_contact_location_at_single() ) ): ?>
                        <li>
                            <i class="fas fa-map-marker-alt"></i><?php echo implode( '<span class="rtcl-delimiter">,</span> ', $listing->user_contact_location_at_single() ); ?>
                        </li>
					<?php endif; ?>
					<?php if ( $listing->can_show_date() ): ?>
                        <li><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
					<?php endif; ?>
					<?php if ( $listing->can_show_views() ): ?>
                        <li>
                            <i class="far fa-eye"></i><?php echo sprintf( _n( 'View: <span>%s</span>', 'Views: <span>%s</span>', $listing->get_view_counts(), 'homlisti' ),
								number_format_i18n( $listing->get_view_counts() ) ); ?>
                        </li>
					<?php endif; ?>
                </ul>
				<?php if ( ! empty( $rating_count ) ): ?>
                    <div class="product-rating">
                        <div class="item-icon">
							<?php echo Functions::get_rating_html( $average_rating, $rating_count ); ?>
                        </div>
                        <div class="item-text"><?php echo apply_filters( 'homlisti_rating_count_format',
								sprintf( __( '(<span>%s</span>) Reviews', 'homlisti' ), esc_html( $rating_count ) ) ); ?></div>
                    </div>
				<?php endif; ?>
            </div>

			<?php if ( $listing->can_show_price() ): ?>
                <div class="product-price d-none"><?php printf( "%s", $listing->get_price_html() ); ?></div>
			<?php endif; ?>
        </div>
        <div class="col-lg-4 product-price-wrap">
			<?php if ( $listing->can_show_price() ): ?>
                <div class="product-price"><?php printf( "%s", $listing->get_price_html() ); ?></div>
			<?php endif; ?>

			<?php if ( RDTheme::$options['show_listing_button_area'] ) : ?>
                <div class="btn-area">
                    <ul>
						<?php if ( $can_report_abuse ): ?>
                            <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr__("Report Abuse", "homlisti") ?>">
								<?php if ( is_user_logged_in() ): ?>
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       data-target="#rtcl-report-abuse-modal">
                                        <i class='fas fa-bug'></i>
										<?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
								<?php else: ?>
                                    <a href="javascript:void(0)" class="rtcl-require-login">
                                        <i class='fas fa-bug'></i>
										<?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
								<?php endif; ?>
                            </li>
						<?php endif; ?>

						<?php if ( $listing->the_social_share( false ) ) : ?>
                            <li data-toggle="tooltip" data-placement="top" data-original-title="<?php echo esc_attr__("Share", "homlisti")?>">
                                <a href="#" id="share-btn"><i class="flaticon-share"></i></a>
                                <div class="share-icon">
									<?php $listing->the_social_share(); ?>
                                </div>
                            </li>
						<?php endif; ?>

                        <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr__("Add to Favourite", "homlisti")?>">
                            <?php echo Functions::get_favourites_link( $listing->get_id() ); ?>
                        </li>

                        <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr__("Compare", "homlisti")?>">
							<?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_compare() ) {
								$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
								$selected_class = '';
								if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
									$selected_class = ' selected';
								}
								?>
                                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                   data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                    <i class="flaticon-left-and-right-arrows icon-round"></i>
                                </a>
							<?php } ?>
                        </li>

                        <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr__("Print", "homlisti")?>">
                            <a href="#" onclick="window.print();"><i class="flaticon-printer"></i></a>
                        </li>

						<?php if ( function_exists('rtclClaimListing') && ClaimFunctions::claim_listing_enable() ) : ?>
                            <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr__("Claim", "homlisti")?>">
								<?php if ( is_user_logged_in() ): ?>
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       data-target="#rtcl-claim-listing-modal">
                                        <i class="fas fa-hand-holding-heart"></i>
										<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>
                                    </a>
								<?php else: ?>
                                    <a href="javascript:void(0)" class="rtcl-require-login">
                                        <i class="fas fa-hand-holding-heart"></i>
										<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>
                                    </a>
								<?php endif; ?>
                            </li>
						<?php endif; ?>

                    </ul>
                </div>
			<?php endif; ?>

        </div>
    </div>
</div>

<?php do_action( 'rtcl_single_listing_after_action', $listing->get_id() ); ?>

<div class="modal fade" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rtcl-report-abuse-form" class="form-vertical">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'homlisti' ); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rtcl-report-abuse-message"><?php esc_html_e( 'Your Complaint', 'homlisti' ); ?>
                            <span class="rtcl-star">*</span></label>
                        <textarea class="form-control" id="rtcl-report-abuse-message" rows="3"
                                  name="message"
                                  placeholder="<?php esc_attr_e( 'Message... ', 'homlisti' ); ?>"
                                  required></textarea>
                    </div>
                    <div id="rtcl-report-abuse-g-recaptcha"></div>
                    <div id="rtcl-report-abuse-message-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-primary"><?php esc_html_e( 'Submit', 'homlisti' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>