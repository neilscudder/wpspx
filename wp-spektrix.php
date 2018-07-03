<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 Plugin Name: WPSPX (WordPress & Spektrix)
 Description: A WordPress plugin that intergrates WordPress with Spektrix API V2. NOTE: This plugin has been forked and modified from Martin Greenwood's "WPSPX" plugin: http://pixelpudu.com/wpspx
 Version: 1.1.0
 License: GPL2+
 Text Domain: wpspx
 Domain Path: /languages
 License: GPL v2 or later

 This plugin is a fork of WPSPX (WordPress & Spektrix) by Martin Greenwood.

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 */

if (defined('WPSPX')) {
	// The plugin was already loaded (maybe as another plugin with different directory name)
} else {
	define( 'WPSPX', true) ;
	define( 'WPPSX_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define( 'WPPSX_PLUGIN_URL', plugin_dir_url(__FILE__));

	register_activation_hook(__FILE__,'create_pages');

	// load config settings
	require WPPSX_PLUGIN_DIR . 'config.php';

	//  define loaded and local plugin dir
	define( 'SPEKTRIX_URL', 'https://api.system.spektrix.com/'.SPEKTRIX_USER.'/api/v2/');
	define( 'SPEKTRIX_WEB_URL', 'https://system.spektrix.com/'.SPEKTRIX_USER.'/website/secure/');
	define( 'SPEKTRIX_WEB_INSECURE_URL', 'https://system.spektrix.com/'.SPEKTRIX_USER.'/website/');
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
