<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 3.8.1
 */
use radiustheme\HomListi\RDTheme;
global $product;
?>
<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
	<div class="product_meta">
		<?php
		$sku = $product->get_sku();
		$sku = $sku ? $sku : esc_html_e( 'N/A', 'homlisti' );
		?>
		<span class="sku-label"><?php esc_html_e( 'SKU:', 'homlisti' ); ?></span><span class="sku"><?php echo wp_kses_post( $sku ); ?></span>
	</div>
<?php endif;