<?php

use Rtcl\Helpers\Functions;

class RtclAgentActionHooks {

	public function __construct() {
		add_action( 'rtcl_after_register_post_type', [ __CLASS__, 'register_agent_post_type' ] );
		add_action( 'rtcl_permalink_structure', [ __CLASS__, 'add_agent_permalink_structure' ] );
		add_action( 'rtcl_store_manager_created', [ __CLASS__, 'add_store_agent' ], 10, 2 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'agent_meta_box' ] );
		add_action( 'rtcl_agent_contact_form', [ __CLASS__, 'agent_contact_form' ] );
		add_action( 'rtcl_agent_contact_form_captcha', [ __CLASS__, 'g_recaptcha_v2_agent_placeholder' ] );
		add_action( 'rtcl_agent_sidebar', array( __CLASS__, 'get_agent_sidebar' ), 10 );
		add_action( 'wp_ajax_rtcl_send_mail_to_agent', [ __CLASS__, 'rtcl_send_mail_to_agent' ] );
		add_action( 'wp_ajax_nopriv_rtcl_send_mail_to_agent', [ __CLASS__, 'rtcl_send_mail_to_agent' ] );
		add_action( 'rtcl_agent_contact_form_validation', [ __CLASS__, 'agent_contact_form_validation' ], 10, 2 );
		add_action( 'rtcl_edit_account_form', [ __CLASS__, 'edit_account_form_agent_field' ], 60 );
		add_action( 'rtcl_update_user_account', [ __CLASS__, 'save_edit_account_form_agent_field' ], 10, 2 );
		add_action( 'rtcl_store_removed_listing_manager', [ __CLASS__, 'remove_agent' ] );
	}

	static function register_agent_post_type() {

		$permalinks = Functions::get_permalink_structure();

		$labels = array(
			'name'               => _x( 'Agent', 'Post Type General Name', 'rtcl-agent' ),
			'singular_name'      => _x( 'Agent', 'Post Type Singular Name', 'rtcl-agent' ),
			'menu_name'          => __( 'Agent', 'rtcl-agent' ),
			'name_admin_bar'     => __( 'Agent', 'rtcl-agent' ),
			'all_items'          => __( 'Agents', 'rtcl-agent' ),
			'add_new_item'       => __( 'Add New Agent', 'rtcl-agent' ),
			'add_new'            => __( 'Add New', 'rtcl-agent' ),
			'new_item'           => __( 'New Agent', 'rtcl-agent' ),
			'edit_item'          => __( 'Edit Agent', 'rtcl-agent' ),
			'update_item'        => __( 'Update Agent', 'rtcl-agent' ),
			'view_item'          => __( 'View Agent', 'rtcl-agent' ),
			'search_items'       => __( 'Search Agent', 'rtcl-agent' ),
			'not_found'          => __( 'No Agent found', 'rtcl-agent' ),
			'not_found_in_trash' => __( 'No Agent found in Trash', 'rtcl-agent' ),
		);

		$agent_page_id = Functions::get_page_id( 'agent' );

		if ( current_theme_supports( 'rtcl' ) ) {
			$has_archive = $agent_page_id && get_post( $agent_page_id ) ? urldecode( get_page_uri( $agent_page_id ) ) : 'agents';
		} else {
			$has_archive = false;
		}

		// If theme support changes, we may need to flush permalinks since some are changed based on this flag.
		$theme_support = current_theme_supports( 'rtcl' ) ? 'yes' : 'no';
		if ( get_option( 'current_theme_supports_rtcl' ) !== $theme_support && update_option( 'current_theme_supports_rtcl', $theme_support ) ) {
			update_option( 'rtcl_queue_flush_rewrite_rules', 'yes' );
		}

		$args = array(
			'label'               => __( 'Agents', 'rtcl-agent' ),
			'description'         => __( 'Agent Description', 'rtcl-agent' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . rtcl()->post_type,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => $has_archive,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $permalinks['permalink_agent'] ? array(
				'slug'       => $permalinks['permalink_agent'],
				'with_front' => false,
				'feeds'      => true,
			) : false,
			'capabilities'        => array(
				'edit_post'          => 'manage_rtcl_store',
				'read_post'          => 'manage_rtcl_store',
				'delete_post'        => 'manage_rtcl_store',
				'edit_posts'         => 'manage_rtcl_store',
				'edit_others_posts'  => 'manage_rtcl_store',
				'delete_posts'       => 'manage_rtcl_store',
				'publish_posts'      => 'manage_rtcl_store',
				'read_private_posts' => 'manage_rtcl_store',
				'create_posts'       => 'do_not_allow'
			)
		);

		register_post_type( rtclAgent()->post_type, apply_filters( 'rtcl_agent_register_post_type_args', $args ) );

		do_action( 'rtcl_agent_after_register_post_type' );
	}

	static function add_agent_permalink_structure( $saved_permalinks ) {

		if ( $agent_base = Functions::get_option_item( 'rtcl_advanced_settings', 'permalink_agent' ) ) {
			$saved_permalinks['permalink_agent'] = untrailingslashit( $agent_base );
		}

		return wp_parse_args(
			$saved_permalinks,
			[
				'permalink_agent' => _x( 'agent', 'slug', 'rtcl-agent' ),
			]
		);
	}

	/**
	 * @param bool $store_id
	 * @param array $user_id
	 *
	 * @return mixed
	 */

	public static function add_store_agent( $store_id, $user_id ) {
		if ( $store_id && $user_id ) {
			$user_info = get_userdata( $user_id );
			$agent_id  = wp_insert_post(
				[
					'post_title'     => $user_info->display_name,
					'post_name'      => $user_info->user_login,
					'post_content'   => $user_info->description,
					'post_status'    => 'publish',
					'post_author'    => 1,
					'post_type'      => 'rtcl_agent',
					'comment_status' => 'closed'
				]
			);
			if ( ! is_wp_error( $agent_id ) ) {
				update_post_meta( $agent_id, '_rtcl_user_id', $user_id );
				update_user_meta( $user_id, '_rtcl_agent_id', $agent_id );
			}
		}
	}

	public static function agent_meta_box() {
		add_meta_box(
			'rtcl_agent_moderation',
			__( 'Agent Information', 'rtcl-agent' ),
			'rtcl_agent_get_agent_info',
			rtclAgent()->post_type,
			'side',
			'default'
		);
	}

	static function get_agent_sidebar() {
		Functions::get_template( 'agent/sidebar', null, '', rtclAgent()->get_plugin_template_path() );
	}

	static function agent_contact_form() {
		Functions::get_template( 'agent/contact-form', null, '', rtclAgent()->get_plugin_template_path() );
	}

	public static function g_recaptcha_v2_agent_placeholder() {
		$misc_settings = Functions::get_option( 'rtcl_misc_settings' );
		if ( ! empty( $misc_settings['recaptcha_site_key'] ) && ! empty( $misc_settings['recaptcha_forms'] ) && in_array( 'agent_contact', $misc_settings['recaptcha_forms'] ) && ( ! empty( $misc_settings['recaptcha_version'] ) && $misc_settings['recaptcha_version'] !== 3 ) )
			?>
            <div class="form-group">
                <div class="rtcl-g-recaptcha-wrap"></div>
            </div>
			<?php
	}

	public static function rtcl_send_mail_to_agent() {
		$user_id = (int) $_POST["user_id"];
		$name    = sanitize_text_field( $_POST["name"] );
		$email   = sanitize_email( $_POST["email"] );
		$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$message = stripslashes( wp_kses( nl2br( $_POST["message"] ), [
			'a'      => [
				'href'  => true,
				'title' => true,
			],
			'br'     => [],
			'ul'     => [],
			'ol'     => [],
			'li'     => [],
			'strong' => []
		] ) );
		$data    = wp_parse_args( [
			'user_id' => $user_id,
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone,
			'message' => $message
		], $_POST );

		$error = new WP_Error();

		if ( ! Functions::verify_nonce() ) {
			$error->add( 'rtcl_session_error', __( "Your session have been expired.", "rtcl-agent" ) );
		}
		if ( ! Functions::is_human( 'agent_contact' ) ) {
			$error->add( 'rtcl_recaptcha_error', esc_html__( 'Invalid Captcha: Please try again.', 'rtcl-agent' ) );
		}

		do_action( 'rtcl_agent_contact_form_validation', $error, $data );

		if ( is_wp_error( $error ) && ! empty( $error->errors ) ) {
			wp_send_json_error( [
				'error' => apply_filters( 'rtcl_agent_contact_form_error', $error->get_error_message(), $error )
			] );
		}

		if ( ! rtcl()->mailer()->emails['Agent_Contact_Email']->trigger( $user_id, $data ) ) {
			wp_send_json_error( [ 'error' => __( "An error to send mail!", "rtcl-agent" ) ] );
		}

		wp_send_json_success( [ 'message' => __( "Your e-mail has been sent!", "rtcl-agent" ) ] );

	}

	/**
	 * @param WP_Error $error
	 * @param array $data
	 */
	public static function agent_contact_form_validation( $error, $data ) {
		if ( empty( $data['user_id'] ) || empty( $data['name'] ) || empty( $data['email'] ) || empty( $data['message'] ) ) {
			$error->add( 'rtcl_field_required', esc_html__( 'Need to fill all the required field.', 'rtcl-agent' ) );
		}
	}

	public static function edit_account_form_agent_field() {
		$user_id  = get_current_user_id();
		$agent_id = get_user_meta( $user_id, '_rtcl_agent_id', true );
		if ( $agent_id ) {
			$services     = get_post_meta( $agent_id, 'rtcl_agent_services', true );
			$specialties  = get_post_meta( $agent_id, 'rtcl_agent_specialties', true );
			$content_post = get_post( $agent_id );
			$content      = $content_post->post_content;
			?>
            <div class="form-group rtcl-agent-info-row row">
                <label class="col-sm-3 control-label"><?php esc_html_e( 'Agent Information', 'rtcl-agent' ); ?></label>
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for='rtcl-agent-desc'><?php esc_html_e( 'Content', 'rtcl-agent' ); ?></label>
                        <textarea name="content" id="rtcl-agent-desc"
                                  class="form-control"><?php echo esc_attr( $content ); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for='rtcl-agent-services'><?php esc_html_e( 'Service Areas', 'rtcl-agent' ); ?></label>
                        <input type="text" name="services" id="rtcl-agent-services"
                               value="<?php echo esc_attr( $services ); ?>" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for='rtcl-agent-specialties'><?php esc_html_e( 'Specialties', 'rtcl-agent' ); ?></label>
                        <textarea name="specialties" id="rtcl-agent-specialties"
                                  class="form-control"><?php echo esc_attr( $specialties ); ?></textarea>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	public static function save_edit_account_form_agent_field( $user_id, $data ) {
		$agent_id = get_user_meta( $user_id, '_rtcl_agent_id', true );

		if ( $agent_id ) {
			// Content
			if ( isset( $data['content'] ) ) {
				$post               = get_post( $agent_id );
				$post->post_content = sanitize_text_field( $data['content'] );
				wp_update_post( $post );
			}
			// Services
			if ( isset( $data['services'] ) ) {
				$services = sanitize_text_field( $data['services'] );
				update_post_meta( $agent_id, 'rtcl_agent_services', $services );
			}
			// Specialties
			if ( isset( $data['specialties'] ) ) {
				$specialties = sanitize_text_field( $data['specialties'] );
				update_post_meta( $agent_id, 'rtcl_agent_specialties', $specialties );
			}
		}

	}

	// Remove agent when removing listing manager
	static function remove_agent( $user_id ) {
		$agent_id = get_user_meta( $user_id, '_rtcl_agent_id', true );
		wp_delete_post( $agent_id, true );
		delete_user_meta( $user_id, '_rtcl_agent_id' );
	}

}
