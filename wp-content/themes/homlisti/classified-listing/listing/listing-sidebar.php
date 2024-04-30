<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

use Rtcl\Helpers\Functions;
use radiustheme\HomListi\Helper;
use radiustheme\HomListi\RDTheme;

global $listing;
$sidebar_position = Functions::get_option_item( 'rtcl_moderation_settings', 'detail_page_sidebar_position', 'right' );

$sidebar_class = [
	'col-lg-4 col-md-10 offset-lg-0 offset-md-1',
	'order-2',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
} elseif ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-4 col-md-10 offset-lg-0 offset-md-1' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}
?>

<!-- Seller / User Information -->
<div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
		<?php
		if ( $listing->can_show_user() ) {
			if ( RDTheme::$options['show_user_info_on_details'] === 'show_store_info' ) {
				$listing->the_user_info();
				//Social Profile
				do_action( 'rtcl_single_listing_social_profiles' );
			} else {
				Helper::get_custom_listing_template( 'listing-content-info' );
			}
		}
		?>

        <!-- Business Hours  -->
        <?php do_action( 'rtcl_single_listing_business_hours' ) ?>

	    <?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>


		<?php if ( is_active_sidebar( 'single-property-sidebar' ) ): ?>
            <aside class="sidebar-widget">
				<?php dynamic_sidebar( 'single-property-sidebar' ); ?>
            </aside>
		<?php endif; ?>
    </div>
</div>
