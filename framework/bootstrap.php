<?php

	// Enqueue scrips for iframes
	function wpspx_scripts() {
		// Register Scripts
		wp_register_script(
			'wpspk-resize', 
			'//system.spektrix.com/'.SPECTRIX_USER.'/website/scripts/resizeiframe.js', 
			'', 
			'', 
			true 
		);
		// This one needs to be enqueded on spectrix iframe pages
		wp_register_script(
			'wpspk-viewfromseats',
			'//system.spektrix.com/'.SPECTRIX_USER.'/website/scripts/viewfromseats.js', 
			'', 
			'', 
			true 
		);
		
		wp_enqueue_script( 'wpspk-resize' );
		wp_enqueue_script( 'wpspk-viewfromseats' );
	}
	add_action( 'wp_enqueue_scripts', 'wpspx_scripts' );

	
	// load custom templates
	add_filter('single_template', 'wpspx_templates');

	function wpspx_templates($single) {
		global $wp_query, $post;

		// Check for single template by post type
		if ($post->post_type == "shows"){
			if(file_exists(WPPSX_PLUGIN_DIR . '/lib/templates/single-shows.php'))
				return WPPSX_PLUGIN_DIR . '/lib/templates/single-shows.php';
		}
		return $single;
	}

	// NEED TO MAKE THE PLUGIN GEN TH SPEKTRIX TXT FILE
	$classnames = array(
		'spectrix',
		'iframe',
		'cachedfile',
		'show',
		'performance',
		'pricelist',
		'availability',
		'fakeshow',
		'fakeperformance'
	);

	foreach($classnames as $classname) {
		$filename = WPPSX_PLUGIN_DIR . "framework/spektrix/". $classname .".class.php";
		require $filename;
	}