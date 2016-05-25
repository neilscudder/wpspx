<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

	/* 	
	Creating a Custom Post Type is blissfully simple ...
	Simply add your Post Types to the $cpts array.
	The first descriptor should be lowercase and plural
	The second descriptor should be singular and title case
	The third descriptor should be plural and title case 
	The fourth descriptor should be icon (https://developer.wordpress.org/resource/dashicons) 
	The fith descriptor should be the supports array
	*/

	$cpts = array(
		$shows = array(
			'shows',
			'Show',
			'Shows',
			'dashicons-tickets-alt',
			array(
				'title',
				'editor',
				'thumbnail',
				'comments'
			)
		),
	);


	function cpts_register() {
		
		global $cpts;
		
		foreach($cpts as $cpt){
			
			$cpt_wp_name = $cpt[0];
			$cpt_singular = $cpt[1];
			$cpt_plural = $cpt[2];
			$cpt_icon = $cpt[3];
			$cpt_supports = $cpt[4];

			$labels = array(
				'name' 					=> _x($cpt_plural, 'post type general name'),
				'singular_name' 		=> _x($cpt_singular, 'post type singular name'),
				'add_new' 				=> _x('Add New', $cpt_wp_name),
				'add_new_item' 			=> __('Add New '.$cpt_singular),
				'edit_item' 			=> __('Edit '.$cpt_singular),
				'new_item' 				=> __('New '.$cpt_singular),
				'view_item' 			=> __('View '.$cpt_singular),
				'search_items' 			=> __('Search '.$cpt_plural),
				'not_found' 			=>  __('No '.$cpt_plural.' Found'),
				'not_found_in_trash' 	=> __('No '.$cpt_plural.' Found in Trash'), 
				'parent_item_colon' 	=> '',
			);

			$args = array(
				'labels' 				=> $labels,
				'menu_icon' 			=> $cpt_icon,
				'public' 				=> true,
				'show_ui' 				=> true,
				'publicly_queryable' 	=> true,
				'query_var'	 			=> true,
				'capability_type' 		=> 'post',
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'supports' 				=> $cpt_supports
			);

			register_post_type($cpt_wp_name, $args );
			
		}
		
	}

	//create custom post type
	add_action('init', 'cpts_register');
