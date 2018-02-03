<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );


/*================================
=            Includes            =
================================*/

// Include framework bootstrap file
require WPPSX_PLUGIN_DIR . 'framework/bootstrap.php';

// Inlude custom post types items
require WPPSX_PLUGIN_DIR . 'lib/custom-post-types/cpts.php';
//require WPPSX_PLUGIN_DIR . 'lib/custom-post-types/cpts-meta.php';
require WPPSX_PLUGIN_DIR . 'lib/custom-post-types/cpts-tax.php';

// load helpers
require WPPSX_PLUGIN_DIR . 'lib/helpers/misc.php';
require WPPSX_PLUGIN_DIR . 'lib/helpers/array_colums.php';
require WPPSX_PLUGIN_DIR . 'lib/helpers/number_to_words.php';
require WPPSX_PLUGIN_DIR . 'lib/helpers/options-page.php';

// load post / show association function
require WPPSX_PLUGIN_DIR . 'lib/helpers/post-assoc.php';

// load shortcodes
require WPPSX_PLUGIN_DIR . 'lib/shortcodes/shortcodes.php';


/*=================================
=            Functions            =
=================================*/

/*----------  enqueue admin scripts  ----------*/

add_action( 'admin_enqueue_scripts', 'wpspx_admin_scripts' );
function wpspx_admin_scripts() {
  wp_register_script(
    'wpspk_select', plugins_url( 'lib/assets/js', __FILE__ ) . '/wpspk.js', array( 'jquery' ), '1.0', true
  );
  wp_register_style(
    'wpspx_admin_css', plugins_url( 'lib/assets/css', __FILE__ ) . '/wpspx.css', false, '1.0'
  );

  wp_enqueue_style( 'wpspx_admin_css' );
  wp_enqueue_script( 'wpspk_select' );
}


/*----------  enqueue front end scripts  ----------*/

add_action( 'wp_enqueue_scripts', 'wpspx_scripts' );
function wpspx_scripts() {
  wp_register_script(
    'wpspk-resize', '//system.spektrix.com/'.SPEKTRIX_USER.'/website/scripts/resizeiframe.js', '', '', true
  );
  wp_register_script(
    'wpspk-viewfromseats','//system.spektrix.com/'.SPEKTRIX_USER.'/website/scripts/viewfromseats.js', '', '', true
  );

  wp_enqueue_script( 'wpspk-resize' );
  wp_enqueue_script( 'wpspk-viewfromseats' );
}

/*----------  load custom templates for post types  ----------*/

add_filter('single_template', 'wpspx_templates');
function wpspx_templates($single) {
  global $wp_query, $post;
  // Allow themes to override custom template for single 'shows'.
  if ($post->post_type == "shows") {
    if(file_exists(STYLESHEETPATH . '/single-shows.php')) {
      return STYLESHEETPATH . '/single-shows.php';
    } elseif(file_exists(WPPSX_PLUGIN_DIR . '/lib/templates/single-shows.php')) {
      return WPPSX_PLUGIN_DIR . '/lib/templates/single-shows.php';
    }
  }
  return $single;
}

/*----------  load custom templates for taxonomies  ----------*/

add_filter('template_include','wpspx_override_tax_template');
function wpspx_override_tax_template($template){
  // Allow themes to override custom template for 'genres' taxonomy archive.
  $taxonomy_array = array('genres');
  foreach ($taxonomy_array as $taxonomy_single) {
    if ( is_tax($taxonomy_single) ) {
      if(file_exists(STYLESHEETPATH . '/taxonomy-'.$taxonomy_single.'.php')) {
        return STYLESHEETPATH . '/taxonomy-'.$taxonomy_single.'.php';
      }
      elseif(file_exists(WPPSX_PLUGIN_DIR . 'lib/templates/taxonomy-'.$taxonomy_single.'.php')) {
        return WPPSX_PLUGIN_DIR . 'lib/templates/taxonomy-'.$taxonomy_single.'.php';
      }
      break;
    }
  }
  return $template;
}
