<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
?>
<div class="section-heading">
    <h2 class="heading-title"><span class="title-typejs" data-options="<?php echo esc_attr( $data['options'] );?>"></span>&nbsp;</h2>
    <?php if ( $data['subtitle'] ): ?>
        <p class="heading-subtitle"><?php echo wp_kses_post( $data['subtitle'] );?></p>
    <?php endif; ?>
</div>