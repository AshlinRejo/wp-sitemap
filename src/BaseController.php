<?php
namespace WPSitemapAshlin;

if (!defined('ABSPATH')) exit;

/**
 * Base controller for register and initialise the event
 */
abstract class BaseController {

	/**
	 * Protected variable
	 * @param $path string
	 * */
	protected $path = '';

	/**
	 * Protected variable
	 * @param $data array
	 * */
	protected $data = [];

	/**
	 * Initialise the event
	 * */
	public function init(){
		$this->hooks();
	}

	/**
	 * To render content from a file
	 * @param $path string
	 * @param $data array
	 * */
	public function render($path, $data = []) {
		$this->setPath($path)->setData($data)->display();
	}

	/**
	 * Set the file path
	 * @param $path string
	 * @return $this object
	 */
	protected function setPath($path) {
		$this->path = $path;
		return $this;
	}

	/**
	 * Set data for template
	 * @param $data array
	 * @return $this object
	 */
	protected function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * Load template contents
	 */
	protected function display() {
		ob_start();
		if (file_exists($this->path)) {
			extract($this->data);
			include $this->path;
		}
		echo ob_get_clean();
	}

	/**
	 * Method to register hooks/events
	 * */
	abstract function hooks();
}
