<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_loginlogout', 'spektrix_load_loginlogout' );
function spektrix_load_loginlogout() {

  $spektrix_iframe_url = new iFrame('LoginLogout',NULL,true);

  echo $spektrix_iframe_url->render_iframe();

}
