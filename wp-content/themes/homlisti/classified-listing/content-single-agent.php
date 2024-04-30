<?php

use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;
use radiustheme\HomListi\Helper;

if ( ! class_exists( 'RtclStore' ) ) {
	return;
}

$user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
if ( ! $user = get_user_by( 'id', $user_id ) ) {
	return;
}
$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$whatsApp = get_user_meta( $user_id, '_rtcl_whatsapp_number', true );
$website  = get_user_meta( $user_id, '_rtcl_website', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );

$services    = get_post_meta( get_the_ID(), 'rtcl_agent_services', true );
$specialties = get_post_meta( get_the_ID(), 'rtcl_agent_specialties', true );

$total_user_post     = count( Helper::get_user_listing_ids( $user_id ) );
$total_manager_posts = count( $store->get_manager_listing_ids( $user_id ) );
$total_posts_count   = $total_user_post + $total_manager_posts;
?>

<div class="rtcl-agent-single-wrapper rtcl product-grid">
    <div class="rtcl-agent-info-wrap">
        <div class="rtcl-agent-img">
			<?php
			if ( $pp_id ) {
				echo wp_get_attachment_image( $pp_id, [ 400, 240 ] );
			} else {
				echo get_avatar( $user_id, 400 );
			}
			?>
            <span class="listing-count">
                <?php printf( _n( '%s Listing', '%s Listings', $total_posts_count, 'homlisti' ), $total_posts_count ); ?>
            </span>
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
            <!--TODO: Listing Title Condition may be change -->
	        <?php if ( RDTheme::$options['breadcrumb_title'] !== 'homlisti-page-title' ) : ?>
            <h2 class="agent-name">
				<?php echo get_the_title(); ?>
				<?php do_action( 'rtcl_after_author_meta', $user_id ); ?>
            </h2>
            <?php endif; ?>

            <h2 class="agency-name">
                <a href="<?php echo get_the_permalink( $store_id ); ?>"><?php echo get_the_title( $store_id ); ?></a>
            </h2>
            <div class="agent-bio">
				<?php the_content(); ?>
            </div>
            <div class="rtcl-agent-meta">
				<?php if ( $services ): ?>
                    <div class="agent-meta item-services">
                        <strong><?php esc_html_e( 'Service Areas', 'homlisti' ); ?>:</strong>
						<?php echo esc_html( $services ); ?>
                    </div>
				<?php endif; ?>
				<?php if ( $specialties ): ?>
                    <div class="agent-meta item-services">
                        <strong><?php esc_html_e( 'Specialties', 'homlisti' ); ?>:</strong>
						<?php echo esc_html( $specialties ); ?>
                    </div>
				<?php endif; ?>
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
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
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

	<?php Functions::get_template( 'agent/ad-listing', compact( 'user_id', 'store_id' ), '', rtclAgent()->get_plugin_template_path() ); ?>

</div>
