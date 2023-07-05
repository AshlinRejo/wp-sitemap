<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Class to check PHP and WordPress versions to meet plugin requirement
 * */
class WPSitemapAshlinRequirementChecks
{

	/**
	 * Check the plugin requirement pass
	 *
	 * @return boolean
	 * */
	public function check()
	{
		if ($this->isValidPHP() && $this->isValidWordPress()) {
			return true;
		} else {
			if (current_user_can('manage_options')) add_action('admin_notices', array($this, 'displayNotice'));
			return false;
		}
	}

	/**
	 * Display error message on admin screen
	 * */
	public function displayNotice()
	{
		echo '<div class="notice notice-warning	"><p>' . esc_html__('WP Sitemap Ashlin plugin needs to meet the following requirement to functional', 'wp-sitemap-ashlin') . '</p><ul>';
		if (!$this->isValidPHP()) {
			echo '<li>' . sprintf(esc_html_e('Your PHP version: %1$s, Needs atleast %2$s or higher', 'wp-sitemap-ashlin'), PHP_VERSION, WPS_ASHLIN_REQUIRED_PHP_VERSION) . '</li>';
		}
		if (!$this->isValidWordPress()) {
			global $wp_version;
			echo '<li>' . sprintf(esc_html_e('Your WordPress version: %1$s, Needs atleast %2$s or higher', 'wp-sitemap-ashlin'), $wp_version, WPS_ASHLIN_REQUIRED_WP_VERSION) . '</li>';
		}
		echo '</ul></div';
	}

	/**
	 * Checks is PHP version meet plugin requirement
	 *
	 * @return boolean
	 * */
	private function isValidPHP()
	{
		return version_compare(PHP_VERSION, WPS_ASHLIN_REQUIRED_PHP_VERSION, '>=');
	}

	/**
	 * Checks is WordPress version meet plugin requirement
	 *
	 * @return boolean
	 * */
	private function isValidWordPress()
	{
		global $wp_version;
		return version_compare($wp_version, WPS_ASHLIN_REQUIRED_WP_VERSION, '>=');
	}
}
