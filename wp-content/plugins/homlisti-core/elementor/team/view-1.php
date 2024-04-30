<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Helper;

?>

<div class="rt-agents-wrapper <?php echo esc_attr( $data['layout'] ) ?>">

    <div class="agent-block">
        <div class="item-img">
			<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $data, 'thumbnail', 'image' ); ?>

			<?php
			if ( ! empty( $data['social_icon_list'] ) ) {
				Helper::get_user_social_info( $data['social_icon_list'] );
			}
			?>

			<?php if ( $data['listing_number'] ) : ?>
                <div class="category-box">
                    <div class="item-category"><?php echo esc_html( $data['listing_number'] ) ?></div>
                </div>
			<?php endif; ?>
        </div>

        <div class="item-content">
            <div class="item-title">
                <h3 class="agent-name"><?php echo esc_html( $data['agent_name'] ) ?></h3>
				<?php if ( $data['agency_name'] ) :
					$target = $data['agency_url']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $data['agency_url']['nofollow'] ? ' rel="nofollow"' : '';
					?>
                    <h4 class="item-subtitle">
						<?php echo ! empty( $data['agency_url'] ) ? '<a href="' . $data['agency_url']['url'] . '"' . $target . $nofollow . '>' : null ?>
						<?php echo esc_html( $data['agency_name'] ); ?>
						<?php echo ! empty( $data['agency_url'] ) ? '</a>' : null ?>
                    </h4>
				<?php endif; ?>
            </div>

			<?php if ( $data['agent_phone'] ) : ?>
                <div class="item-contact">
                    <div class="item-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="item-phn-no">
						<?php printf( "%s %s", esc_html__( 'Call:', 'homlisti-core' ), esc_html( $data['agent_phone'] ) ); ?>
                    </div>
                </div>
			<?php endif; ?>

        </div>
    </div>

</div>
