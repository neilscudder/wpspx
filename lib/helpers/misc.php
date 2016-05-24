<?php
/*=======================================
=            ADD IMAGE SIZES            =
=======================================*/

add_image_size( 'cover43', 1600, 400, true );
add_image_size( 'poster', 320, 474, true );

/*==========================================
=            QUERY VAR - CUSTOM            =
==========================================*/

add_filter('query_vars', 'add_my_var');
function add_my_var($public_query_vars) {
	$public_query_vars[] = 'performance';
	$public_query_vars[] = 'lid';
	return $public_query_vars;
}

/*==================================
=            DO REWRITE            =
==================================*/

add_action('init', 'do_rewrite');
function do_rewrite() {
	add_rewrite_rule('book-online/([^/]+)/?$', 'index.php?pagename=book-online&performance=$matches[1]','top');
}

/*====================================
=            ThEME ASSETS            =
====================================*/

function theme_asset($string){
  return get_stylesheet_directory_uri() . '/' . $string;
}

/*===========================================
=            GET FIRST PARAGRAPH            =
===========================================*/

function get_first_paragraph($post_content){
	$str = wpautop($post_content);
	$str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
	$str = strip_tags($str, '<a><strong><em>');

	return '<p>' . $str . '</p>';
}

/*========================================
=            GRAB FIRST IMAGE            =
========================================*/

function catch_that_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches[0];

	if(empty($first_img)) {
		$first_img = null;
	}
	return $first_img;
}

/*======================================
=            BUAT THE CACHE            =
======================================*/

function wpspk_bust_cache() {
	$cached_files = WPPSX_PLUGIN_DIR . 'cache/*.txt';

	try {
		array_map('unlink', glob($cached_files)); ?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'KaPow! Spektrix cache has been Busted.!', 'wpspx' ); ?></p>
		</div>
	<?php } catch (Exception $e) { ?>
		<div class="notice notice-success is-dismissible">
			<p>Oops... <?php echo  $e->getMessage() . '\n'; ?></p>
		</div>
	<?php }
}

/*=====================================================
=            REPLACE SPACE WITH UNDERSCORE            =
=====================================================*/

function parameterize($string){
  return strtolower(str_replace(' ','_',$string));
}

/*============================================
=            LOAD PERFORMACE ATTR            =
============================================*/

function load_performance_attributes(){
	$performance_attributes_options = array('CAP', 'AD', 'BSL', 'REL', 'Post Show Talk', 'Football At Home', 'Use Underground Car Park', 'Underground Car Park Closed');
	$performance_attributes = array();
	foreach($performance_attributes_options as $pao){
	$pao_param = parameterize($pao);
		$performance_attributes[$pao] = array(get_field($pao_param.'_title','option'), get_field($pao_param.'_desc','option'));
	}
	return $performance_attributes;
}

/*==============================================
=            ACCESSIBLE PERFORMACES            =
==============================================*/

function accessible_performance_types(){
	$aps = array('CAP','AD','BSL','REL');
	return $aps;
}

/*====================================================
=            FORMAT ACESSIBLE PERFORMACES            =
====================================================*/

function format_performances_attributes($attributes) {
  $string = '';
  global $dir;
  $accessible_performances = accessible_performance_types();
  $performance_attributes = load_performance_attributes();
  $aps = $performance_attributes;
  foreach($aps as $ap => $ap_details):
	if(in_array($ap,$attributes)):
	  if(in_array($ap,$accessible_performances)):
		$string.= '<a href="'.home_url('/access/#accessible-performances').'">';
	  endif;
	  $string.= '<img class="accessible_performance" data-trigger="hover" data-animation="true" title="'.$ap_details[0].'" data-content="'.$ap_details[1].'" src="' . $dir . '/img/accessibility/' . str_replace(' ','-',strtolower($ap)) . '.png" style="margin-right:10px;"></a>';
	  if(in_array($ap,array('CAP','AD','BSL','REL'))):
		$string.= '</a>';
	  endif;
	  if(($key = array_search($ap, $attributes)) !== false) {
		unset($attributes[$key]);
	  }
	endif;
  endforeach;

  //show other attributes in a list
  $string .= '<div style="margin-top:10px;">';
  foreach($attributes as $attr):
	$string .= '<span class="label label-info">'.$attr.'</span>';
  endforeach;
  $string .= '</div>';

  echo $string;
}

/*====================================
=            PRETTYFY URL            =
====================================*/

function prettify_url($url){
  $url = str_replace(array('http://www.','http://'),'',$url);
  $url = rtrim($url,'/');
  return $url;
}

/*=============================================
=            CONVERT HOURS to MINS            =
=============================================*/

