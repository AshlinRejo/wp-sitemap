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
	 * Display date and time format
	 *
	 * @param $time integer|string
	 * @return string
	 * */
	protected function dateFormat($time){
		if(empty($time)) return '';
		$date_format = get_option( 'date_format' );
		if ( empty( $date_format ) ) {
			// Return default date format if the option is empty.
			$date_format = 'F j, Y';
		}
		$time_format = get_option( 'time_format' );
		if ( empty( $time_format ) ) {
			// Return default time format if the option is empty.
			$time_format = 'g:i a';
		}
		return sprintf( __( '%1$s at %2$s', 'wp-sitemap-ashlin' ), date_i18n( $date_format, strtotime( $time )), date_i18n( $time_format, strtotime( $time )));
	}

	/**
	 * Method to register hooks/events
	 * */
	abstract function hooks();
}
