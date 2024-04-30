<?php

namespace radiustheme\HomListi;
use Rtcl\Helpers\Functions;

if(! RDTheme::$options['show_floating_menu']){
    return;
}
global $listing;
$video_urls = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}

?>
<div class="rtcl-content-sidebar">
    <div class="rtcl-single-side-menu">
        <ul class='side-menu'>
            <li><a class="active" href="#listing-home"><i class="flaticon-home"></i></a></li>

            <li><a href="#list-info"><i class="fas fa-info"></i></a></li>

			<?php if ( method_exists('Rtcl\Helpers\Functions','has_map') && Functions::has_map() ): ?>
                <li><a href="#map"><i class="fas fa-map-marker-alt"></i></a></li>
			<?php endif; ?>

			<?php if ( ! empty( $video_urls ) ): ?>
                <li><a href="#video"><i class="flaticon-camera"></i></a></li>
			<?php endif; ?>

			<?php if ( Listing_Functions::is_enable_yelp_review() ) : ?>
                <li><a href="#list-review"><i class="fas fa-star"></i></a></li>
			<?php endif; ?>

			<?php if ( Listing_Functions::is_enable_panorama_view() ) : ?>
                <li><a href="#panorama_view"><i class="far fa-image"></i></a></li>
			<?php endif; ?>
        </ul>
    </div>
</div>