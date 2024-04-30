<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Helper;
use radiustheme\HomListi\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;

$size = $data['thumbnail_size'] ? $data['thumbnail_size'] : 'rdtheme-size3';

$args = [
	'post_type'           => 'rtcl_listing',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => $data['number'],
];

// Ordering
if ( $data['random'] ) {
	$args['orderby'] = 'rand';
} else {
	$args['orderby'] = $data['orderby'];
	if ( $data['orderby'] == 'title' ) {
		$args['order'] = 'ASC';
	}
}

if ( ! empty( $data['cat'] ) ) {
	$args['tax_query'] = [
		[
			'taxonomy' => 'rtcl_category',
			'field'    => 'term_id',
			'terms'    => $data['cat'],
		],
	];
}

if ( $data['type'] ) {
	$args['meta_query'][] = [
		'key'     => 'ad_type',
		'value'   => $data['type'],
		'compare' => 'in',
	];
}

if ( $data['promotions_product'] ) {
	$args['meta_query'][] = [
		'key'     => $data['promotions_product'],
		'value'   => '1',
		'compare' => 'in',
	];
}

if ( $data['exclude'] ) :
	$excluded_ids         = explode( ',', $data['exclude'] );
	$args['post__not_in'] = $excluded_ids;
endif;


if ( $data['offset'] ) {
	$args['offset'] = $data['offset'];
}

$query = new \WP_Query( $args );

$display = [
	'cat'    => $data['cat_display'] ? true : false,
	'views'  => $data['views_display'] ? true : false,
	'fields' => $data['field_display'] === 'yes' ? true : false,
	'label'  => $data['label_display'] === 'yes' ? true : false,
	'author' => $data['author_display'] === 'yes' ? true : false,
	'type'   => true,
];

$gird_column_desktop = ( isset( $data['gird_column_desktop'] ) ? $data['gird_column_desktop'] : '4' );
$gird_column_tab     = ( isset( $data['gird_column_desktop_tablet'] ) && ! empty( $data['gird_column_desktop_tablet'] ) ) ? $data['gird_column_desktop_tablet'] : '6';
$gird_column_mobile  = ( isset( $data['gird_column_desktop_mobile'] ) && ! empty( $data['gird_column_desktop_mobile'] ) ) ? $data['gird_column_desktop_mobile'] : '6';
$col_class           = "col-lg-{$gird_column_desktop} col-md-{$gird_column_tab} col-sm-{$gird_column_mobile}";
?>
    <div class="rt-el-listing-wrapper product-grid <?php echo esc_attr( $data['layout'] . ' ' . $data['hanging_visibility'] ) ?>">


        <div class="row featuredContainer">

			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					global $listing;
					$listing_type = Listing_Functions::get_listing_type( $listing );
					$thumb_img    = wp_get_attachment_image_src( $listing->get_the_thumbnail_id(), $size );
					$images       = Functions::get_listing_images( $listing->get_id() );
					?>
                    <div class="<?php echo esc_attr( $col_class ); ?>">
                        <div class="product-box style2">
							<?php if ( $data['listing_thumb_visibility'] === 'yes' ) : ?>
                                <div class="product-thumb">

									<?php if ( $display['label'] ):
										$listing->the_badges();
									endif; ?>

									<?php if ( 'slider' !== $data['thumbnail_source'] ) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="thumbnail-bg" style="background-image:url(<?php echo esc_url( $thumb_img[0] ) ?>)"></div>
                                        </a>
									<?php else :
										Helper::homlisti_thumb_carousel( $listing->get_id(), $size );
									endif; ?>

									<?php $type_hide = ( $data['type_visibility'] != 'yes' || empty( $listing_type ) ) ? 'hide-product-type' : null; ?>
                                    <div class="product-type <?php echo esc_attr( $type_hide ) ?>">
										<?php if ( $data['type_visibility'] && ! empty( $listing_type ) ): ?>
                                            <span class="listing-type-badge">
                                            <?php echo sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'homlisti-core' ) ), $listing_type['label'] ); ?>
                                        </span>
										<?php endif; ?>
										<?php if ( $display['label'] ):
											$listing->the_badges();
										endif; ?>
                                    </div>
                                </div>
							<?php endif; ?>
                            <div class="product-content">
                                <div class="product-top-content">

									<?php if ( $listing->can_show_price() ): ?>
                                        <div class="product-price"><?php echo $listing->get_price_html(); ?></div>
									<?php endif; ?>

									<?php if ( $listing->get_the_title() ): ?>
                                        <<?php echo esc_attr($data['title_tag']) ?> class="item-title rt-main-title">
                                            <a href="<?php $listing->the_permalink(); ?>">
												<?php $listing->the_title(); ?>
                                            </a>
                                        </<?php echo esc_attr($data['title_tag']) ?>>
									<?php endif; ?>

									<?php
									if ( class_exists('RtclPro') && $display['fields'] ) { ?>
                                        <div class="list-information <?php echo esc_attr( $data['info_style'] ) ?>">
											<?php TemplateHooks::loop_item_listable_fields(); ?>
                                        </div>
									<?php }
									?>

                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
        </div>
    </div>