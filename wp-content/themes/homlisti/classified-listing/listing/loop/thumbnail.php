<?php
/**
 * Listing Thumbnail
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\HomListi\Helper;
use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunction;
use RtclPro\Helpers\Fns;
use radiustheme\HomListi\Listing_Functions;

global $listing;
global $rtclIsAjax;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_general_settings', 'default_view', 'list' );
}
?>
<div class="product-thumb">

	<?php $images = Functions::get_listing_images( $listing->get_id() ); ?>
	<?php
	if ( ( class_exists( 'RtclStore' ) && StoreFunction::is_single_store() ) || ! empty( $rtclIsAjax ) ) {
		$listing->the_thumbnail();
	} else {
		Helper::homlisti_thumb_carousel( $listing->get_id() );
		if ( class_exists( 'RtclPro' ) && $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
			echo '<span class="rtcl-sold-out">' . apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'homlisti' ) ) . '</span>';
		}
		$listing_type = Listing_Functions::get_listing_type( $listing );
		?>
        <div class="product-type">
			<?php if ( ! empty( $listing_type ) ) : ?>
                <span class="listing-type-badge">
                    <?php echo sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'homlisti' ) ), $listing_type['label'] ); ?>
                </span>
			<?php endif; ?>

			<?php $listing->the_badges(); ?>
        </div>

		<?php if ( $listing->can_show_price() ): ?>
            <div class="product-price"><?php printf( "%s", $listing->get_price_html() ); ?></div>
		<?php endif; ?>
        <div class="listing-action">
			<?php echo Listing_Functions::get_favourites_link( $listing->get_id() ); ?>
			<?php if ( class_exists( 'RtclPro' ) && class_exists( 'RtclStore' ) && Fns::is_enable_compare() ) {
				$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
				$selected_class = '';
				if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
					$selected_class = ' selected';
				}
				?>
                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-toggle="tooltip" data-placement="top"
                   title="<?php esc_attr_e( "Compare", "homlisti" ) ?>"
                   data-original-title="<?php esc_attr_e( "Compare", "homlisti" ) ?>" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                    <i class="flaticon-left-and-right-arrows"></i>
                </a>


			<?php } ?>

			<?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_quick_view() ) {
				?>
                <a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'homlisti' ); ?>" data-listing_id="<?php echo absint( $listing->get_id() ); ?>">
                    <i class="rtcl-icon rtcl-icon-zoom-in"></i>
                </a>
				<?php
			} ?>
        </div>
	<?php } ?>
</div>