function convert_to_hours_minutes($minutes){
  $return_string = '';
  $hours = floor($minutes / 60);
  if($hours) $return_string .= $hours . ' hours';
  $minutes = $minutes % 60;
  if($minutes) $return_string .= ' ' . $minutes . ' minutes';
  return $return_string;
}

/*============================================
=            CONVERT MINS to SECS            =
============================================*/

function convert_to_seconds($minutes){
  $seconds = $minutes * 60;
  return $seconds;
}

/*===================================
=            IFRAME CODE            =
===================================*/

function iframe_shortcode($atts,$content = null){
  extract(shortcode_atts(
	array(
	  'secure' => false,
	),
	$atts
  ));
  $spektrix_iframe_url = new iFrame($content,NULL,$secure);
  return $spektrix_iframe_url->render_iframe();
}
add_shortcode('spektrix_iframe', 'iframe_shortcode');

/*============================================
=            TEMPLATE REDIRED SSL            =
============================================*/

function wpspx_ssl_template_redirect() {
  $blank_page = 'blank-page';
  $secure_pages = array('checkout','your-account');

  if(!defined('WP_LOCAL_DEV')) {
	if (is_page($secure_pages) && !is_ssl() ) {
	  if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
		wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']), 301 );
		exit();
	  } else {
		wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		exit();
	  }
	} else if (!is_page($secure_pages) && is_ssl() && !is_admin()) {
	  if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
		wp_redirect(preg_replace('|^https://|', 'http://', $_SERVER['REQUEST_URI']), 301 );
		exit();
	  } else if (is_page($blank_page)) {
		exit();
	  } else {
		wp_redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		exit();
	  }
	}
  }
}
// uncomment if younwant to redirect to SSL
//add_action('template_redirect','wpspx_ssl_template_redirect',1);

/*=========================================
=            CUSTOM SHORTCODES            =
=========================================*/

add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
add_shortcode('caption', 'fixed_img_caption_shortcode');

function fixed_img_caption_shortcode($attr, $content = null) {
	// New-style shortcode with the caption inside the shortcode with the link and image tags.
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}

	// Allow plugins/themes to override the default caption template.
	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	if ( $output != '' )
		return $output;

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '">'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}

/*========================================
=            ADD DWG MIMETYPE            =
========================================*/

//Adding the dwg mime type for tech specs
function my_myme_types($mime_types){
	$mime_types['dwg'] = 'image/x-dwg';
	return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);

/*==============================================
=            SET A COOKIE FOR CACHE            =
==============================================*/

// SETTING A COOKIE FOR NOT CHACHE THE SHOW PAGE
add_action( 'wp', 'show_page_cookie' );
function show_page_cookie() {
  // Set cookie
  if (is_singular('shows')) {
	setcookie("wordpress_show", "mis", time()+31536000);
  }
}

/*====================================================
=            JETPACK REMOVE DEFUALT SHARE            =
====================================================*/

// Removes Jetpack sharing in favour of putting where we'd likev
add_action( 'loop_start', 'jptweak_remove_share' );
function jptweak_remove_share() {
	remove_filter( 'the_content', 'sharing_display',19 );
	remove_filter( 'the_excerpt', 'sharing_display',19 );
	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	}
}

/*========================================
=            GET ID FROM SLUG            =
========================================*/

// Get page id from slug.
// Usage:
// get_id_by_slug('any-page-slug');

function get_id_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  if ($page) {
	return $page->ID;
  } else {
	return null;
  }
}



/*==============================================
=            Create Required Pages            =
==============================================*/

function create_pages() {

	$wpspx_pages = array(
		$basket = array(
			'Basket',
			'basket',
			'[basket]',
			'0',
		),
		$checkout = array(
			'Checkout',
			'checkout',
			'[checkout]',
			'0',
		),
		$myaccount = array(
			'My Account',
			'my-account',
			'[my_account]',
			'0',
		),
		$myaccount = array(
			'Book Online',
			'book-online',
			'[book_online]',
			'0',
		),
	);

	foreach ( $wpspx_pages as $wpspx_page ) {
		wpspx_create_page ( 
			$wpspx_page[0], 
			$wpspx_page[1], 
			$wpspx_page[2]
		);
	}
}

function wpspx_create_page($page_title = '', $slug, $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	$valid_page_found = apply_filters( 'wpspx_create_page_id', $valid_page_found, $slug, $page_content );

	if ( $valid_page_found ) {
		return $valid_page_found;
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'             => $page_id,
			'post_status'    => 'publish',
		);
	 	wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);
		$page_id = wp_insert_post( $page_data );
	}

	return $page_id;
}