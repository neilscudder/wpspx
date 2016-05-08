<?php

	add_action( 'init', 'create_genre_taxonomy');

	//create taxonomy for the experiences page
	function create_genre_taxonomy() {
		
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name' => _x( 'Genres', 'taxonomy general name' ),
			'singular_name' => _x( 'Genre', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Genres' ),
			'all_items' => __( 'All Genres' ),
			'parent_item' => __( 'Parent Genre' ),
			'parent_item_colon' => __( 'Parent Genre:' ),
			'edit_item' => __( 'Edit Genre' ), 
			'update_item' => __( 'Update Genre' ),
			'add_new_item' => __( 'Add New Genre' ),
			'new_item_name' => __( 'New Genre' ),
			'menu_name' => __( 'Genres' ),
		);

		register_taxonomy('genres',array('shows'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'genre' ),
		));
	}
	
