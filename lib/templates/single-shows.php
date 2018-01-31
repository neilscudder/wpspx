<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// load meta ID
$spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);

if($spektrix_id){

	// load spektrix template
	include 'single-shows-spektrix.php';

} else {

 	// load none spektrix template
	include 'single-shows-not-spektrix.php';

}