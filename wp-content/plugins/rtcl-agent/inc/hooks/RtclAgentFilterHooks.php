<?php

use Rtcl\Helpers\Functions as RtclFunctions;

class RtclAgentFilterHooks {
	public function __construct() {
		add_filter( 'rtcl_advanced_settings_options', [ __CLASS__, 'add_agent_end_point_options' ], 12 );
		add_filter( 'rtcl_locate_template', [ __CLASS__, 'locate_agent_template' ], 20, 2 );
		add_filter( 'rtcl_get_template_part', [ __CLASS__, 'get_template_part' ], 20, 3 );
		add_filter( 'rtcl_recaptcha_form_list', [ __CLASS__, 'add_recaptcha_agent_contact_form' ] );
		add_filter( 'rtcl_custom_pages_list', [ __CLASS__, 'add_agent_page' ] );
		add_filter( 'rtcl_page_title', [ __CLASS__, 'agent_page_title' ] );
		add_filter( 'template_include', [ __CLASS__, 'template_loader' ] );
		add_filter( 'rtcl_email_services', [ __CLASS__, 'add_agent_email_services' ], 10 );
		add_action( 'rtcl_before_enqueue_script', [ __CLASS__, 'add_recaptcha_script_agent_contact_form' ] );
		add_filter( 'rtcl_listing_the_author', [ __CLASS__, 'listing_agent_name' ], 10, 2 );
		add_filter( 'rtcl_listing_get_author_name', [ __CLASS__, 'listing_agent_name' ], 10, 2 );
		add_filter( 'rtcl_listing_get_author_url', [ __CLASS__, 'rtcl_listing_agent_url' ], 10, 2 );
		add_filter( 'manage_edit-rtcl_agent_columns', [ __CLASS__, 'manage_get_columns' ], 100 );
		add_action( 'manage_rtcl_agent_posts_custom_column', [ __CLASS__, 'agent_column_content' ], 10, 2 );
		add_filter( 'rtcl_licenses', [ __CLASS__, 'license' ], 12 );
	}

	public static function add_agent_end_point_options( $options ) {
		$position = array_search( 'store_category_base', array_keys( $options ) );
		if ( $position > - 1 ) {
			$option = array(
				'permalink_agent' => array(
					'title'       => esc_html__( 'Agent base', 'rtcl-agent' ),
					'type'        => 'text',
					'default'     => _x( 'agent', 'slug', 'rtcl-agent' ),
					'description' => esc_html__( 'Agent base permalink.', 'rtcl-agent' ),
				)
			);
			RtclFunctions::array_insert( $options, $position, $option );
		}

		$position = array_search( 'store', array_keys( $options ) );
		if ( $position > - 1 ) {
			$option = array(
				'agent' => [
					'title'       => esc_html__( 'Agent page', 'rtcl-agent' ),
					'type'        => 'select',
					'class'       => 'rtcl-select2',
					'blank_text'  => esc_html__( "Select a page", 'rtcl-agent' ),
					'options'     => RtclFunctions::get_pages(),
					'description' => esc_html__( 'This is the page where all agent list will be display.', 'rtcl-agent' ),
					'css'         => 'min-width:300px;',
				]
			);
			RtclFunctions::array_insert( $options, $position, $option );
		}

		return $options;
	}

	public static function add_recaptcha_agent_contact_form( $list ) {
		$list['agent_contact'] = esc_html__( 'Agent contact form', 'rtcl-agent' );

		return $list;
	}

	static function add_agent_page( $pages ) {
		$pages['agent'] = [
			'title'   => __( 'Agent', 'rtcl-agent' ),
			'content' => ''
		];

		return $pages;
	}

	static function add_agent_email_services( $services ) {
		$services['Agent_Contact_Email'] = new RtclAgentContactEmail();

		return $services;
	}

	public static function locate_agent_template( $template_file, $name ) {
		if ( strpos( $template_file, "classified-listing/" . $name ) === false && ! file_exists( $template_file ) ) {
			$template_file = rtclAgent()->plugin_path() . "/templates/$name.php";
		}

		return $template_file;
	}

