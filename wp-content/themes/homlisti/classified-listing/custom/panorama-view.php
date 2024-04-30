<?php
/**
 * This file is for showing listing header
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
global $listing;
$panorama = get_post_meta( $listing->get_id(), 'homlisti_panorama_img', true );
$generalSettings = Functions::get_option( 'rtcl_general_settings' );
$text            = isset( $generalSettings['panorama_section_label'] ) && ! empty( $generalSettings['panorama_section_label'] ) ? $generalSettings['panorama_section_label'] : '';

if ( $panorama ) { ?>
    <div class="product-video widget" id="panorama_view">
		<?php if ( $text ): ?>
            <div class="item-heading">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="heading-title"><?php echo esc_html( $text ); ?></h2>
                    </div>
                </div>
            </div>
		<?php endif; ?>
        <div id="panorama"></div>
    </div>
<?php } ?>