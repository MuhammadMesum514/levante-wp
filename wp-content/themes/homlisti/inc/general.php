<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

use Rtcl\Controllers\Hooks\AppliedBothEndHooks;
use Rtcl\Helpers\Breadcrumb;
use Rtcl\Helpers\Functions;

class General_Setup {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
		add_action( 'admin_init', [ $this, 'admin_init_action' ] );
		add_filter( 'max_srcset_image_width', [ $this, 'disable_wp_responsive_images' ] );
		add_action( 'widgets_init', [ $this, 'register_sidebars' ], 99 );
		add_action( 'homlisti_breadcrumb', [ $this, 'breadcrumb' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'wp_head', [ $this, 'noscript_hide_preloader' ], 1 );
		add_action( 'wp_head', [ $this, 'pingback' ] );
		add_action( 'wp_body_open', [ $this, 'preloader' ] );
		add_action( 'wp_footer', [ $this, 'scroll_to_top_html' ], 1 );
		add_filter( 'get_search_form', [ $this, 'search_form' ] );
		//add_filter( 'comment_form_fields', [ $this, 'move_textarea_to_bottom' ] );
		add_filter( 'post_class', [ $this, 'hentry_config' ] );
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		add_filter( 'wp_list_categories', [ $this, 'add_span_cat_count' ] );
		add_filter( 'get_archives_link', [ $this, 'add_span_archive_count' ] );
		add_filter( 'widget_text', 'do_shortcode' );

		//Add user 
		add_action( 'show_user_profile', [ $this, 'rt_show_extra_profile_fields' ] );
		add_action( 'edit_user_profile', [ $this, 'rt_show_extra_profile_fields' ] );
		add_action( 'personal_options_update', [ $this, 'rt_update_user_profile_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'rt_update_user_profile_fields' ] );

		//Disable Gutenberg widget block
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'use_widgets_block_editor', '__return_false' ); // Disables the block editor from managing widgets.
		add_filter( 'wp_calculate_image_srcset', [ $this, 'calculate_image_srcset' ] );
		add_action( 'wp_ajax_homlist_app_notice_dismiss', [ $this, 'homlist_app_notice_dismiss' ] );

		add_action( 'wp_ajax_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );
		add_action( 'wp_ajax_nopriv_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );
	}

	function rtcl_cf_by_category_func() {
		if ( 'all' == $_POST['cat_id'] ) {
			$args      = [ 'is_searchable' => true ];
			$fields_id = Functions::get_cf_ids( $args );
		} else {
			$fields_id = self::get_cf_ids( $_POST['cat_id'] );
		}

		$html = '';
		foreach ( $fields_id as $field ) {
			$html .= Listing_Functions::get_advanced_search_field_html( $field );
		}
		printf( "%s", $html );
		?>
		<?php if ( isset( $_POST['price_search'] ) && $_POST['price_search'] == 'yes' ): ?>
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

					$listing_price_range = Helper::listing_price_range();
					$_min_price = $_POST['price']['minPrice'] ?? $listing_price_range['min_price'];
					$_max_price = $_POST['price']['maxPrice'] ?? $listing_price_range['max_price'];

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
					$data_currency_position = sprintf( "data-prefix=%s", $currency_symbol );
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
		<?php endif;


		wp_die();
	}

	public static function get_cf_ids( $category_id ) {
		$group_id = AppliedBothEndHooks::get_custom_field_group_ids( null, $category_id );
		$args     = [
			'post_type'        => rtcl()->post_type_cf,
			'post_status'      => 'publish',
			'posts_per_page'   => - 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_parent__in'  => $group_id,
			'meta_query'       => [
				[
					'key'   => '_searchable',
					'value' => 1
				],
			]
		];

		return get_posts( $args );
	}

	// Render user profile info
	function rt_show_extra_profile_fields( $user ) {
		$user_agency = get_the_author_meta( 'user_agency', $user->ID ); ?>
        <h3><?php esc_html_e( 'Agency Info', 'homlisti' ); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="user_agency"><?php esc_html_e( 'Select an Agency', 'homlisti' ); ?></label></th>
                <td>
					<?php
					$args       = [
						'post_type'      => 'store',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
					];
					$get_agency = new \WP_Query( $args )
					?>
                    <select name="user_agency" id="user_agency">
                        <option><?php echo esc_html__( 'Select Agency', 'homlisti' ) ?></option>
						<?php
						if ( $get_agency->have_posts() ) {
							while ( $get_agency->have_posts() ) {
								$get_agency->the_post();
								$selected = ( $user_agency == get_the_id() ) ? 'selected' : null;
								echo "<option " . $selected . " value='" . get_the_id() . "'>" . get_the_title() . "</option>";
							}
						}
						?>
                    </select>
                </td>
            </tr>
        </table>
		<?php
	}

	/**
	 * Remove calculate image srcset
	 * @return array
	 */
	public function calculate_image_srcset() {
		return [];
	}

	//disable wp responsive images
	function disable_wp_responsive_images() {
		return 1;
	}

	// Update user profile info
	function rt_update_user_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['user_agency'] ) && intval( $_POST['user_agency'] ) >= 1900 ) {
			update_user_meta( $user_id, 'user_agency', intval( $_POST['user_agency'] ) );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function admin_init_action() {
		if ( is_plugin_active( 'tcl-agent/rtcl-agent.php' ) ) {
			deactivate_plugins( 'tcl-agent/rtcl-agent.php' );
		}

		if ( is_plugin_active( 'rtcl-agent/rtcl-agent.php' ) && file_exists( WP_PLUGIN_DIR . '/tcl-agent/rtcl-agent.php' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			delete_plugins( [ 'tcl-agent/rtcl-agent.php' ] );
		}
	}

	public function theme_setup() {
		// Theme supports
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'custom-logo' );
		add_theme_support( "custom-header" );
		add_theme_support( "custom-background" );
		add_theme_support( 'editor-styles' );

		// Image sizes
		$sizes = [
			'rdtheme-size1'  => [ 1200, 650, true ], // When Full width
			'rdtheme-size2'  => [ 370, 245, true ], // Listing Thumbnail Size and blog grid
			'rdtheme-size3'  => [ 350, 420, true ],
			'rdtheme-square' => [ 500, 500, true ],
		];

		$sizes = apply_filters( 'homlisti_image_size', $sizes );

		foreach ( $sizes as $size => $value ) {
			add_image_size( $size, $value[0], $value[1], $value[2] );
		}

		// Register menus
		register_nav_menus(
			[
				'primary'   => esc_html__( 'Primary', 'homlisti' ),
				'secondary' => esc_html__( 'Footer Menu', 'homlisti' ),
			]
		);
	}

	public function register_sidebars() {

		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'homlisti' ),
				'id'            => 'sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		$footer_widget_titles = [
			'1' => esc_html__( 'Footer 1', 'homlisti' ),
			'2' => esc_html__( 'Footer 2', 'homlisti' ),
			'3' => esc_html__( 'Footer 3', 'homlisti' ),
			'4' => esc_html__( 'Footer 4', 'homlisti' ),
		];

		foreach ( $footer_widget_titles as $id => $name ) {
			register_sidebar(
				[
					'name'          => $name,
					'id'            => 'footer-' . $id,
					'before_widget' => '<div id="%1$s" class="footer-box %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="footer-title">',
					'after_title'   => '</h3>',
				]
			);
		}

		register_sidebar(
			[
				'name'          => esc_html__( 'Single Property', 'homlisti' ),
				'id'            => 'single-property-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Agency/Store Sidebar', 'homlisti' ),
				'id'            => 'store-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Agent Sidebar', 'homlisti' ),
				'id'            => 'agent-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Listing Archive Sidebar', 'homlisti' ),
				'id'            => 'listing-archive-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Archive Sidebar', 'homlisti' ),
					'id'            => 'woocommerce-archive-sidebar',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-heading">',
					'after_title'   => '</h3>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Single Sidebar', 'homlisti' ),
					'id'            => 'woocommerce-single-sidebar',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-heading">',
					'after_title'   => '</h3>',
				]
			);
		}
	}

	public function body_classes( $classes ) {
		//Theme Version
		$header_style   = RDTheme::$header_style ? RDTheme::$header_style : 4;
		$homlisti_theme = wp_get_theme();
		$classes[]      = $homlisti_theme->Name . '-version-' . $homlisti_theme->Version;
		$classes[]      = 'theme-homlisti';
		// Header
		if ( '5' == $header_style ) {
			$classes[] = 'header-style-4';
		}
		$classes[] = 'header-style-' . $header_style;
		$classes[] = 'header-width-' . RDTheme::$header_width;
		$sticky    = RDTheme::$options['sticky_header'] ? 1 : 0;

		if ( $sticky ) {
			$classes[] = 'sticky-header';
		}

		if ( RDTheme::$has_tr_header ) {
			$classes[] = 'trheader';
		} else {
			$classes[] = 'no-trheader';
		}

		if ( is_front_page() && ! is_home() ) {
			$classes[] = 'front-page';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			$classes[] = 'single-listing-style-' . Helper::listing_single_style();
		}

		if ( class_exists( 'HomListi_Core' ) ) {
			$classes[] = 'homlisti-core-installed';
		}

		if ( ! class_exists( 'HomListi_Core' ) ) {
			$classes[] = 'need-homlisti-core';
		}

		if ( Helper::has_full_width() ) {
			$classes[] = 'is-full-width';
		}

		// WooCommerce
		if ( isset( $_COOKIE["shopview"] ) && $_COOKIE["shopview"] == 'list' ) {
			$classes[] = 'product-list-view';
		} else {
			$classes[] = 'product-grid-view';
		}

		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}

		if ( isset( $_GET ) && ! empty( $_GET ) ) {
			$classes[] = 'homlisti-have-request';
		}

		return $classes;
	}

	public function is_blog() {
		return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' == get_post_type();
	}

	public function noscript_hide_preloader() {
		// Hide preloader if js is disabled
		echo '<noscript><style>#preloader{display:none;}</style></noscript>';
	}

	public function pingback() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function preloader() {
		// Preloader
		if ( RDTheme::$options['preloader'] ) {
			if ( ! empty( wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' ) ) ) {
				$preloader_img = wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' );
			} else {
				$preloader_img = Helper::get_img( 'preloader.gif' );
			}
			echo '<div id="preloader" style="background-image:url(' . esc_url( $preloader_img ) . ');"></div>';
		}
	}

	public function wp_body_open() {
		do_action( 'wp_body_open' );
	}

	public function scroll_to_top_html() {
		// Back-to-top link
		if ( RDTheme::$options['back_to_top'] ) {
			echo '<a href="#" class="scrollToTop" style=""><i class="fa fa-angle-double-up"></i></a>';
		}
	}

	public function search_form() {
		$output = '
		<form method="get" class="custom-search-form" action="' . esc_url( home_url( '/' ) ) . '">
            <div class="search-box">
                <div class="row gutters-10">
                    <div class="col-12 form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="' . esc_attr__( 'Search here...', 'homlisti' ) . '" value="' . get_search_query() . '" name="s" />
                            <span class="input-group-append">
                                <button class="item-btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
		</form>
		';

		return $output;
	}

	public function move_textarea_to_bottom( $fields ) {
		$temp = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $temp;

		return $fields;
	}

	public function hentry_config( $classes ) {
		if ( is_search() || is_page() ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}

		return $classes;
	}

	public function excerpt_more() {
		if ( is_search() ) {
			$readmore = '<a href="' . get_the_permalink() . '"> [' . esc_html__( 'read more ...', 'homlisti' ) . ']</a>';

			return $readmore;
		}

		return ' ...';
	}

	public function add_span_cat_count( $links ) {
		$links = str_replace( '</a> (', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function add_span_archive_count( $links ) {
		$links = str_replace( '</a>&nbsp;(', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function breadcrumb() {
		$args = [
			'delimiter'   => '&nbsp;<i class="fas fa-angle-right"></i>&nbsp;',
			'wrap_before' => '<nav class="rtcl-breadcrumb">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'homlisti' ),
		];

		$breadcrumbs = new Breadcrumb();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], home_url() );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		if ( ! empty( $args['breadcrumb'] ) ) {
			$breadcrumb_style = RDTheme::$breadcrumb_style;
			global $post;

			$page_title = end( $args['breadcrumb'] );
			?>
            <section class="breadcrumbs-banner <?php echo esc_attr( $breadcrumb_style ) ?>">
                <div class="container">
					<?php if ( RDTheme::$options['breadcrumb_title'] !== 'disable' ) :

						//oldCondition: is_single() ? 'screen-reader-text' :
						?>
                        <h1 class="<?php echo esc_attr( RDTheme::$options['breadcrumb_title'] ); ?>">
							<?php

							if ( is_post_type_archive() || is_home() ) {
								$current_post_type = get_post_type();
								$archive_title     = get_theme_mod( $current_post_type . '_archive' );
								if ( $archive_title ) {
									echo $archive_title;
								} else if ( is_array( $page_title ) && isset( $page_title[0] ) ) {
									echo esc_html( $page_title[0] );
								}
							} else if ( is_array( $page_title ) && isset( $page_title[0] ) ) {
								echo esc_html( $page_title[0] );
							}
							?>
                        </h1>
					<?php
					endif;

					printf( "%s", $args['wrap_before'] );
					foreach ( $args['breadcrumb'] as $key => $crumb ) {
						printf( "%s", $args['before'] );
						if ( ! empty( $crumb[1] ) && sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
							echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
						} else {
							echo '<span>' . esc_html( $crumb[0] ) . '</span>';
						}
						printf( "%s", $args['after'] );
						if ( sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
							printf( "%s", $args['delimiter'] );
						}
					}
					printf( "%s", $args['wrap_after'] );
					?>
                </div>

            </section>
			<?php
		}
	}

	/**
	 * Ajax Functionality for Remove App Notice
	 * @return void
	 *
	 */
	public function homlist_app_notice_dismiss() {
		if ( check_ajax_referer( "dismissnotice", "nonce" ) ) {
			if ( isset( $_POST['dismiss'] ) && $_POST['dismiss'] == 1 ) {
				set_transient( "homlisti_dismiss_notice", 1, MONTH_IN_SECONDS * 2 );
				wp_die( "done" );
			}
		}
	}

}

General_Setup::instance();