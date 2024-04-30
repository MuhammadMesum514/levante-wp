<?php
/**
 * Agent single content
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates
 * @version    1.0
 *
 */

use Rtcl\Helpers\Functions;
use radiustheme\HomListi\Helper;
use RtclStore\Models\Store;

if ( ! class_exists( 'RtclStore' ) ) {
	return;
}

$user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
if ( ! $user = get_user_by( 'id', $user_id ) ) {
	return;
}
$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
$name     = trim( implode( ' ', [ $user->first_name, $user->last_name ] ) );
$name     = $name ? $name : $user->display_name;
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );
$total_user_post     = count( Helper::get_user_listing_ids( $user_id ) );
$total_manager_posts = count( $store->get_manager_listing_ids( $user_id ) );
$total_posts_count   = $total_user_post + $total_manager_posts;

?>

<div class="agent-holder">
    <div class="agent-block">
        <div class="item-img">
			<?php
			if ( $pp_id ) {
				echo wp_get_attachment_image( $pp_id, [ 340, 340, ] );
			} else {
				echo get_avatar( $user_id, 340 );
			}
			?>
            <span class="listing-count">
                <?php
                $count = $total_posts_count;
                $count = $count == 0 ? 1 : $count;
                printf( _n( '%s Listing', '%s Listings', $count, 'homlisti' ), $total_posts_count );
                ?>
            </span>
        </div>
        <div class="item-content">
            <div class="item-title">
                <h3 class="agent-name">
                    <a href="<?php echo get_the_permalink(); ?>">
						<?php echo esc_html( $name ); ?>
						<?php do_action( 'rtcl_after_author_meta', $user_id ); ?>
                    </a>
                </h3>
                <h4 class="agency-name">
                    <a href="<?php echo get_the_permalink( $store_id ); ?>"><?php echo get_the_title( $store_id ); ?></a>
                </h4>
            </div>
			<?php if ( $phone ): ?>
                <div class="item-phone">
                    <i class="rtcl-icon rtcl-icon-phone"></i>
                    <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                </div>
			<?php endif; ?>
            <div class="item-contact">
                <!--<i class="rtcl-icon rtcl-icon-envelope-open"></i>-->
                <i class="rtcl-icon fas fa-envelope"></i>
                <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
            </div>

            <div class="details-btn">
                <a class="btn btn-details mt-3"
                   href="<?php echo get_the_permalink(); ?>"><?php echo esc_html__( 'Details', 'homlisti' ) ?></a>
            </div>
        </div>
        <div class="social-icon">
			<?php
			$social_list = Functions::get_user_social_profile( $user_id );
			if ( ! empty( $social_list ) ) {
				?>
                <a href="#" class="social-hover-icon social-link">
                    <i class="fas fa-share-alt"></i>
                </a>
				<?php
				foreach ( $social_list as $item => $value ) { ?>
                    <a target="_blank" href="<?php echo esc_url( $value ) ?>">
                        <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
                    </a>
					<?php
				}
			}
			?>
        </div>
    </div>
</div>