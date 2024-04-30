<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5
 */

namespace radiustheme\HomListi;

use radiustheme\HomListi_Core\Listing_Location_Box;
use radiustheme\HomListi_Core\YelpReview;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;
use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclCFGField;
use RtclPro\Helpers\Fns;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclStore\Helpers\Functions as StoreFunctions;
use RtclStore\Controllers\Hooks\RtclApplyHook;

class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_filter' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_action' ] );
		add_action( 'template_redirect', [ $this, 'disable_lazy_load' ] );
		add_action( 'the_content', [ $this, 'rt_set_post_view_count' ], 9999 );
		add_action(
			'rtcl_listing_form_after_save_or_update',
			[
				$this,
				'listing_form_save',
			],
			12,
			5
		); // save extra listing form fields
		add_action( 'wp_ajax_delete_panorama_attachment', [ $this, 'delete_panorama_attachment' ] );
		add_action( 'homlisti_listing_grid_search_filer', [ $this, 'listing_map_filter' ] );
		add_action( 'rtcl_shortcode_before_listings_loop_start', [ $this, 'listing_map_filter' ] );
		add_action( 'wp_ajax_delete_floor_attachment', [ $this, 'delete_floor_attachment' ] );
		// Change Store Breadcrumb Title
		add_filter( 'rtcl_store_register_post_type_args', [ $this, 'modify_store_post_breadcrumb_args' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_store_the_excerpt', [ $this, 'rtcl_store_the_excerpt' ] );
		add_filter( 'rtcl_format_price_range', [ $this, 'rtcl_format_price_range' ], 10, 4 );
		add_filter( 'rtcl_type_prefix', [ $this, 'rtcl_type_prefix' ] );
		add_filter( 'rtcl_general_settings_options', [ $this, 'rtcl_general_settings_options' ] );

		//Change text Store -> Agency
		add_action( 'current_screen', function ( $screen ) {
			if ( is_object( $screen ) && $screen->post_type == 'rtcl_listing' ) {
				add_filter( 'gettext', [ $this, 'homlisti_store_to_agency' ], 99, 3 );
			}
		} );


		// Change Store Category label
		add_filter( 'rtcl_register_store_category_args',
			function ( $label ) {
				$label['labels']['name']      = esc_html_x( 'Agency Categories', 'Agency Category Name', 'homlisti' );
				$label['labels']['menu_name'] = esc_html_x( 'Agency Categories', 'Agency Category Name', 'homlisti' );

				return $label;
			} );

		// Change Store Category label
		add_filter( 'rtcl_store_myaccount_store_title',
			function ( $store_label ) {
				$store_label = esc_html__( 'Agency', 'homlisti' );

				return $store_label;
			} );


		//Swiper js
		add_filter( 'rtcl_swiper_js_source', function ( $path ) {
			if ( defined( 'ELEMENTOR_ASSETS_PATH' ) ) {
				$is_swiper8_enable = get_option( 'elementor_experiment-e_swiper_latest' );
				if ( $is_swiper8_enable == 'active' ) {
					$el_swiper_path = 'lib/swiper/v8/swiper.min.js';
				} else {
					$el_swiper_path = 'lib/swiper/swiper.min.js';
				}
				$elementor_swiper_path = ELEMENTOR_ASSETS_PATH . $el_swiper_path;
				if ( file_exists( $elementor_swiper_path ) ) {
					$path = ELEMENTOR_ASSETS_URL . $el_swiper_path;
				}
			}

			return $path;
		} );


		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		add_action( 'widgets_init', [ $this, 'rtcl_widget_init' ], 15 );
	}

	function homlisti_store_to_agency( $translated_text, $untranslated_text, $domain ) {
		$translated_text = str_replace( [ 'Store', 'Stores' ], [ 'Agency', 'Agencies' ], $translated_text );

		return $translated_text;
	}

	public function rtcl_widget_init() {
		unregister_sidebar( 'rtcl-archive-sidebar' );
		unregister_sidebar( 'rtcl-single-sidebar' );
	}

	function rtcl_general_settings_options( $options ) {
		unset( $options['load_bootstrap'] );

		return $options;
	}

	function rtcl_type_prefix( $prefix ) {
		if ( RDTheme::$options['remove_listing_type_prefix'] ) {
			return null;
		}
		if ( RDTheme::$options['listing_type_prefix_text'] ) {
			return esc_html( RDTheme::$options['listing_type_prefix_text'] );
		}

		return $prefix;
	}

	function rtcl_format_price_range( $price, $from, $to, $args ) {
		$price = sprintf(
			_x(
				'<div class="rtcl-price-range"><span class="price-from">%1$s</span> <span class="dash">&ndash;</span> <span class="price-to">%2$s</span></div>', 'Price range: from-to', 'homlisti' ),
			is_numeric( $from ) ? Functions::price( $from, false, $args ) : $from,
			is_numeric( $to ) ? Functions::price( $to, false, $args ) : $to
		);

		return $price;
	}

	function disable_lazy_load() {
		$is_listing_archive = Functions::is_listings() || Functions::is_listing_taxonomy();
		if ( $is_listing_archive || is_singular( 'rtcl_listing' ) ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		}
	}

	public function rtcl_store_the_excerpt( $excerpt ) {
		$excerpt = wp_trim_words( $excerpt, 20, '' );

		return $excerpt;
	}

	public function rt_set_post_view_count( $content ) {
		//Set store View Count 
		if ( is_singular() ) {
			$rt_store_views_key = 'rt_post_views_count';
			$store_view_count   = get_post_meta( get_the_ID(), $rt_store_views_key, true );
			if ( '' == $store_view_count ) {
				$store_view_count = 0;
				delete_post_meta( get_the_ID(), $rt_store_views_key );
				add_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			} else {
				$store_view_count ++;
				update_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			}
		}

		return $content;
	}

	public static function rt_get_post_view_count( $storeID ) {
		//Get store View Count 
		$rt_store_views_key = 'rt_post_views_count';
		$store_view_count   = get_post_meta( $storeID, $rt_store_views_key, true );

		return $store_view_count;
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function modify_store_post_breadcrumb_args( $args ) {
		$labels = [
			'name'               => _x( 'Agencies', 'Post Type General Name', 'homlisti' ),
			'singular_name'      => _x( 'Agency', 'Post Type Singular Name', 'homlisti' ),
			'menu_name'          => esc_html__( 'Agency', 'homlisti' ),
			'name_admin_bar'     => esc_html__( 'Agency', 'homlisti' ),
			'all_items'          => esc_html__( 'Agencies', 'homlisti' ),
			'add_new_item'       => esc_html__( 'Add New Agency', 'homlisti' ),
			'add_new'            => esc_html__( 'Add New', 'homlisti' ),
			'new_item'           => esc_html__( 'New Agency', 'homlisti' ),
			'edit_item'          => esc_html__( 'Edit Agency', 'homlisti' ),
			'update_item'        => esc_html__( 'Update Agency', 'homlisti' ),
			'view_item'          => esc_html__( 'View Agency', 'homlisti' ),
			'search_items'       => esc_html__( 'Search Agency', 'homlisti' ),
			'not_found'          => esc_html__( 'No stores found', 'homlisti' ),
			'not_found_in_trash' => esc_html__( 'No stores found in Trash', 'homlisti' ),
		];

		$args['label']       = esc_html__( 'Agency', 'homlisti' );
		$args['description'] = esc_html__( 'Agency Description', 'homlisti' );
		$args['labels']      = $labels;

		return $args;
	}

	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" flaticon-user",
			" flaticon-user-1",
			" flaticon-speech-bubble",
			" flaticon-next",
			" flaticon-share",
			" flaticon-share-1",
			" flaticon-left-and-right-arrows",
			" flaticon-heart",
			" flaticon-camera",
			" flaticon-video-player",
			" flaticon-maps-and-flags",
			" flaticon-check",
			" flaticon-envelope",
			" flaticon-phone-call",
			" flaticon-call",
			" flaticon-clock",
			" flaticon-play",
			" flaticon-loupe",
			" flaticon-user-2",
			" flaticon-bed",
			" flaticon-shower",
			" flaticon-pencil",
			" flaticon-two-overlapping-square",
			" flaticon-printer",
			" flaticon-comment",
			" flaticon-home",
			" flaticon-garage",
			" flaticon-full-size",
			" flaticon-tag",
			" flaticon-right-arrow",
			" flaticon-left-arrow",
			" flaticon-left-arrow-1",
			" flaticon-left-arrow-2",
			" flaticon-right-arrow-1",
		];

		return array_merge( $icons_lists, $new_icons );
	}

	public function classifiedads_rtcl_filter() {
		// Change Title Class
		add_filter(
			'rtcl_listing_loop_title_classes',
			function () {
				return $classes = 'item-title';
			}
		);

		if ( version_compare( RTCL_VERSION, '2.3.2', '>' ) ) {
			add_filter( 'formatted_rtcl_price', function ( $price, $price2, $decimals, $decimal_separator, $thousand_separator, $original_price, $args ) {
				global $listing;
				if ( isset( $args['listing_id'] ) ) {
					$listing = rtcl()->factory->get_listing( $args['listing_id'] );
				}

				if ( $listing == null ) {
					return $price;
				}
				$is_shorthand = Helper::rt_get_meta_option( $listing->get_id(), 'listing_price_style' );
				$precision    = RDTheme::$options['listing_price_precision'];

				if ( 'short' === $is_shorthand ) {
					$price = Helper::rt_number_shorten( str_replace( ',', '', $price ), $precision );
				} else if ( 'short-lac' === $is_shorthand ) {
					$price = Helper::number_to_lac( str_replace( ',', '', $price ) );
				}

				return $price;
			}, 0, 7 );
		}

		// Change Store Category label
		add_filter( 'rtcl_store_category_label',
			function ( $label ) {
				return __( "Agency Categories", "homlisti" );
			} );


		// Change Grid Column for listing
		add_filter(
			'rtcl_listings_grid_columns_class',
			function () {
				$columns    = 'columns-2';
				$full_width = isset( $_GET['layout'] ) ? sanitize_text_field( wp_unslash( $_GET['layout'] ) ) : null;
				if ( $full_width == 'fullwidth' ) {
					$columns = 'columns-3';
				} else if ( Helper::has_sidebar() ) {
					$columns = 'columns-2';
				} else if ( ! Helper::has_sidebar() ) {
					$columns = 'columns-3';
				}

				return $columns;
			}
		);
		// Change Grid Column for store
		add_filter(
			'rtcl_stores_grid_columns_class',
			function () {
				$columns = 'columns-2';
				if ( Helper::has_sidebar() ) {
					$columns = 'columns-1';
				}

				return $columns;
			}
		);
		// Override Level Color
		add_filter(
			'rtcl_style_settings',
			function ( $settings ) {
				$primary_color   = Helper::get_primary_color(); // #5f40fb
				$secondary_color = Helper::get_secondary_color(); // #ef9301
				$args            = [
					'primary'      => $primary_color,
					'link'         => $primary_color,
					'link_hover'   => $secondary_color,
					'top'          => empty( $settings['top'] ) ? '#ff1616' : $settings['top'],
					'top_text'     => empty( $settings['top_text'] ) ? '#ffffff' : $settings['top_text'],
					'feature'      => empty( $settings['feature'] ) ? '#ff8f00' : $settings['feature'],
					'feature_text' => empty( $settings['feature_text'] ) ? '#ffffff' : $settings['feature_text'],
					'popular'      => empty( $settings['popular'] ) ? '#14e0ad' : $settings['popular'],
					'popular_text' => empty( $settings['popular_text'] ) ? '#ffffff' : $settings['popular_text'],
					'bump_up'      => empty( $settings['bump_up'] ) ? '#5f40fb' : $settings['bump_up'],
					'bump_up_text' => empty( $settings['bump_up_text'] ) ? '#ffffff' : $settings['bump_up_text'],
				];
				$settings        = wp_parse_args( $args, $settings );

				return $settings;
			}
		);
		// Override Related Listing Item Number
		add_filter(
			'rtcl_related_slider_options',
			function ( $options ) {
				$options['items']  = 3;
				$options['margin'] = 30;

				return $options;
			}
		);
		// Modify Add to favorite text
		add_filter(
			'rtcl_text_add_to_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Modify remove from favorite text
		add_filter(
			'rtcl_text_remove_from_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Remove report abuse text
		add_filter(
			'rtcl_text_report_abuse',
			function ( $text ) {
				return '';
			}
		);
		// Loop item wrapper start
		add_filter(
			'rtcl_loop_item_wrapper_start',
			function ( $content ) {
				global $listing;
				$content = sprintf( '<div class="product-content%s">', esc_attr( $listing->can_show_price() ? '' : ' no-price' ) );

				return $content;
			}
		);

		add_filter(
			'get_the_archive_title',
			function ( $title ) {
				if ( is_post_type_archive( 'rtcl_listing' ) ) {
					$title = esc_html__( 'All Ads', 'homlisti' );
				}

				return $title;
			}
		);

		// Override page template
		add_filter( 'template_include', [ $this, 'template_include' ] );

		// Override Store Ad count
		add_filter(
			'rtcl_store_get_ad_count_html',
			function ( $content, $obj, $count ) {
				$count_string = $count <= 0
					? apply_filters( 'homlisti_store_no_ad_text', __( "No Property", "homlisti" ), $this, $count )
					: sprintf(
						_n( "%s Property", "%s Properties", $count, 'homlisti' ),
						number_format_i18n( $count )
					);
				$content      = sprintf( '<span class="ads-count">%s</span>', $count_string );

				return $content;
			},
			10,
			3
		);

		// Change Unit Text
		add_filter(
			'rtcl_get_price_unit_list',
			function ( $unitList ) {
				$unitList['year']['short']  = esc_html__( "yr", 'homlisti' );
				$unitList['month']['short'] = esc_html__( "mo", 'homlisti' );
				$unitList['week']['short']  = esc_html__( "week", 'homlisti' );
				$unitList['day']['short']   = esc_html__( "day", 'homlisti' );
				$unitList['hour']['short']  = esc_html__( "hr", 'homlisti' );
				$unitList['sqft']['short']  = esc_html__( "sqft", 'homlisti' );
				$unitList['total']['short'] = esc_html__( "total", 'homlisti' );

				return $unitList;
			}
		);

		// Yelp Settings
		add_filter( 'rtcl_general_settings_options', [ $this, 'yelp_options' ] );

		// Panorama Settings
		add_filter( 'rtcl_general_settings_options', [ $this, 'panorama_options' ] );

		// Floor Plan Settings
		add_filter( 'rtcl_general_settings_options', [ $this, 'floor_plan_options' ] );
	}

	public function delete_panorama_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'homlisti_panorama_img' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function delete_floor_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			$floor_data                                 = get_post_meta( $_POST['post_id'], 'homlisti_floor_plan' );
			$floor_data[ $_POST['index'] ]['floor_img'] = '';
			delete_post_meta( $_POST['post_id'], 'homlisti_floor_plan' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function listing_map_filter( $atts ) {
		global $wp;
		$loc_text = esc_attr__( 'Select Location', 'homlisti' );
		$cat_text = esc_attr__( 'Select Category', 'homlisti' );
		$typ_text = esc_attr__( 'Select Type', 'homlisti' );
		$orderby  = strtolower( Functions::get_option_item( 'rtcl_general_settings', 'taxonomy_orderby', 'name' ) );
		$order    = strtoupper( Functions::get_option_item( 'rtcl_general_settings', 'taxonomy_order', 'DESC' ) );
		?>

        <div class="listing-grid-box">

            <form action="<?php echo esc_url( home_url( $wp->request ) ); ?>"
                  class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">

                <div class="search-box">

                    <h2 class="screen-reader-text"><?php echo esc_html__( "Search Listing", "homlisti" ) ?></h2>

                    <div class="search-item search-keyword w100">
                        <div class="input-group">
                            <input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
                                   placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'homlisti' ); ?>"
                                   value="<?php if ( isset( $_GET['q'] ) ) {
								       echo esc_attr( sanitize_text_field( wp_unslash( $_GET['q'] ) ) );
							       } ?>"/>
                        </div>
                    </div>

                    <div class="search-item search-select">
                        <select class="select2" name="filters[ad_type]"
                                data-placeholder="<?php echo esc_attr( $typ_text ); ?>">
							<?php
							$listing_types = Functions::get_listing_types();
							$listing_types = empty( $listing_types ) ? [] : $listing_types;
							$get_ad_type   = isset( $_REQUEST['filters']['ad_type'] ) ? $_REQUEST['filters']['ad_type'] : '';
							?>
                            <option selected="selected"><?php echo esc_html( $typ_text ); ?></option>
							<?php foreach ( $listing_types as $key => $listing_type ):
								$selected = null;
								if ( $get_ad_type == $key ) {
									$selected = 'selected=selected';
								}
								?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ) ?>><?php echo esc_html( $listing_type ); ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>

					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type() ): ?>
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
								'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
							];
							if ( '_rtcl_order' === $orderby ) {
								$location_args['orderby']  = 'meta_value_num';
								$location_args['meta_key'] = '_rtcl_order';
							}
							wp_dropdown_categories( $location_args );
							?>
                        </div>
					<?php endif; ?>

                    <div class="search-item search-select rtin-category">
						<?php
						$cat_args = [
							'show_option_none'  => $cat_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->category,
							'name'              => 'rtcl_category',
							'id'                => 'rtcl-category-cat-' . wp_rand(),
							'class'             => 'select2 rtcl-category-search rtcl-category-search-ajax',
							'selected'          => get_query_var( 'rtcl_category' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_category_depth_limit(),
							'show_count'        => false,
							'hide_empty'        => false,
							'orderby'           => $orderby,
							'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
						];
						if ( '_rtcl_order' === $orderby ) {
							$cat_args['orderby']  = 'meta_value_num';
							$cat_args['meta_key'] = '_rtcl_order';
						}
						Helper::homlisti_dropdown_categories( $cat_args );

						?>
                    </div>
                </div>

                <div class="search-box-2">
                    <div class="search-item price-item-box">
                        <div class="price-range">
                            <label><?php esc_html_e( 'Price:', 'homlisti' ); ?></label>
							<?php
							$data_form = '';
							$data_to   = '';
							if ( isset( $_GET['filters']['price']['min'] ) ) {
								$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
							}
							if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
								$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
							}
							$min_price = RDTheme::$options['listing_widget_min_price'];
							$max_price = RDTheme::$options['listing_widget_max_price'];

							$_min_price = ( isset( $min_price ) && ! empty( $min_price ) ) ? $min_price : 0;
							$_max_price = ( isset( $max_price ) && ! empty( $max_price ) ) ? $max_price : 5000;

							$_min_price = apply_filters( 'rtcl_raw_price', $_min_price );
							$_max_price = apply_filters( 'rtcl_raw_price', $_max_price );

							global $rtclmcData;
							if ( ! is_array( $rtclmcData ) && class_exists( 'RtclMc_Frontend_Price_Filters' ) ) {
								$RtclMc_Frontend_Price_Filters = \RtclMc_Frontend_Price_Filters::instance();
								$rtclmcData                    = $RtclMc_Frontend_Price_Filters->getCurrencyData();
							}
							$currency_symbol = Functions::get_currency_symbol();
							if ( isset( $rtclmcData['currency'] ) && ! empty( $rtclmcData['currency'] ) ) {
								$currency_symbol = Functions::get_currency_symbol( $rtclmcData['currency'] );
							}
							//TODO: Should implement short about frefix::
							//$price_test = Helper::rt_number_shorten('1005', 1);
							?>

                            <input type="number" class="ion-rangeslider"
								<?php echo esc_attr( $data_form ); ?>
								<?php echo esc_attr( $data_to ); ?>
                                   data-min="<?php echo esc_attr( $_min_price ) ?>"
                                   data-max="<?php echo esc_attr( $_max_price ) ?>"
                                   data-prefix="<?php echo esc_attr( $currency_symbol . " " ) ?>"/>
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

                    <div class="search-item search-button">
                        <button class="rtcl-item-visible-btn">
                            <i class="fas fa-sliders-h"></i>
                        </button>
                    </div>

                    <div class="search-item search-btn">
                        <button type="submit" class="submit-btn">
							<?php esc_html_e( 'Search', 'homlisti' ); ?>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="form-cf-items expanded-wrap">
                    <div class="advanced-box advanced-banner-box rtcl_cf_by_category_html" data-price-search="no">
						<?php
						$args      = [
							'is_searchable' => true,
						];
						$fields_id = Functions::get_cf_ids( $args );

						$html = '';
						foreach ( $fields_id as $field ) {
							$html .= self::get_advanced_search_field_html( $field );
						}
						Functions::print_html( $html, true );
						?>
                    </div>

                </div>


            </form>
        </div>
		<?php
	}

	public function yelp_options( $options ) {
		$options['yelp_section']        = [
			'title' => esc_html__( 'Yelp Settings', 'homlisti' ),
			'type'  => 'title',
		];
		$options['enable_yelp_review']  = [
			'title' => esc_html__( 'Enable Yelp Review', 'homlisti' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show yelp review in listing details page.', 'homlisti' ),
		];
		$options['yelp_api_key']        = [
			'title'       => esc_html__( 'Yelp API Key', 'homlisti' ),
			'type'        => 'text',
			'description' => esc_html__( 'Create API key from yelp and enter here', 'homlisti' ),
		];
		$options['yelp_business_limit'] = [
			'title'       => esc_html__( 'Business Limit', 'homlisti' ),
			'type'        => 'number',
			'default'     => 3,
			'css'         => 'width:100px',
			'description' => esc_html__( 'Number of business in per category', 'homlisti' ),
		];
		$options['yelp_search_radius']  = [
			'title'       => esc_html__( 'Radius', 'homlisti' ),
			'type'        => 'number',
			'default'     => 2000,
			'css'         => 'width:100px',
			'description' => esc_html__( 'A suggested search radius in meters', 'homlisti' ),
		];
		$options['business_sort_by']    = [
			'title'       => esc_html__( 'Business Sort by', 'homlisti' ),
			'description' => esc_html__( 'Suggestion to the search algorithm that the results be sorted by one of the these modes', 'homlisti' ),
			'type'        => 'select',
			'options'     => [
				'best_match'   => esc_html__( 'Best Match', 'homlisti' ),
				'rating'       => esc_html__( 'Rating', 'homlisti' ),
				'review_count' => esc_html__( 'Review Count', 'homlisti' ),
				'distance'     => esc_html__( 'Distance', 'homlisti' ),
			],
			'default'     => 'rating',
		];

		return $options;
	}

	public function panorama_options( $options ) {
		$options['panorama_section']         = [
			'title' => esc_html__( 'Panorama Settings', 'homlisti' ),
			'type'  => 'title',
		];
		$options['enable_panorama']          = [
			'title' => esc_html__( 'Enable Panorama', 'homlisti' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show 360 degree view in listing details page.', 'homlisti' ),
		];
		$options['panorama_section_label']   = [
			'title'   => esc_html__( 'Section Label', 'homlisti' ),
			'type'    => 'text',
			'default' => '360° Virtual Tour',
		];
		$options['enable_panorama_autoload'] = [
			'title' => esc_html__( 'Autoload', 'homlisti' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Check this to load automatically', 'homlisti' ),
		];
		$options['enable_panorama_control']  = [
			'title' => esc_html__( 'Show Controls', 'homlisti' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Check this to show controls', 'homlisti' ),
		];

		return $options;
	}

	public function floor_plan_options( $options ) {
		$options['floor_plan_section']       = [
			'title' => esc_html__( 'Floor Plan Settings', 'homlisti' ),
			'type'  => 'title',
		];
		$options['enable_floor_plan']        = [
			'title' => esc_html__( 'Enable Floor Plan', 'homlisti' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add floor plan features.', 'homlisti' ),
		];
		$options['floor_plan_section_label'] = [
			'title'   => esc_html__( 'Section Label', 'homlisti' ),
			'type'    => 'text',
			'default' => 'Floor Plan',
		];

		return $options;
	}

	public function classifiedads_rtcl_action() {
		$show_listing_custom_field = RDTheme::$options['show_listing_custom_fields'];
		add_filter(
			'rtcl_loop_listing_per_page',
			function ( $per_page ) {
				$post_per_pare = Functions::get_option_item( 'rtcl_moderation_settings', 'listing_top_per_page', 2 );
				if ( isset( $_GET['layout'] ) && 'fullwidth' == sanitize_text_field( wp_unslash( $_GET['layout'] ) ) ) {
					$per_page = $per_page < 10 ? 9 : 12;
					$per_page -= absint( $post_per_pare );
				}

				return $per_page;
			}
		);
		if ( class_exists( 'RtclPro' ) ) {
			remove_action( 'rtcl_after_listing_loop_item', [ NewTemplateHooks::class, 'sold_out_banner' ] );
			remove_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 40 );
			remove_action( 'rtcl_listing_loop_action', [ NewTemplateHooks::class, 'view_switcher' ], 30 );
			if ( $show_listing_custom_field ) {
				add_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 55 );
			}
			add_action( 'rtcl_listing_loop_action', [ NewTemplateHooks::class, 'view_switcher' ], 30 );
			//Quick View template overwrite
			remove_action( 'rtcl_quick_view_gallery', [ NewTemplateHooks::class, 'quick_view_gallery' ], 10 );
			add_action( 'rtcl_quick_view_gallery', [ $this, 'quick_view_gallery' ], 10 );
		}
		remove_action( 'rtcl_edit_account_form_end', [ TemplateHooks::class, 'edit_account_form_submit_button' ], 10 );
		add_action( 'rtcl_edit_account_form_end', [ $this, 'edit_account_form_submit_button' ], 10 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_listing_title' ], 20 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta_buttons' ], 60 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta' ], 50 );
		//remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_excerpt' ], 70 );
		//add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_category' ], 15 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_title_new' ], 20 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_meta_location' ], 50 );
		add_action( 'homlisti_listing_loop_top_actions', [ $this, 'top_action_wrapper_start' ], 10 );
		add_action( 'homlisti_listing_loop_top_actions', [ $this, 'top_action_wrapper_end' ], 40 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'listing_loop_item_author_meta' ], 70 );

		// Listing Form
		if ( self::is_enable_yelp_review() ) {
			add_action( "rtcl_listing_form", [ $this, 'listing_yelp_category' ], 25 );
		}
		//remove_action( 'rtcl_listing_form_end', [ TemplateHooks::class, 'listing_form_submit_button' ], 50 );
		// Single Listing Hook
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_meta' ], 10 );
		remove_action(
			'rtcl_single_listing_inner_sidebar',
			[
				TemplateHooks::class,
				'add_single_listing_inner_sidebar_custom_field',
			],
			10
		);
		remove_action(
			'rtcl_single_listing_inner_sidebar',
			[
				TemplateHooks::class,
				'add_single_listing_inner_sidebar_action',
			],
			20
		);

		// Store Archive Hooks
		if ( class_exists( 'RtclStore' ) ) {
			remove_action( 'rtcl_before_store_loop_item', [ StoreHooks::class, 'open_store_link' ], 10 );
			remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'close_store_link' ], 5 );
			remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'rtcl_after_store_loop_item' ], 5 );
			remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'loop_item_store_title' ], 10 );
			remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'store_meta' ], 20 );
		}

		//Store Settings rename to Agent Settings
		if ( class_exists( 'RtclStore' ) ) {
			remove_filter( 'rtcl_registered_only_options', [ RtclApplyHook::class, 'add_registered_only_store_contact_option' ] );
			remove_filter( 'rtcl_get_admin_email_notification_options', [ RtclApplyHook::class, 'add_store_update_admin_email_notification' ] );
			remove_filter( 'rtcl_misc_settings_options', [ RtclApplyHook::class, 'add_misc_options' ] );
			remove_filter( 'rtcl_recaptcha_form_list', [ RtclApplyHook::class, 'add_recaptcha_store_contact_form' ] );
			add_filter( 'rtcl_registered_only_options', [ __CLASS__, 'add_registered_only_store_contact_option' ] );
			add_filter( 'rtcl_get_admin_email_notification_options', [ __CLASS__, 'add_store_update_admin_email_notification' ] );
			add_filter( 'rtcl_misc_settings_options', [ __CLASS__, 'add_misc_options' ] );
			add_filter( 'rtcl_recaptcha_form_list', [ __CLASS__, 'add_recaptcha_store_contact_form' ] );
		}

		//...End Store Settings rename to Agent Settings


		add_action( 'rtcl_store_loop_item', [ $this, 'store_top_content_start' ], 5 );
		add_action( 'rtcl_store_loop_item', [ $this, 'loop_item_store_title' ], 10 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_meta' ], 20 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_excerpt' ], 25 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_top_content_end' ], 25 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_bottom_content' ], 40 );


		//Hook for rtcl-agents
		add_filter( 'rtcl_agents_grid_columns_class', [ $this, 'rtcl_agents_grid_columns_class' ] );

		add_action( 'admin_notices',
			function () {
				$is_open_homlisti_app = get_transient( 'homlisti_dismiss_notice' );
				if ( ! $is_open_homlisti_app ) {
					?>
                    <div class="notice notice-info is-dismissible homlisti-app-notice">
						<?php
						$message
							= '<b>HomListi – Real Estate Listing Android & iOS Mobile App</b> is available on <a target="_blank" href="//codecanyon.net/item/homlisti-real-estate-listing-android-ios-app/37047016"><i>CodeCanyon</i></a>.';
						?>
                        <p style="font-size: 16px;">
							<?php
							echo wp_kses( $message,
								[
									'a' => [
										'href'   => [],
										'title'  => [],
										'target' => [],
									],
									'b' => [],
									'i' => [],
								] );
							?>
                        </p>
                    </div>
					<?php
				}
			} );
	}

	/**
	 * @param $listing
	 *
	 * @return void
	 */
	public function quick_view_gallery( $listing ) {
		if ( ! $listing ) {
			return;
		}
		if ( ! Functions::is_gallery_disabled() ) {
			$video_urls = [];
			if ( ! Functions::is_video_urls_disabled() && ! apply_filters( 'rtcl_disable_gallery_video', Functions::is_video_gallery_disabled() ) ) {
				$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
				$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
			}
			Functions::get_template( "listing/gallery-quick-view", [ 'images' => $listing->get_images(), 'videos' => $video_urls ] );
		}
	}


	public static function add_registered_only_store_contact_option( $options ) {
		$options['store_contact'] = esc_html__( 'Agency contact information', 'homlisti' );

		return $options;
	}

	public static function add_store_update_admin_email_notification( $options ) {

		$options['store_update'] = esc_html__( 'Agency Update', 'homlisti' );

		return $options;

	}

	public static function add_recaptcha_store_contact_form( $list ) {
		$list['store_contact'] = esc_html__( 'Agency contact form', 'homlisti' );

		return $list;
	}

	public static function add_misc_options( $options ) {
		$position = array_search( 'image_size_thumbnail', array_keys( $options ) );
		if ( $position > - 1 ) {
			$option = [
				'store_banner_size' => [
					'title'       => esc_html__( 'Agency Banner', 'homlisti' ),
					'type'        => 'image_size',
					'default'     => [ 'width' => 1200, 'height' => 360, 'crop' => 'yes' ],
					'options'     => [
						'width'  => esc_html__( 'Width', 'homlisti' ),
						'height' => esc_html__( 'Height', 'homlisti' ),
						'crop'   => esc_html__( 'Hard Crop', 'homlisti' ),
					],
					'description' => esc_html__( 'This image size is being used in banner at the agency detail page.', "homlisti" )
				],
				'store_logo_size'   => [
					'title'       => esc_html__( 'Agency Logo', 'homlisti' ),
					'type'        => 'image_size',
					'default'     => [ 'width' => 200, 'height' => 150, 'crop' => 'yes' ],
					'options'     => [
						'width'  => esc_html__( 'Width', 'homlisti' ),
						'height' => esc_html__( 'Height', 'homlisti' ),
						'crop'   => esc_html__( 'Hard Crop', 'homlisti' ),
					],
					'description' => esc_html__( 'This image size is being used at the agency detail page and where agency link is given.', "homlisti" )
				]
			];
			Functions::array_insert( $options, $position, $option );
		}

		return $options;
	}

	public function edit_account_form_submit_button() {
		?>
        <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
                <input type="submit" name="submit" class="btn btn-primary"
                       value="<?php esc_attr_e( 'Update Account', 'homlisti' ); ?>"/>
            </div>
        </div>
		<?php
	}

	/**
	 * Agent archive page grid column
	 *
	 * @return string
	 */
	public function rtcl_agents_grid_columns_class() {
		$agent_archive_layout = RDTheme::$options['agent_archive_layout'];
		if ( 'full-width' == $agent_archive_layout ) {
			return 'columns-3';
		}

		return 'columns-2';
	}

	public function loop_item_excerpt() {
		global $listing;
		if ( $listing->can_show_excerpt() ) {
			$excerpt_limit = RDTheme::$options['listing_excerpt_limit'];
			echo "<div class='listing-excerpt'>";
			if ( $excerpt_limit ) {
				echo wp_trim_words( $listing->get_the_content(), $excerpt_limit, '' );
			} else {
				$listing->the_excerpt();
			}
			echo "</div>";
		}
	}

	public function loop_item_listing_title_new() {
		global $listing;

		if ( $listing->has_category() ):
			$category = $listing->get_categories();
			$category = end( $category );
			if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
				$view = sanitize_text_field( wp_unslash( $_GET['view'] ) );
			} else {
				$view = Functions::get_option_item( 'rtcl_general_settings', 'default_view', 'list' );
			}
			?>
			<?php if ( $view == 'list' || is_singular( 'rtcl_agent' ) ) : ?>
            <div class="listing-action">
				<?php echo Listing_Functions::get_favourites_link( $listing->get_id() ); ?>
				<?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_compare() ) {
					$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
					$selected_class = '';
					if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
						$selected_class = ' selected';
					}
					?>
                    <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-toggle="tooltip"
                       data-placement="top"
                       title="<?php esc_attr_e( "Compare", "homlisti" ) ?>"
                       data-original-title="<?php esc_attr_e( "Compare", "homlisti" ) ?>"
                       data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                        <i class="flaticon-left-and-right-arrows"></i>
                    </a>
				<?php } ?>
	            <?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_quick_view() ) {
		            ?>
                    <a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'homlisti' ); ?>" data-listing_id="<?php echo absint( $listing->get_id() ); ?>">
                        <i class="rtcl-icon rtcl-icon-zoom-in"></i>
                    </a>
		            <?php
	            } ?>
            </div>
		<?php endif; ?>

            <div class="product-category">
                <a href="<?php echo esc_url(
					get_term_link(
						$category->term_id,
						$category->taxonomy
					)
				); ?>"><?php echo esc_html( $category->name ) ?></a>
            </div>
		<?php endif;
		echo '<h3 class="' . esc_attr( apply_filters( 'rtcl_listing_loop_title_classes', 'listing-title rtcl-listing-title' ) ) . '"><a href="' . get_the_permalink() . '">'
		     . get_the_title() . '</a></h3>';
	}

	public function loop_item_meta_location() {
		global $listing;
		?>
        <ul class="entry-meta">
			<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
                <li><i class="fas fa-map-marker-alt"></i><?php $listing->the_locations(); ?></li>
			<?php endif; ?>

        </ul>
		<?php
	}

	public static function is_enable_yelp_review() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_yelp_review', false, 'checkbox' );
	}

	public static function is_enable_panorama_view() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_panorama', false, 'checkbox' );
	}

	public static function is_enable_floor_plan() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_floor_plan', false, 'checkbox' );
	}

	public function listing_yelp_category( $post_id ) {
		$selectedCat = get_post_meta( $post_id, "homlisti_yelp_categories", true );
		$selectedCat = empty( $selectedCat ) ? [] : $selectedCat;
		$categories  = YelpReview::yelp_categories();
		Functions::get_template( "listing-form/yelp-category", compact( 'post_id', 'selectedCat', 'categories' ) );
	}

	public function listing_loop_item_author_meta() {
		global $listing;
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = sanitize_text_field( wp_unslash( $_GET['view'] ) );
		} else {
			$view = Functions::get_option_item( 'rtcl_general_settings', 'default_view', 'list' );
		}
		?>
        <div class="product-bottom-content">
            <ul>

				<?php if ( $listing->can_show_user() && method_exists( $listing, 'get_the_author_url' ) ): ?>
                    <li class="item-author">
                        <div class="media">
                            <div class="item-img">
								<?php
								$avatar_attr = [
									'class'      => 'author-avatar',
									'extra_attr' => 'data-toggle="tooltip" data-placement="right" data-original-title="' . $listing->get_author_name() . '"',
								];
								Helper::get_listing_author_iamge( $listing, 40, 'Author', $avatar_attr );
								?>
                            </div>
                            <div class="media-body">
                                <div class="item-title">
									<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                                        <a class="author-link"
                                           href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
											<?php echo esc_html( $listing->get_author_name() ); ?>
											<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                                        </a>
									<?php else: ?>
										<?php $listing->the_author(); ?>
										<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
				<?php endif; ?>


                <li class="action-btn">
                    <a class="btn btn-primary" href="<?php $listing->the_permalink(); ?>">
						<?php echo esc_html__( 'Details', 'homlisti' ); ?>
                    </a>
					<?php if ( $listing->can_show_price() ): ?>
                        <div class="rtcl-product-price"><?php printf( "%s", $listing->get_price_html() ); ?></div>
					<?php endif; ?>
                </li>

            </ul>
        </div>
		<?php
	}

	public function get_listing_floor_attachment_id( $post_id, $file ) {
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			get_template_part( ABSPATH . "/wp-admin" . '/includes/image.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/file.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/media.php' );
		}

		Filters::beforeUpload();
		// you can use WP's wp_handle_upload() function:
		$status = wp_handle_upload(
			$file,
			[
				'test_form' => false,
			]
		);
		Filters::afterUpload();

		if ( $status && ! isset( $status['error'] ) ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $status['file'];

			// Check the type of tile. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			];
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename );
		}

		return isset( $attach_id ) ? $attach_id : 0;
	}

	public function listing_form_save( $listing, $type, $cat_id, $new_listing_status, $data ) {
		$files = $data['files'];

		if ( isset( $_POST['homlisti_floor_plan'] ) ) {
			$raw_data = $_POST['homlisti_floor_plan'];

			$sanitized_data = [];
			$count          = 0;
			foreach ( $raw_data as $floors ) {
				$floor_plan = [];
				$attach_id  = 0;
				foreach ( $floors as $key => $value ) {
					if ( $key === 'title' || $key === 'size' ) {
						$floor_plan[ $key ] = sanitize_text_field( $value );
					} else if ( $key === 'bed' || $key === 'bath' || $key === 'parking' ) {
						$floor_plan[ $key ] = absint( $value );
					} else if ( $key === 'description' ) {
						$floor_plan[ $key ] = sanitize_textarea_field( $value );
					}
				}

				if ( ! empty( $files['homlisti_floor_img'] ) && empty( $floors['attachment_id'] ) ) {
					$attachmentData = $files['homlisti_floor_img'];
					foreach ( $attachmentData as $attachmentKey => $attachmentValue ) {
						$image[ $attachmentKey ] = $attachmentValue[ $count ];
					}
					if ( ! empty( $image['name'] ) ) {
						$attach_id = $this->get_listing_floor_attachment_id( $listing->get_id(), $image );
					}
					$count ++;
				} else if ( ! empty( $floors['attachment_id'] ) ) {
					$attach_id = $floors['attachment_id'];
					$count ++;
				}

				if ( ! empty( $attach_id ) ) {
					$floor_plan['floor_img'] = $attach_id;
				}

				if ( ! empty( $floor_plan ) ) {
					$sanitized_data[] = $floor_plan;
				}
			}

			if ( ! empty( $sanitized_data ) ) {
				update_post_meta( $listing->get_id(), 'homlisti_floor_plan', $sanitized_data );
			}
		}

		if ( self::is_enable_yelp_review() ) {
			if ( isset( $_POST['homlisti_yelp_categories'] ) ) {
				$raw_data = $_POST['homlisti_yelp_categories'];

				$sanitized_data = [];

				foreach ( $raw_data as $category ) {
					$sanitized_data[] = $category;
				}

				update_post_meta( $listing->get_id(), 'homlisti_yelp_categories', $sanitized_data );
			} else {
				delete_post_meta( $listing->get_id(), 'homlisti_yelp_categories' );
			}
		}

		if ( self::is_enable_panorama_view() ) {
			$panoramaImage = $files['homlisti_panorama_img'];

			if ( ! empty( $panoramaImage ) && empty( $_POST['panorama_attachment_id'] ) ) {
				$panoramaID = $this->get_listing_floor_attachment_id( $listing->get_id(), $panoramaImage );
			} else if ( isset( $_POST['panorama_attachment_id'] ) ) {
				$panoramaID = $_POST['panorama_attachment_id'];
			}

			if ( $panoramaID ) {
				update_post_meta( $listing->get_id(), 'homlisti_panorama_img', $panoramaID );
			}
		}
	}

	public function store_top_content_start() {
		?>
        <div class="top-content">
		<?php
	}

	public function loop_item_store_title() {
		global $store;
		?>
        <h2 class="rtcl-store-title">
            <a href="<?php esc_url( $store->the_permalink() ); ?>"><?php esc_html( $store->the_title() ); ?></a>
        </h2>
		<?php
	}

	public function store_meta() {
		global $store;
		?>
		<?php if ( $store->get_address() || $store->get_website() || $store->get_phone() ): ?>
            <ul class="entry-meta">
				<?php if ( $store->get_address() ): ?>
                    <li><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $store->get_address() ); ?></li>
				<?php endif; ?>

				<?php if ( $stor_view_count = self::rt_get_post_view_count( $store->get_id() ) ) : ?>
					<?php if ( $stor_view_count > 999 ): ?>
                        <li data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr__( "Total Views: " . $stor_view_count, 'homlisti' ) ?>">
					<?php else : ?>
                        <li>
					<?php endif; ?>
                    <i class="fas fa-eye"></i>
					<?php
					$label = $stor_view_count < 10 ? esc_html__( 'View: ', 'homlisti' ) : esc_html__( 'Views: ', 'homlisti' );
					printf(
						"<span class='count-label'>%s</span><span class='count-number'>%s</span>",
						$label,
						Helper::rt_number_shorten( $stor_view_count, 1 )
					);
					?>
                    </li>
				<?php endif; ?>

				<?php if ( $store->is_rating_enable() && $store->get_review_counts() ): ?>
                    <li>
                        <div class="store-rating">
							<?php echo Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ); ?>
                            <span class="reviews-rating-count">(<?php echo absint( $store->get_review_counts() ); ?>)</span>
                        </div>
                    </li>
				<?php endif; ?>
            </ul>
		<?php endif; ?>
		<?php
	}

	public function store_excerpt() {
		global $store;
		?>
        <div class='store-excerpt'>
			<?php $store->the_excerpt() ?>
        </div>
		<?php
	}

	public function store_top_content_end() {
		?>
        </div>
		<?php
	}

	public function store_bottom_content() {
		global $store;
		?>
        <div class="bottom-content">
            <div class="listing-view-btn">
                <a href="<?php esc_url( $store->the_permalink() ); ?>"
                   class="item-btn"><?php esc_html_e( 'View Details', 'homlisti' ) ?></a>
            </div>

            <div class="store-social-info">
                <ul>
					<?php if ( $store->get_phone() ): ?>
                        <li>
                            <a class="item-btn" data-toggle="tooltip" data-placement="top"
                               href="tel:<?php echo esc_html( $store->get_phone() ); ?>"
                               title="<?php echo esc_html( $store->get_phone() ); ?>"
                               data-original-title="<?php echo esc_attr( $store->get_email() ); ?>">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                        </li>
					<?php endif; ?>

					<?php if ( $store->get_email() ): ?>
                        <li>
                            <a class="item-btn" data-toggle="tooltip" data-placement="top"
                               title="<?php echo esc_attr( $store->get_email() ); ?>"
                               href="mailto:<?php echo esc_attr( $store->get_email() ); ?>"
                               data-original-title="<?php echo esc_attr( $store->get_email() ); ?>">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </li>
					<?php endif; ?>

					<?php if ( $store->get_website() ): ?>
                        <li>
                            <a class="item-btn" data-toggle="tooltip" data-placement="top"
                               title="<?php echo esc_url( $store->get_website() ); ?>"
                               href="<?php echo esc_url( $store->get_website() ); ?>"
                               data-original-title="<?php echo esc_url( $store->get_website() ); ?>">
                                <i class="fas fa-globe"></i>
                            </a>
                        </li>
					<?php endif; ?>
                </ul>
            </div>

        </div>
		<?php
	}

	public function loop_item_listing_category() {
		global $listing;
		if ( $listing->has_category() && $listing->can_show_category() && ! Functions::is_listing() ):
			$category = $listing->get_categories();
			$category = end( $category );
			?>
            <div class="product-category">
                <a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
            </div>
		<?php endif;
	}

	public function top_action_wrapper_start() {
		?>
        <div class="product-filter product-heading-filter">
        <div class="row">
		<?php
	}

	public function listing_archive_title() {
		?>
        <div class="col-8">
            <h2 class="heading-title"><?php echo get_the_archive_title(); ?></h2>
        </div>
		<?php
	}

	public function top_action_wrapper_end() {
		?>
        </div>
        </div>
		<?php
	}

	public function template_include( $template ) {
		if ( Functions::is_account_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-account', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}

		return $template;
	}

	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id.wp_rand(),
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                                <div class="price-range">
                                                    <label>%s</label>
                                                    <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                                    %s
                                                </div>
                                             </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_moderation_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'homlisti' )
				       . '" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="rtcl-icon rtcl-icon-heart"></span><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::remove_from_favourite() . '</span></a>';
			} else {
				return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'homlisti' )
				       . '" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::add_to_favourite() . '</span></a>';
			}
		} else {
			return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'homlisti' )
			       . '" class="rtcl-require-login"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite()
			       . '</span></a>';
		}
	}


}

Listing_Functions::instance();