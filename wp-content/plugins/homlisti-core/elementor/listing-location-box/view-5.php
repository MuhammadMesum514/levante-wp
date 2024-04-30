<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$count_html = sprintf( _nx( '%s Property', '%s Properties', $data['count'], 'Number of Properties', 'homlisti-core' ), number_format_i18n( $data['count'] ) );

$link_start = $data['enable_link'] ? '<a href="' . $data['permalink'] . '">' : '';
$link_end   = $data['enable_link'] ? '</a>' : '';

$class  = $data['display_count'] ? 'rtin-has-count' : '';
$class  .= empty( $data['location_image']['id'] ) ? ' rtin-no-image' : '';
$radius = '';
if ( $data['disable_border_radius'] ) {
	$radius = 'no-border-radius';
}
?>
<div class="rt-el-listing-location-box category-browse category-cities style2 style3 style4 <?php echo esc_attr( $data['layout'] . ' ' . $radius ) ?>">
	<div class="category-box <?php echo esc_attr( $class ); ?>">

		<div class="img-wrap">
			<div class="item-img"></div>
            <div class="overlay"></div>
		</div>
		<div class="item-content">

			<h3 class="item-title">
				<?php echo wp_kses_post( $link_start ); ?>
				<?php echo esc_html( $data['title'] ); ?>
				<?php echo wp_kses_post( $link_end ); ?>
			</h3>

			<?php if ( $data['display_count'] ): ?>
				<div class="item-count <?php echo esc_attr( $data['show_dots'] ) ?>">
					<span><span><?php echo esc_html( $count_html < 10 ? '0' . ltrim( $count_html, "0" ) : $count_html ); ?></span></span>
				</div>
			<?php endif; ?>
			<?php if ( $data['show_arrow_icon'] == 'yes' ) : ?>
				<?php echo wp_kses_post( $link_start ); ?>
<!--				<i class="fas fa-arrow-right link-icon"></i>-->
<!--                <i class="fas fa-angle-right link-icon"></i>-->
                <i class="fas fa-chevron-right link-icon"></i>
				<?php echo wp_kses_post( $link_end ); ?>
			<?php endif; ?>
		</div>

	</div>
</div>