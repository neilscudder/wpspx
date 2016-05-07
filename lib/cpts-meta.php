<?php
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function wpspk_metaboxes( array $meta_boxes ) {

	$fields = array(

		array( 
			'id' 			=> 'poster_image', 
			'name' 			=> '', 
			'type' 			=> 'image', 
			'repeatable' 	=> false, 
			'show_size' 	=> true 
		),
	);

	$meta_boxes[] = array(
		'title' 	=> 'Poster Image',
		'pages' 	=> 'shows',
		'context' 	=> 'side',
		'fields' 	=> $fields,
	);

	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'wpspk_metaboxes' );
