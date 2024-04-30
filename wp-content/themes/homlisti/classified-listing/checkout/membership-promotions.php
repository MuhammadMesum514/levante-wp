<?php
/**
 * Membership checkout
 *
 * @var array      $promotions
 * @var Membership $membership
 * @var int        $listing_id
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

use Rtcl\Resources\Options;
use RtclStore\Models\Membership;

?>
<table id="rtcl-membership-promotions-table"
       class="rtcl-responsive-table form-group table table-hover table-stripped table-bordered">
    <tr>
        <th><?php esc_html_e( "Promotions", "homlisti" ); ?></th>
        <th><?php esc_html_e( "Remaining ads", "homlisti" ); ?></th>
        <th class="promotion-validity"><?php _e( "Validation Duration<small>(# Days)</small>", "homlisti" ); ?></th>
    </tr>
	<?php if ( ! empty( $promotions ) ) :
		$all_promotions = Options::get_listing_promotions();
		foreach ( $promotions as $promotion_key => $promotion ) {
			?>
            <tr>
                <td class="form-check rtcl-membership-promotion-item"
                    data-label="<?php esc_attr_e( "Promotions:", "homlisti" ); ?>">
					<?php
					printf( '<input type="checkbox" name="%s" value="%s" class="rtcl-membership-promotion-input" required/><label class="form-check-label">%s</label>',
						'_rtcl_membership_promotions[]',
						esc_attr( $promotion_key ),
						! empty( $all_promotions[ $promotion_key ] ) ? esc_html( $all_promotions[ $promotion_key ] ) : esc_html( $promotion_key )
					);
					?>
                </td>
                <td class="rtcl-membership-promotion-ads"
                    data-label="<?php esc_attr_e( "Remaining ads:", "homlisti" ); ?>">
					<?php echo ! empty( $promotion['ads'] ) ? absint( $promotion['ads'] ) : 0; ?>
                </td>
                <td class="rtcl-membership-promotion-validate text-right"
                    data-label="<?php esc_attr_e( 'Validation Duration:', 'homlisti' ) ?>">
					<?php printf( __( "%d Days", "homlisti" ),
						! empty( $promotion['validate'] ) ? absint( $promotion['validate'] ) : 0
					); ?>
                </td>
            </tr>
		<?php } ?>
	<?php endif; ?>
</table>