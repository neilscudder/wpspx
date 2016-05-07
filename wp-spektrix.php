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
	/**
	 * The plugin was already loaded (maybe as another plugin with different directory name)
	 */
} else {
	// 
	define('WPSPX', true);
	define( 'WPPSX_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

	// load human mades custom meta boxes becuae I'm lazy 
	// need to check ig if its not already loaded...
	require WPPSX_PLUGIN_DIR . 'framework/Custom-Meta-Boxes/custom-meta-boxes.php';

	// load config, defines the variables used to build required constants
	require WPPSX_PLUGIN_DIR . 'lib/config.php';

	// load setting for settings pages
	require WPPSX_PLUGIN_DIR . 'lib/settings.php';
	
	// Inlude lib items
	require WPPSX_PLUGIN_DIR . 'lib/cpts.php';
	require WPPSX_PLUGIN_DIR . 'lib/cpts-meta.php';
	require WPPSX_PLUGIN_DIR . 'lib/cpts-tax.php';
	require WPPSX_PLUGIN_DIR . 'lib/post-assoc.php';

	// Include bootstrap file
	require WPPSX_PLUGIN_DIR . 'framework/bootstrap.php';
}