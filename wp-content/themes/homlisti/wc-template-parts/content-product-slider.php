<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\HomListi\RDTheme;

wp_enqueue_script( 'swiper' );
$total_product = count( $products );
$swiper_data   = [
	'slidesPerView' => 1,
	'spaceBetween'  => 10,
	//    'loop' => true,
	'navigation'    => [
		'nextEl' => '.swiper-button-next',
		'prevEl' => '.swiper-button-prev',
	],
	'breakpoints'   => [
		640  => [
			'slidesPerView' => 1,
			'spaceBetween'  => 20,
		],
		768  => [
			'slidesPerView' => 2,
			'spaceBetween'  => 20,
		],
		1024 => [
			'slidesPerView' => 3,
			'spaceBetween'  => 20,
		],
	],
];
$btn_class     = null;
if ( $total_product < 2 ) {
	$btn_class = "d-md-none";
} elseif ( $total_product < 3 ) {
	$btn_class = "d-lg-none";
}

$swiper_data = json_encode( $swiper_data );

?>

<div class="rt-main-slider-wrapper related products related-product-slider">
    <div class="section-title clearfix">
        <h2 class="owl-custom-nav-title"><?php echo esc_html( $title ); ?></h2>
    </div>
    <div class="rt-swiper-slider swiper-container" data-options="<?php echo esc_attr( $swiper_data ); ?>">
        <div class="swiper-wrapper">
			<?php foreach ( $products as $product ) : ?>
				<?php
				$post_object = get_post( $product->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object );
				?>
                <ul class="products swiper-slide"><?php wc_get_template_part( 'content', 'product' ); ?></ul>
			<?php endforeach; ?>
        </div>
    </div>

    <div class="swiper-button-wrap <?php echo esc_attr( $btn_class ) ?>">
        <div class="swiper-button-prev nav-button"></div>
        <div class="swiper-button-next nav-button"></div>
    </div>
</div>