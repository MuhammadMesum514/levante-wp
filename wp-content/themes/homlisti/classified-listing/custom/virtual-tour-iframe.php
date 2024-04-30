<?php
/**
 * This file is for showing listing header
 * @version 1.0
 */

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\RDTheme;

global $listing;
$virtual_tour = get_post_meta( $listing->get_id(), 'homlisti_virtual_tour', true );
$title        = isset( RDTheme::$options['virtual_tour_label'] ) ? RDTheme::$options['virtual_tour_label'] : 0;

if ( ! $virtual_tour ) {
	return;
}
?>
<div class="product-video widget" id="virtual_tour">
	<?php if ( $title ): ?>
        <div class="item-heading">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="heading-title"><?php echo esc_html( $title ); ?></h2>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div id="virtual-tour-iframe">
        <button class="expand-iframe"><i class="fa fa-expand"></i></button>
        <button class="compress-iframe"><i class="fa fa-compress"></i></button>
		<?php echo Helper::homlisti_kses( $virtual_tour ) ?>
    </div>
</div>
