<?php
namespace WPSitemapAshlin\Sitemap;

use WPSitemapAshlin\BaseController;

if (!defined('ABSPATH')) exit;

/**
 * Cron Page
 */
class Cron extends BaseController{

	/**
	 * Class instance.
	 * @var Cron $instance
	 * */
	protected static $instance = null;

	/**
	 * Get class instance.
	 * @return Cron
	 */
  	public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Register the event
	 * */
	public function hooks(){
		add_action('wp_sitemap_ashlin_refresh_sitemap', [$this, 'refreshSitemap']);
	}

	/**
	 * Refresh sitemap
	 * */
	public function refreshSitemap(){
		Sitemap::instance()->refreshSitemap();
	}

	/**
	 * Remove scheduled events
	 * */
	public function removeScheduledEvents(){
		$timestamp = wp_next_scheduled( 'wp_sitemap_ashlin_refresh_sitemap' );
		wp_unschedule_event( $timestamp, 'wp_sitemap_ashlin_refresh_sitemap' );
	}

	/**
	 * Register cron
	 *
	 * @return boolean
	 * */
	public function registerCron(){
		if (!wp_next_scheduled('wp_sitemap_ashlin_refresh_sitemap')) {
			return wp_schedule_event( time(), 	'hourly', 'wp_sitemap_ashlin_refresh_sitemap' );
		}
		return true;
	}

	/**
	 * Get next scheduled
	 * */
	public function getNextScheduled(){
		$scheduledTime = wp_next_scheduled('wp_sitemap_ashlin_refresh_sitemap');
		if(!empty($scheduledTime)){
			return $this->dateFormat($scheduledTime);
		}
		return '';
	}
}
