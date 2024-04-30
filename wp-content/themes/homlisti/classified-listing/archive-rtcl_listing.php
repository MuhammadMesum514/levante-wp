<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;

defined( 'ABSPATH' ) || exit;

get_header( 'listing' );

/**
 * Hook: rtcl_before_main_content.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'rtcl_before_main_content' );
$full_width = isset( $_GET['layout'] ) ? sanitize_text_field( wp_unslash( $_GET['layout'] ) ) : null;
?>
    <div class="container rtcl-widget-border-enable rtcl-widget-is-sticky listing-title-wrap-enable rtcl-listing-archive-<?php echo esc_attr($full_width) ?>">
		<?php do_action( 'homlisti_listing_loop_top_actions' ); ?>

        <div class='listing-inner rt-advanced-search-wrapper rtcl-archive-top-search-wrapper'>
            <h2><?php echo esc_html__( 'Advanced Search', 'homlisti' ) ?></h2>
			<?php do_action( 'homlisti_listing_grid_search_filer' ); ?>
        </div>

        <div class="row product-grid product-grid-inner pb-5">
            <div class="<?php if ( $full_width == 'fullwidth' ) {
				echo "col-sm-12 col-12";
			} else {
				Helper::the_layout_class();
			} ?>">
				<?php
				if ( rtcl()->wp_query()->have_posts() ) {
					?>
                    <div class="product-wrap">
						<?php
						/**
						 * Hook: rtcl_before_listing_loop.
						 *
						 * @hooked TemplateHooks::output_all_notices() - 10
						 * @hooked TemplateHooks::listings_actions - 20
						 *
						 */
						do_action( 'rtcl_before_listing_loop' );


						Functions::listing_loop_start();

						/**
						 * Top listings
						 */
						if ( class_exists( 'RtclPro' ) && Fns::is_enable_top_listings() ) {
							do_action( 'rtcl_listing_loop_prepend_data' );
						}

						while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

							/**
							 * Hook: rtcl_listing_loop.
							 */
							do_action( 'rtcl_listing_loop' );

							Functions::get_template_part( 'content', 'listing' );
						endwhile;

						Functions::listing_loop_end();
						?>
                    </div>
					<?php
					/**
					 * Hook: rtcl_after_listing_loop.
					 *
					 * @hooked TemplateHook::pagination() - 10
					 */
					do_action( 'rtcl_after_listing_loop' );
				} else {
					/**
					 * Top listings
					 */
					if ( class_exists( 'RtclPro' ) && Fns::is_enable_top_listings() ) {
						Functions::listing_loop_start();
						do_action( 'rtcl_top_listings' );
						Functions::listing_loop_end();
					}

					/**
					 * Hook: rtl_no_listings_found.
					 *
					 * @hooked no_listings_found - 10
					 */
					do_action( 'rtcl_no_listings_found' );
				}
				?>
            </div>
			<?php
			if ( Helper::has_sidebar() && $full_width != 'fullwidth' ) {
				do_action( 'rtcl_sidebar' );
			}
			?>
        </div>

    </div>

<?php

if ( function_exists( '_mc4wp_load_plugin' ) && RDTheme::$options['newsletter_section'] ) {
	get_template_part( 'template-parts/rt', 'newsletter' );
}

/**
 * Hook: rtcl_after_main_content.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );

get_footer( 'listing' );
