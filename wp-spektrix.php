<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 Plugin Name: WPSPX (WordPress & Spektrix)
 Plugin URI: http://pixelpudu.com/wpspx
 Description: A WordPress plugin that intergrates WordPress with Spektrix API V2
 Version: 1.1.0
 Author: Martin Greenwood
 Author URI: http://www.pixelpudu.com/
 License: GPL2+
 Text Domain: wpspx
 Domain Path: /languages
 License: GPL v2 or later

 Copyright © 2016 Martin Greenwood

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
	define( 'THEME_SLUG', wp_get_theme()->get( 'Name' ));

	// load plugin settings
	require WPPSX_PLUGIN_DIR . 'settings.php';

	/*----------  load action links  ----------*/
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
	function add_action_links ( $links ) {
	    $mylinks = array(
	        '<a href="' . admin_url( 'options-general.php?page=wpspx-settings' ) . '">Settings</a>',
	        '<a target="_blank" href="https://pixelpudu.com/submit-ticket/">Support</a>',
	        '<a target="_blank" href="https://paypal.me/martingreenwood">Donate</a>',
	    );
	    return array_merge( $links, $mylinks );
	}
}