	public static function get_template_part( $template, $slug, $name ) {
		if ( strpos( $template, "classified-listing/" . $name ) === false && ! file_exists( $template ) ) {
			$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name, RTCL_AGENT_VERSION ) ) );
			$template  = (string) wp_cache_get( $cache_key, 'rtcl_agent' );

			if ( ! $template ) {
				if ( $name ) {
					$template = RTCL_AGENT_TEMPLATE_DEBUG_MODE ? '' : locate_template(
						array(
							rtcl()->get_template_path() . "{$slug}-{$name}.php",
						)
					);

					if ( ! $template ) {
						$fallback = rtclAgent()->plugin_path() . "/templates/{$slug}-{$name}.php";
						$template = file_exists( $fallback ) ? $fallback : '';
					}
				}
				wp_cache_set( $cache_key, $template, 'rtcl_agent' );
			}
		}

		return $template;
	}

	static function agent_page_title( $page_title ) {
		if ( rtcl_is_agent() ) {
			$agent_page_id = RtclFunctions::get_page_id( 'agent' );
			$page_title    = $agent_page_id ? get_the_title( $agent_page_id ) : __( 'Agents', 'rtcl-agent' );
		}

		return $page_title;
	}

	public static function template_loader( $template ) {
		if ( is_embed() ) {
			return $template;
		}

		$default_file = self::get_template_loader_default_file();

		if ( $default_file ) {
			/**
			 * Filter hook to choose which files to find before WooCommerce does it's own logic.
			 *
			 * @since 3.0.0
			 * @var array
			 */
			$search_files = self::get_template_loader_files( $default_file );
			$template     = locate_template( $search_files );

			if ( ! $template ) {
				$template = rtclAgent()->plugin_path() . '/templates/' . $default_file;
				$template = apply_filters( 'rtcl_agent_template_loader_fallback_file', $template, $default_file );
			}
		}

		return $template;
	}

	private static function get_template_loader_default_file() {
		$default_file = '';
		if ( rtcl_is_single_agent() ) {
			$default_file = 'single-' . rtclAgent()->post_type . '.php';
		} elseif ( rtcl_is_agent() || ( ( $agent_page_id = RtclFunctions::get_page_id( 'agent' ) ) && is_page( $agent_page_id ) ) ) {
			$default_file = 'archive-' . rtclAgent()->post_type . '.php';
		}

		return $default_file;
	}

	private static function get_template_loader_files( $default_file ) {

		$templates   = apply_filters( 'rtcl_agent_template_loader_files', array(), $default_file );
		$templates[] = 'rtcl.php';

		if ( is_page_template() ) {
			$templates[] = get_page_template_slug();
		}

		if ( rtcl_is_single_agent() ) {
			$object       = get_queried_object();
			$name_decoded = urldecode( $object->post_name );
			if ( $name_decoded !== $object->post_name ) {
				$templates[] = "single-rtcl_agent-{$name_decoded}.php";
			}
			$templates[] = "single-rtcl_agent-{$object->post_name}.php";
		}

		$templates[] = $default_file;
		$templates[] = rtcl()->get_template_path() . $default_file;

		return array_unique( $templates );
	}

	public static function add_recaptcha_script_agent_contact_form() {
		if ( rtcl_is_single_agent() &&
		     RtclFunctions::get_option_item( 'rtcl_misc_settings', 'recaptcha_forms', 'agent_contact', 'multi_checkbox' ) ) {
			wp_enqueue_script( 'rtcl-recaptcha' );
		}
	}

	static function listing_agent_name( $author_name, $listing ) {

		if ( is_object( $listing ) ) {
			$manager_id = get_post_meta( $listing->get_id(), '_rtcl_manager_id', true );
			if ( $manager_id ) {
				$author_name = rtcl_get_agent_name( $manager_id );
			}
		}

		return $author_name;
	}

	static function rtcl_listing_agent_url( $author_url, $listing ) {
		if ( is_object( $listing ) ) {
			$manager_id = get_post_meta( $listing->get_id(), '_rtcl_manager_id', true );
			if ( $manager_id ) {
				$agent_id   = get_user_meta( $manager_id, '_rtcl_agent_id', true );
				$author_url = $agent_id ? get_the_permalink( $agent_id ) : $author_url;
			}
		}

		return $author_url;
	}

	static function manage_get_columns( $columns ) {

		$new_columns   = array(
			'agency' => esc_html__( 'Agency', 'rtcl-agent' )
		);
		$target_column = 'title';

		return RtclFunctions::array_insert_after( $target_column, $columns, $new_columns );
	}

	static function agent_column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'agency' :
				$user_id  = get_post_meta( $post_id, '_rtcl_user_id', true );
				$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
				if ( $store_id ) {
					echo get_the_title( $store_id );
				}
				break;
		}

	}

	public static function license( $licenses ) {
		$licenses[] = array(
			'plugin_file' => RTCL_AGENT_FILE,
			'api_data'    => array(
				'key_name'    => 'license_agent_key',
				'status_name' => 'license_agent_status',
				'action_name' => 'rtcl_agent_manage_licensing',
				'product_id'  => 174619,
				'version'     => RTCL_AGENT_VERSION,
			),
			'settings'    => array(
				'title' => esc_html__( 'Agent addon license key', 'rtcl-agent' ),
			),
		);
		return $licenses;
	}

}