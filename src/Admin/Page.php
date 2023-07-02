<?php
namespace WPSitemapAshlin\Admin;

use WPSitemapAshlin\BaseController;
use WPSitemapAshlin\Sitemap\Sitemap;

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
		if (!current_user_can('manage_options')) return;
		$message = $this->validateAndInitiateSitemapProcess();
		$filepath = WPS_ASHLIN_PATH . 'src/Admin/templates/dashboard.php';
		$data = array('message' => $message);
		$this->render($filepath, $data);
	}

	/**
	 * Validate and generate sitemap
	 * */
	private function validateAndInitiateSitemapProcess(){
		if('wp_sitemap_crawl' !== sanitize_text_field(wp_unslash($_POST['action']?? ''))) return;
		$nonce = sanitize_text_field(wp_unslash($_POST['_nonce']?? ''));
		// Verify nonce
		if(!empty($nonce) && wp_verify_nonce($nonce,'wp_sitemap_crawl')){
			$result = (new Sitemap())->initiateSitemapGenerationProcess();
			if($result === true){
				$message = esc_html__('Sitemap created successfully and scheduled to auto generate on every one hour', 'wp-sitemap-ashlin');
				return '<div class="notice notice-success"><p>'.$message.'</p></div>';
			} else {
				$message = esc_html__('Failed to create sitemap', 'wp-sitemap-ashlin');
			}
		} else {
			$message = esc_html__('Invalid request', 'wp-sitemap-ashlin');
		}
		return '<div class="notice notice-warning"><p>'.$message.'</p></div>';
	}

	public function displayNotice(){
		echo '<div class="notice notice-warning"><p>'.esc_html__('WP Sitemap Ashlin plugin needs to meet the following requirement to functional', 'wp-sitemap-ashlin').'</p><div>';
	}
}
