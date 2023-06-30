<?php
namespace WPSitemapAshlin;

if (!defined('ABSPATH')) exit;

/**
 * Base controller for register and initialise the event
 */
abstract class BaseController {

	/**
	 * Initialise the event
	 * */
	public function init(){
		$this->hooks();
	}

	/**
	 * Method to register hooks/events
	 * */
	abstract function hooks();
}
