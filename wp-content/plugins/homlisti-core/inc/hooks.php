<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;


use radiustheme\HomListi\RDTheme;

class Plugins_Hooks {

	protected static $instance = null;

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __construct() {
		//Add user contact info
		add_filter( 'user_contactmethods', [ $this, 'rt_user_extra_contact_info' ] );
		add_filter( 'the_password_form', [ $this, 'rt_post_password_form' ] );

		//remove admin bar
		add_action( 'after_setup_theme', [ $this, 'remove_admin_bar' ], 999 );

		//Menu query string pass
		add_action('wp_nav_menu_item_custom_fields', function($item_id, $item) {
			$menu_query_string_key = get_post_meta($item_id, 'rt_menu_query_string_key', true);
			$menu_query_string = get_post_meta($item_id, 'rt_menu_query_string', true);
			?>
			<div class="menu-query-string description-wide">
				<p class="description description-thin">
					<label for="rt-menu-query-string-key-<?php echo $item_id; ?>" >
					<?php _e('Query String Key', 'homlisti-core'); ?><br>
						<input type="text" 
							id="rt-menu-query-string-key-<?php echo $item_id; ?>" 
							name="rt-menu-query-string-key[<?php echo $item_id; ?>]" 
							value="<?php echo esc_html($menu_query_string_key); ?>"
						/>
					</label>
				</p>
				<p class="description description-thin">
					<label for="rt-menu-query-string-<?php echo $item_id; ?>" >
					<?php _e('Query String Value', 'homlisti-core'); ?><br>
						<input type="text" 
							id="rt-menu-query-string-<?php echo $item_id; ?>" 
							name="rt-menu-query-string[<?php echo $item_id; ?>]" 
							value="<?php echo esc_html($menu_query_string); ?>"
						/>
					</label>
				</p>
			</div>
			<?php
			
		}, 10, 2);

		add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id) {
			$query_string_key = isset($_POST['rt-menu-query-string-key'][$menu_item_db_id]) ? $_POST['rt-menu-query-string-key'][$menu_item_db_id] : '';
			$query_string_value = isset($_POST['rt-menu-query-string'][$menu_item_db_id]) ? $_POST['rt-menu-query-string'][$menu_item_db_id] : '';
			update_post_meta($menu_item_db_id, 'rt_menu_query_string_key', $query_string_key);
			update_post_meta($menu_item_db_id, 'rt_menu_query_string', $query_string_value);
		}, 10, 2);

		
		add_filter( 'wp_get_nav_menu_items', function( $items, $menu, $args ) {
			foreach( $items as $item ) {
				$menu_query_string_key = get_post_meta($item->ID, 'rt_menu_query_string_key', true);
				$menu_query_string = get_post_meta($item->ID, 'rt_menu_query_string', true);
				if ( $menu_query_string )  {
					$item->url = add_query_arg( $menu_query_string_key, $menu_query_string, $item->url );
				}
			}
			return $items;
		}, 11, 3 );
	}

	//Remove admin bar
	function remove_admin_bar() {
		$remove_admin_bar = RDTheme::$options['remove_admin_bar'];
		if ( $remove_admin_bar && ! current_user_can( 'administrator' ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}

	/* User Contact Info */
	function rt_user_extra_contact_info( $contactmethods ) {
		// unset($contactmethods['aim']);
		// unset($contactmethods['yim']);
		// unset($contactmethods['jabber']);
		$contactmethods['rt_phone']     = __( 'Phone Number', 'homlisti-core' );
		$contactmethods['rt_facebook']  = __( 'Facebook', 'homlisti-core' );
		$contactmethods['rt_twitter']   = __( 'Twitter', 'homlisti-core' );
		$contactmethods['rt_linkedin']  = __( 'LinkedIn', 'homlisti-core' );
		$contactmethods['rt_vimeo']     = __( 'Vimeo', 'homlisti-core' );
		$contactmethods['rt_youtube']   = __( 'Youtube', 'homlisti-core' );
		$contactmethods['rt_instagram'] = __( 'Instagram', 'homlisti-core' );
		$contactmethods['rt_pinterest'] = __( 'Pinterest', 'homlisti-core' );
		$contactmethods['rt_reddit']    = __( 'Reddit', 'homlisti-core' );
		return $contactmethods;
	}

	/*
	 * change post password from
	 */
	public function rt_post_password_form() {
		global $post;
		$label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$output = '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
		<p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
		<p><label for="' . $label . '"><span class="pass-label">' . __( 'Password:' ) . ' </span><input name="post_password" id="' . $label
		          . '" type="password" size="20" /> <input type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '" /></label></p></form>
		';

		return $output;
	}

}

Plugins_Hooks::instance();