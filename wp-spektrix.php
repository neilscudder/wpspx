<?php if ( ! defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Plugin Name: WPWPX (WordPress & Spektrix)
 * Plugin URI: http://pixelpudu.com/
 * Description: A WordPress plugin that intergrates WordPress with Spektrix API V2
 * Version: 1.0.0
 * Author: Martin Greenwood
 * Author URI: http://www.pixelpudu.com/
 * License: GPL2+
 * Text Domain: wpspx
 * Domain Path: /languages
 */

if (defined('WPSPX')) {
	 // The plugin was already loaded (maybe as another plugin with different directory name)
} else {

	define( 'WPSPX', true) ;
	define( 'WPPSX_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define( 'WPPSX_PLUGIN_URL', plugin_dir_url(__FILE__));
	
	// load config settings
	require WPPSX_PLUGIN_DIR . 'config.php';

	//  define loaded and local plugin dir
	define( 'SPECTRIX_URL', 'https://api.system.spektrix.com/'.SPECTRIX_USER.'/api/v2/');
	define( 'SPECTRIX_WEB_URL', 'https://system.spektrix.com/'.SPECTRIX_USER.'/website/secure/');
	define( 'THEME_SLUG', wp_get_theme()->get( 'Name' ));


	// load plugin settings
	require WPPSX_PLUGIN_DIR . 'settings.php';

	/*----------  load action links  ----------*/
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
	function add_action_links ( $links ) {
	    $mylinks = array(
	        '<a href="' . admin_url( 'options-general.php?page=wpspx-settings' ) . '">Settings</a>',
	    );
	    return array_merge( $links, $mylinks );
	}

}