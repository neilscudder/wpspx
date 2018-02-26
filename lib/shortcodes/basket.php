<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_basket', 'spektrix_load_basket' );
function spektrix_load_basket() {

	$spektrix_iframe_url = new iFrame('Basket2', NULL, false);

  echo $spektrix_iframe_url->render_iframe();

}
