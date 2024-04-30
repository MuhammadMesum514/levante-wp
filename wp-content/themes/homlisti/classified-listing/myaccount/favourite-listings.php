<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\HomListi\Listing_Functions;
use Rtcl\Helpers\Pagination;
use Rtcl\Models\Listing;

global $post;
?>

<div class="rtcl-favourite-listings rtcl rtcl-listings product-grid">

	<?php if ( $rtcl_query->have_posts() ) : ?>
        <div class="rtcl-list-view">
            <!-- the loop -->
			<?php while ( $rtcl_query->have_posts() ) : $rtcl_query->the_post();
				$post_meta    = get_post_meta( $post->ID );
				$listing      = new Listing( $post->ID );
				$listing_type = Listing_Functions::get_listing_type( $listing );
				?>
                <div class="listing-item rtcl-listing-item product-box">
                    <div class="product-thumb">
                        <a href="<?php the_permalink(); ?>"><?php $listing->the_thumbnail(); ?></a>
                        <div class="product-type">
							<?php if ( ! empty( $listing_type ) ) : ?>
                                <span class="listing-type-badge">
                                    <?php echo sprintf( "%s %s", apply_filters('rtcl_type_prefix', __('For', 'homlisti')), $listing_type['label'] ); ?>
                                </span>
							<?php endif; ?>
							<?php $listing->the_badges(); ?>
                        </div>
						<?php if ( $listing->can_show_price() ): ?>
                            <div class="product-price"><?php printf( "%s", $listing->get_price_html() ); ?></div>
						<?php endif; ?>
                    </div>
                    <div class="product-content">
						<?php if ( $listing->has_category() && $listing->can_show_category() ):
							$category = $listing->get_categories();
							$category = end( $category );
							?>
                            <div class="product-category">
                                <a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
                            </div>
						<?php endif; ?>
                        <h3 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php $listing->the_meta(); ?>
                        <div class="rtcl-actions">
                            <a href="#" class="btn btn-danger btn-sm rtcl-delete-favourite-listing"
                               data-id="<?php echo esc_attr( $post->ID ) ?>"><?php _e( 'Remove from Favourites', 'homlisti' ) ?></a>
                        </div>
                    </div>
                </div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
            <!-- end of the loop -->
        </div>
        <!-- pagination here -->
		<?php Pagination::pagination( $rtcl_query ); ?>
	<?php else : ?>
        <p class="listing-archive-noresult"> <?php _e( 'No listing found.', 'homlisti' ) ?></p>
	<?php endif; ?>

</div>