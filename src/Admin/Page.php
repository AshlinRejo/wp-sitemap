<?php
namespace WPSitemapAshlin\Admin;

use WPSitemapAshlin\BaseController;

if (!defined('ABSPATH')) exit;

/**
 * Admin Page
 */
class Page extends BaseController{

	/**
	 * Register the event
	 * */
	public function hooks(){
		add_action('admin_menu', [$this, 'addAdminMenu']);
	}

	/**
	 * For adding admin menu
	 * */
	public function addAdminMenu(){
		add_menu_page(
			__( 'WP Sitemap', 'wp-sitemap-ashlin' ),
			'WP Sitemap',
			'manage_options',
			'wp-sitemap-ashlin',
			[$this, 'adminMenuContent'],
			'',
			6
		);
	}

	/**
	 * Admin menu content
	 * */
	public function adminMenuContent(){
		echo 'Hello Ashlin!!!';
	}
}
