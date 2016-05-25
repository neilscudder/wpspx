<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// load meta ID
$spectrix_id = get_post_meta($post->ID,'_spectrix_id',true);

if($spectrix_id){

	// load spektrix template
	include 'single-shows-spektrix.php';

} else {

 	// load none spektrix template
	include 'single-shows-not-spektrix.php';

}