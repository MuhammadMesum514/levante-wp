<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $store;

?>
<div class="store-thumb">
	<?php $store->the_metas(); ?>
	<?php $store->the_logo(); ?>
</div>
