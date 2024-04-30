<?php
/**
 * @var array $data
 * @var bool $can_search_by_keyword
 * @since   1.0
 * @version 1.0
 *
 * @author  RadiusTheme
 * @package
 */

namespace radiustheme\HomListi;

use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options as RtclOptions;

extract( $data );
$loc_text = esc_attr__( 'All Cities', 'homlisti' );
$cat_text = esc_attr__( 'All Categories', 'homlisti' );
$typ_text = esc_attr__( 'Property Type', 'homlisti' );

$listing_price_range = Helper::listing_price_range();
$_min_price          = $min_price ?? $listing_price_range['min_price'];
$_max_price          = $max_price ?? $listing_price_range['max_price'];

$_min_price = apply_filters( 'rtcl_raw_price', $_min_price );
$_max_price = apply_filters( 'rtcl_raw_price', $_max_price );
?>

<div class="listing-inner">
    <form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>"
          class="advance-search-form rtcl-widget-search-form is-preloader"
          data-min-price="<?php echo esc_attr( $_min_price ) ?>"
          data-max-price="<?php echo esc_attr( $_max_price ) ?>">
		<?php $permalink_structure = get_option( 'permalink_structure' ); ?>
		<?php if ( ! $permalink_structure ) : ?>
            <input type="hidden" name="post_type" value="rtcl_listing">
		<?php endif; ?>
        <div class="search-box">
			<?php if ( $can_search_by_keyword ): ?>
                <div class="search-item search-keyword">
                    <div class="input-group">
                        <input type="text" data-type="listing" name="s" class="rtcl-autocomplete form-control"
                               placeholder="<?php esc_attr_e( 'What are you looking for?', 'homlisti' ); ?>"
                               value="<?php if ( isset( $_GET['s'] ) ) {
							       echo sanitize_text_field( wp_unslash( $_GET['s'] ) );
						       } ?>"/>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_listing_types ): ?>
                <div class="search-item search-select">
                    <div class="search-check-box">

                        <select class="select2" name="filters[ad_type]"
                                data-placeholder="<?php echo esc_attr( $typ_text ); ?>">
							<?php
							$listing_types = Functions::get_listing_types();
							$listing_types = empty( $listing_types ) ? [] : $listing_types;
							?>
                            <option selected="selected"><?php echo esc_html( $typ_text ); ?></option>
							<?php
							foreach ( $listing_types as $key => $listing_type ) {
								?>
                                <option value="<?php echo esc_attr( $key ) ?>" <?php echo isset( $_GET['filters']['ad_type'] ) && trim( $_GET['filters']['ad_type'] ) == $key
									? ' selected' : null ?>><?php echo esc_html( $listing_type ); ?></option>
								<?php
							}
							?>
                        </select>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_category ): ?>
                <div class="search-item search-select rtin-category">
					<?php

					$orderby = strtolower( Functions::get_option_item( 'rtcl_general_settings', 'taxonomy_orderby', 'name' ) );
					$order   = strtoupper( Functions::get_option_item( 'rtcl_general_settings', 'taxonomy_order', 'DESC' ) );

					$cat_args = [
						'show_option_none'  => $cat_text,
						'option_none_value' => '',
						'taxonomy'          => rtcl()->category,
						'name'              => 'rtcl_category',
						'id'                => 'rtcl-category-cat' . wp_rand(),
						'class'             => 'select2 rtcl-category-search rtcl-category-search-ajax',
						'selected'          => get_query_var( 'rtcl_category' ),
						'hierarchical'      => true,
						'value_field'       => 'slug',
						'depth'             => Functions::get_category_depth_limit(),
						'show_count'        => false,
						'hide_empty'        => false,
						'orderby'           => $orderby,
						'order'             => ( 'desc' == $order ) ? 'DESC' : 'ASC',
					];

					if ( '_rtcl_order' === $orderby ) {
						$cat_args['orderby']  = 'meta_value_num';
						$cat_args['meta_key'] = '_rtcl_order';
					}

					if ( $category_is_parent ) {
						$cat_args['parent'] = 0;
					}

					Helper::homlisti_dropdown_categories( $cat_args );
					?>
                </div>
			<?php endif; ?>


			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && $can_search_by_location && 'local' === Functions::location_type() ): ?>
                <div class="search-item search-select rtin-location">
					<?php
					$location_args = [
						'show_option_none'  => $loc_text,
						'option_none_value' => '',
						'taxonomy'          => rtcl()->location,
						'name'              => 'rtcl_location',
						'id'                => 'rtcl-location-search-' . wp_rand(),
						'class'             => 'select2 rtcl-location-search',
						'selected'          => get_query_var( 'rtcl_location' ),
						'hierarchical'      => true,
						'value_field'       => 'slug',
						'depth'             => Functions::get_location_depth_limit(),
						'show_count'        => false,
						'hide_empty'        => false,
						'orderby'           => $orderby,
						'order'             => ( 'desc' == $order ) ? 'DESC' : 'ASC',
					];

					if ( '_rtcl_order' === $orderby ) {
						$location_args['orderby']  = 'meta_value_num';
						$location_args['meta_key'] = '_rtcl_order';
					}
					wp_dropdown_categories( $location_args );
					?>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_radius_search ):
				//$rs_data = Options::get_radius_search_options();
				$rs_data = RtclOptions::radius_search_options();
				?>
                <div class="search-item search-keyword rtcl-radius-group">
                    <label for="rtcl-geo-address-search"><?php esc_html_e( 'Radius Search', 'homlisti' ); ?></label>
                    <div class="input-group rtcl-geo-address-field">
                        <input id="rtcl-geo-address-search" type="text" name="geo_address" autocomplete="off"
                               value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
                               placeholder="<?php esc_attr_e( "Select a location", "homlisti" ) ?>"
                               class="form-control rtcl-geo-address-input"/>
                        <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                        <input type="hidden" class="latitude" name="center_lat"
                               value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
                        <input type="hidden" class="longitude" name="center_lng"
                               value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
                    </div>
                    <div class="rtcl-range-slider-field">
                        <input type="number" class="ion-rangeslider rtcl-range-slider-input" name="distance"
                               min="<?php echo absint( $rs_data['default_distance'] ) ?>"
                               max="<?php echo absint( $rs_data['max_distance'] ) ?>"
                               value="<?php echo ! empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : absint( $rs_data['default_distance'] ) ?>"
                               data-min="<?php echo absint( $rs_data['default_distance'] ) ?>"
                               data-max="<?php echo absint( $rs_data['max_distance'] ) ?>"
                               data-prefix="<?php in_array(
							       $rs_data['units'],
							       [ 'km', 'kilometers' ]
						       ) ? esc_html_e( "Radius km ", "homlisti" ) : esc_html_e( "Radius Miles ", "homlisti" ) ?>"
                               data-type="single"
                        >
                    </div>
                </div>
			<?php endif ?>

			<?php if ( $can_search_by_price ): ?>
                <div class="search-item">
                    <div class="price-range">
                        <label><?php esc_html_e( 'Price', 'homlisti' ); ?></label>
						<?php
						$data_form = '';
						$data_to   = '';
						if ( isset( $_GET['filters']['price']['min'] ) ) {
							$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
						}
						if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
							$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
						}

						global $rtclmcData;
						if ( ! is_array( $rtclmcData ) && class_exists( 'RtclMc_Frontend_Price_Filters' ) ) {
							$RtclMc_Frontend_Price_Filters = \RtclMc_Frontend_Price_Filters::instance();
							$rtclmcData                    = $RtclMc_Frontend_Price_Filters->getCurrencyData();
						}

						$currency_symbol = Functions::get_currency_symbol();
						if ( isset( $rtclmcData['currency'] ) && ! empty( $rtclmcData['currency'] ) ) {
							$currency_symbol = Functions::get_currency_symbol( $rtclmcData['currency'] );
						}
						$currency_pos           = Functions::get_option_item( 'rtcl_general_settings', 'currency_position', 'left' );
						$data_currency_position = ! empty( $currency_symbol ) ? sprintf( "data-prefix=%s", $currency_symbol ) : '';
						if ( in_array( $currency_pos, [ 'right', 'right_space' ] ) ) {
							$data_currency_position = sprintf( "data-postfix=%s", $currency_symbol );
						}

						?>

                        <input type="number" class="ion-rangeslider"
							<?php echo esc_attr( $data_form ); ?>
							<?php echo esc_attr( $data_to ); ?>
							<?php echo esc_attr( $data_currency_position ); ?>
                               data-min="<?php echo esc_attr( $_min_price ) ?>"
                               data-max="<?php echo esc_attr( $_max_price ) ?>"
                        />
                        <input type="hidden" class="min-volumn" name="filters[price][min]"
                               value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
							       echo absint( $_GET['filters']['price']['min'] );
						       } ?>">
                        <input type="hidden" class="max-volumn" name="filters[price][max]"
                               value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
							       echo absint( $_GET['filters']['price']['max'] );
						       } ?>">
                    </div>
                </div>
			<?php endif; ?>


			<?php


			if ( $can_search_by_custom_field ): ?>
				<?php
				$group_id = [];
				if ( isset( RDTheme::$options['custom_group_individual'] ) ) {
					$group_id[] = RDTheme::$options['custom_group_individual'];
				}
				?>

                <div class="search-item search-button">
                    <button class="rtcl-item-visible-btn">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                    <span><?php echo esc_html__( 'Amenities', 'homlisti' ); ?></span>
                </div>

                <div class="rtcl-widget-custom-field expanded-wrap rtcl_cf_by_category_html" data-price-search="no">
					<?php
					$args      = [
						'is_searchable' => true,
					];
					$fields_id = Functions::get_cf_ids( $args );
					if ( ! empty( $fields_id ) ) { ?>

						<?php


						$html = '';
						foreach ( $fields_id as $field ) {
							$html .= Listing_Functions::get_advanced_search_field_html( $field );
						}
						printf( "%s", $html );
						?>

					<?php } ?>


                </div>
			<?php endif; ?>
            <div class="search-item search-btn mb-0">
                <button type="submit" class="submit-btn"><?php esc_html_e( 'Find Property', 'homlisti' ); ?></button>
                <button class="reset-btn">
                    <a href="<?php echo esc_url( get_permalink( Functions::get_page_id( 'listings' ) ) ); ?>">
                        <i class="fas fa-sync-alt"></i>
						<?php echo esc_html__( 'Reset', 'homlisti' ) ?></a>
                </button>
            </div>
        </div>
    </form>
</div>