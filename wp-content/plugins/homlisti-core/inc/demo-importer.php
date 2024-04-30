<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */


namespace radiustheme\HomListi_Core;


use \FW_Ext_Backups_Demo;

if ( ! defined( 'ABSPATH' ) ) exit;

class Demo_Importer {

	public function __construct() {
		add_filter( 'plugin_action_links_rt-demo-importer/rt-demo-importer.php', array( $this, 'add_action_links' ) ); // Link from plugins page 
		add_filter( 'rt_demo_installer_warning', array( $this, 'data_loss_warning' ) );
		add_filter( 'fw:ext:backups-demo:demos', array( $this, 'demo_config' ) );
		add_action( 'fw:ext:backups:tasks:success:id:demo-content-install', array( $this, 'after_demo_install' ) );
		//add_filter( 'fw:ext:backups:add-restore-task:image-sizes-restore', '__return_false' ); // Enable it to skip image restore step
	}

	public function add_action_links( $links ) {
		$mylinks = array(
			'<a href="' . esc_url( admin_url( 'tools.php?page=fw-backups-demo-content' ) ) . '">'.__( 'Install Demo Contents', 'homlisti-core' ).'</a>',
		);
		return array_merge( $links, $mylinks );
	}

	public function data_loss_warning( $links ) {
		$html  = '<div style="margin-top:20px;color:#f00;font-size:17px;line-height:1.3;font-weight:600;margin-bottom:40px;border-color: #f00;border-style: dashed;border-width: 1px 0;padding:10px 0;">';
		$html .= __( 'Warning: All your old data will be lost if you install One Click demo data from here, so it is suitable only for a new website.', 'homlisti-core');
		$html .= '</div>';
		return $html;
	}

	public function demo_config( $demos ) {
		$demos_array = array(
			'demo1' => array(
				'title' => __( 'Home 1', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/1.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/',
			),
			'demo2' => array(
				'title' => __( 'Home 2', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/2.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/home-2/',
			),
			'demo3' => array(
				'title' => __( 'Home 3', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/3.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/home-3/',
			),
			'demo4' => array(
				'title' => __( 'Home 4', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/4.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/home-4/',
			),
			'demo5' => array(
				'title' => __( 'Home 5', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/5.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/home-5/',
			),
			'demo6' => array(
				'title' => __( 'Home 6', 'homlisti-core' ),
				'screenshot' => plugins_url( 'screenshots/6.png', dirname(__FILE__) ),
				'preview_link' => 'https://www.radiustheme.com/demo/wordpress/themes/homlisti/home-6/',
			),
		);

		$download_url = 'http://demo.radiustheme.com/wordpress/demo-content/homlisti/';

		foreach ($demos_array as $id => $data) {
			$demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
				'url' => $download_url,
				'file_id' => $id,
			));
			$demo->set_title($data['title']);
			$demo->set_screenshot($data['screenshot']);
			$demo->set_preview_link($data['preview_link']);

			$demos[ $demo->get_id() ] = $demo;

			unset($demo);
		}

		return $demos;
	}

	public function after_demo_install( $collection ){
		// Update front page id
		$demos = array(
			'demo1'  => 3344,
			'demo2'  => 2671,
			'demo3'  => 3911,
			'demo4'  => 7642,
			'demo5'  => 16981,
			'demo6'  => 17609,
		);

		$data = $collection->to_array();

		foreach( $data['tasks'] as $task ) {
			if( $task['id'] == 'demo:demo-download' ){
				$demo_id = $task['args']['demo_id'];
				$page_id = $demos[$demo_id];
				update_option( 'page_on_front', $page_id );
				flush_rewrite_rules();
				break;
			}
		}

		// Update post author id
		global $wpdb;
		$id = get_current_user_id();
		$query = "UPDATE $wpdb->posts SET post_author = $id";
		$wpdb->query($query);

		// Import Users
		new \Homlisti_Core_Demo_User_Import();
	}
}

new Demo_Importer;