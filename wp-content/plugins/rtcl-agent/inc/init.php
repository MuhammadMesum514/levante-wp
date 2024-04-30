<?php

if ( ! class_exists( RtclAgent::class ) ) {
	final class RtclAgent {
		protected static $instance = null;
		public $post_type = "rtcl_agent";

		private function __construct() {
			add_action( 'init', array( $this, 'load_textdomain' ), 20 );
			add_action( 'wp_enqueue_scripts', [ $this, 'front_end_script' ] );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_script_for_agent_post_type' ), 999 );
			$this->includes();
			$this->init();
		}

		public static function getInstance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'rtcl-agent', false, dirname( plugin_basename( RTCL_AGENT_FILE ) ) . '/languages' );
		}

		public function includes() {
			require_once "helpers/functions.php";
			require_once "emails/RtclAgentContactEmail.php";
			require_once "hooks/RtclAgentActionHooks.php";
			require_once "hooks/RtclAgentFilterHooks.php";
			require_once "admin/RtclAgentMeta.php";
		}

		public function init() {
			new RtclAgentActionHooks();
			new RtclAgentFilterHooks();
			if ( is_admin() ) {
				RtclAgentMeta::init();
			}
		}

		public function front_end_script() {
			$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTCL_AGENT_VERSION;
			wp_register_script( 'rtcl-agent', RTCL_AGENT_URL . '/assets/js/agent.js', array(
				'jquery',
				'rtcl-validator',
				'rtcl-public'
			), $version, true );
			wp_register_style( 'rtcl-agent', RTCL_AGENT_URL . '/assets/css/agent.css', array(
				'rtcl-public'
			), $version );

			global $post;

			$user_id = 0;
			if ( is_author() ) {
				$author  = get_user_by( 'slug', get_query_var( 'author_name' ) );
				$user_id = $author->ID;
			}

			wp_localize_script( 'rtcl-agent', 'rtcl_agent', apply_filters( 'rtcl_agent_public_localize', array(
				"ajaxurl"       => admin_url( "admin-ajax.php" ),
				'is_rtl'        => is_rtl(),
				rtcl()->nonceId => wp_create_nonce( rtcl()->nonceText ),
				'user_id'       => is_singular( rtclAgent()->post_type ) ? get_post_meta( $post->ID, '_rtcl_user_id', true ) : $user_id,
			) ) );
			wp_enqueue_style( 'rtcl-store-public' );
			if ( rtcl_is_single_agent() || rtcl_is_agent() ) {
				wp_enqueue_style( 'rtcl-agent' );
			}
			if ( rtcl_is_single_agent() ) {
				wp_enqueue_script( 'rtcl-agent' );
			}
		}

		/**
		 * @return string
		 */
		public function get_plugin_template_path() {
			return $this->plugin_path() . '/templates/';
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( RTCL_AGENT_FILE ) );
		}

		function load_admin_script_for_agent_post_type() {
			global $pagenow, $post_type;

			// validate page
			if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
				return;
			}
			if ( rtclAgent()->post_type != $post_type ) {
				return;
			}

			wp_enqueue_style( 'rtcl-bootstrap' );
			wp_enqueue_script( 'rtcl-bootstrap' );
		}

	}

	function rtclAgent() {
		return RtclAgent::getInstance();
	}

	add_action( 'plugins_loaded', 'rtclAgent', 30 );
}