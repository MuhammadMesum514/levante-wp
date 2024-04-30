<?php
/**
 *
 * @author 		RadiusTheme
 * @package 	classified-listing/templates
 * @version     1.0.0
 */

use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Functions::print_notices();

?>
<div class="rtcl-MyAccount-wrap row">
    <div class="col-lg-3 col-md-4 ol-sm-12 col-12">
        <?php do_action( 'rtcl_account_navigation' ); ?>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12 col-12">
        <div class="rtcl-MyAccount-content">
            <?php do_action( 'rtcl_account_content' ); ?>
        </div>
    </div>
</div>
