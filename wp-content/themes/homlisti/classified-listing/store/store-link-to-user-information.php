<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing-store/templates
 * @version    1.0.0
 *
 * @var Store $store
 */

use RtclStore\Models\Store;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class='rtcl-member-store-info'>
    <div class='media mt-3'>
		<?php if ( $store->has_logo() ): ?>
            <a class="mr-3" href="<?php $store->the_permalink(); ?>">
				<?php $store->the_logo() ?>
            </a>
		<?php endif; ?>

        <div class='media-body'>
            <h5 class="mt-0">
                <a href="<?php $store->the_permalink(); ?>"><?php $store->the_title() ?></a>
            </h5>

            <?php 
            $mobileClass = wp_is_mobile() ? " rtcl-mobile" : null; 
            $phone_options = [
                'safe_phone'   => mb_substr($store->get_phone(), 0, mb_strlen($store->get_phone()) - 3) . apply_filters('rtcl_phone_number_placeholder', 'XXX'),
                'phone_hidden' => mb_substr($store->get_phone(), -3)
            ];
            ?>
            <div class='item-number reveal-phone<?php echo esc_attr($mobileClass); ?>'
                 data-options="<?php echo $phone_options ? htmlspecialchars(wp_json_encode($phone_options)) : ''; ?>">
                <div class='numbers'><?php echo esc_html($phone_options['safe_phone']); ?></div>
                <small class='text-muted'><?php esc_html_e("(Show)","homlisti") ?></small>
            </div>
            <div class="agency-email">
                <a href="mailto:<?php echo esc_attr($store->get_email()); ?>"><?php echo esc_html($store->get_email()); ?></a>
            </div>
            <?php if ( $store->is_rating_enable() && $store->get_review_counts() ): ?>	
                <div class="store-rating">
                    <?php echo Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ); ?>
                    <?php
                        printf('<span class="reviews-rating-count">(%s %s)</span>',
                            absint( $store->get_review_counts() ),
                            esc_html__('Reviews', 'homlisti')
                        )
                    ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php ?>
</div>
