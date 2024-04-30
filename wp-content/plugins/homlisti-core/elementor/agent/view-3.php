<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;
use radiustheme\HomListi\Helper;

$query               = new \WP_Query( $data['query_args'] );
$gird_column_desktop = ( $data['gird_column'] ? $data['gird_column'] : '3' );
$gird_column_tab     = ( isset($data['gird_column_tab']) ? $data['gird_column_tab'] : '6' );
$gird_column_mobile  = ( isset($data['gird_column_mobile']) ? $data['gird_column_mobile'] : '6' );
$col_class           = "col-lg-{$gird_column_desktop} col-md-{$gird_column_tab} col-sm-{$gird_column_mobile}";
?>

<div class="rt-agents-wrapper row <?php echo esc_attr( $data['layout'] ) ?>">
	<?php if ( $query->have_posts() ) : ?>
		<?php while ( $query->have_posts() ) : $query->the_post();

			$user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
			if ( ! $user = get_user_by( 'id', $user_id ) ) {
				return;
			}
			$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
			$name     = trim( implode( ' ', [ $user->first_name, $user->last_name ] ) );
			$name     = $name ? $name : $user->display_name;
			$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
			$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
			$store    = new Store( $store_id );
			?>
            <div class="<?php echo esc_attr( $col_class ); ?>">
                <div class="agent-block">
                    <div class="item-img">
						<?php echo $pp_id ? wp_get_attachment_image( $pp_id,
							[
								420,
								240,
							] ) : get_avatar( $user_id, 250 );
						?>
                    </div>

                    <div class="item-content">
                        <div class="item-title">
                            <h3 class="agent-name">
                                <a href="<?php echo get_the_permalink(); ?>">
                                    <?php echo esc_html( $name ); ?>
                                    <?php do_action('rtcl_after_author_meta', $user_id ); ?>
                                </a>
                            </h3>

                            <h4 class="item-subtitle">
                                <a href="<?php echo get_the_permalink( $store_id ); ?>"><?php echo get_the_title( $store_id ); ?></a>
                            </h4>

                            <div class="listing-count">
	                            <?php
	                            $total_user_post     = count( Helper::get_user_listing_ids( $user_id ) );
	                            $total_manager_posts = count( $store->get_manager_listing_ids( $user_id ) );
	                            $total_posts_count   = $total_user_post + $total_manager_posts;
	                            $count = $total_posts_count == 0 ? 1 : $total_posts_count;
	                            printf( _n( '%s Listing', '%s Listings', $count, 'homlisti-core' ),
		                            $total_posts_count );
	                            ?>
                            </div>

                        </div>

	                    <?php if ( $data['show_contact'] && $phone ): ?>
                            <div class="item-contact">
                                <div class="item-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="item-phn-no">
									<?php echo esc_html__( 'Call:', 'homlisti-core' ); ?>
                                    <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </div>
                            </div>
						<?php endif; ?>

						<?php if ( $data['show_social_cion'] ) : ?>
                            <div class="social-icon">
								<?php
								$social_list = Functions::get_user_social_profile( $user_id );
								if ( ! empty( $social_list ) ) {
									?>
                                    <a href="#" class="social-hover-icon social-link">
                                        <i class="fas fa-share-alt"></i>
                                    </a>
									<?php
									foreach ( $social_list as $item => $value ) { ?>
                                        <a target="_blank" href="<?php echo esc_url( $value ) ?>">
                                            <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
                                        </a>
										<?php
									}
								}
								?>
                            </div>
						<?php endif; ?>

                    </div>
                </div>

            </div>
		<?php endwhile; ?>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

</div>
