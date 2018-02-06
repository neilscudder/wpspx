<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_basket', 'spektrix_load_basket' );
function spektrix_load_basket() {

	$spektrix_iframe_url = new iFrame('Basket2', NULL, false);
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>
	</div>

	<?php
}
