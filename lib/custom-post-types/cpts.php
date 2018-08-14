<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

function cpts_register() {

  $labels = array(
    'name' 					=> _x('Shows', 'post type general name'),
    'singular_name' 		=> _x('Show', 'post type singular name'),
    'add_new' 				=> _x('Add New', 'shows'),
    'add_new_item' 			=> __('Add New Show'),
    'edit_item' 			=> __('Edit Show'),
    'new_item' 				=> __('New Show'),
    'view_item' 			=> __('View Show'),
    'search_items' 			=> __('Search Shows'),
    'not_found' 			=>  __('No Shows Found'),
    'not_found_in_trash' 	=> __('No Shows Found in Trash'),
    'parent_item_colon' 	=> '',
  );

  $args = array(
    'labels' 				=> $labels,
    'menu_icon' 			=> 'dashicons-tickets-alt',
    'public' 				=> true,
    'show_ui' 				=> true,
    'publicly_queryable' 	=> true,
    'query_var'	 			=> true,
    'capability_type' 		=> 'post',
    'hierarchical' 			=> false,
    'rewrite' 				=> true,
    'supports' 				=> array(
      'title',
      'editor',
      'thumbnail',
    )
  );

  register_post_type('shows', $args );

}

//create custom post type
add_action('init', 'cpts_register');
