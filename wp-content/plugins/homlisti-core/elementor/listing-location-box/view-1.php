<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$count_html = sprintf( _nx( '%s Property', '%s Properties', $data['count'], 'Number of Properties', 'homlisti-core' ), number_format_i18n( $data['count'] ) );

$link_start = $data['enable_link'] ? '<a href="'.$data['permalink'].'">' : '';
$link_end   = $data['enable_link'] ? '</a>' : '';

$class = $data['display_count'] ? 'rtin-has-count' : '';
$class .= empty($data['location_image']['id']) ? ' rtin-no-image' : '';
$radius = '';
if($data['disable_border_radius']) {
    $radius = 'no-border-radius';
}
?>
<div class="rt-el-listing-location-box category-browse category-cities <?php echo esc_attr($data['layout']. ' ' . $radius) ?>">
    <div class="category-box <?php echo esc_attr( $class ); ?>">
        <?php echo wp_kses_post( $link_start );?>
            <div class="img-wrap">
                <div class="item-img"><div class="overlay"></div></div>
            </div>
            <div class="item-content">
                <div class="title-wrap">
                    <h3 class="item-title"><?php echo esc_html( $data['title'] ); ?></h3>
                </div>
                <?php if ( $data['display_count'] ): ?>
                    <div class="item-count <?php echo esc_attr($data['show_dots']) ?>">
                        <span><?php echo esc_html( $count_html < 10 ? '0'.ltrim($count_html, "0") : $count_html ); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php echo wp_kses_post( $link_end ); ?>
    </div>
</div>