<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions as RtclFunctions;

get_header( 'store' );
/**
 * rtcl_before_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked rtcl_breadcrumb - 20
 */
do_action( 'rtcl_before_main_content' );
?>
    <div class="single-agent rtcl-widget-border-enable rtcl-widget-is-sticky">
        <div class="container">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>

				<?php RtclFunctions::get_template_part( 'content', 'single-store' ); ?>

			<?php endwhile; // end of the loop. ?>
        </div>
    </div>
<?php
/**
 * rtcl_after_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );

get_footer( 'store' );
