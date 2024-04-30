<?php
/**
 * Single Agent Listing
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates/agent
 * @version    1.0.0
 *
 * @var $store_id
 * @var $user_id
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp;

$general_settings = Functions::get_option( 'rtcl_general_settings' );
// Define the query
$paged = Pagination::get_page_number();

$args = array(
	'post_type'      => rtcl()->post_type,
	'posts_per_page' => ! empty( $general_settings['listings_per_page'] ) ? absint( $general_settings['listings_per_page'] ) : 10,
	'paged'          => $paged,
);

if ( isset( $_GET['store_listing'] ) ) {
	$args['meta_query'] = [
		[
			'key'   => '_rtcl_manager_id',
			'value' => $user_id
		]
	];
} else {
	$args['author'] = $user_id;
}

$agent_ads_query = new \WP_Query( apply_filters( 'rtcl_agent_listing_args', $args ) );
?>
<div class="rtcl-agent-listing-list rtcl-agent-ad-listing-wrapper">
    <ul>
        <li class="<?php echo ! isset( $_GET['store_listing'] ) ? 'active' : ''; ?>">
            <a href="<?php echo esc_url( home_url( $wp->request ) ); ?>"><?php esc_html_e( "My Listing", "rtcl-agent" ); ?></a>
        </li>
        <li class="<?php echo isset( $_GET['store_listing'] ) ? 'active' : ''; ?>">
            <a href="<?php echo esc_url( add_query_arg( 'store_listing', 1, home_url( $wp->request ) ) ); ?>"><?php printf( __( "Listing for %s", "rtcl-agent" ), get_the_title( $store_id ) ); ?></a>
        </li>
    </ul>
	<?php
	if ( $agent_ads_query->have_posts() ) : ?>
        <div class="rtcl-listings rtcl-list-view rtcl-listing-wrapper"
             data-pagination='{"max_num_pages":<?php echo esc_attr( $agent_ads_query->max_num_pages ) ?>, "current_page": 1, "found_posts":<?php echo esc_attr( $agent_ads_query->found_posts ) ?>, "posts_per_page":<?php echo esc_attr( $agent_ads_query->query_vars['posts_per_page'] ) ?>}'>
            <!-- the loop -->
			<?php
			while ( $agent_ads_query->have_posts() ) : $agent_ads_query->the_post();
				$listing = rtcl()->factory->get_listing( get_the_ID() );
				Functions::get_template_part( 'content', 'listing' );
			endwhile; ?>
            <!-- end of the loop -->

            <!-- Use reset postdata to restore original query -->
			<?php wp_reset_postdata(); ?>
        </div>
	<?php else: ?>
        <div class="no-listing-found">
			<?php do_action( 'rtcl_no_listings_found' ); ?>
        </div>
	<?php endif; ?>
</div>