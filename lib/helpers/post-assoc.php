<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Associate spektrix record with wp cpt

/* Define the custom box */
add_action('add_meta_boxes','spektrix_record_add_custom_box' );

/* Do something with the data entered */
add_action('save_post','wpspx_save_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function spektrix_record_add_custom_box() {
  global $pagenow;
  $meta_box_title = ($pagenow == 'post-new.php' && is_admin()) ? 'Choose Record from Spektrix' : 'You Are Editing';

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
  // If Creating A New Show
  if($pagenow == 'post-new.php' || ! get_post_meta($post->ID,'_spektrix_id',true)) {

    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'wpspx' );
    $shows_in_spektrix = Show::find_all(true);
    $shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1,'post_status' => 'any'));

    // Create an array of IDs of shows in WP.
    // We use this to ensure we don't ask the user to choose
    // an Event in Spektrix that has already been added to WP
    $wp_shows = array();
    $available_shows = array();
    foreach($shows_in_wordpress as $siw){
      $wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
    }
    foreach($shows_in_spektrix as $show) {
      // Check it's not already in WP.
      if(!in_array($show->id, $wp_shows)){
        $available_shows[$show->id] = $show->name;
      }
    }

    echo '<p>NOTE: Events <em>must be set to "Live" and "Visible On Web"</em> in Spektrix in order to be selectable in this list.</p>';
    echo '<label for="spektrix_show_data">';
    _e("Choose An Event From Spektrix", 'wpspx' );
    echo '</label> ';

    echo '<select id="spektrix_show_data" name="spektrix_data" class="advanced-selectbox">';

    if(count($available_shows) > 0) :
      echo '<option disabled selected value> -- Select An Event -- </option>';
      foreach($available_shows as $show_id => $show_name):
        echo '<option value="'.$show_id.'|'.$show_name.'">'.$show_name.'</option>';
      endforeach;
    else :
      echo '<option disabled selected value> -- NO NEW SHOWS ARE AVAILABLE -- </option>';
    endif;
    echo '</select>';

  } else {
      // If Editing An Existing Show
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

// if saving in a custom table, get post_ID
  if (isset($_POST['post_ID'])) {
    $post_ID = $_POST['post_ID'];
  }

  if (isset($_POST['spektrix_data'])) {
    $spektrix_data = sanitize_text_field($_POST['spektrix_data']);
    $spektrix_data = explode('|',$spektrix_data);

    // remove action to prevent infinite loop
    // remove_action('save_post','wpspx_save_postdata');
    // wp_update_post(array('ID'=>$post_id,'post_title'=>$spektrix_data[1],'post_name'=>sanitize_title($spektrix_data[1])));
    // add_action('save_post','wpspx_save_postdata');

    if( ! add_post_meta($post_id, '_spektrix_id', $spektrix_data[0], true) ) {
      update_post_meta($post_id, '_spektrix_id', $spektrix_data[0]);
    }

    $show = new Show($spektrix_data[0]);

    // Get performance data
    $performances = $show->get_performances();
    $dates = get_performance_dates_unix($performances);

    // Set or update meta data
    update_post_meta($post_id, '_spektrix_event_type', $show->event_type);
    update_post_meta($post_id, '_spektrix_presenting_org', $show->presenting_organization);
    update_post_meta($post_id, '_spektrix_short_information', $show->short_description);

    // Check if the show has multiple performances
    if(count($dates) > 1) {
      asort($dates);
      $now = time();
      $next_date = '';
      foreach ($dates as $date) {
        if($date < $now) continue;
        $next_date = $date;
        break;
      }
      if(empty($next_date)) {
        $next_date = $dates[count($dates) - 1];
      }
      // Update the next performance date
      update_post_meta($post_id, '_spektrix_next_performance', $next_date);

    } else {
      // Shows with one performance just use the one date
      update_post_meta($post_id, '_spektrix_next_performance', $dates[0]);
    }

    // Update the Event Type/Genre taxonomy terms
    wp_set_object_terms($post_id, $show->event_type, 'genres', true);

  }
}

  // Now move advanced meta boxes after the title:
function foo_move_deck() {
// Get the globals:
  global $post, $wp_meta_boxes;
// Output the "advanced" meta boxes:
  do_meta_boxes(get_current_screen(), 'advanced', $post);
// Remove the initial "advanced" meta boxes:
  unset($wp_meta_boxes['shows']['advanced']);
}

add_action('edit_form_after_title', 'foo_move_deck');
