<?php
/**
 * Login Form Information
 *
 * @var Listing $listing
 * @var int $title_limit
 * @var array $hidden_fields
 * @var string $selected_type
 * @var string $title
 * @var string $price_type
 * @var string $price
 * @var string $post_content
 * @var string $editor
 * @var int $category_id
 * @var int $post_id
 * @var int $description_limit
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use radiustheme\HomListi\Listing_Functions;

?>
<div class="rtcl-post-details rtcl-post-section">
    <div class="rtcl-post-section-title">
        <h3>
            <i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( "Listing Information", "homlisti" ); ?>
        </h3>
    </div>
    <div class="form-group">
        <label for="rtcl-title"><?php esc_html_e( 'Title', 'homlisti' ); ?><span
                    class="require-star">*</span></label>
        <input type="text"
			<?php echo esc_attr( $title_limit ? 'data-max-length="3" maxlength="' . $title_limit . '"' : '' ); ?>
               class="form-control"
               value="<?php echo esc_attr( $title ); ?>"
               id="rtcl-title"
               name="title"
               required/>
		<?php
		if ( $title_limit ) {
			echo sprintf( '<div class="rtcl-hints">%s</div>',
				apply_filters( 'rtcl_listing_title_character_limit_hints', sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'homlisti' ), $title_limit )
				) );
		}
		?>
    </div>
	<?php if ( ! in_array( 'price', $hidden_fields ) ):
		$listingPricingTypes = Options::get_listing_pricing_types();
		?>
        <div id="rtcl-pricing-wrap">
			<?php if ( ! Functions::is_pricing_disabled() ) { ?>
                <div class="rtcl-post-section-title">
                    <h3><?php esc_html_e( "Pricing:", "homlisti" ); ?></h3>
                </div>
                <div class="rtcl-from-group rtcl-checkbox-list rtcl-checkbox-inline rtcl-listing-pricing-types">
					<?php
					foreach ( $listingPricingTypes as $type_id => $type ) {
						$checked = ( $listing_pricing === $type_id ) ? 'checked' : '';
						?>
                        <div class="rtcl-checkbox rtcl-listing-pricing-type">
                            <input type="radio" name="_rtcl_listing_pricing"
                                   id="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>"
								<?php echo esc_attr( $checked ); ?>
                                   value="<?php echo esc_attr( $type_id ) ?>">
                            <label for="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>">
								<?php echo esc_html( $type ); ?>
                            </label>
                        </div>
						<?php
					}
					?>
                </div>
			<?php } ?>
            <div id="rtcl-pricing-items" class="<?php echo esc_attr( 'rtcl-pricing-' . $listing_pricing ) ?>">
				<?php if ( ! Functions::is_price_type_disabled() ): ?>
                    <div class="form-group rtcl-pricing-item rtcl-from-group">
                        <label for="rtcl-price-type">
							<?php esc_html_e( 'Price Type', 'homlisti' ); ?>
                            <span class="require-star">*</span>
                        </label>
                        <select class="form-control" id="rtcl-price-type" name="price_type">
							<?php
							$price_types = Options::get_price_types();
							foreach ( $price_types as $key => $type ) {
								$slt = $price_type == $key ? " selected" : null;
								echo "<option value='{$key}'{$slt}>{$type}</option>";
							}
							?>
                        </select>
                    </div>
				<?php endif; ?>

				<?php do_action( 'rtcl_listing_form_price_items', $listing ); ?>

                <div id="rtcl-price-items"
                     class="rtcl-pricing-item<?php echo ! Functions::is_price_type_disabled() ? ' rtcl-price-type-' . esc_attr( $price_type ) : '' ?>">
                    <div class="form-group rtcl-price-item" id="rtcl-price-wrap">
                        <div class="price-wrap">
                            <label for="rtcl-price">
								<?php echo sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
									esc_html__( "Price", 'homlisti' ),
									apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
								); ?>
                                <span class="require-star">*</span>
                            </label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_price() : '' ); ?>"
                                   name="price"
                                   id="rtcl-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                        <div class="price-wrap rtcl-max-price rtcl-hide">
                            <label for="rtcl-max-price"><?php echo sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
									__( "Max Price", 'homlisti' ),
									apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
								); ?><span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_max_price() : '' ); ?>"
                                   name="_rtcl_max_price"
                                   id="rtcl-max-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                    </div>
					<?php do_action( 'rtcl_listing_form_price_unit', $listing, $category_id ); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div id="rtcl-custom-fields-list" data-post_id="<?php echo esc_attr( $post_id ); ?>">
		<?php do_action( 'wp_ajax_rtcl_custom_fields_listings', $post_id, $category_id ); ?>
    </div>

	<?php if ( Listing_Functions::is_enable_floor_plan() ): ?>
		<?php
		$generalSettings = Functions::get_option( 'rtcl_general_settings' );
		$text            = isset( $generalSettings['floor_plan_section_label'] ) && ! empty( $generalSettings['floor_plan_section_label'] )
			? $generalSettings['floor_plan_section_label'] : '';
		?>
        <div class="additional-input-wrap">
            <div class="form-group">
                <label><?php echo esc_html( $text ); ?>:</label>
            </div>
            <div class="rn-recipe-wrapper">
                <div class="rn-recipe-wrap">
					<?php
					$floorList = get_post_meta( $post_id, "homlisti_floor_plan", true );
					if ( ! empty( $floorList ) ) {
						$count = 0;
						foreach ( $floorList as $floor ) {
							$title      = $floor['title'] ?? '';
							$desc       = $floor['description'] ?? '';
							$bed        = $floor['bed'] ?? '';
							$bath       = $floor['bath'] ?? '';
							$size       = $floor['size'] ?? '';
							$parking    = $floor['parking'] ?? '';
							$floorImage = $floor['floor_img'] ?? '';
							?>
                            <div class="rn-recipe-item">
                                <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                                <div class="rn-recipe-title">
                                    <input type="text" name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][title]" class="form-control"
                                           placeholder="<?php esc_attr_e( 'Title', 'homlisti' ); ?>"
                                           value="<?php echo esc_attr( $title ? $title : '' ); ?>">
                                    <textarea rows="3" cols="10" class="form-control" name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][description]"
                                              placeholder="<?php esc_attr_e( 'Description', 'homlisti' ); ?>"><?php echo esc_html( $desc ? $desc : '' ); ?></textarea>
                                </div>
                                <div class="rn-ingredient-item">
                                    <span class="item-sort"><i class="fa fa-arrows-alt"></i></span>
                                    <div class="rn-ingredient-fields">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Bed', 'homlisti' ); ?>"
                                               class="form-control"
                                               name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][bed]"
                                               value="<?php echo esc_attr( $bed ? $bed : '' ); ?>">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Bath', 'homlisti' ); ?>"
                                               class="form-control"
                                               name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][bath]"
                                               value="<?php echo esc_attr( $bath ? $bath : '' ); ?>">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Size', 'homlisti' ); ?>"
                                               class="form-control"
                                               name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][size]"
                                               value="<?php echo esc_attr( $size ? $size : '' ); ?>">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Parking', 'homlisti' ); ?>"
                                               class="form-control"
                                               name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][parking]"
                                               value="<?php echo esc_attr( $parking ? $parking : '' ); ?>">
                                    </div>
                                </div>
                                <div class="floor-image-wrap">
									<?php if ( ! empty( $floorImage ) ): ?>
                                        <div class="floor-image">
                                            <input name="homlisti_floor_plan[<?php echo esc_attr( $count ) ?>][attachment_id]" type="hidden"
                                                   value="<?php echo esc_attr( $floorImage ? $floorImage : '' ); ?>">
											<?php echo wp_get_attachment_image( $floorImage, 'full' ); ?>
                                            <div class="remove-floor-image">
                                                <a href="#" data-index="<?php echo esc_attr( $count ) ?>" data-post_id="<?php echo esc_attr( $post_id ); ?>"
                                                   data-attachment_id="<?php echo esc_attr( $floorImage ); ?>"><?php esc_html_e( 'Remove Image', 'homlisti' ); ?></a>
                                            </div>
                                        </div>
									<?php endif; ?>
                                    <div class="floor-input-wrapper <?php echo esc_attr( $floorImage ? 'd-none' : '' ); ?>">
                                        <input name="homlisti_floor_img[<?php echo esc_attr( $count ) ?>]" class="homlisti-floor-image" type="file"/>
                                    </div>
                                </div>
                            </div>
							<?php
							$count ++;
						}
					} else { ?>
                        <div class="rn-recipe-item">
                            <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                            <div class="rn-recipe-title">
                                <input type="text" name="homlisti_floor_plan[0][title]" class="form-control"
                                       placeholder="<?php esc_attr_e( 'Title', 'homlisti' ); ?>">
                                <textarea rows="3" cols="10" class="form-control" name="homlisti_floor_plan[0][description]"
                                          placeholder="<?php esc_attr_e( 'Description', 'homlisti' ); ?>"></textarea>
                            </div>
                            <div class="rn-ingredient-wrap">
                                <div class="rn-ingredient-item">
                                    <span class="item-sort"><i class="fa fa-arrows-alt"></i></span>
                                    <div class="rn-ingredient-fields">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Bed', 'homlisti' ); ?>"
                                               class="form-control"
                                               name="homlisti_floor_plan[0][bed]">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Bath', 'homlisti' ); ?>" class="form-control"
                                               name="homlisti_floor_plan[0][bath]">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Size', 'homlisti' ); ?>" class="form-control"
                                               name="homlisti_floor_plan[0][size]">
                                        <input type="text" placeholder="<?php esc_attr_e( 'Parking', 'homlisti' ); ?>" class="form-control"
                                               name="homlisti_floor_plan[0][parking]">
                                    </div>
                                </div>
                            </div>
                            <div class="floor-image-wrap">
                                <div class="floor-input-wrapper">
                                    <input name="homlisti_floor_img[0]" class="homlisti-floor-image" type="file"/>
                                </div>
                            </div>
                        </div>
					<?php } ?>
                </div>
                <div class="rn-recipe-actions">
                    <a href="javascript:void()"
                       class="btn-upload add-ingredient add-recipe btn-sm btn-primary"><?php esc_html_e( 'Add Floor', 'homlisti' ); ?></a>
                </div>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( Listing_Functions::is_enable_panorama_view() ): ?>
        <div class="rtcl-post-panorama rtcl-post-section">
            <div class="rtcl-post-section-title">
                <h3><i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( 'Panorama Image', 'homlisti' ); ?></h3>
            </div>
			<?php
			$panoramaID = get_post_meta( $post_id, "homlisti_panorama_img", true );
			?>
			<?php if ( ! empty( $panoramaID ) ): ?>
                <div class="panorama-image">
                    <input name="panorama_attachment_id" type="hidden" value="<?php echo esc_attr( $panoramaID ); ?>">
					<?php echo wp_get_attachment_image( $panoramaID, 'full' ); ?>
                    <div class="remove-panorama-image">
                        <a href="#" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $panoramaID ); ?>">
                            <i class="rtcl-icon rtcl-icon-trash"></i>
                        </a>
                    </div>
                </div>
			<?php endif; ?>
            <div class="panorama-input-wrapper <?php echo esc_attr( $panoramaID ? 'd-none' : '' ); ?>">
                <input name="homlisti_panorama_img" class="homlisti-panorama-image" type="file"/>
            </div>
        </div>
	<?php endif; ?>

	<?php if ( ! in_array( 'description', $hidden_fields ) ): ?>
        <div class="form-group">
            <label for="description"><?php esc_html_e( 'Description', 'homlisti' ); ?></label>
			<?php

			if ( 'textarea' == $editor ) { ?>
                <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        <?php echo esc_attr( $description_limit ? 'maxlength="' . $description_limit . '"' : '' ); ?>
                        rows="8"><?php Functions::print_html( $post_content ); ?></textarea>
				<?php
			} else {
				wp_editor(
					$post_content,
					'description',
					[
						'media_buttons' => false,
						'editor_height' => 200,
					]
				);
			}

			if ( $description_limit ) {
				echo sprintf( '<div class="rtcl-hints">%s</div>',
					apply_filters( 'rtcl_listing_description_character_limit_hints',
						sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'homlisti' ), $description_limit )
					) );
			}
			?>
        </div>
	<?php endif; ?>
</div>