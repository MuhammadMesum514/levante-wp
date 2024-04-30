<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * top_sub_title
 * title
 * subtitle
 * bg_title
 * top_title_icon
 *
 */

namespace radiustheme\HomListi_Core;
?>
<div class="section-title-wrapper">

    <!--Background Title-->
    <?php if ( $data['bg_title'] ): ?>
	    <div class="bg-title-wrap">
            <span class="background-title <?php echo esc_attr($data['bg_title_style']) ?>">
                <?php echo esc_html( $data['bg_title'] ); ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="title-inner-wrapper">

        <!--Top Sub Title-->
        <?php if ( $data['top_sub_title'] ): ?>
            <div class="top-sub-title-wrap">
                <span class="top-sub-title">
                    <?php
                    if($data['top_title_icon'] && ('left' == $data['icon_position'] || 'both' == $data['icon_position'])) {
                        echo '<i style="margin-right:5px" class="' . $data['top_title_icon'] . '" aria-hidden="true"></i>';
                    }
                    echo esc_html( $data['top_sub_title'] );
                    if($data['top_title_icon'] && ('right' == $data['icon_position']|| 'both' == $data['icon_position'])) {
	                    echo '<i style="margin-left:5px;transform:scaleX(-1)" class="' . $data['top_title_icon'] . '" aria-hidden="true"></i>';
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <!--Main Title-->
        <?php if ( $data['title'] ): ?>
            <<?php echo esc_attr($data['main_title_tag']) ?> class="main-title"><?php echo wp_kses_post( $data['title'] ); ?></<?php echo esc_attr($data['main_title_tag']) ?>>
        <?php endif; ?>

        <!--Description-->
        <?php if ( $data['description'] ): ?>
            <div class="description"><?php echo wp_kses_post( $data['description'] ); ?></div>
        <?php endif; ?>
    </div>
</div>