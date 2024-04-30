<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RtclStore' ) ) {
	return;
}

$author   = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user_id  = $author->ID;
$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$whatsApp = get_user_meta( $user_id, '_rtcl_whatsapp_number', true );
$website  = get_user_meta( $user_id, '_rtcl_website', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );
?>

<div class="rtcl-agent-single-wrapper rtcl product-grid">
    <div class="rtcl-agent-info-wrap">
        <div class="rtcl-agent-img">
			<?php
			if ( $pp_id ) {
				echo wp_get_attachment_image( $pp_id, [ 400, 240 ] );
			} else {
				echo get_avatar( $user_id );
			}
			?>
			<?php
			$social_list = Functions::get_user_social_profile( $user_id );
			if ( ! empty( $social_list ) ) {
				?>
                <div class="rtcl-agent-social">
					<?php
					foreach ( $social_list as $item => $value ) {
						?>
                        <a class="<?php echo esc_attr( $item ) ?>" target="_blank"
                           href="<?php echo esc_url( $value ) ?>">
                            <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
                        </a>
						<?php
					}
					?>
                </div>
			<?php } ?>

        </div>
        <div class="rtcl-agent-info">
            <h3 class="agent-name"><?php echo esc_html( $author->display_name ); ?></h3>
            <h4 class="agency-name">
                <a href="<?php echo get_the_permalink( $store_id ); ?>"><?php echo get_the_title( $store_id ); ?></a>
            </h4>

            <div class="agent-bio">
				<?php echo esc_html( $author->description ); ?>
            </div>

            <div class="rtcl-agent-meta">
				<?php if ( $phone ): ?>
                    <div class="agent-meta item-phone">
                        <i class="rtcl-icon rtcl-icon-phone"></i>
                        <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                    </div>
				<?php endif; ?>
				<?php if ( $whatsApp ): ?>
                    <div class="agent-meta item-whatsapp">
                        <i class="rtcl-icon rtcl-icon-whatsapp"></i>
                        <a target="_blank"
                           href="https://wa.me/<?php echo esc_attr( $whatsApp ); ?>"><?php echo esc_html( $whatsApp ); ?></a>
                    </div>
				<?php endif; ?>
                <div class="agent-meta item-contact">
                    <i class="rtcl-icon rtcl-icon-envelope-open"></i>
                    <a href="mailto:<?php echo esc_attr( $author->user_email ); ?>"><?php echo esc_html( $author->user_email ); ?></a>
                </div>
				<?php if ( $website ): ?>
                    <div class="agent-meta item-whatsapp">
                        <i class="rtcl-icon rtcl-icon-link"></i>
                        <a target="_blank"
                           href="<?php echo esc_url( $website ); ?>"><?php echo esc_url( $website ); ?></a>
                    </div>
				<?php endif; ?>
            </div>

        </div>
    </div>
	<?php Functions::get_template( 'listing/author-listing' ); ?>
</div>