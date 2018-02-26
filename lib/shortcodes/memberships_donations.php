<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_memberships_donations', 'spektrix_memberships_donations_shortcode' );
function spektrix_memberships_donations_shortcode() {

  $spektrix_iframe_url = new iFrame('donations', array('Attribute_Memberships' => 'Yes'), false);

  echo $spektrix_iframe_url->render_iframe();

}
