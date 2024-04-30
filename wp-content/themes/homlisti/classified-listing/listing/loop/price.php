<?php
/**
 * Price
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $listing;

if ( ! $listing->can_show_price() ) {
	return;
}
?>
<div class="item-price"><?php printf( "%s", $listing->get_price_html() ); ?></div>
