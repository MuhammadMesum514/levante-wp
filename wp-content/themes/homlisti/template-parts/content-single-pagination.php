<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

$previous = get_previous_post();
$next = get_next_post();
if ( $previous && $next ) {
	$cols = 'half-width';
} else {
	$cols = 'full-width';
}
if ( $previous || $next ):
	?>
    <div class="thumb-pagination <?php echo esc_attr( $cols ) ?>">
		<?php if ( $previous ):
			$prev_image = get_the_post_thumbnail_url( $previous, 'rdtheme-size2' ); ?>
            <div class="col prev">
                <div class="post-nav prev-post">
                    <div class="overlay" style="background-image:url(<?php echo esc_url( $prev_image ) ?>)"></div>
                    <a href="<?php echo esc_url( get_permalink( $previous ) ); ?>" class="pg-prev">
                        <i class="fas fa-chevron-left"></i>
                        <h4 class="item-title"><span><?php esc_html_e( 'Previous Post: ', 'homlisti' ) ?></span><?php echo get_the_title( $previous ); ?></h4>
                    </a>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( $next ):
			$prev_image = get_the_post_thumbnail_url( $next, 'rdtheme-size2' ); ?>
            <div class="col next">
                <div class="post-nav next-post">
                    <div class="overlay" style="background-image:url(<?php echo esc_url( $prev_image ) ?>)"></div>
                    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="pg-next">
                        <h4 class="item-title"><span><?php esc_html_e( 'Next Post: ', 'homlisti' ) ?></span><?php echo get_the_title( $next ); ?></h4>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>