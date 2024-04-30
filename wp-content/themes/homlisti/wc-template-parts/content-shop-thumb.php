<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\HomListi\RDTheme;
?>
<div class="product-thumb-area">
	<?php
	global $product;
	woocommerce_show_product_loop_sale_flash();
	woocommerce_template_loop_product_thumbnail();
	?>
	<div class="overlay"></div>
	<div class="product-info">
		<ul>
			<li><?php rdtheme_wc_add_to_cart_icon();?></li>

			<?php if ( function_exists( 'YITH_WCQV_Frontend' ) && RDTheme::$options['wc_quickview_icon'] ): ?>
				<li><a href="" class="yith-wcqv-button" data-product_id="<?php echo esc_attr( $product->get_id() );?>"><i class="fas fa-search"></i></a></li>
			<?php endif; ?>

			<?php if ( class_exists( 'YITH_WCWL_Shortcode' ) && RDTheme::$options['wc_wishlist_icon'] ): ?>
                <?php
                $product_id = $product->get_id();
                $is_in_wishlist = YITH_WCWL()->is_product_in_wishlist( $product_id, false );
                $wishlist_url = YITH_WCWL()->get_wishlist_url();

                $title_before = esc_html__( 'Add to Wishlist', 'homlisti' );
                $title_after = esc_html__( 'Aleady exists in Wishlist! Click here to view Wishlist', 'homlisti' );

                if ( $is_in_wishlist ) {
                    $class = 'rdtheme-remove-from-wishlist';
                    $icon_font = 'fas fa-check';
                    $title = $title_after;
                }
                else {
                    $class = 'rdtheme-add-to-wishlist';
                    $icon_font = 'far fa-heart';
                    $title = $title_before;
                }

                $html = '';
                $html .= '<i class="wishlist-icon '.$icon_font.'"></i>';

                $nonce = wp_create_nonce( 'homlisti_wishlist_nonce' );
                ?>
                <li>
                    <a href="<?php echo esc_url( $wishlist_url );?>" title="<?php echo esc_attr( $title ); ?>" rel="nofollow" data-product-id="<?php echo esc_attr( $product_id );?>" data-title-after="<?php echo esc_attr( $title_after );?>" class="rdtheme-wishlist-icon <?php echo esc_attr( $class );?>" data-nonce="<?php echo esc_attr( $nonce ); ?>" target="_blank">
                        <?php echo wp_kses_post( $html ); ?>
                    </a>
                </li>
            <?php endif; ?>
		</ul>
	</div>
</div>