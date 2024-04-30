<?php
/**
 * The template for displaying product content in the single-rtcl_listing.php template
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/content-single-rtcl_listing.php.
 *
 * @package ClassifiedListing/Templates
 * @version 1.5.56
 */

namespace radiustheme\HomListi;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

global $listing;
$sidebar_position = Functions::get_option_item( 'rtcl_moderation_settings', 'detail_page_sidebar_position', 'right' );
$sidebar_class    = [
	'col-lg-3 col-md-12',
	'order-2',
];
$content_class    = [
	'col-lg-8 col-md-12',
	'order-1',
	'listing-content',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
	$content_class   = array_diff( $content_class, [ 'order-1' ] );
	$content_class[] = 'order-2';
} elseif ( $sidebar_position == "bottom" ) {
	$content_class   = array_diff( $content_class, [ 'col-md-9' ] );
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-md-3' ] );
	$content_class[] = 'col-sm-12';
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

if(!RDTheme::$options['listing_detail_sidebar']) {
	$content_class    = [
		'col-lg-10 offset-lg-1 col-md-12',
		'order-1',
		'listing-content',
	];
}

$style = Helper::listing_single_style();


/**
 * Hook: rtcl_before_single_product.
 *
 * @hooked rtcl_print_notices - 10
 */
do_action( 'rtcl_before_single_listing' );

Helper::get_custom_listing_template( 'content-single-' . $style, true, compact( 'sidebar_position', 'content_class' ) );

do_action( 'rtcl_after_single_listing' );

if ( function_exists( '_mc4wp_load_plugin' ) && RDTheme::$options['newsletter_section'] ) {
	get_template_part( 'template-parts/rt', 'newsletter' );
}