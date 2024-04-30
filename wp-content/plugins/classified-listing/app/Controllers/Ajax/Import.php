<?php

namespace Rtcl\Controllers\Ajax;


use Rtcl\Helpers\Functions;

class Import {

	function __construct() {
		add_action( 'wp_ajax_rtcl_import_location', [ $this, 'rtcl_import_location' ] );
		add_action( 'wp_ajax_rtcl_import_category', [ $this, 'rtcl_import_category' ] );
	}

	function rtcl_import_category() {
		if ( ! wp_verify_nonce( $_POST[rtcl()->nonceId] ?? '', rtcl()->nonceText) ) {
			wp_send_json( [
				'success' => false,
				'data'    => null,
				'message' => esc_html__( "Session Expired!!", "classified-listing" )
			] );
			return;
		}
		
		$data = $_REQUEST['data'];
		$return = $this->create_term( rtcl()->category, $data );
		wp_send_json( $return );
	}

	function rtcl_import_location() {
		if ( ! wp_verify_nonce( $_POST[rtcl()->nonceId] ?? '', rtcl()->nonceText) ) {
			wp_send_json( [
				'success' => false,
				'data'    => null,
				'message' => esc_html__( "Session Expired!!", "classified-listing" )
			] );
			return;
		}
		
		$data = $_REQUEST['data'];
		$return = $this->create_term( rtcl()->location, $data );
		wp_send_json( $return );
	}

	private function create_term( $taxonomy, $data ) {

		$data = wp_parse_args( $data, [
			"name"        => '',
			"slug"        => '',
			"parent"      => 0,
			"description" => '',
			"order"       => 0,
			"meta"        => [],
			"child"       => []
		] );

		$return = [
			'success' => false,
			'data'    => null,
			'message' => __( "Item is empty.", "classified-listing" )
		];
		if ( $data['name'] ) {
			$unique = !empty( $data['slug'] ) ? $data['slug'] : $data['name'];
			$term_exist = term_exists( $unique, $taxonomy );
			if ( empty( $term_exist ) ) {
				$term = wp_insert_term( $data['name'], $taxonomy, [
					'parent'      => isset( $data['parent'] ) ? absint( $data['parent'] ) : 0,
					'slug'        => $data['slug'],
					'description' => $data['description']
				] );
				if ( !is_wp_error( $term ) ) {
					update_term_meta( $term['term_id'], "_rtcl_order", absint( $data['order'] ) );
					if ( is_array( $data['meta'] ) && !empty( $data['meta'] ) ) {
						foreach ( $data['meta'] as $meta_key => $meta ) {
							update_term_meta( $term['term_id'], $meta_key, $meta );
						}
					}
					$return['success'] = true;
					$return['data'] = $term;
					/* translators:  name */
					$return['message'] = sprintf( esc_html__( "%s Successfully created", "classified-listing" ), esc_html($data['name']) );
				} else {
					$return['message'] = __( "Error!!!", "classified-listing" );
				}
			} else {
				$return['success'] = 'exist';
				$return['data'] = $term_exist;
				/* translators:  Name */
				$return['message'] = sprintf( esc_html__( "%s is already exist", "classified-listing" ), esc_html($data['name']) );
			}
		}

		return $return;
	}

	private function old_create_term( $taxonomy, $data, $parent = 0 ) {

		$data = wp_parse_args( $data, [
			"name"        => '',
			"slug"        => '',
			"parent"      => 0,
			"description" => '',
			"order"       => 0,
			"meta"        => [],
			"child"       => []
		] );

		if ( $data['name'] ) {
			$unique = !empty( $data['slug'] ) ? $data['slug'] : $data['name'];
			$term_exist = term_exists( $unique, $taxonomy );
			if ( $term_exist == 0 && $term_exist == null ) {
				$term = wp_insert_term( $data['name'], $taxonomy, [
					'parent'      => $parent,
					'slug'        => $data['slug'],
					'description' => $data['description']
				] );
				if ( !is_wp_error( $term ) ) {
					update_term_meta( $term['term_id'], "_rtcl_order", absint( $data['order'] ) );
					if ( is_array( $data['child'] ) && !empty( $data['child'] ) ) {
						foreach ( $data['child'] as $subLoc ) {
							$this->create_term( $taxonomy, $subLoc, $term['term_id'] );
						}
					}
					if ( is_array( $data['meta'] ) && !empty( $data['meta'] ) ) {
						foreach ( $data['meta'] as $meta_key => $meta ) {
							update_term_meta( $term['term_id'], $meta_key, $meta );
						}
					}
				}
			}
		}
	}

}