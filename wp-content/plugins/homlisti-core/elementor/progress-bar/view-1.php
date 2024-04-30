<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
$progress_percentage = is_numeric( $data['percent']['size'] ) ? $data['percent']['size'] : '0';
if ( 100 < $progress_percentage ) {
    $progress_percentage = 100;
}

?>
<div class="rt-progress-bar <?php echo esc_attr($data['layout']) ?>">
    <h4 class="progress-title"><?php echo $data['title']; ?></h4>
    <div 
        class="elementor-progress-wrapper <?php echo esc_attr($data['progress_type']) ?>"
        role = "progressbar"
        aria-valuemin = "0"
        aria-valuemax = "100"
        aria-valuenow = "<?php echo esc_attr( $progress_percentage ) ?>"
        aria-valuetext = "<?php echo esc_attr( $data['inner_text'] ) ?>"
    >
        <div 
            class="elementor-progress-bar <?php echo esc_attr($data['progress_animation']) ?>" 
            data-max="<?php echo esc_attr( $progress_percentage) ?>"
        >
            <span class="elementor-progress-text"><?php echo $data['inner_text']; ?></span>
            <?php if ( 'hide' !== $data['display_percentage'] ) { ?>
                <span class="elementor-progress-percentage">
                    <?php echo $progress_percentage; ?>%
                </span>
            <?php } ?>
        </div>
    </div>
</div>