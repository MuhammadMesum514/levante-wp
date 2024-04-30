<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

Helper::requires( 'class-tgm-plugin-activation.php' );
Helper::requires( 'class-homlisti-walker-category.php' );
Helper::requires( 'tgm-config.php' );
Helper::requires( 'general.php' );
Helper::requires( 'scripts.php' );
Helper::requires( 'layout-settings.php' );
Helper::requires( 'utility/utility.php' );


// WooCommerce
if ( class_exists( 'WooCommerce' ) ) {
	Helper::requires( 'woo-functions.php' );
	Helper::requires( 'woo-hooks.php' );
}

if ( class_exists( 'Rtcl' ) ) {
	Helper::requires( 'custom/functions.php', 'classified-listing' );
}

// Add Customizer
Helper::requires( 'customizer/customizer-default-data.php' );
Helper::requires( 'customizer/init.php' );
Helper::requires( 'rdtheme.php' );