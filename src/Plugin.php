<?php
namespace WPSitemapAshlin;

if (!defined('ABSPATH')) exit;

/**
 * Plugin initialize
 */
class Plugin {

	/**
	 * Plugin loaded state
	 * */
	private $loaded = false;

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
		$eventClasses = ['\WPSitemapAshlin\Admin\Page'];
		foreach ($eventClasses as $eventClass){
			(new $eventClass())->init();
		}
	}
}
