<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

	$classnames = array(
		'spektrix',
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