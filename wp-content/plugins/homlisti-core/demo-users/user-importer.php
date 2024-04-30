<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

class Homlisti_Core_Demo_User_Import {

	public $user_migration = [];

	public function __construct() {
		$this->import_users();
		$this->import_usermeta();
		$this->update_authors_in_post_and_postmeta();
		/*$this->update_userid_from_comments();*/
	}


	public function import_users() {
		global $wpdb;

		$existing_users     = [];
		$existing_users_obj = get_users( [ 'number' => - 1, 'fields' => [ 'ID', 'user_login' ] ] );
		foreach ( $existing_users_obj as $existing_user ) {
			$existing_users[ $existing_user->user_login ] = $existing_user->ID;
		}

		$user_data = file_get_contents( HOMLIST_CORE_BASE_DIR . 'demo-users/users.json' );
		$user_data = json_decode( $user_data, true );

		$_user_migration = [];
		foreach ( $user_data as $user_value ) {
			if ( array_key_exists( $user_value['user_login'], $existing_users ) ) {
				//continue;
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				wp_delete_user( $existing_users[$user_value['user_login']] );
			}

			$old_id = $user_value['ID'];
			unset( $user_value['ID'] );
			if ( $wpdb->insert( $wpdb->users, $user_value ) ) {
				//$this->user_migration[ $old_id ] = $wpdb->insert_id;
				$_user_migration[ $old_id ] = $wpdb->insert_id;
			}
		}

		update_option( 'homlisti_users', $_user_migration );
	}

	public function import_usermeta() {
		global $wpdb;

		$user_meta_data = file_get_contents( HOMLIST_CORE_BASE_DIR . 'demo-users/usermeta.json' );
		$user_meta_data = json_decode( $user_meta_data, true );

		$_homlisti_users = get_option( 'homlisti_users' );
		foreach ( $user_meta_data as $user_meta_value ) {
			if ( ! array_key_exists( $user_meta_value['user_id'], $_homlisti_users ) ) {
				continue;
			}

			$user_meta_value['user_id']    = $_homlisti_users[ $user_meta_value['user_id'] ];
			$user_meta_value['meta_value'] = maybe_unserialize( $user_meta_value['meta_value'] );

			// run update
			update_user_meta( $user_meta_value['user_id'], $user_meta_value['meta_key'], $user_meta_value['meta_value'] );
		}
	}

	public function update_authors_in_post_and_postmeta() {
		$_homlisti_users = get_option( 'homlisti_users' );

		//Update Listing author : Listing_id => user_id
		$existing_post_authors = [
			17441 => 19,
			17438 => 19,
			17433 => 19,
			17430 => 19,
			17393 => 9,
			17389 => 9,
			17384 => 8,
			17380 => 7,
			17376 => 7,
			17373 => 7,
			17362 => 7,
			17357 => 7,
			17353 => 7,
		];

		foreach ( $existing_post_authors as $post_id => $user_id ) {
			if ( ! array_key_exists( $user_id, $_homlisti_users ) ) {
				continue;
			}
			@wp_update_post( [ 'ID' => $post_id, 'post_author' => $_homlisti_users[ $user_id ] ] );
			update_post_meta( $post_id, '_rtcl_manager_id', $_homlisti_users[ $user_id ] );
		}

		//Update Listing Agent ID : Listing_id => user_id
		$existing_listing_agent = [
			17441 => 17,
			17438 => 17,
			17433 => 16,
			17430 => 16,
			17393 => 15,
			17389 => 15,
			17384 => 14,
			17380 => 12,
			17376 => 12,
			17373 => 12,
			17362 => 12,
			17357 => 13,
			17353 => 13,
		];

		foreach ( $existing_listing_agent as $post_id => $agent_id ) {
			if ( ! array_key_exists( $agent_id, $_homlisti_users ) ) {
				continue;
			}
			update_post_meta( $post_id, '_rtcl_manager_id', $_homlisti_users[ $agent_id ] );
		}

		//Update Agent meta : agent => user_id
		$existing_agent_ids = [
			17352 => 13,
			17351 => 12,
			17350 => 14,
			17349 => 15,
			17348 => 17,
			17347 => 16,
		];

		foreach ( $existing_agent_ids as $agent_id => $user_id ) {
			if ( ! array_key_exists( $user_id, $_homlisti_users ) ) {
				continue;
			}
			update_post_meta( $agent_id, '_rtcl_user_id', $_homlisti_users[ $user_id ] );
		}

		//Update Store Meta : store_id => user_id
		$existing_store_ids = [
			5536 => 7,
			5534 => 8,
			2640 => 9,
			2636 => 19,
		];

		foreach ( $existing_store_ids as $store_id => $user_id ) {
			if ( ! array_key_exists( $user_id, $_homlisti_users ) ) {
				continue;
			}
			update_post_meta( $store_id, '_rtcl_manager_ids', $_homlisti_users[ $user_id ] );
			update_post_meta( $store_id, 'store_owner_id', $_homlisti_users[ $user_id ] );
		}
	}


	public function update_userid_from_comments() {
		$existing_userid = [
			5 => 6,
			6 => 4,
		];
		$_homlisti_users = get_option( 'homlisti_users' );

		foreach ( $existing_userid as $key => $value ) {
			if ( ! array_key_exists( $value, $_homlisti_users ) ) {
				continue;
			}
			wp_update_comment( [ 'comment_ID' => $key, 'user_id' => $_homlisti_users[ $value ] ] );
		}
	}


	public static function export_users() {
		global $wpdb;
		$users_id = [ 7, 8, 9, 12, 13, 14, 15, 16, 17, 19 ];

		$users_id_sql = implode( ',', $users_id );

		// user table
		$query = "SELECT * FROM $wpdb->users WHERE ID IN ($users_id_sql)";
		$users = $wpdb->get_results( $query, ARRAY_A );

		// usermeta table
		$query     = "SELECT * FROM $wpdb->usermeta WHERE user_id IN ($users_id_sql)";
		$usermetas = $wpdb->get_results( $query, ARRAY_A );


		// json
		$json1 = json_encode( $users );
		$json2 = json_encode( $usermetas );
		file_put_contents( HOMLIST_CORE_BASE_DIR . 'demo-users/users.json', $json1 );
		file_put_contents( HOMLIST_CORE_BASE_DIR . 'demo-users/usermeta.json', $json2 );
	}

}