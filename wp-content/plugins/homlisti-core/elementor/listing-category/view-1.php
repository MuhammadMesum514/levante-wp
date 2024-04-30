<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$terms = get_terms( [
	'taxonomy' => 'rtcl_category',
	'orderby'  => $data['orderby'],
	'order'    => $data['order'],
	'number'   => $data['number'],
] );

if ( ! empty( $data['categories'] ) && is_array( $data['categories'] ) ) {
	$all_term = [];
	foreach ( $data['categories'] as $current_term_id ) {
		$all_term[] = get_term_by( 'id', $current_term_id, 'rtcl_category' );
	}
	$terms = $all_term;
}
$icon_list = [];
if ( 'custom_icon' == $data['cat_icon_style'] && $data['custom_icon_list'] ) {
	$icon_list = [];
	foreach ( $data['custom_icon_list'] as $icon ) {
		$icon_list[] = $icon['custom_icon']['value'];
	}
}

if ( 'custom_image' == $data['cat_icon_style'] && $data['custom_image_list'] ) {
	$icon_list = [];
	foreach ( $data['custom_image_list'] as $icon ) {
		$icon_list[] = $icon['custom_image']['id'];
	}
}
$gird_column_desktop = ( $data['gird_column'] ? $data['gird_column'] : '5' );
$gird_column_tab     = ( $data['gird_column_tablet'] ? $data['gird_column_tablet'] : '4' );
$gird_column_mobile  = ( $data['gird_column_mobile'] ? $data['gird_column_mobile'] : '2' );
$col_class           = "row-cols-lg-{$gird_column_desktop} row-cols-md-{$gird_column_tab} row-cols-{$gird_column_mobile}";
?>

<div class="rt-listing-category-wrapper <?php echo esc_attr( $data['layout'] ) ?>">
    <div class="row <?php echo esc_attr( $col_class ); ?>">

		<?php if ( ! empty( $terms ) ) :
			$i = 0;
			?>
			<?php foreach ( $terms as $term ) :
			$term_id = $term->term_id;
			$name = $term->name;
			$count = __( $term->count, 'homlisti-core' );
			$icon = get_term_meta( $term_id, '_rtcl_icon', true );
			?>
            <div class="col">
                <div class="listing-category-inner">
                    <div class="category-thumbnail">
						<?php if ( isset( $icon_list[ $i ] ) ) : ?>
							<?php if ( 'custom_icon' == $data['cat_icon_style'] ) : ?>
                                <i class="<?php echo esc_attr( $icon_list[ $i ] ) ?>"></i>
							<?php else :
								echo wp_get_attachment_image( $icon_list[ $i ], 'full' ); ?>
							<?php endif; ?>
						<?php else : ?>
                            <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $icon ) ?>"></i>
						<?php endif; ?>

                    </div>
                    <div class="category-content">
                        <h3 class="cat-title">
                            <a href="<?php echo get_term_link( $term_id ) ?>"><?php echo esc_html( $name ) ?></a>
                        </h3>
                        <span class="cat-count-number"><?php echo esc_html( $count . ' ' . $data['cat_count_suffix'] ) ?></span>
                    </div>
                </div>
            </div>
			<?php $i ++;
		endforeach; ?>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
    </div>
</div>
