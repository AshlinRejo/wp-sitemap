<?php
namespace WPSitemapAshlin;

use WPSitemapAshlin\Sitemap\Cron;
use WPSitemapAshlin\Sitemap\Sitemap;

if (!defined('ABSPATH')) exit;

/**
 * Plugin initialize
 */
class Plugin {

	/**
	 * Class instance.
	 * @var Plugin $instance
	 * */
	protected static $instance = null;

	/**
	 * Plugin loaded state
	 * */
	private $loaded = false;

	/**
	 * Get class instance.
	 * @return Plugin
	 */
	public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Initialise the plugin
	 * */
	public function load(){
		if($this->loaded === true) return;
		$this->registerEvents();
		$this->loaded = true;
	}

	/**
	 * Register events
	 * */
	private function registerEvents(){
		$eventClasses = ['\WPSitemapAshlin\Admin\Page',
			'\WPSitemapAshlin\Sitemap\Cron',
			'WPSitemapAshlin\Sitemap\Sitemap'];
		foreach ($eventClasses as $eventClass){
			(new $eventClass())->init();
		}
	}

	/**
	 * While deactivate plugin
	 * */
	public function pluginDeactivated(){
		Cron::instance()->removeScheduledEvents();
		Sitemap::instance()->deleteSitemapFromOptions();
	}
}
