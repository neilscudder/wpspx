<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Associate spektrix record with wp cpt

/* Define the custom box */
add_action('add_meta_boxes','spektrix_record_add_custom_box' );

/* Do something with the data entered */
add_action('save_post','wpspx_save_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function spektrix_record_add_custom_box() {
	global $pagenow;
	$meta_box_title = ($pagenow == 'post-new.php' && is_admin()) ? 'Choose Record from Spektrix' : 'You are editing';

	$screens = array('shows');
	foreach ($screens as $screen) {
		add_meta_box(
			'spektrix_record_sectionid',
			__($meta_box_title, 'wpspx' ),
			'spektrix_record_inner_custom_box',
			$screen,
			'advanced',
			'high'
		);
	}
}

/* Prints the box content */
function spektrix_record_inner_custom_box( $post ) {
	global $pagenow;
	if($pagenow == 'post-new.php'){

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'wpspx' );
		$shows_in_spektrix = Show::find_all();
		$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));
    
		// Create an array of IDs of shows in WP.
    	// (We use this to ensure we don't ask the user to choose a shows in Spektrix that has already been added to WP)
		$wp_shows = array();
		foreach($shows_in_wordpress as $siw){
			$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
		}
		
		echo '<label for="myplugin_new_field">';
		_e("Choose a show from Spektrix", 'wpspx' );
		echo '</label> ';

		echo '<select id="myplugin_new_field" name="spektrix_data" class="advanced-selectbox">';
		foreach($shows_in_spektrix as $show):
			// Check it's not already in WP.
			if(!in_array($show->id,$wp_shows)){
				echo '<option value="'.$show->id.'|'.$show->name.'">'.$show->name.'</option>';
			}
		endforeach;
		echo '</select>';
		echo '<p><label for="not_spektrix"><input type="checkbox" id="not_spektrix" name="not_spektrix" value="1"> Not in Spektrix?</label></p>';
		echo '<p><input type="text" name="spektrix_override" class="text" style="width:90%;padding:5px;"></p>';
	} else {
		if(get_post_meta($post->ID,'_spektrix_id',true)){
			echo '<h1>'.$post->post_title . ' // Spektrix ID: ' . get_post_meta($post->ID,'_spektrix_id',true) . '</h1>';
		} else {
			echo '<h1>'.$post->post_title . ' // No Spektrix ID</h1>';
		}
	}
}

/* When the post is saved, saves our custom data */
function wpspx_save_postdata($post_id) {

	// check if user can edit the post
	if ( ! current_user_can( 'edit_post', $post_id ) )
    	return;

	// Secondly, check if the user intended to change this value.
	//if ( !isset( $_POST['wpspk-nonce'] ) || !wp_verify_nonce( $_POST['wpspk-nonce'], plugin_basename( __FILE__ ) ) )
    	//return;

	// if saving in a custom table, get post_ID
	if (isset($_POST['post_ID'])) {
		$post_ID = $_POST['post_ID'];
	}

	// sanitize user input
	if(isset($_POST['not_spektrix']) == "1"){
		$override_title = sanitize_text_field($_POST['spektrix_override']);
		
		//remove action to prevent infinite loop
		remove_action('save_post','wpspx_save_postdata');
		wp_update_post(array('ID'=>$post_ID,'post_title'=>$override_title,'post_name'=>str_replace(' ','-',$override_title)));
		add_action('save_post','wpspx_save_postdata');
	} else {
		if (isset($_POST['spektrix_data'])) {
			$spektrix_data = sanitize_text_field($_POST['spektrix_data']);
			$spektrix_data = explode('|',$spektrix_data);

			//remove action to prevent infinite loop
			remove_action('save_post','wpspx_save_postdata');
			wp_update_post(array('ID'=>$post_id,'post_title'=>$spektrix_data[1],'post_name'=>str_replace(' ','-',$spektrix_data[1])));
			add_action('save_post','wpspx_save_postdata');

			add_post_meta($post_id, '_spektrix_id', $spektrix_data[0], true) or
			update_post_meta($post_id, '_spektrix_id', $spektrix_data[0]);
		}
	}
}

# Now move advanced meta boxes after the title:
function foo_move_deck() {

    # Get the globals:
    global $post, $wp_meta_boxes;

    # Output the "advanced" meta boxes:
    do_meta_boxes(get_current_screen(), 'advanced', $post);

    # Remove the initial "advanced" meta boxes:
    unset($wp_meta_boxes['shows']['advanced']);
}

add_action('edit_form_after_title', 'foo_move_deck');

/*
Hide the title on edit only
http://wordpress.stackexchange.com/questions/110427/remove-post-title-input-from-edit-page
http://wordpress.stackexchange.com/questions/97241/how-do-i-only-load-js-on-the-post-new-php-and-post-php-pages-in-admin
*/
add_action('admin_init', 'wpse_110427_hide_title');
function wpse_110427_hide_title($post) {
	global $pagenow;
	if (!empty($pagenow) && ('post-new.php' === $pagenow)){
		remove_post_type_support('shows', 'title');
	}
	if(isset($_GET['post'])) { 
		if(get_post_meta($_GET['post'],'_spektrix_id',true)){
			remove_post_type_support('shows', 'editor');
		}
	}
}