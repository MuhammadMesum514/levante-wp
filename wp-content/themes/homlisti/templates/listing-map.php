<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

get_header();
?>
    <div id="primary" class="product-grid listing-inner">
        <div class="container-fluid full-width">
            <div class="homlisti-listing-map-wrapper">
				<?php
				if ( get_the_content() ) {
					the_content();
				} else {
					echo do_shortcode( '[rtcl_listings map="1" paginate="true" limit="8"]' );
				}
				?>
            </div>
        </div>
    </div>
	<?php get_footer();