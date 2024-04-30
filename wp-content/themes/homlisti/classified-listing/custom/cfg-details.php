<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

global $listing;

$group_id = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;

if ( $group_id ) {
	$field_ids   = Functions::get_cf_ids_by_cfg_id( $group_id );
	$group_title = get_the_title( $group_id );
}
?>

<div class="product-details">
    <ul class="amenities-list">
		<?php
		if ( ! empty( $field_ids ) ) {
			foreach ( $field_ids as $single_field ) {
				$field = new RtclCFGField( $single_field );
				$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
				$icon  = $field->getIconClass() ? $field->getIconClass() : 'home';
				if ( ! $value || empty( $value ) ) {
					continue;
				}
				?>
                <li>
					<?php if ( $field->getLabel() ): ?>

                        <span class="heading-title rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
                            <?php echo esc_html( $field->getLabel() ); ?>
                        </span>
					<?php endif; ?>
                    <span class="cfp-value">
                        <?php
                        if ( $field->getType() === 'checkbox' ) {
	                        $amenities = Functions::get_cf_data( $single_field );
	                        $data      = $amenities['value'];
	                        $options   = $amenities['options']['choices'];
	                        foreach ( $options as $key => $value ) {
		                        if ( in_array( $key, $data ) === true ) {
			                        echo esc_html( $value );
		                        }
	                        }
	                        ?>
	                        <?php
                        } else {
	                        if ( ! empty( $value ) ): ?>
		                        <?php if ( $field->getType() === 'url' ):
			                        $nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                                    <a href="<?php echo esc_url( $value ); ?>"
                                       target="<?php echo esc_attr( $field->getTarget() ) ?>"
                                                <?php echo esc_html( $nofollow ) ?>><?php echo esc_html( $field->getLabel() ) ?></a>
		                        <?php else: ?>
			                        <?php Functions::print_html( $value ); ?>
		                        <?php endif; ?>
	                        <?php endif;
                        }
                        ?>
                    </span>
                </li>
				<?php
			}
		}
		?>
    </ul>
</div>