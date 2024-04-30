<?php
/**
 * Agent Archive Content
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates
 * @version    1.0.0
 *
 */

use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;

$user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
if (!$user = get_user_by('id', $user_id)) {
    return;
}
$store_id  = get_user_meta( $user_id, '_rtcl_store_id', true );
$name = trim(implode(' ', [$user->first_name, $user->last_name]));
$name = $name ? $name : $user->display_name;
$phone = get_user_meta( $user_id, '_rtcl_phone', true );
$pp_id     = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store = new Store($store_id);
?>
<div class="agent-holder">
    <div class="agent-block">
        <div class="item-img">
            <?php echo $pp_id ? wp_get_attachment_image( $pp_id, [
                420,
                240
            ] ) : get_avatar( $user_id ) ?>
            <span class="listing-count">
                <?php printf( _n( '%s Listing', '%s Listings', count( $store->get_manager_listing_ids( $user_id ) ), 'rtcl-agent' ), count( $store->get_manager_listing_ids( $user_id ) ) ); ?>
            </span>
        </div>
        <div class="item-content">
            <div class="item-title">
                <h3 class="agent-name"><a href="<?php echo get_the_permalink(); ?>"><?php echo esc_html($name); ?></h3></a>
                <h5 class="agency-name">
                    <a href="<?php echo get_the_permalink( $store_id ); ?>"><?php echo get_the_title( $store_id ); ?></a>
                </h5>
            </div>
            <?php if ($phone): ?>
                <div class="item-phone">
                    <i class="rtcl-icon rtcl-icon-phone"></i>
                    <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                </div>
            <?php endif; ?>
            <div class="item-contact">
                <i class="rtcl-icon rtcl-icon-envelope-open"></i>
                <a href="mailto:<?php echo esc_attr($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a>
            </div>
            <?php
            $social_list = Functions::get_user_social_profile($user_id);
            if (!empty($social_list)) {
                foreach ($social_list as $item => $value) {
                    ?>
                    <a target="_blank" href="<?php echo esc_url($value) ?>">
                        <i class="rtcl-icon rtcl-icon-<?php echo esc_attr($item) ?>"></i>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>