<?php
/**
 * Plugin name: WP Sitemap Ashlin
 * Description: WP Sitemap Ashlin.
 * Author: Ashlin
 * Author URI: https://github.com/AshlinRejo
 * Version: 1.0
 * Slug: wp-sitemap-ashlin
 * Text Domain: wp-sitemap-ashlin
 * Domain Path: /i18n/languages/
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) exit;

define( 'WPS_ASHLIN_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
define( 'WPS_ASHLIN_REQUIRED_PHP_VERSION', '7.2' );
define( 'WPS_ASHLIN_REQUIRED_WP_VERSION', '5.0' );

require WPS_ASHLIN_PATH . 'wp-sitemap-requirement-checks.php';

// Checks plugin requirement
if((new WPSitemapAshlinRequirementChecks())->check()){
	// Composer autoload.
	if ( file_exists( WPS_ASHLIN_PATH . 'vendor/autoload.php' ) ) {
		require WPS_ASHLIN_PATH . 'vendor/autoload.php';
	}

	add_action( 'plugins_loaded', [ new WPSitemapAshlin\Plugin(), 'load' ] );
}

