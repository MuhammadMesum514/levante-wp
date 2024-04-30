<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks;

$size = $data['thumbnail_size'] ? $data['thumbnail_size'] : 'rtcl-thumbnail';
// Post type
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

$gird_column_desktop = ( isset( $data['gird_column_desktop'] ) ? $data['gird_column_desktop'] : '12' );
$gird_column_tab     = ( isset( $data['gird_column_desktop_tablet'] ) && ! empty( $data['gird_column_desktop_tablet'] ) ) ? $data['gird_column_desktop_tablet'] : '12';
$gird_column_mobile  = ( isset( $data['gird_column_desktop_mobile'] ) && ! empty( $data['gird_column_desktop_mobile'] ) ) ? $data['gird_column_desktop_mobile'] : '12';

$col_class           = "col-lg-{$gird_column_desktop} col-md-{$gird_column_tab} col-sm-{$gird_column_mobile}";
?>
	<div class="rt-el-listing-wrapper product-grid <?php echo esc_attr( $data['layout'].' '.$data['hanging_visibility'] ) ?>">
		<div class="row featuredContainer">

			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					global $listing;
					$listing_type = Listing_Functions::get_listing_type($listing);
					$thumb_img = wp_get_attachment_image_src( $listing->get_the_thumbnail_id(), $size );
					?>
					<div class="<?php echo esc_attr( $col_class ); ?>">
						<div class="product-box style2">
							<div class="product-thumb">

								<?php $type_hide = ( $data['type_visibility'] != 'yes' || empty( $listing_type ) ) ? 'hide-product-type' : null; ?>
                                <div class="product-type <?php echo esc_attr( $type_hide ) ?>">
									<?php if ( $data['type_visibility'] && ! empty($listing_type) ): ?>
										<span class="listing-type-badge">
                                            <?php echo sprintf( "%s %s", apply_filters('rtcl_type_prefix', __('For', 'homlisti-core')), $listing_type['label'] ); ?>
                                        </span>
									<?php endif; ?>

								</div>

								<?php if ( $display['label'] ):
									$listing->the_badges();
								endif; ?>

							</div>
							<div class="product-content">
								<div class="product-top-content">
									<?php
									if ( $listing->has_category() && $display['cat'] ):
										$category = $listing->get_categories();
										$category = end( $category );
										?>
										<div class="product-category">
											<a href="<?php echo esc_url( get_term_link( $category->term_id,
												$category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
										</div>
									<?php endif;
									?>
									<?php if ( $listing->get_the_title() ): ?>
										<<?php echo esc_attr($data['title_tag']) ?> class="item-title rt-main-title">
											<a href="<?php $listing->the_permalink(); ?>">
												<?php $listing->the_title(); ?>
											</a>
										</<?php echo esc_attr($data['title_tag']) ?>>
									<?php endif; ?>

									<ul class="entry-meta">
										<?php if ( $listing->has_location() && $listing->can_show_location() && $data['location_visibility'] ): ?>
											<li><i class="fas fa-map-marker-alt"></i><?php $listing->the_locations(); ?></li>
										<?php endif; ?>

										<?php if ( $listing->can_show_date() && $data['date_visibility'] ): ?>
											<li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
										<?php endif; ?>

										<?php if ( $display['views'] ): ?>
											<li class="rt-views">
												<i class="far fa-eye"></i><?php echo sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'homlisti-core' ),
													number_format_i18n( $listing->get_view_counts() ) ); ?>
											</li>
										<?php endif; ?>
									</ul>

									<?php if ( $data['content_visibility'] ) : ?>
										<div class="listing-content">
											<?php
											$content = strip_shortcodes( $listing->get_the_content() );
											$content = force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( $content ), $data['content_limit'], '' ) ) );
											echo wp_kses( $content, [
												'a'      => [
													'href'   => [],
													'title'  => [],
													'target' => [],
												],
												'em'     => [],
												'strong' => [],
												'ul'     => [],
												'li'     => [],
											] );
											?>
										</div>
									<?php endif; ?>

									<?php
									if ( class_exists('RtclPro') && $display['fields'] ) { ?>
										<div class="list-information <?php echo esc_attr( $data['info_style'] ) ?>">
											<?php TemplateHooks::loop_item_listable_fields(); ?>
										</div>
									<?php }
									?>

								</div>
								<div class="product-bottom-content">

									<?php if ( $listing->can_show_price() ): ?>
										<div class="product-price"><?php echo $listing->get_price_html(); ?></div>
									<?php endif; ?>

									<div class="action-btn">
										<a class="btn btn-primary" href="<?php $listing->the_permalink(); ?>">
											<?php echo esc_html__( 'See Details', 'homlisti-core' ); ?>
										</a>
									</div>

								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>