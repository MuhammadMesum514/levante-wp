<?php
/**
 * @package ClassifiedListing/Templates
 * @version 2.2.1.1
 */

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;

defined('ABSPATH') || exit;

get_header('listing');
$agent_single_layout = RDTheme::$layout;

$content_column = "col-lg-8 col-sm-12 col-12";
if ( 'full-width' == $agent_single_layout ) {
	$content_column = "col-12";
}

/**
 * Hook: rtcl_before_main_content.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action('rtcl_before_main_content');

echo "<div class='rtcl-agents-single-main rtcl-widget-is-sticky rtcl-widget-border-enable " . esc_attr( $agent_single_layout ) . "'>";
echo "<div class='container'>";
echo "<div class='row agent-main-row'>";
echo "<div class='" . esc_attr( $content_column ) . "'>";


Functions::get_template( 'listing/author-content');

echo "</div>";

if ( 'full-width' !== $agent_single_layout ) {
	//	do_action( 'rtcl_agent_sidebar' );
	?>
<div id="sticky_sidebar" class="<?php Helper::the_sidebar_class(); ?>">
	<aside class="sidebar-widget main-sidebar-wrapper">
		<?php
		do_action( 'rtcl_agent_contact_form' );
		if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) {
			dynamic_sidebar( RDTheme::$sidebar );
		} elseif ( is_active_sidebar( 'sidebar' ) ) {
			dynamic_sidebar( 'sidebar' );
		}
		?>
	</aside>
	<?php
}

echo "</div>";
echo "</div>";
echo "</div>";

/**
 * Hook: rtcl_after_main_content.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('rtcl_after_main_content');

get_footer('listing');
