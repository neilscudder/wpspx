<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Plugin Name: WP Spektrix
 * Plugin URI: http://www.wpspektrix.com/
 * Description: A WordPress plugin that intergrates WordPress with Spektrix API V2
 * Version: 1.0.0
 * Author: Matin Greenwood
 * Author URI: http://www.pixelpudu.com/
 * License: GPL2+
 * Text Domain: wpspx
 * Domain Path: /languages
 */

if (defined('WPSPX')) {
	 // The plugin was already loaded (maybe as another plugin with different directory name)
} else {
	
	//  dwfine loaded and local plugin dir
	define('WPSPX', true);
	define( 'WPPSX_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define( 'WPPSX_PLUGIN_URL', plugin_dir_url(__FILE__));

	// load config settings
	require WPPSX_PLUGIN_DIR . 'config.php';

	// load plugin settings
	require WPPSX_PLUGIN_DIR . 'settings.php';

}