<?php
/**
 *
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

use Rtcl\Models\RtclCFGField;


if ( count( $fields ) ) :
	ob_start();
	foreach ( $fields as $field ) :

		$field = new RtclCFGField( $field->ID );
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( $value ) :
			?>
            <li>
				<?php if ( $field->getIconClass() ): ?>
                    <i class='rtcl-icon rtcl-icon-<?php echo esc_html( $field->getIconClass() ); ?>'></i>
				<?php else: ?>
                    <span class='listable-label'><?php echo esc_html( $field->getLabel() ); ?></span>
				<?php endif; ?>
                <span class='listable-value'>
                    <span class="prefix">
                        <?php
                        if ( $field->getLabel() == 'Bedroom' ) {
	                        echo esc_html__( 'Beds', 'homlisti' );
                        }

                        if ( $field->getLabel() == 'Bath' ) {
	                        echo esc_html__( 'Baths', 'homlisti' );
                        }
                        ?>
                    </span>

                    <span class="value">
                    <?php
                    $value = ( ! is_array( $value ) && strlen( $value ) == 1 ) ? '<span>0</span>' . $value : $value;
                    echo stripslashes_deep( $value );
                    ?>
                    </span>

                    <span class="suffix">
                    <?php
                    if ( ! in_array( strtolower( $field->getLabel() ), [ 'bedroom', 'beds', 'bed', 'bath', 'baths' ] ) ) {
	                    echo esc_html( $field->getLabel() );
                    }
                    ?>
                    </span>
                </span>
            </li>
		<?php endif;
	endforeach;

	$fields_html = ob_get_clean();
	if ( $fields_html ) {
		printf( '<ul class="product-features">%s</ul>', $fields_html );
	}
endif;
